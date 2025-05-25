@extends('akademik.layouts.app')

@section('title', 'Sistem Informasi Pelayanan TU Fakultas Sains dan Teknologi')

@section('content_header')
    <h1>Selamat Datang di Akademik Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info mt-4">
                <div class="inner">
                    <h3>{{ $totalProposals }}</h3>
                    <p>Total Surat</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6 mt-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingApprovals }}</h3>
                    <p>Menunggu Persetujuan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6 mt-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $approvedProposals }}</h3>
                    <p>Surat Disetujui</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6 mt-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $rejectedProposals }}</h3>
                    <p>Surat Ditolak</p>
                </div>
                <div class="icon">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
        </div>
    </div>
@stop
