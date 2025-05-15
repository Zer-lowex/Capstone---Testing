<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function registerCustomer(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:customers',
            'email' => 'nullable|email|max:255|unique:customers',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Create new customer
            $customer = Customer::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Create authentication token
            $token = $customer->createToken('CustomerAuthToken')->plainTextToken;

            // Return success response
            return response()->json([
                'message' => 'Customer registered successfully',
                'customer' => $customer,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 201);

        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function login(Request $request): JsonResponse
    {
        // Validate the incoming request
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255'
        ]);

        // Find the user by username
        $user = User::where('username', $request->username)->first();

        // Check if the user exists and the password matches
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials. Please Try Again.'
            ], 401);
        }

        if ($user->usertype !== 'Driver') {
            return response()->json([
                'message' => 'This app is for KC Prime Enterprise Drivers only.'
            ], 403); // Forbidden status code
        }

        // Generate a token for the user
        $token = $user->createToken('AppLoginToken')->plainTextToken;

        // Update the user's status to 'Online'
        $user->status = 'Online';
        $user->save();
        $usertype = $user->usertype;

        // Create activity log
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Login',
            'description' => "{$user->username} ({$usertype} ID: $user->id) has logged in",
        ]);

        // Return a successful response with the user data and token
        return response()->json([
            'message' => 'Login Successful',
            'token_type' => 'Bearer',
            'token' => $token,
            // 'user' => $user, 
        ], 200);
    }

    public function loginCustomer(Request $request): JsonResponse
    {
        // Validate the incoming request
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255'
        ]);

        // Find the customer by username
        $customer = Customer::where('username', $request->username)->first();

        // Check if the customer exists and the password matches
        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json([
                'message' => 'Invalid Credentials. Please Try Again.'
            ], 401);
        }

        // Generate a token for the customer
        $token = $customer->createToken('CustomerLoginToken')->plainTextToken;

        // Return a successful response with the token
        return response()->json([
            'message' => 'Login Successful',
            'token_type' => 'Bearer',
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user(); // Get the authenticated user

        if ($user) {
            // Revoke all tokens
            $user->tokens()->delete();

            // Update the user's status to 'Offline'
            $user->status = 'Offline';
            $user->save();
            $usertype = $user->usertype;


            // Create activity log
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Logout',
                'description' => "{$user->username} ({$usertype} ID: $user->id) has logged out",
            ]);

            // Return a successful response
            return response()->json([
                'message' => 'Logged Out Successfully',
            ], 200);
        }

        // If no user is found (unlikely), return an error
        return response()->json([
            'message' => 'User not found.',
        ], 404);
    }


    public function profile(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // Return the authenticated user's details
            return response()->json([
                'message' => 'Profile Fetched.',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,  
                    'address' => $user->address,  
                    'usertype' => $user->usertype, 
                    'status' => $user->status,  
                ]
            ], 200);
        } else {
            // Return an error if the user is not authenticated
            return response()->json([
                'message' => 'Not Authenticated.',
            ], 401);
        }
    }

    public function updateProfile(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:15', 
            'address' => 'required|string|max:255', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        try {
            // Get the authenticated user
            $user = Auth::user();

            // Update the user's profile
            $user->first_name = $request->input('firstName');
            $user->last_name = $request->input('lastName');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone'); 
            $user->address = $request->input('address'); 

            // Save the updated user data
            $user->save();

            $usertype = $user->usertype;
            // Create activity log
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Update',
                'description' => "{$user->username} ({$usertype} ID: $user->id) updated their profile.",
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile. Please try again.',
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Return validation errors as a response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();
        $usertype = $user->usertype;

        // Log the activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Update',
            'description' => "{$user->username} ({$usertype} ID: $user->id) Changed Account Password",
        ]);

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password does not match our records.',
            ]);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Return a success response
        return response()->json(['message' => 'Password Changed Successfully.'], 200);
    }
}
