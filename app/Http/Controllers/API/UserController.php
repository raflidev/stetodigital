<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use PasswordValidationRules;
    public function login(Request $request)
    {
        try {
            // validasi input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // cek Credentials Login

            $credentials = request(['email', 'password']);
            // var_dump($credentials);
            // die;
            // if (!Auth::attempt($credentials)) {
            //     return ResponseFormatter::error([
            //         'message' => 'Unauthorized'
            //     ], 'Authentication Failed', 500);
            // }

            // jika hash tidak sesuai muncul alert
            $user = User::where('email', $request->email)->first();
            // if (!Hash::check($request->password, $user->password, [])) {
            //     throw new \Exception('Invalid Credentials');
            // }
            if (($request->password != $user->password)) {
                throw new \Exception('Invalid Credentials');
            }

            // jika berhasil
            $token = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'something went wrong',
                'error' => $error
            ], 'Authentication failed', 500);
        }
    }

    public function register(Request $request)
    {
        try {
            // validasi
            // $validator = $request->validate([
            //     'name' => ['required', 'string', 'max:255'],
            //     'email' => ['required', 'string', 'max:255', 'unique:users'],
            //     'password' => $this->passwordRules()
            // ]);
            //set validation
            $validator = Validator::make($request->all(), [
                'name'      => 'required',
                'email'     => 'required|email|unique:users',
                'password'  => 'required|min:8|confirmed'
            ]);
            //if validation fails
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'ktp' => $request->ktp,
                'role_id' => $request->role_id,
                'phonenumber' => $request->phonenumber,
                'gender' => $request->gender,
                'password' => $request->password,
            ]);
            //return response JSON user is created
            if ($user) {
                return response()->json([
                    'success' => true,
                    'user'    => $user,
                ], 201);
            } else {
                //return JSON process insert failed
                return response()->json([
                    'success' => false,
                ], 409);
            }
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'something went wrong',
                'error' => $error
            ], 'Authentication failed tenan', 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token, 'Token Revoked');
    }

    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'data profile user berhasil diambil');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $user->update($data);
        return ResponseFormatter::success($user, 'Profile Updated');
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'Updated Photo fails',
                400
            );
        }

        if ($request->file('file')) {
            $file = $request->file->store('assets/user', 'public');

            // simpan foto ke db dengan (urlnya)
            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();

            return ResponseFormatter::success([$file, 'File Success Upload']);
        }
    }
}
