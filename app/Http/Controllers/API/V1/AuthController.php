<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'businessName' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'bizState' => 'required|string'
        ]);

        $user = User::create([
            'firstname' => $fields['firstName'],
            'lastname' => $fields['lastName'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'verification_code' => rand(100000, 999999),
            'verification_otp_expire' => Carbon::now()->addMinutes(60),
        ]);

        // $business = Business::create([
        //     'user_id' => $user->id,
        //     'name' => $fields['businessName'],
        // ]);

        // $address = Address::create([
        //     'business_id' => $business->id,
        //     'line_1' => $fields['address'],
        //     'city' => $fields['city'],
        //     'state' => $fields['bizState'],
        //     'contact_name' => $user->firstname . " " . $user->lastname,
        //     'default' => 1,
        // ]);

        $message = [
            'message' => 'Account created successfully',
        ];

        //event(new Registered($user));

        //$user->notify(new EmailVerificationNotification());

        return response()->json([
            'success' => true,
            'verified' => false,
            'email_verified' => false,
            'user_email' => $user->email,
            'message' => 'Please verify your account'
        ], 200);



    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        //Check Email
        $user = User::with('business')->where('email', $fields['email'])->first();

        //Check Password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Email or password is incorrect',
            ], 401);
        }

        if ($user->email_verified_at === null) {
            return response([
                'message' => 'Account not verified',
            ], 401);
        }


        $token = $user->createToken('berryflow-api-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);


    }

    public function verify(Request $request)
    {

        $user = User::where('email', $request->email)->first();


        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found with this email address.'
            ], 400);
        }
        if ($user->verification_code != $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Code does not match.'
            ], 400);
        }
        else {
            $user->email_verified_at = date('Y-m-d H:m:s');
            $user->save();
            return response()->json([
                'success' => true,
                'verified' => true,
                'email_verified' => true,
                'message' => 'Your account has been successfully verified.'
            ], 200);
        }
    }

    public function resend_code(Request $request)
    {

        $user = User::where('email', $request->email)->first();


        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => translate('No user found with this email address.')
            ], 200);
        }

        $user->verification_code = rand(100000, 999999);
        $user->save();
        $user->notify(new EmailVerificationNotification());
        return response()->json([
            'success' => true,
            'verified' => false,
            'message' => translate('A verification code has been sent to your email.')
        ], 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out',
        ];
    }
}
