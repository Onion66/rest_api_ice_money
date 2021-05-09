<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 400);
        }
        // Cek Data User
        $email = $request->email;
        $password = hash('sha256', $request->password);
        $response = User::where([
            'email' => $email,
            'password' => $password,
        ])->first();

        if (!$response) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Credentials not match',
            ], 401);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Login Success',
            'token' => $response->createToken('API Token')->plainTextToken
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 400);
        }

        $response = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => hash('sha256', $request->password),
        ]);

        if (!$response) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Database insert error',
            ], 400);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Register Success',
            'token' => $response->createToken('API Token')->plainTextToken
        ], 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 'Success',
            'message' => 'Logout Success',
        ], 200);
    }

    public function userData()
    {
        return auth()->user();
    }

    public function updateUser(Request $request)
    {
        $target = auth()->user();
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 400);
        }
        if ($target['email'] != $request->email) {
            if ($this->checkEmail($request->email)) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Email has already been taken',
                ], 400);
            }
        }
        $user = $this->getUserById($target['id']);
        $response = $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => hash('sha256', $request->password),
        ]);

        if (!$response) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Database insert error',
            ], 400);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Update User Success',
        ], 200);
    }

    private function getUserById($id)
    {
        return User::find($id);
    }

    private function checkEmail($email)
    {
        return User::where('email', $email)->first();
    }
}
