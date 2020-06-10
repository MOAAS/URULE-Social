<?php

namespace App\Http\Controllers;

use App\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function create(Request $request)
    {
        $request->validate([
            'content' => 'required|max:' . Announcement::MAX_LENGTH,
            'duration_num' => 'required|integer|min:1|max:999',
            'duration_unit' => 'required|in:Hours,Days,Weeks,Months'
        ]);


        $announcement = new Announcement();
        $announcement->content = $request->input('content');
        $announcement->author_id = Auth::user()->user_id;
        $duration_num = $request->input('duration_num');
        $duration_unit = $request->input('duration_unit');
        switch ($duration_unit) {
            case 'Hours':
                $announcement->duration_secs = $duration_num * 3600;
                break;
            case 'Days':
                $announcement->duration_secs = $duration_num * 86400;
                break;
            case 'Weeks':
                $announcement->duration_secs = $duration_num * 604800;
                break;
            case 'Months':
                $announcement->duration_secs = $duration_num * 2629743;
                break;
        }
        $announcement->date_of_creation = new Carbon();
        $announcement->save();

        /*
        DB::transaction(function () use ($content, $post) {
            $content->save();
            $post->post_id = $content->content_id;
            $post->save();
        });*/

        return response()->json($announcement);
    }

    public function delete(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();
        return response()->json($announcement);
    }
}
