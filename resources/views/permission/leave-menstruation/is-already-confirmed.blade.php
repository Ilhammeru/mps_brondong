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
            <h2 class="text-success mb-5">Izin ini telah di setujui</h2>
            <p class="lead mb-5">
                Tiket izin telah disetujui pada tanggal {{ date('d F Y H:i', strtotime($data->updated_at)) }} oleh {{ $checkedBy->name }}.
            </p>
            {{-- <h1 class="mb-5"><span class="p-3 text-white bg-info mb-5">{{ implode(' ', str_split($prospect->registration_code)) }}</span></h1>
            <p class="mb-4 fs-5">Silakan lakukan pembayaran senilai <strong>{{ formatRupiah($settings->where('name', 'registration_payment')->first()->value) }}</strong> hanya melalui data rekening dibawah ini.</p>
            <h2 class="mb-4"><strong>{{ $settings->where('name', 'payment_bank')->first()->value }} </strong></h2>
            <h1 class="mb-2"><strong>{{ $settings->where('name', 'payment_account')->first()->value }}</strong></h1>
            <h2 class="mb-2"><strong>{{ $settings->where('name', 'payment_name')->first()->value }}</strong></h2>
            <p class="fs-5" >Jika telah melakukan pembayaran, simpan bukti pembayaran anda dan segera hubungi admin di <strong>{{ $settings->where('name', 'company_phone')->first()->value }}</strong></p> --}}
        </div>
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection