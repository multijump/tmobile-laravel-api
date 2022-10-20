<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) {
        $data = null;

        $user = Auth::guard('api')->user();
        Log::info('USER : ' . json_encode($user));

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        if (!$this->error) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'store_number' => 'required|string',
                'workfront_id' => 'required|string',
                'start_date' => 'required|string',
                'end_date' => 'required|string',
                'description' => 'required|string',
                'region' => 'required|string',
                'state' => 'required|string',
                'default_language' => 'required|string',
                'has_surveys_enabled' => 'required|boolean',
                'public' => 'required|boolean',
                'company' => 'required|string'
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $e) {
                    $this->error[] = [
                        'message' => $e
                    ];
                }
                Log::info('Validate error : ' . json_encode($this->error));
            }
        }

        if (!$this->error) {
            $title = $request->json('title');
            $storeNumber = $request->json('store_number');
            $workfrontId = $request->json('workfront_id');
            $description = $request->json('description');
            $region = $request->json('region');
            $state = $request->json('state');
            $defaultLanguage = $request->json('default_language');
            $hasSurveysEnabled = $request->json('has_surveys_enabled');
            $public = $request->json('public');
            $company = $request->json('company');

            $startDateArray = explode('/', $request->get('start_date'));
            $endDateArray = explode('/', $request->get('end_date'));

            $startDate = Carbon::create($startDateArray[2], $startDateArray[0], $startDateArray[1]);
            $endDate = Carbon::create($endDateArray[2], $endDateArray[0], $endDateArray[1]);

            try {
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
                $event->created_by_id = $user->id;
                $event->has_surveys_enabled = !!$hasSurveysEnabled;
                $event->public = !!$public;
                $event->company = $company;
                $event->is_archived = false;

                $event->save();

                $data = $event;
            } catch (\Exception $e) {
                $this->error[] = [
                    'message' => $e->getMessage()
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
     * Handle a registration request for the application.
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request) {
        $data = null;

        if (!$id) {
            $this->error[] = [
                'message' => "Event does not exist"
            ];
        }

        $user = Auth::guard('api')->user();
        Log::info('USER : ' . json_encode($user));

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        $event = Event::find($id);
        if (!$event) {
            $this->error[] = [
                'message' => "Event does not exist"
            ];
        }

        if (!$this->error) {
            if ($title = $request->json('title')) {
                $event->title = $title;
            }

            if ($store_number = $request->json('store_number')) {
                $event->store_number = $store_number;
            }

            if ($workfront_id = $request->json('workfront_id')) {
                $event->workfront_id = $workfront_id;
            }

            if ($description = $request->json('description')) {
                $event->description = $description;
            }

            if ($region = $request->json('region')) {
                $event->region = $region;
            }

            if ($state = $request->json('state')) {
                $event->state = $state;
            }

            if ($default_language = $request->json('default_language')) {
                $event->default_language = $default_language;
            }

            if ($has_surveys_enabled = $request->json('has_surveys_enabled')) {
                $event->has_surveys_enabled = $has_surveys_enabled;
            }

            if ($public = $request->json('public')) {
                $event->public = $public;
            }

            if ($company = $request->json('company')) {
                $event->company = $company;
            }

            if ($start_date = $request->json('start_date')) {
                $startDateArray = explode('/', $start_date);
                $startDate = Carbon::create($startDateArray[2], $startDateArray[0], $startDateArray[1]);
                $event->start_date = $startDate;
            }

            if ($end_date = $request->json('end_date')) {
                $endDateArray = explode('/', $end_date);
                $endDate = Carbon::create($endDateArray[2], $endDateArray[0], $endDateArray[1]);
                $event->end_date = $endDate;
            }

            try {
                $event->save();

                $data = $event;
            } catch (\Exception $e) {
                $this->error[] = [
                    'message' => $e->getMessage()
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
     * Handle a registration request for the application.
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function get($id, Request $request) {
        $data = null;

        if (!$id) {
            $this->error[] = [
                'message' => "Event does not exist"
            ];
        }

        $user = Auth::guard('api')->user();

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        $event = Event::find($id);
        $data = $event->toArray();
        if (!$event) {
            $this->error[] = [
                'message' => "Event does not exist"
            ];
        }

        if (!$this->error) {
            $creator = $event->createdByUser()->first();
            if ($creator) {
                $data['creator'] = $creator->toArray();
            }

            $participants = $event->participants()->get();
            if ($participants) {
                $data['participants'] = $participants->toArray();
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
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUpcoming(Request $request) {
        $data = null;
        $page = $request->get('page') > 0 ? $request->get('page') : 1;
        $limit = $request->get('limit') ? $request->get('limit') : 20;

        $user = Auth::guard('api')->user();

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        if (!$this->error) {
            $now = Carbon::now()->format('Y-m-d');

            $events = Event::where('public', 1)
                ->where('is_archived', 0)
                ->where('start_date', '>', $now)
                ->orderBy('start_date', 'asc')
                ->paginate($limit);

            $data = [
                'page' => $events->currentPage(),
                'totalPages' => $events->lastPage(),
                'limit' => $events->perPage(),
                'total' => $events->total(),
                'links' => [
                    'nextPageUrl' => $events->nextPageUrl(),
                    'previousPageUrl' => $events->previousPageUrl()
                ],
                'events' => []
            ];

            foreach ($events as $event) {
                $creator = $event->createdByUser()->first();
                $eventArray = $event->toArray();
                $eventArray['creator'] = $creator->toArray();

                $participants = $event->participants()->get();
                if ($participants) {
                    $eventArray['participants'] = $participants->toArray();
                }
                $data['events'][] = $eventArray;
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
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCurrent(Request $request) {
        $data = null;
        $page = $request->get('page') > 0 ? $request->get('page') : 1;
        $limit = $request->get('limit') ? $request->get('limit') : 20;

        $user = Auth::guard('api')->user();

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        if (!$this->error) {
            $now = Carbon::now()->format('Y-m-d');

            $events = Event::where('public', 1)
                ->where('is_archived', 0)
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->orderBy('start_date', 'asc')
                ->paginate($limit);

            $data = [
                'page' => $events->currentPage(),
                'totalPages' => $events->lastPage(),
                'limit' => $events->perPage(),
                'total' => $events->total(),
                'links' => [
                    'nextPageUrl' => $events->nextPageUrl(),
                    'previousPageUrl' => $events->previousPageUrl()
                ],
                'events' => []
            ];

            foreach ($events as $event) {
                $creator = $event->createdByUser()->first();
                $eventArray = $event->toArray();
                $eventArray['creator'] = $creator->toArray();

                $participants = $event->participants()->get();
                if ($participants) {
                    $eventArray['participants'] = $participants->toArray();
                }
                $data['events'][] = $eventArray;
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
}
