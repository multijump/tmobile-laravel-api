<?php

namespace App\Http\Controllers\Api;

use App\Events\ForgotPassword;
use App\Events\UserWelcome;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request) {
        Log::info('Start Register');
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|max:255|confirmed'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $e) {
                $this->error[] = [
                    'message' => $e
                ];
            }
            Log::info('Validate error : ' . json_encode($this->error));
        }

        $data = null;
        if (!$this->error) {
            try {
                $password = $request->json('password');
                $uniqueKey = bin2hex(random_bytes(8));
                $apiKey = bin2hex(random_bytes(8));

                $newUser = User::create([
                    'first_name' => $request->json('first_name'),
                    'last_name' => $request->json('last_name'),
                    'email' => $request->json('email'),
                    'password' => Hash::make($password),
                    'unique_key' => $uniqueKey,
                    'api_token' => Hash::make($apiKey),
                    'is_mobile' => true,
                ]);

                event(new Registered($newUser));

                // Admin could approve user but for now, just added.
                // May be removed in future by client request.
//                $now = new Carbon();
//                $newUser->approved_date = $now;
//                $newUser->accepted_date = $now;
//                $newUser->save();
//
//                event(new UserWelcome($newUser));
                ///////////////////////////////////////////////////

                $data = [
                    'token' => $newUser->api_token,
                    'uuid'  => $newUser->unique_key,
                ];
            } catch (\Exception $e) {
                Log::info('Error : ' . $e->getMessage());
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
     * Handle a logout request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request) {
        $user = Auth::guard('api')->user();

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        if (!$this->error) {
            Auth::guard('api')->user()->token()->revoke();
        }

        $json = [
            'success' => $this->error ? false: true
        ];

        if ($this->error) {
            $json['error'] = $this->error;
        } else {
            $json['data'] = [];
        }

        return response()->json($json, 200);
    }

    /**
     * Handle a login request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request) {
        Log::info('Start Login');
        $validator = Validator::make($request->all(), [
            'email'      => 'required|string',
            'password'   => 'required|string'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $e) {
                $this->error[] = [
                    'message' => $e
                ];
            }
            Log::info('Validate error : ' . json_encode($this->error));
        }

        $login = [
            'email'      => 'required|string',
            'password'   => 'required|string'
        ];

        $data = null;

        if (!$this->error) {
            if (!auth()->attempt($login)) {
                Log::info('USER LOGIN FAILED');
                $this->error[] = [
                    'message' => 'Invalid email or password'
                ];
            } else {
                $user = auth()->user();
                $data = [
                    'user' => $user
                ];
                Log::info('USER : ' . json_encode($user));
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
     * Handle a get request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request) {
        $user = Auth::guard('api')->user();
        Log::info('USER : ' . json_encode($user));

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        if (!$this->error) {
            if ($request->json('first_name')) {
                $user->first_name = $request->json('first_name');
            }

            if ($request->json('last_name')) {
                $user->last_name = $request->json('last_name');
            }

            if ($request->json('email')) {
                $user->email = $request->json('email');
            }

            if ($request->json('password') && $request->json('password_confirmation')) {
                if ($request->json('password') === $request->json('password_confirmation')) {
                    $user->password = Hash::make($request->json('password'));
                } else {
                    $this->error[] = [
                        'message' => "Password "
                    ];
                }
            }
        }

        if (!$this->error) {
            $user->save();
        }

        $json = [
            'success' => $this->error ? false: true
        ];

        if ($this->error) {
            $json['error'] = $this->error;
        } else {
            $json['data'] = $user;
        }

        return response()->json($json, 200);
    }

    /**
     * Handle a get request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request) {
        $user = Auth::guard('api')->user();
        Log::info('USER : ' . json_encode($user));

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        $json = [
            'success' => $this->error ? false: true
        ];

        if ($this->error) {
            $json['error'] = $this->error;
        } else {
            $json['data'] = $user;
        }

        return response()->json($json, 200);
    }

    /**
     * Resend verification email.
     *
     * @param $uuid
     * @param Request $request
     * @return JsonResponse
     */
    public function resend($uuid, Request $request) {
        $user = User::where('unique_key', $uuid)->first();

        if (!$user) {
            $this->error[] = [
                'message' => "User does not exist"
            ];
        }

        if (!$this->error) {
            event(new Registered($user));
        }

        $json = [
            'success' => $this->error ? false: true
        ];

        if ($this->error) {
            $json['error'] = $this->error;
        } else {
            $json['data'] = [
                'token' => $user->api_token,
                'uuid'  => $user->unique_key,
                'message' => 'Verification email has been sent'
            ];
        }

        return response()->json($json, 200);
    }

    /**
     * Forgot password.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request) {
        $email = $request->json('email') ?? null;

        if (!$email) {
            $this->error[] = [
                'message' => "Email address is invalid"
            ];
        }

        $user = null;
        if (!$this->error) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->error[] = [
                    'message' => "User does not exist"
                ];
            }
        }

        if (!$this->error) {
            if ($user && $user->denied_date != null) {
                $this->error[] = [
                    'message' => "This user is invalid"
                ];
            }
        }

        if (!$this->error) {
            $code = rand(100000, 999999);
            $user->code = $code;
            $user->save();
            event(new ForgotPassword($user, $code));
        }

        $json = [
            'success' => $this->error ? false: true
        ];

        if ($this->error) {
            $json['error'] = $this->error;
        } else {
            $json['data'] = [
                'message' => 'Please check your email'
            ];
        }

        return response()->json($json, 200);
    }

    /**
     * Forgot password confirm.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPasswordConfirm(Request $request) {
        $code = $request->json('code') ?? null;

        $validator = Validator::make($request->all(), [
            'password'   => 'required|string|min:8|max:255|confirmed'
        ]);

        $user = null;
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $e) {
                $this->error[] = [
                    'message' => $e
                ];
            }
            Log::info('Validate error : ' . json_encode($this->error));
        }

        if (!$this->error) {
            $user = User::where('code', $code)->first();

            if (!$user) {
                $this->error[] = [
                    'message' => "User does not exist"
                ];
            }
        }

        if (!$this->error) {
            $user->code = null;
            $password = $request->json('password');
            $user->password = Hash::make($password);
            $user->save();
        }

        $json = [
            'success' => $this->error ? false: true
        ];

        if ($this->error) {
            $json['error'] = $this->error;
        } else {
            $json['data'] = [
                'token' => $user->api_token,
                'message' => 'Your password has been updated'
            ];
        }

        return response()->json($json, 200);
    }
}
