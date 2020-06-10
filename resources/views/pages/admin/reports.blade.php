@extends('layouts.admin', ['admin_tab' => 'reports'])

@section('title', 'Admin - Reports')

@section('content_admin')
    @if(count($reports) > 0)
    <table class="table">
         <thead class="thead-light">
           <tr>
               <th scope="col">Reports</th>
               <th scope="col" class="small-hide">#</th>
               <th scope="col"> Preview</th>
               <th scope="col" class="xsmall-hide">Author</th>
               <th scope="col" id="action-col">Action</th>
           </tr>
         </thead>
         <tbody>
        @foreach ($reports as $report)
            @include('partials.admin.report_admin', ['report' => $report])
        @endforeach
        </tbody>
    </table>
    @else
        <div class="container text-center mt-5 h5 no-reports">No reported content was found. Good job!</div>
    @endif

    <div class="row d-flex justify-content-center">
        {{$reports->links()}}
    </div>
@endsection