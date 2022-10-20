<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Survey;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $event_id = intval($request->query('event_id', '0'));

        if ($event_id > 0) {
            $event = Event::find($event_id);

            if (!$event) {
                return 'Event not found.';
            }

            return view('surveys.create', compact('event'));
        }

        return 'Invalid event ID.';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestUser = \Auth::user();
        $age = request('age');
        $lengthWithProvider = request('length_with_provider');
        $leaveLikelihood = request('leave_likelihood');
        $mostImportant = request('most_important');
        $brandRating = request('brand_rating');
        $brandRatingWhy = request('brand_rating_why');
        $connectWherever = request('connect_wherever');
        $connectWhereverWhy = request('connect_wherever_why');
        $switchRating = request('switch_rating');
        $comments = request('comments', 'No comment.');

        if ($age == '-18') {
            return $this->redirectWithMessage( 'errorMessage', 'Sorry, we don\'t allow responses for participants under 18.', $request);
        }

        if (empty($age)) {
            return $this->redirectWithMessage( 'errorMessage', 'What is your age? Is Required.', $request);
        }

        if (empty($lengthWithProvider)) {
            return $this->redirectWithMessage( 'errorMessage', 'About how long have you been with your current wireless service provider? Is Required.', $request);
        }

        if (empty($leaveLikelihood)) {
            return $this->redirectWithMessage( 'errorMessage', 'What is the likelihood that you will leave your carrier for another wireless service provider in the next year? Is Required.', $request);
        }

        if (empty($mostImportant)) {
            return $this->redirectWithMessage( 'errorMessage', 'What is most important to you when choosing a wireless service provider? Is Required.', $request);
        }

        if (empty($brandRating)) {
            return $this->redirectWithMessage( 'errorMessage', 'Do you feel like T-Mobile is a brand for you? Is Required.', $request);
        }

        if (empty($connectWherever)) {
            return $this->redirectWithMessage( 'errorMessage', 'Do you feel like T-Mobile would give/gives you the ability to connect wherever you are? Is Required.', $request);
        }

        if (empty($switchRating)) {
            return $this->redirectWithMessage( 'errorMessage', 'Would you ever consider switching to T-Mobile? Is Required.', $request);
        }

        $survey = new Survey();
        $survey->event_id = intval($request->query('event_id'));
        $survey->age = $age;
        $survey->length_with_provider = $lengthWithProvider;
        $survey->leave_likelihood = $leaveLikelihood;
        $survey->most_important = $mostImportant;
        $survey->brand_rating = $brandRating;
        $survey->brand_rating_why = $brandRatingWhy;
        $survey->connect_wherever = $connectWherever;
        $survey->connect_wherever_why = $connectWhereverWhy;
        $survey->switch_rating = $switchRating;
        $survey->comments = $comments;

        $survey->save();

        // Session::flash('successMessage', "Survey: " . $survey->id . ' has been created.');

        // if($requestUser->hasAdminRole()) {
        //     return redirect(route('admin.home'));
        // }

        // return redirect(route('home'));
        // return back()->with('status', 'Thank You!');
        return redirect(route('surveys.create', ['event_id' => intval($request->query('event_id'))]))->with('status', 'Thank You!');
        // return redirect('/surveys/create?event_id=' . $survey->event_id)->with('status', 'Thank You!');
        // return 'success';
        // return redirect('home/admin');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function redirectWithMessage($messageType, $messageText, $request = null, $redirectPath = '') {

        Session::flash($messageType, $messageText);

        if(!empty($request)) {
            $request->flash();
        }

        if(!empty($redirectPath)) {
            return redirect($redirectPath);
        }

        return redirect()->back();
    }
}
