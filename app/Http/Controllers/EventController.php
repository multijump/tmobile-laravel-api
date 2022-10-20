<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

use PDF;

class EventController extends Controller
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {


    }

    /**
     * Show the event creation form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Show the QR share page.
     *
     * @return \Illuminate\Http\Response
     */
    public function qr($id)
    {
        $event = Event::find($id);

        if (empty($event)) {
            Session::flash('errorMessage', 'Could Not Find Event To QR Share.');
            return redirect()->back();
        }

        return view('qr.print')->with(['event' => $event]);
    }

    /**
     * Show the event creation form.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestUser = \Auth::user();
        $title = $request->get('title');
        $storeNumber = $request->get('store_number');
        $workfrontId = $request->get('workfront_id');
        $formattedStartDate = $request->get('start_date');
        $formattedEndDate = $request->get('end_date');
        $description = $request->get('description');
        $region = $request->get('region');
        $state = $request->get('state');
        $defaultLanguage = $request->get('default_language');
        $hasSurveysEnabled = $request->get('has_surveys_enabled');
        $public = $request->get('public');
        $company = $request->get('company');

        if (empty($title)) {
            return $this->redirectWithMessage( 'errorMessage', 'Title Is Required.', $request);
        }
        if (empty($storeNumber)) {
            return $this->redirectWithMessage('errorMessage', 'Store Number Is Required.', $request);
        }
        if (empty($formattedStartDate)) {
            return $this->redirectWithMessage('errorMessage', 'Start Date Is Required.', $request);
        }
        if (empty($formattedEndDate)) {
            return $this->redirectWithMessage('errorMessage', 'End Date Is Required.', $request);
        }
        if (empty($description)){
            return $this->redirectWithMessage('errorMessage', 'Description Is Required.', $request);
        }
        if (empty($region)){
            return $this->redirectWithMessage('errorMessage', 'Region Is Required.', $request);
        }
        if (empty($state)){
            return $this->redirectWithMessage('errorMessage', 'State Is Required.', $request);
        }
        if (empty($defaultLanguage)){
            return $this->redirectWithMessage('errorMessage', 'Default Language Is Required.', $request);
        }
        if(!$public === 0 && !$public === 1){
            return $this->redirectWithMessage('errorMessage', 'Public Is Required.', $request);
        }
        if (empty($company)){
            return $this->redirectWithMessage('errorMessage', 'Company Is Required.', $request);
        }
        
        $startDateArray = explode('/', $request->get('start_date'));
        // Check for correct formatting
        if (is_array($startDateArray) && (count($startDateArray) !== 3)) {
            return $this->redirectWithMessage('errorMessage','Start Date Format Is Invalid.', $request);
        }
        $endDateArray = explode('/', $request->get('end_date'));
        // Check for correct formatting
        if (is_array($endDateArray) && (count($endDateArray) !== 3)) {
            return $this->redirectWithMessage('errorMessage','End Date Format Is Invalid.', $request);
        }

        $startDate = Carbon::create($startDateArray[2], $startDateArray[0], $startDateArray[1]);
        $endDate = Carbon::create($endDateArray[2], $endDateArray[0], $endDateArray[1]);

        if ($startDate->gt($endDate)) {
            return $this->redirectWithMessage('errorMessage', 'Start Date Must Be Before End Date.', $request);
        }

        $event = new Event();
        $event->title = $title;
        $event->store_number = $storeNumber;
        $event->workfront_id = $workfrontId;
        $event->start_date = $startDate;
        $event->end_date = $endDate;
        $event->description = $description;
        $event->region = $region;
        $event->state = $state;
        $event->default_language = $defaultLanguage;
        $event->created_by_id = $requestUser->id;
        $event->has_surveys_enabled = !!$hasSurveysEnabled;
        $event->public = !!$public;
        $event->company = $company;

        $event->save();

        Session::flash('successMessage', "Event: " . $event->title . ' has been created.');

        if($requestUser->hasAdminRole()) {
            return redirect(route('admin.home'));
        }

        return redirect(route('home'));
    }

    /**
     * Show the event edit form.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id);

        if (empty($event)) {
            Session::flash('errorMessage', 'Could Not Find Event.');
            return redirect()->back();
        }

        return view('events.edit')->with(['event' => $event]);
    }


    /**
     * Archives the event (Archiving is soft-deletion.)
     * 
     * @return \Illuminate\Http\Response
     */
    public function archive(Request $request)
    {
        $requestUser = \Auth::user();
        if(!$requestUser->hasAdminRole()) {
            Session::flash('errorMessage', 'You must be an admin user to archive an event.');
            return redirect()->back();
        }

        $id = $request->get('event_id');
        $eventArchiveQuery = Event::query()
        ->where('id', $id)
        ->update(['is_archived' => true]);

        return $this->redirectWithMessage(
            'successMessage',
            "Event has been archived. ",
            null,
            route('admin.home')
        );
    }
    /**
     * Update the event.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $requestUser = \Auth::user();
        $title = $request->get('title');
        $storeNumber = $request->get('store_number');
        $workfrontId = $request->get('workfront_id');
        $formattedStartDate = $request->get('start_date');
        $formattedEndDate = $request->get('end_date');
        $description = $request->get('description');
        $region = $request->get('region');
        $state = $request->get('state');
        $defaultLanguage = $request->get('default_language');
        $event = Event::find($id);
        $hasSurveysEnabled = $request->get('has_surveys_enabled');
        $public = $request->get('public');
        $company = $request->get('company');

        if (empty($event)) {
            return $this->redirectWithMessage( 'errorMessage', 'Could Not Find Event.', $request);
        }
        if (empty($title)) {
            return $this->redirectWithMessage( 'errorMessage', 'Title Is Required.', $request);
        }
        if (empty($storeNumber)) {
            return $this->redirectWithMessage('errorMessage', 'Store Number Is Required.', $request);
        }
        if (empty($formattedStartDate)) {
            return $this->redirectWithMessage('errorMessage', 'Start Date Is Required.', $request);
        }
        if (empty($formattedEndDate)) {
            return $this->redirectWithMessage('errorMessage', 'End Date Is Required.', $request);
        }
        if (empty($region)) {
            return $this->redirectWithMessage('errorMessage', 'Region Is Required.', $request);
        }
        if (empty($state)) {
            return $this->redirectWithMessage('errorMessage', 'State Is Required.', $request);
        }
        if (empty($defaultLanguage)){
            return $this->redirectWithMessage('errorMessage', 'Default Language Is Required.', $request);
        }
        if (!$hasSurveysEnabled === 0 && !$hasSurveysEnabled === 1) {
            return $this->redirectWithMessage('errorMessage', 'Survey Used On-Site Is Required.', $request);
        }
        if (!$public === 0 && !$public === 1){
            return $this->redirectWithMessage('errorMessage', 'Public Is Required.', $request);
        }
        if (empty($company)){
            return $this->redirectWithMessage('errorMessage', 'Company Is Required.', $request);
        }

        $startDateArray = explode('/', $request->get('start_date'));
        // Check for correct formatting
        if (is_array($startDateArray) && (count($startDateArray) !== 3)) {
            return $this->redirectWithMessage('errorMessage','Start Date Format Is Invalid.', $request);
        }

        $endDateArray = explode('/', $request->get('end_date'));
        // Check for correct formatting
        if (is_array($endDateArray) && (count($endDateArray) !== 3)) {
            return $this->redirectWithMessage('errorMessage', 'End Date Format Is Invalid.', $request);
        }

        $startDate = Carbon::create($startDateArray[2], $startDateArray[0], $startDateArray[1]);
        $endDate = Carbon::create($endDateArray[2], $endDateArray[0], $endDateArray[1]);

        if ($startDate->gt($endDate)) {
            return $this->redirectWithMessage('errorMessage', 'Start Date Must Be Before End Date.', $request);
        }

        if ($hasSurveysEnabled) {
            $hasSurveysEnabled = true;
        } else {
            $hasSurveysEnabled = false;
        }

        $event->title = $title;
        $event->store_number = $storeNumber;
        $event->workfront_id = $workfrontId;
        $event->start_date = $startDate;
        $event->end_date = $endDate;
        $event->description = $description;
        $event->region = $region;
        $event->state = $state;
        $event->default_language = $defaultLanguage;
        $event->created_by_id = $requestUser->id;
        $event->has_surveys_enabled = $hasSurveysEnabled;
        $event->public = !!$public;
        $event->company = $company;

        $event->save();

        if($requestUser->hasAdminRole()) {
            $redirectPath = route('admin.home');
        } else {
            $redirectPath = route('home');
        }

        return $this->redirectWithMessage(
            'successMessage',
            "Event: " . $event->title . ' has been updated.',
            null,
            $redirectPath
        );
    }

    /**
     * Sends the link to a printable version of the QR screen.
     *
     * @param Request $request
     *
     * @throws \Mandrill_Error
     *
     * @return \Illuminate\Http\Response
     */
    public function sendQREmail(Request $request, $id){

        $requestUser = \Auth::user();
        if($requestUser->hasAdminRole()) {
            $redirectPath = route('admin.home');
        } else {
            $redirectPath = route('home');
        }
        $eventId = $id;
        /** @var Event $event */
        $event = Event::find($eventId);

        $data = [
            'eventId' => $eventId,
            'eventName' => $event->title
        ];
        $pdf = PDF::loadView('pdfs.qrShare', $data, [], [
            'tempDir' => public_path() . '/pdfTemp/',
            'format' => 'Letter',
            'orientation' => 'L',
        ]);

        $fileName = "qr" . $eventId;
        $directoryName = public_path() . '/pdf';
        $savePath = $directoryName . '/' . $fileName.'.pdf';
        
        $pdf->save($savePath);

        try {
            $mandrill = new \Mandrill(env('MANDRILL_API_KEY'));
            $message = array(
                'html' => view('emails.qrCode', ['eventId' => $eventId])->render(),
                'subject' => $event->title . ' ' . $event->store_number . ' - QR Code Printable Page',
                'from_email' => env('EMAIL_FROM_ADDRESS'),
                'from_name' => env('EMAIL_FROM_NAME'),
                'to' => array(
                    array(
                        'email' => $requestUser->email,
                        'name' => 'Register',
                        'type' => 'to'
                    )
                ),
                'headers' => array('Reply-To' => env('EMAIL_FROM_ADDRESS')),
                'attachments' => array(
                    array(
                        'type' => 'application/pdf',
                        'name' => $fileName . '.pdf',
                        'content' => base64_encode(file_get_contents($savePath))
                    )
                ),
            );

            $result = $mandrill->messages->send($message);

            if (!empty($result[0]) && !empty($result[0]['status'])) {
                if($result[0]['status'] == 'rejected')
                {
                    Session::flash('errorMessage', 'There was a problem sending the QR Page, please contact support at: ' . env('USER_SUPPORT_EMAIL_ADDRESS'));
                    return redirect($redirectPath);
                }
            }

        } catch(\Mandrill_Error $e) {
            // Mandrill errors are thrown as exceptions
//            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
//            throw $e;

            Session::flash('errorMessage', 'There was a problem sending the report, please contact support at: ' . env('USER_SUPPORT_EMAIL_ADDRESS'));
            return redirect($redirectPath);

        }

        return $this->redirectWithMessage(
            'successMessage',
            "Printable QR Page for event: " . $event->title . ' has been emailed to '. $requestUser->email .'.',
            null,
            $redirectPath
        );
    }

    /**
     * Show the event edit form.
     *
     * @param Request $request
     *
     * @throws \Mandrill_Error
     *
     * @return \Illuminate\Http\Response
     */
    public function winnersSelect(Request $request)
    {
        /** @var User $requestUser */
        $requestUser = \Auth::user();
        $winnerCount = (int) $request->get('winner_count');
        $eventId = (int) $request->get('event_id');
        /** @var Event $event */
        $event = Event::find($eventId);
        $now = new Carbon();

        if(empty($eventId) || empty($event)) {
            return $this->redirectWithMessage('errorMessage', 'Could not find event.', $request);
        }
        if($event->created_by_id !== $requestUser->id && !$requestUser->hasAdminRole()) {
            // Currently only the user that created event can select winners
            return $this->redirectWithMessage('errorMessage', 'Winners can only be selected by person who created event.', $request);
        }

        $nonWinnerCount = $event->nonWinnerCount();

        if ($nonWinnerCount < $winnerCount) {
            return $this->redirectWithMessage(
                'errorMessage',
                'Cannot select ' . $winnerCount .' winners, because there are only '. $nonWinnerCount. ' participants still eligible to win event.',
                $request
            );
        }

        if($requestUser->hasAdminRole()) {
            $redirectPath = route('admin.home');
        } else {
            $redirectPath = route('home');
        }


        $event->nonWinners()
            ->inRandomOrder()
            ->limit($winnerCount)
            ->update(['selected_by_id' => $requestUser->id,'selected_date' => $now]);

        $eventRecords = Participant::query()
            ->join('events', 'participants.event_id', '=', 'events.id')
            ->leftJoin('users as cu', 'participants.created_by_id', '=', 'cu.id')
            ->leftJoin('users as su', 'participants.selected_by_id', '=', 'su.id')
            ->where('participants.event_id', '=', $eventId)
            ->whereNotNull('participants.selected_date')
            ->select(\DB::raw('
                events.title as event_title,
                events.store_number as event_store_number,
                DATE_FORMAT(events.start_date, "%m/%d/%Y") as event_start_date,
                DATE_FORMAT(events.end_date, "%m/%d/%Y") as event_end_date,
                events.region as event_region,
                events.state as event_state,
                events.default_language as event_language,
                participants.first_name as participant_first_name,
                participants.last_name as participant_last_name,
                participants.email as participant_email,
                participants.phone as participant_phone,
                participants.zip_code as participant_zip_code,
                participants.language as language,
                CASE WHEN participants.is_customer <> 0 THEN "yes" ELSE "no" END as participant_is_customer,
                CASE WHEN participants.is_contact <> 0 THEN "yes" ELSE "no" END as participant_is_contact,
                DATE_FORMAT(participants.created_at, "%m/%d/%Y") as participant_created_date,
                cu.first_name as creating_user_first_name,
                cu.last_name as creating_user_last_name,
                cu.email as creating_user_email,
                DATE_FORMAT(CONVERT_TZ(participants.selected_date, "+00:00", "-04:00"), "%m/%d/%Y") as participant_selected_date,
                DATE_FORMAT(CONVERT_TZ(participants.selected_date, "+00:00", "-04:00"), "%l:%i:%s %p") as participant_selected_time,
                su.first_name as selecting_user_first_name,
                su.last_name as selecting_user_last_name,
                su.email as selecting_user_email
                ')
            )
            ->orderBy('events.title', 'asc')
            ->orderBy('participants.selected_date', 'desc')
            ->get()
            ->toArray();

        $rowTitles = [
            'Event Name',
            'Event Store Number',
            'Event Start Date',
            'Event End Date',
            'Event Region',
            'Event State',
            'Event Language',
            'Participant First Name',
            'Participant Last Name',
            'Participant Email',
            'Participant Phone',
            'Participant Zip Code',
            'Participant Language',
            'Participant Is Customer',
            'Participant Can Contact',
            'Participant Created Date',
            'Created By First Name',
            'Created By Last Name',
            'Created By Email',
            'Selected Date',
            'Selected Time (EST)',
            'Selected By First',
            'Selected By Last',
            'Selected By Email',
        ];

        //README: The below functionality emails a list of the winners out in excel format. They've asked for this feature to be disabled,
        //but I'm leaving the code here in case it ever needs to be re-enabled.

        /* $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', $event->title . ' Winners. ' . count($eventRecords) . 'Records');
        $sheet->setCellValue('B1', $event->description);

        $sheet->fromArray($rowTitles, null, 'A2');

        for ($i = 0; $i < (count($eventRecords)); $i++){
            $sheet->fromArray($eventRecords[$i], null, 'A' . (3 + $i));
        }

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25); // Event Name
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Event Store Number
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15); // Event Start Date
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15); // Event End Date
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15); // Event Region
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15); // Event State
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15); // Event Default Language
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(18); // Participant First Name
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(18); // Participant Last Name
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25); // Participant Email
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15); // Participant Phone
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15); // Participant Zip Code
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15); // Participant Language
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15); // Participant Is Customer
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(15); // Participant Can Be Contacted
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(20); // Participant Created Date
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(18); // Created By First Name
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(18); // Created By Last Name
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(20); // Created By Email
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(20); // Selected Date
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(20); // Selected Time
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(15); // Selected By First
        $spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(18); // Selected By Last
        $spreadsheet->getActiveSheet()->getColumnDimension('X')->setWidth(18); // Selected By Email

        $writer = new Xls($spreadsheet);


        $timestamp = Carbon::now()->format('m-d-Y_') . time();

        $filename = 'EventWinnersReportGeneratedOn' . $timestamp ;
        */
        /**
         * Create temporary files as we
         * save .xls file,
         * zip the file,
         * email file to user,
         * remove temporary files
         */

        //The name of the directory that we need to create.
        /*
        $directoryName = public_path() . '/filestorage/eventwinners';

        $savePath = $directoryName . '/' . $filename.'.xls';
        $zipSavePath = $directoryName . '/' . $filename . '.zip';

//        $filename = 'Event Winners Selected' . Carbon::now()->format('m-d-Y_') . time() .'.xls';
//
//        $savePath = public_path() . '/filestorage/eventwinners/' . $filename;

        $writer->save($savePath);

        unset($sheet);

        // Zip .xls file inside same directory
        echo system('zip -j ' . $zipSavePath . ' ' . $savePath);

        try {
            $mandrill = new \Mandrill(env('MANDRILL_API_KEY'));
            $message = array(
                'html' => view('emails.eventWinners')->render(),
                'subject' => $event->title . ' ' . $event->store_number . ' - Event Winners Export',
                'from_email' => env('EMAIL_FROM_ADDRESS'),
                'from_name' => env('EMAIL_FROM_NAME'),
                'to' => array(
                    array(
                        'email' => $requestUser->email,
                        'name' => 'Register',
                        'type' => 'to'
                    )
                ),
                'headers' => array('Reply-To' => env('EMAIL_FROM_ADDRESS')),
                'attachments' => array(
                    array(
                        'type' => 'application/vnd.ms-excel',
                        'name' => $filename . '.xls',
                        'content' => base64_encode(file_get_contents($savePath))
                    )
                ),
            );

            $result = $mandrill->messages->send($message);

            if (!empty($result[0]) && !empty($result[0]['status'])) {
                if($result[0]['status'] == 'rejected')
                {
                    Session::flash('errorMessage', 'There was a problem sending the report, please contact support at: ' . env('USER_SUPPORT_EMAIL_ADDRESS'));
                    return redirect($redirectPath);
                }
            }

        } catch(\Mandrill_Error $e) {
            // Mandrill errors are thrown as exceptions
//            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
//            throw $e;

            Session::flash('errorMessage', 'There was a problem sending the report, please contact support at: ' . env('USER_SUPPORT_EMAIL_ADDRESS'));
            return redirect($redirectPath);

        }

        unlink($savePath);
        */
        // return $this->redirectWithMessage(
        //     'successMessage',
        //     "Winners for event: " . $event->title . ' have been emailed to '. $requestUser->email .'.',
        //     null,
        //     $redirectPath
        // );

        return view('events.winners')->with(['event' => $event, 'winners' => $eventRecords, 'winnerCount' => $winnerCount]);
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
