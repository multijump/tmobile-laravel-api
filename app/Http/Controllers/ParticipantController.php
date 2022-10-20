<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {

    }

    /**
     * Show the sign up form for a participant to register for an event.
     *
     * @return \Illuminate\Http\Response
     */
    public function register($id)
    {
        $event = Event::find($id);

        if (empty($event)) {
            return $this->redirectWithMessage( 'errorMessage', 'Could Not Find Event.');
        }

        return view('participants.register')->with(['event' => $event]);
    }

    /**
     * Show the sign up form for a participant to register for an event.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicRegister($id)
    {
        $event = Event::find($id);

        if (empty($event)) {
            return $this->redirectWithMessage( 'errorMessage', 'Could Not Find Event.');
        }
        if(!$event->public){
            return $this->redirectWithMessage( 'errorMessage', 'Event is not public.');
        }

        return view('participants.register')->with(['event' => $event]);
    }

    /**
     * Sign up the participant.
     *
     * @return \Illuminate\Http\Response
     */
    public function registerStore(Request $request, $id)
    {
        try {
            $requestUser = User::where('api_token', $request->get('api_token'))->first();

            if(empty($request->get('event_id'))){
                return response()->json(['errors'=>['Event ID is required.']]);
            }
            $requestEvent =  Event::find($request->get('event_id'));
            if (empty($requestEvent)) {
                return response()->json(['errors'=>['No event found with that ID.']]);
            }
            if((!$requestEvent->public) && empty($requestUser)){
                return response()->json(['errors'=>['This event is not accepting public API submissions.']]);
            }

            $messages = [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'email.required' => 'Email is required.',
                'email.regex' => 'Email must be a valid t-mobile.com email address.',
                'phone.required' => 'Phone number is required.',
                'zip_code.required' => 'Zip code is required.',
                'is_contact.required' => 'Please select if we can contact you.',
                'event_id.required' => 'Could not find event.',
            ];

            $validator = Validator::make($request->toArray(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255'

                ],
                'phone' => ['required', 'string', 'max:255'],
                'zip_code' => ['required', 'string', 'max:255'],
                'is_contact' => ['required'],
                'event_id' => ['required'],
            ],$messages);

            if ($validator->fails()) {
                return response()->json(['errors'=>$validator->errors()->all()]);

            }

        } catch (\Exception $e) {

            header('HTTP/1.1 500 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(['message' => 'An Error Occurred', 'code' => $e->getCode()]));
        }

        try {

            $firstName = $request->get('first_name');
            $lastName = $request->get('last_name');
            $email = $request->get('email');
            $phone = $request->get('phone');
            $zipCode = $request->get('zip_code');
            $language = $request->get('language');
            if($request->get('is_customer') == null){
                $isCustomer = null;
            }else{
                $isCustomer =  (bool) $request->get('is_customer');
            }
            $currentCarrier = $request->get('current_carrier');
            $isContact = (bool) $request->get('is_contact');
            $eventId = (int) $request->get('event_id');
            $event = Event::find($eventId);

            if (empty($event)) {
                throw new \Exception('Could not find event.', 400);
            }

            //Check for duplicates
            $existing = Participant::where('email', $email)->where('first_name', $firstName)->where('last_name', $lastName)->where('event_id', $eventId)->get();
            if(count($existing) != 0){
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode([
                    'message' => "An Error Occurred",
                    'code' => "23000",
                    "specificMessage" => "Entry rejected for duplicate values at the API level."
                ]));
            }

            $participant = new Participant();
            $participant->first_name = ucfirst($firstName);
            $participant->last_name = ucfirst($lastName);
            $participant->email = $email;
            $participant->phone = $phone;
            $participant->zip_code = $zipCode;
            $participant->language = $language;
            $participant->is_customer = (int) $isCustomer;
            $participant->current_carrier = $currentCarrier;
            $participant->is_contact = (int) $isContact;
            $participant->event_id = $eventId;
            if(!empty($requestUser)){
                $participant->created_by_id = $requestUser->id;
            }
            $participant->save();

        } catch (\Exception $e) {

            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(['message' => 'An Error Occurred', 'code' => $e->getCode(), 'specificMessage' => $e->getMessage()]));
        }

        header('HTTP/1.1 200 Success');
        header('Content-Type: application/json; charset=UTF-8');

        return json_encode([
            'status' => 'success',
            'message' => $participant,
            'code' => 200
        ]);
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
