<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetRequest;
use App\Http\Requests\ResetRequest;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ResetController extends Controller
{
    public function forgetPassword(ForgetRequest $request)
    {

        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if ($user) {
            $user->notify(new PasswordResetNotification());
        } else {
            return response()->json([
                'success' => false,
                'message' => 'not authorize',
            ], 201);
        }
    }

    public function ResetPassword(ResetRequest $request)
    {

        $data = $request->validated();

        $otp = new Otp;
        $verify = $otp->validate($data['email'], $data['otp']);

        if (!$verify->status) {
            return response()->json(['message' => $verify->message], 401);
        }

        $user = User::where('email', $data['email'])->first();
        $user->update(['password' => Hash::make($data['password'])]);

        return response()->json(['message' => 'Password reset successfully']);
    }
}
