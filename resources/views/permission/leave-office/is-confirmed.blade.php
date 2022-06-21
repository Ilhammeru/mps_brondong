@extends('layouts.auth')

@section('content')
<!--begin::Content-->
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <!--begin::Logo-->
    <a href="{{ route('dashboard') }}" class="mb-12">
        <img alt="Logo" src="{{ asset('images/logo_mps_1.png') }}" class="h-40px" />
    </a>
    <!--end::Logo-->
    <!--begin::Wrapper-->
    <div class="w-lg-1000px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <div class="container text-center">
            <h2 class="text-success mb-5">Izin Berhasil di setujui</h2>
            <p class="lead mb-5">
                Tiket izin telah disetujui pada tanggal {{ date('d F Y H:i', strtotime($data->updated_at)) }} oleh {{ $checkedBy->name }} dengan detail:
            </p>
        </div>
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection