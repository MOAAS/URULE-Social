<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Content;
use App\FriendRequest;
use App\GroupOfFriends;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    private function getPosts($id, $offset, $limit) {
        return Content::with('post', 'author')
            ->join('post', 'post_id', '=', 'content_id')
            ->whereRaw('author_id = :id')
            ->whereRaw('can_view_post(:user_id, author_id, private)')
            ->orderBy('content_date', 'DESC')
            ->offset($offset)
            ->take($limit)
            ->setBindings([
                'id' => $id,
                'user_id' => Auth::check() ? Auth::user()->user_id : null
            ])->get();
    }

    public function post_api(Request $request, $id) {
        $request->validate(['offset' => 'integer|min:0', 'limit' => 'integer|min:1|max:500']);

        $offset = $request->offset ?: 0;
        $limit = $request->limit ?: 0;

        $posts = $this->getPosts($id, $offset, $limit);
        return response()->json($posts);
    }

    public function show($id) {
        $profile_user = User::findOrFail($id);

        $posts = $this->getPosts($id, 0, 20);

        if (Auth::check())
            return view('pages.profile', [
                'user' => Auth::user(), 
                'profile_user' => $profile_user, 
                'self' => Auth::user()->user_id == $id,
                'friends_with' => Auth::user()->friends_with($profile_user),
                'requested' => FriendRequest::where('user_from', Auth::user()->user_id)->where('user_to', $id)->exists(),
                'received' => FriendRequest::where('user_to', Auth::user()->user_id)->where('user_from', $id)->exists(),
                'posts' => $posts
            ]);
        else return view('pages.profile', [
            'user' => null,
            'profile_user' => $profile_user, 
            'self' => false,
            'friends_with' => false,
            'requested' => false,
            'received' => false,
            'posts' => $posts
        ]);
    }

    public function show_friends($id) {
        $profile_user = User::findOrFail($id);

        if (!Auth::check() || Auth::user()->user_id != $id) {
            return view('pages.friends.list', [
                'user' => Auth::user(), 
                'profile_user' => $profile_user, 
                'groups' => [],
                'grouplessFriends' => $profile_user->friends()->orderBy('name')->get()
            ]);
        }

        $groups = GroupOfFriends::with('members')
            ->where('owner_id', $id)
            ->orderBy('group_name')
            ->get();

        $grouplessFriends = User::join(DB::raw("friends_of($id)"), 'user_id', '=', 'friend_id')
            ->whereNotExists(function($query) use ($id) {
                $query->select(DB::raw('group_name'))
                    ->from('group_of_friends')->leftJoin('group_member', 'group_of_friends.group_id', 'group_member.group_id')
                    ->where('owner_id', $id)->where('user_id', DB::raw('friend_id'));
            })
            ->orderBy('name')
            ->get();

        $allFriends = collect([]);

        foreach ($groups as $group) {
            foreach ($group->members as $member) {
                if ($allFriends->where('user_id', $member->user_id)->count() == 0)
                    $allFriends->push($member);  
            }
        }
                
        foreach ($grouplessFriends as $friend)
            $allFriends->push($friend);

        foreach ($groups as $group)
            $group->notInGroup = $allFriends->diff($group->members)->sortBy('name');    
            
        return view('pages.friends.list', [
            'user' => Auth::user(), 
            'profile_user' => $profile_user, 
            'groups' => $groups,
            'grouplessFriends' => $grouplessFriends,
        ]);
    }

    public function update_info(Request $request) {
        $this->authorize('update', User::class);

        $request->validate([
            'name' => 'required|max:' . User::MAX_NAME_LENGTH,
            'birthdate' => 'date_format:Y-m-d|before:today',
            'location' => 'max:' . User::MAX_LOCATION_LENGTH,
            'picture' => 'mimes:jpeg,jpg,png,gif|max:10000',
            'banner' => 'mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        $user = Auth::user();

        if ($request->hasFile("picture")) {
            Image::make($request->file('picture'))->save($user->getAvatarPath(), 60);
        }

        if ($request->hasFile("banner")) {
            Image::make($request->file('banner'))->save($user->getBannerPath(), 60);
        }

        $user->name = $request->name;
        $user->birthday = $request->birthday;
        $user->location = $request->location;
        $user->save();

        return response()->json($user);
    }


    public function update_email(Request $request) {
        $this->authorize('update', User::class);
        $user = Auth::user();

        if($user->isGoogleAccount())
            abort(403, "Cannot update a google account email.");
        $request->validate([
            'curr_password_mail' => 'required',
            'new_email' => 'required|string|email|max:' . User::MAX_EMAIL_LENGTH . '|unique:users,email'
        ]);



        if (!Hash::check($request->curr_password_mail, $user->password))
            abort(401, "Incorrect password");

        $user->email = $request->new_email;
        $user->save();
    }


    public function update_password(Request $request) {
        $this->authorize('update', User::class);

        $user = Auth::user();
        if($user->isGoogleAccount())
            abort(403, "Cannot update a google account password.");
        $request->validate([
            'curr_password_pass' => 'required',
            'new_password' => 'required|string|min:' . User::MIN_PASSWORD_LENGTH . 'max:' . User::MAX_PASSWORD_LENGTH . '|confirmed',
        ]);


        if (!Hash::check($request->curr_password_pass, $user->password))
            abort(401, "Incorrect password");

        $user->password = bcrypt($request->new_password);
        $user->save();
    }

    public function delete(Request $request) {
        $this->authorize('delete', User::class);

        if (file_exists(Auth::user()->getAvatarPath()))
            unlink(Auth::user()->getAvatarPath());

        if (file_exists(Auth::user()->getBannerPath()))
            unlink(Auth::user()->getBannerPath());

        Auth::user()->delete();
        Auth::logout();

        return redirect('login');
    }
}
