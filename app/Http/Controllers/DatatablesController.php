<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Log;

class DatatablesController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    /**
     * Process datatables ajax request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function users(Request $request)
    {
        $events = User::select(['id', 'first_name', 'last_name', 'email', 'created_at', 'approved_date', 'denied_date', 'is_admin']);

        $keyword = trim($request->get('keyword'));
        $apiToken = trim($request->get('api_token'));

        $requestUser = User::where('api_token', $apiToken)->first();

        return Datatables::of($events)
            ->filter(function ($query) use ($keyword) {

                if (!empty($keyword)) {

                    $query->where(function ($query) use($keyword) {

                        // Remove commas
                        $keyword = str_replace(',', ' ', $keyword);

                        $keywords = explode(' ', $keyword);

                        foreach($keywords as $separateKeyword) {

                            // Trim keyword
                            $separateKeyword = trim($separateKeyword);

                            $query->where(function ($query) use($separateKeyword) {
                                $query->orWhere('first_name', 'like', "%{$separateKeyword}%")
                                    ->orWhere('last_name', 'like', "%{$separateKeyword}%")
                                    ->orWhere('email', 'like', "%{$separateKeyword}%");
                            });
                        }
                    });
                }
            })
            ->addColumn('role', function ($user){
                if ($user->hasAdminRole()) {
                    return 'Admin';
                } else {
                    return 'User';
                }
            })
            ->addColumn('action', function ($user){
                $html = '';

                if (!$user->hasAdminRole()){

                    $html .= '<div class="" style="text-align:center">';
                    $html .= '<a href="" data-user-id="' . $user->id . '" data-toggle="modal" data-target="#confirm-approve-modal" class="btn-make-admin">Make Admin</a>';
                    $html .= '<a href="" data-user-id="' . $user->id . '" data-toggle="modal" data-target="#confirm-delete-modal" alt="Delete"><i class="fas fa-trash" style="margin-top:5px;"></i></a>';


                    $html .= '</div>';
                }

                return $html;
            })
            ->where('id', '!=', $requestUser->id)
            ->make(true);
    }

    /**
     * Process datatables ajax request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clients(Request $request)
    {
        $clients = DB::table('oauth_clients')->where('revoked', 0);

        $apiToken = trim($request->get('api_token'));

        $requestUser = User::where('api_token', $apiToken)->first();

        return Datatables::of($clients)
            ->addColumn('action', function ($client) use ($requestUser) {
                $html = '';

                $html .= '<div class="" style="text-align:center">';
//                    $html .= '<a href="" data-user-id="' . $user->id . '" data-toggle="modal" data-target="#confirm-approve-modal" class="btn-make-admin">Make Admin</a>';
                $html .= '<a href="" data-client-id="' . $client->id . '" data-toggle="modal" data-target="#confirm-delete-modal" alt="Delete"><i class="fas fa-trash" style="margin-top:5px;"></i></a>';

                $html .= '</div>';

                return $html;
            })
            ->make(true);
    }


    /**
     * Process datatables ajax request.
     *
     * @return JsonResponse
     */
    public function events(Request $request)
    {
        $requestUser = \Auth::user();

        $events = Event::select(['id', 'title', 'store_number', 'start_date', 'end_date', 'description', 'region', 'state', 'created_by_id', 'created_at', 'has_surveys_enabled', 'public']);

        $keyword = $request->get('title');

        return Datatables::of($events)
            ->filter(function ($query) use ($request, $keyword) {
                $query->where('is_archived', '=', 'false');
                $oldCarbonDateTime = Carbon::now()->subDays(90);
                $query->where('end_date', '>', "{$oldCarbonDateTime}" );
                if (!empty($keyword)) {
                    $query->where(function ($query) use($keyword) {
                        $query->where('title', 'like', "%{$keyword}%")
                            ->orWhere('store_number', 'like', "%{$keyword}%")
                            ->orWhere('region', 'like', "%{$keyword}%")
                            ->orWhere('state', 'like', "%{$keyword}%");
                    });
                }

//                // The following can be used as a multiple date filter,
//                // just define start_date and end_date in the request
//                $startDate = $request->get('start_date');
//                $endDate = $request->get('start_date');
//
//                /*
//                 * If start_date is selected, filter any event that starts or ends after start_date
//                 */
//                if (!empty($startDate)) {
//                    $startDateArray = explode('/', $request->get('start_date'));
//
//                    // Check for correct formatting
//                    if (is_array($startDateArray) && (count($startDateArray) == 3)) {
//                        $startDate = Carbon::create($startDateArray[2], $startDateArray[0], $startDateArray[1]);
//                        $query->where(function($query) use ($request, $startDate) {
//                            $query->where('start_date', '>', "{$startDate}")
//                                ->orWhere('end_date', '>', "{$startDate}");
//                        });
//                    }
//                }
//
//                /*
//                 * If end_date is selected, filter any event that starts or ends before end_date.
//                 */
//                if (!empty($endDate)) {
//                    $endDateArray = explode('/', $request->get('end_date'));
//
//                    // Check for correct formatting
//                    if (is_array($endDateArray) && (count($endDateArray) == 3)) {
//                        $endDate = Carbon::create($endDateArray[2], $endDateArray[0], $endDateArray[1]);
//
//                        $query->where(function($query) use ($request, $endDate) {
//                            $query->where('start_date', '<', "{$endDate}")
//                                ->orWhere('end_date', '<', "{$endDate}");
//                        });
//                    }
//                }
            })
            ->addColumn('title', function ($event) use ($requestUser) {
                return '<a href="'. route('events.participants.register', $event->id) .'">'. $event->title . '</a>';
            })
            ->addColumn('participants', function ($event) use ($requestUser) {
                return $event->participantCount();
            })
            ->addColumn('action', function ($event) use ($requestUser) {

                $html = '';

                // User that created event, or admin can
                // Pick winners
                // Retrieve event data
                // Edit event
                // if ($requestUser->id == $event->created_by_id || $requestUser->hasAdminRole()) {

                    // This is the html returned to the datatables action column.
                    $pickWinnersLink = '<a href="" class="pick-winner-link" data-event-id="'.$event->id.'" data-toggle="modal" data-target="#winners-select-modal" alt="Pick Winners"><i class="fas fa-trophy"></i></a>';

                    $retrieveDataAdminLink = '<a href="" class="retrieve-data-link" data-event-id="'.$event->id.'" data-toggle="modal" data-target="#event-report-modal" alt="Retrieve Data"><i class="fas fa-file-download"></i></a>';

                    $editLink = '<a href="' . route('events.edit', $event->id) . '" class="" alt="Edit Event"><i class="fas fa-edit"></i></a>';

                    $html .= $pickWinnersLink;

                    if($requestUser->hasAdminRole()){
                        $html .= $retrieveDataAdminLink;
                    }

                    $html .= $editLink;

                    if ($event->has_surveys_enabled) {
                        $surveyLink = '<a href="' . route('surveys.create', ['event_id' => $event->id]) . '" class="" alt="Take Survey"><i class="fas fa-poll"></i></a>';
                    } else {
                        $surveyLink = '<i class="fas fa-poll" style="margin-right: 1em;"></i>';
                    }

                    $html .= $surveyLink;

                    if ($event->public) {
                        $qrLink = '<a href="' . route('qr.print', ['event_id' => $event->id]) . '" class="" alt="Show QR" style="text-decoration:none"><span style="font-weight:bold;">QR</span></a>';
                        $qrQuickLink = '<a href="' . route('qr.quickprint', ['event_id' => $event->id]) . '" class="" alt="Print QR"><i class="fas fa-print"></i></a>';
                    } else {
                        $qrLink = '<span style="margin-right:1em;font-weight:bold">QR</span>';
                        $qrQuickLink = '<i class="fas fa-print" style="margin-right: 1em;"></i>';
                    }

                    $html .= $qrLink;
                    $html .= $qrQuickLink;

                    if ($requestUser->hasAdminRole() || $requestUser->email == $event->createdByUser->email) {
                        if ($event->surveys->count()) {
                            $surveyDownloadLink = '<a href="" class="retrieve-data-link" data-event-id="'.$event->id.'" data-toggle="modal" data-target="#survey-report-modal" alt="Retrieve Survey Data"><i class="fas fa-arrow-alt-circle-down"></i></a>';
                            $html .= $surveyDownloadLink;
                        } else {
                            $surveyDownloadLink = '<i class="fas fa-arrow-alt-circle-down" style="margin-right: 1em;"></i>';
                            $html .= $surveyDownloadLink;
                        }
                    }

                    if($requestUser->hasAdminRole()){
                        $archiveLink = '<a href="" class="delete-event-link" data-event-id="'.$event->id.'" data-toggle="modal" data-target="#event-delete-modal" alt="Retrieve Data"><i class="fas fa-trash-alt"></i></a>';
                        $html .= $archiveLink;
                    }

                return $html;
            })
            ->make(true);
    }
}
