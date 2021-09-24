<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

use App\Http\Middleware\isAdminMiddleware as is_admin;
use App\Models\Member;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request) {
        $isAdmin = auth()->user()->isAdmin;
        $isMember = Member::where('user_id', auth()->user()->id)->first();
        if($isAdmin) {
            $redirect = 'admin/dashboard';
        } elseif ($isMember != null) {
            $redirect = 'member/dashboard';
        } else {
            $redirect = 'user/dashboard';
        }

        return $request->wantsJson()
        ? response()->json(['two_factor' => false])
        : redirect()->intended($redirect);
    }

}