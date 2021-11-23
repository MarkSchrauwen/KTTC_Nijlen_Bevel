<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{

    public function index(Request $request, $array_notifications = null) {
            $selectedNotifications = json_decode($array_notifications);
            $notifications = auth()->user()->unreadNotifications
                ->whereIn('id',$selectedNotifications);
            return view('admin.notifications')->with(compact('notifications'));
    }



    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }
}
