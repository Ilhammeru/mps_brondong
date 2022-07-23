@extends('layouts.master')
@section('content')
    {{-- begin::row-action --}}
    <div class="row mb-5">
        <div class="col">
            <div class="card card-flush">
                <div class="card-body p-3">
                    <div class="text-start">
                        <a href="{{ route('trainings.index') }}" class="btn btn-light-primary">
                            <i class="fas fa-chevron-left me-3"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end::row-action --}}
    {{-- begin::row-detail --}}
    <div class="row mb-5">
        <div class="col">
            <div class="card card-flush">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Nama Training</td>
                                        <td>:</td>
                                        <td><b>{{ $training->name }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>Pelaksanaan Training</td>
                                        <td>:</td>
                                        <td>
                                            <b>{{ formatIndonesiaDate($training->training_date) . ' ' . date('H:i', strtotime($training->training_date)) }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tempat</td>
                                        <td>:</td>
                                        <td>
                                            <b>{{ $training->venue ?? '-' }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>PIC</td>
                                        <td>:</td>
                                        <td>
                                            <b>{{ $pic }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Peserta</td>
                                        <td>:</td>
                                        <td>
                                            <b>
                                                @if ($training->participant != 'null')
                                                    {{ count(json_decode($training->participant, TRUE)) }}
                                                @else
                                                    0
                                                @endif
                                            </b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end::row-detail --}}

    {{-- begin::row-questionnaire --}}
    <div class="row">
        <div class="col">
            <div class="card card-flush">
                <div class="card-body">
                    <h3 class="card-title">Kuisioner</h3>
                    <div class="empty text-center">
                        <img src="{{ asset('images/empty_questionnaire.jpg') }}" style="width: 400px; height: auto;" alt="">
                        <h4>Belum Ada Data</h4>
                        <a class="btn btn-primary" href="#">Isi Kuisioner</a>
                        {{-- <a class="btn btn-primary" href="{{ route('trainings.form.questionnaire', $training->id) }}">Isi Kuisioner</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end::row-questionnaire --}}
@endsection