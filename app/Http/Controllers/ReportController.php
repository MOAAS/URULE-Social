<?php

namespace App\Http\Controllers;

use App\ContentReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except("create");
    }

    public function clear(Request $request, $id){
        $reports = ContentReport::where('content_id',$id);
        //$count = count($reports);

        $reports->delete();
    }

    public function create(Request $request, $id){
        //
    }
}
