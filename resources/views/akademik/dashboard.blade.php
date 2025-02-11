@extends('akademik.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content_header')
    <h1>Welcome to the Akademik Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6 mt-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingApprovals }}</h3>
                    <p>Pending Approvals</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
@stop
