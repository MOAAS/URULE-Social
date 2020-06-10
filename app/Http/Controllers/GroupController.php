<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\User;
use App\Friend;
use App\GroupOfFriends;
use App\GroupMember;

class GroupController extends Controller
{
    public function create(Request $request) {
        $request->validate(['name' => 'required|unique:group_of_friends,group_name|max:' . GroupOfFriends::MAX_NAME_LENGTH]);

        $this->authorize('create', GroupOfFriends::class);

        $group = new GroupOfFriends();
        $group->owner_id = Auth::user()->user_id;
        $group->group_name = $request->name;
        $group->save();
    }

    public function delete(Request $request, $id) {
        $group = GroupOfFriends::findOrFail($id);

        $this->authorize('delete', $group);

        $group->delete();
    }

    public function rename(Request $request, $id) {
        $request->validate(['name' => 'required|unique:group_of_friends,group_name|max:' . GroupOfFriends::MAX_NAME_LENGTH]);

        $group = GroupOfFriends::findOrFail($id);

        $this->authorize('update', $group);

        $group->group_name = $request->name;
        $group->save();
    }

    
    public function add_member(Request $request, $id, $friend_id) {
        $group = GroupOfFriends::findOrFail($id);
        $user = User::findOrFail($friend_id);

        $member = new GroupMember();
        $member->user_id = $friend_id;
        $member->group_id = $id;

        $this->authorize('create', $member);

        $member->save();
    }
        
    public function remove_member(Request $request, $id, $friend_id) {
        $member = GroupMember::findOrFail(array($friend_id, $id));

        $this->authorize('delete', $member);

        $member->delete();
    }



}
