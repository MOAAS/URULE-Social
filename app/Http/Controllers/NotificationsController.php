<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Notification;
use Illuminate\Database\Eloquent\Model;

class NotificationsController extends Controller
{
    public function deleteNotification(Request $request) {
        $request->validate([
            'id' => 'required',
        ]);

        $id = $request->id;
        $notification = Notification::findOrFail($id);
        $this->authorize('delete', $notification);
        $notification->delete();

        return response()->json($notification);
    }
}