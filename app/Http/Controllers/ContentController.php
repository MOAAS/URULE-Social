<?php

namespace App\Http\Controllers;

use App\ContentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Appraisal;
use App\Content;


class ContentController extends Controller
{
    public function addAppraisal(Request $request, $content_id) {
        Content::findOrFail($content_id);
        $this->authorize('create', Appraisal::class);
        $request->validate(['positive' => 'required|boolean']);

        $existingAppraisal = Appraisal::where('user_id', Auth::user()->user_id)->where('content_id', $content_id)->first();
        if($existingAppraisal != null) {
            $existingAppraisal->like = $request->positive;
            try {
                $existingAppraisal->save();
            } catch (PDOException $e) {
                abort(403, $e->getMessage());
            }
        } else {
            $appraisal = new Appraisal();
            $appraisal->content_id = $content_id;
            $appraisal->user_id = Auth::user()->user_id;
            $appraisal->like = $request->positive;
            try {
                $appraisal->save();
            } catch (PDOException $e) {
                abort(403, $e->getMessage());
            }
        }
    }

    public function deleteAppraisal($content_id) {
        $this->authorize('delete', Appraisal::class);

        Appraisal::where('user_id', Auth::user()->user_id)->where('content_id', $content_id)->delete();
    }

    public function report($content_id) {
        Content::findOrFail($content_id);

        $this->authorize('create', ContentReport::class);


        $report = ContentReport::find(array($content_id, Auth::user()->user_id));
        if ($report == null)
            $report = new ContentReport();

        $report->content_id = $content_id;
        $report->user_id = Auth::user()->user_id;

        try {
            $report->save();
        } catch (PDOException $e) {
            abort(403, $e->getMessage());
        }

        return response()->json($report);
    }
}
