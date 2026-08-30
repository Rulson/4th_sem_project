<?php

namespace App\Modules\Api\Controllers;

use App\Modules\User\Models\User;
use Illuminate\Http\Request;

class NotificationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notificationList(Request $request){
        $access_token = $request->header('X-Access-Token');
        $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
        $perPage = $request->input('per_page', 7);
        $page = $request->input('page', 1);
        $notifications = $user->notifications()->paginate($perPage, ['*'], 'page', $page);
        $countData = [
            'total_count' => $user->notifications->count(),
            'unread_count' => $user->unreadNotifications()->count(),
        ];
        return response()->json(['response' => $notifications, 'count' => $countData,'message' => 'Success', 'status' => 200]);
    }


    public function markAsRead(Request $request){
        $access_token = $request->header('X-Access-Token');
        $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
        $user->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })->markAsRead();

        return response()->json(['response' => [], 'message' => 'Success', 'status' => 200]);
    }
}
