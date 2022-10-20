<?php

namespace App\Http\Controllers\Api;

use App\Models\Participant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ParticipantController extends Controller
{
    const STATUSES = [
        'WAITING' => 'waiting',
    ];

    /**
     * get a participant.
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function get($id, Request $request) {
        $data = null;

        if (!$id) {
            $this->error[] = [
                'message' => "Participant does not exist"
            ];
        }

        $user = Auth::guard('api')->user();

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        if (!$this->error) {
            $participant = Participant::find($id);
            if ($participant) {
                $participantArray = $participant->toArray();
                $event = $participant->event()->first();

                if ($event) {
                    $participantArray['event'] = $event->toArray();
                }

                $data = $participantArray;
            } else {
                $this->error[] = [
                    'message' => "Participant does not exist"
                ];
            }
        }

        $json = [
            'success' => $this->error ? false: true
        ];

        if ($this->error) {
            $json['error'] = $this->error;
        } else {
            $json['data'] = $data;
        }

        return response()->json($json, 200);
    }

    /**
     * Get participants by status.
     *
     * @param $status
     * @param Request $request
     * @return JsonResponse
     */
    public function getParticipants($status, Request $request) {
        $data = null;
        $eventId = $request->get('event_id') ?? null;

        if (!$status) {
            $status = self::STATUSES['WAITING'];
        }

        $user = Auth::guard('api')->user();

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        $participants = Participant::whereStatus($status);

        if ($eventId) {
            $participants = $participants->where('event_id', $eventId);
        }

        $participants = $participants->get();

        foreach ($participants as $participant) {
            $data[] = $participant->toArray();
        }

        $json = [
            'success' => $this->error ? false: true
        ];

        if ($this->error) {
            $json['error'] = $this->error;
        } else {
            $json['data'] = $data;
        }

        return response()->json($json, 200);
    }

    /**
     * Get participants.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllParticipants(Request $request) {
        $data = null;
        $eventId = $request->get('event_id') ?? null;
        $status = $request->get('status') ?? null;

        if (!$status) {
            $status = self::STATUSES['WAITING'];
        }

        $user = Auth::guard('api')->user();

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        $participants = Participant::whereStatus($status);

        if ($eventId) {
            $participants = $participants->where('event_id', $eventId);
        }

        $participants = $participants->get();

        foreach ($participants as $participant) {
            $data[] = $participant->toArray();
        }

        $json = [
            'success' => $this->error ? false: true
        ];

        if ($this->error) {
            $json['error'] = $this->error;
        } else {
            $json['data'] = $data;
        }

        return response()->json($json, 200);
    }
}
