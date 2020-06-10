<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Friend;
use App\FriendRequest;
use App\UserNotification;

class FriendController extends Controller
{
    public function show_friend_requests() {
        $friend_requests = FriendRequest::user_requests()->orderBy('request_date', 'DESC')->get();

        return view('pages.friends.requests', [
            'user' => Auth::user(), 
            'friend_requests' => $friend_requests
        ]);
    }

    public function sendRequest(Request $request) {
        $request->validate([
            'id' => 'required'
        ]);

        $this->authorize('create', FriendRequest::class);
       
        $friend_request = new FriendRequest();
        $friend_request->user_from = Auth::user()->user_id;
        $friend_request->user_to = $request->id;
        $friend_request->request_date = Carbon::now();

        $friend_request->save();
    }

    public function respondRequest(Request $request, $id) {
        $request->validate([
            'accept' => 'required|boolean'
        ]);

        $this->authorize('update', FriendRequest::class);
        $friend_request = FriendRequest::findOrFail(array($id, Auth::user()->user_id));

        if($request->accept) {
            $friend = new Friend();

            $friend->user_from = min($id, Auth::user()->user_id);
            $friend->user_to = max($id, Auth::user()->user_id);
            
            DB::transaction(function () use ($friend_request, $friend) {
                $friend_request->delete();
                $friend->save();
            });

            $description = Auth::user()->name . " has accepted your friend request!";
            UserNotification::add($friend_request->user_to, $description, $friend_request->user_from);
        } else {
            $friend_request->delete();
        }
    }

    public function delete($id) {
        $this->authorize('delete', Friend::class);

        $friendship = Friend::where('user_from', Auth::user()->user_id)->where('user_to', $id)
            ->orWhere('user_from', $id)->where('user_to', Auth::user()->user_id)->firstOrFail();
        
        $friendship->delete();
    }



}
