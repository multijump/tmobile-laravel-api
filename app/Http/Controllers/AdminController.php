<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class AdminController extends Controller
{

//    protected $guard = 'admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $events = Event::all();

        return view('admins.home')->with(['events' => $events]);
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function users()
    {
        return view('admins.users');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function clients()
    {
        return view('admins.clients');
    }


    /**
     * Mark a user as an admin.
     *
     * @return Response
     */
    public function promoteAdmin(Request $request)
    {
        try {
            $requestUser = User::where('api_token', $request->get('api_token'))->first();
            $userId = $request->get('user_id');
            $user = User::find($userId);

        } catch (\Exception $e) {
            header('HTTP/1.1 500 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(['message' => 'An error occurred.', 'code' => $e->getCode()]));
        }

        try {
            if (empty($requestUser)) {
                throw new \Exception('Please login.', 400);
            }
            if (empty($userId)) {
                throw new \Exception('Could not find user.', 400);
            }
            if (empty($user)) {
                throw new \Exception('Could not find user.', 400);
            }

            $user->is_admin = 1;
            $user->save();

        } catch (\Exception $e) {

            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(['message' => $e->getMessage(), 'code' => $e->getCode()]));
        }

        header('HTTP/1.1 200 Success');
        header('Content-Type: application/json; charset=UTF-8');

        return json_encode([
            'status' => 'success',
            'message' => '',
            'code' => 200
        ]);
    }

    /**
     * Show the application dashboard.
     *
     */
    public function deleteUser(Request $request)
    {
        try {
            $requestUser = User::where('api_token', $request->get('api_token'))->first();
            $userId = $request->get('user_id');
            $user = User::find($userId);

        } catch (\Exception $e) {
            header('HTTP/1.1 500 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(['message' => 'An error occurred.', 'code' => $e->getCode()]));
        }

        try {
            if (empty($requestUser)) {
                throw new \Exception('Please login.', 400);
            }
            if (empty($userId)) {
                throw new \Exception('Could not find user.', 400);
            }
            if (empty($user)) {
                throw new \Exception('Could not find user.', 400);
            }

            $user->registeredParticipants()->update(['created_by_id' => null]);
            $user->selectedParticipants()->update(['selected_by_id' => null]);
            $user->createdEvents()->update(['created_by_id' => null]);

            $user->delete();

        } catch (\Exception $e) {

            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(['message' => $e->getMessage(), 'code' => $e->getCode()]));
        }

        header('HTTP/1.1 200 Success');
        header('Content-Type: application/json; charset=UTF-8');

        return json_encode([
            'status' => 'success',
            'message' => '',
            'code' => 200
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return false|string
     */
    public function deleteClient(Request $request)
    {
        try {
            $requestUser = User::where('api_token', $request->get('api_token'))->first();
            $clientId = $request->get('client_id');
            $client = DB::table('oauth_clients')->where('id', $clientId)->first();
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(['message' => 'An error occurred.', 'code' => $e->getCode()]));
        }

        try {
            if (empty($requestUser)) {
                throw new \Exception('Please login.', 400);
            }
            if (empty($clientId)) {
                throw new \Exception('Client could not be found.', 400);
            }
            if (empty($client)) {
                throw new \Exception('Client could not be found.', 400);
            }
            if (!$requestUser->hasAdminRole()) {
                throw new \Exception("You do not have permission", 400);
            }

            DB::table('oauth_clients')->where('id', $clientId)->update([
                'revoked' => 1
            ]);
        } catch (\Exception $e) {
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(['message' => $e->getMessage(), 'code' => $e->getCode()]));
        }

        header('HTTP/1.1 200 Success');
        header('Content-Type: application/json; charset=UTF-8');

        return json_encode([
            'status' => 'success',
            'message' => '',
            'code' => 200
        ]);
    }
}
