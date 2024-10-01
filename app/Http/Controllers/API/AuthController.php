<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Mail;


class AuthController extends BaseController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $validation = array_fill_keys(array_keys($request->all()), []);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $errors) {
                $validation[$key] = $errors;
            }
            return response()->json([
                'response' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => 'Validation error occurred', 
                'validation' => $validation,
                'data' => null
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            
            Mail::send('email.registerMail', ['email' => $request->email], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Registrasi Berhasil');
            });

            return response()->json([
                'response' => Response::HTTP_CREATED,
                'success' => true,
                'message' => 'Account created successfully',
                'validation' => $validation,
                'data' => [
                    'data' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email_or_username' => 'required',
            'password' => 'required',
        ]);
        $validation = array_fill_keys(array_keys($request->all()), []);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $errors) {
                $validation[$key] = $errors;
            }
            return response()->json([
                'response' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => 'Validation error occurred',
                'validation' => $validation,
                'data' => null
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            if (filter_var($request->email_or_username, FILTER_VALIDATE_EMAIL)) {
                if (! $token = Auth::attempt(['email' => $request->email_or_username, 'password' => $request->password])) {
                    return response()->json([
                        'response' => Response::HTTP_UNAUTHORIZED,
                        'success' => false,
                        'message' => 'email or password wrong',
                        'validation' => $validation,
                        'data' => null
                    ], Response::HTTP_UNAUTHORIZED);
                }
            } else {
                if (! $token = Auth::attempt(['username' => $request->email_or_username, 'password' => $request->password])) {
                    return response()->json([
                        'response' => Response::HTTP_UNAUTHORIZED,
                        'success' => false,
                        'message' => 'Username or password wrong',
                        'validation' => $validation,
                        'data' => null
                    ], Response::HTTP_UNAUTHORIZED);
                }
            }

            $user = User::where('email', $request->email_or_username)->orWhere('username', $request->email_or_username)->firstOrFail();                 
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'message' => 'Account login successfully',
                'validation' => $validation,
                'data' => [
                    'data' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forget(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        $validation = array_fill_keys(array_keys($request->all()), []);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $errors) {
                $validation[$key] = $errors;
            }
            return response()->json([
                'response' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => 'Validation error occurred',
                'validation' => $validation,
                'data' => null
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $token = Uuid::uuid4();
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
            ]);

            Mail::send('email.forgetPassword', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });

            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'message' => 'Email sent successfully',
                'validation' => $validation,
                'data' => null
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'response' => Response::HTTP_OK,
            'success' => true,
            'message' => 'Account logged out successfully',
            'data' => null
        ], Response::HTTP_OK);
    }
}
