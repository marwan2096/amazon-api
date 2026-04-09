<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\User;
use App\Notifications\EmailVerfiyNotification;
use App\Notifications\VerifiedNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function verify(VerifyOtpRequest $request)
    {

        $data = $request->validated();
        $otp = new Otp;
        $verify = $otp->validate($data['email'], $data['otp']);

        if (!$verify->status) {
            return response()->json(['message' => $verify->message], 401);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['email_verified_at' => now()]);
        $user->notify(new VerifiedNotification());

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Email verified successfully',
            'token'   => $token,
        ]);
    }


    // resend otp
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        $user->notify(new EmailVerfiyNotification());

        return response()->json(['message' => 'OTP resent successfully']);
    }
}
