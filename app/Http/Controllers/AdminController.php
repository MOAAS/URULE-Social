<?php

namespace App\Http\Controllers;

use App\ContentReport;
use App\UserBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Announcement;
use App\Admin;
use App\Content;
use App\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function home(Request $request)
    {
        return redirect('admin/announcements');
    }

    public function announcements(Request $request)
    {
        return view('pages.admin.announcements', ['user' => Auth::user()]);
    }

    public function users(Request $request)
    {
        //$this->authorize('users', Admin::class);
        $banned = $request->input('banned');
        $query = $request->input('query');

        if ($request->has('banned')){
            $banned = true;
            if ($query != '' && isset($query)) {
                $users = User::join('user_ban','user_id','=','user_banned')
                    ->where('user_id', 'LIKE', $query)
                    ->orWhere('name', 'LIKE', $query . '%')
                    ->paginate(5);
            } else {
                $users = User::join('user_ban','user_id','=','user_banned')
                    ->paginate(5);
            }
        }else {
            $banned = false;
            if ($query != '' && isset($query)) {
                $users = User::where('user_id', 'LIKE', $query)
                    ->orWhere('name', 'LIKE', $query . '%')
                    ->paginate(5);
            } else {
                $users = User::paginate(5);
            }
        }
        $users->appends($request->all());
        return view('pages.admin.users', ['user' => Auth::user(), 'users'=>$users,'query'=>$query, 'banned' => $banned]);
    }

    public function ban_user(Request $request, $id){
        $user = User::findOrFail($id);

        $request->validate([
            'reason' => 'max:' . UserBan::MAX_LENGTH,
        ]);

        $reason = $request->input('reason');

        $user_ban = UserBan::where('user_banned','=',$id)->first();
        if($user_ban != null)
            return;

        $user_ban = new UserBan();
        $user_ban->user_banned = $id;
        $user_ban->banned_by = Auth::user()->user_id;
        $user_ban->reason_of_ban = $reason;
        $user_ban->save();

        //return response()->json($user_ban);
    }

    public function unban_user(Request $request, $id){
        $user_ban = UserBan::where('user_banned','=',$id)->firstOrFail();
        $user_ban->delete();
      //  return response()->json($user_ban);
    }

    public function reports(Request $request)
    {
        $reports = DB::table('content_report')
        ->select(DB::raw('count(content_report.content_id) AS num_reports, content_report.content_id'))
        ->groupBy('content_report.content_id')
        ->orderBy('num_reports','DESC')
        ->paginate(5);

        foreach ($reports as $report) 
            $report->content = Content::with('author')->find($report->content_id);
    
        //print_r("<pre>".print_r($reports[0],true)."</pre>");

        return view('pages.admin.reports', ['user' => Auth::user(), 'reports'=> $reports]);
    }
}
