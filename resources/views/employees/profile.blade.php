@extends('layouts.master')

{{-- begin::styles --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/custom/filepond/filepond.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/custom/filepond/plugins-preview.css') }}">
<style>
    .accordion-button:not(.collapsed) {
        background: #fff !important;
    }

    .table-employee-detail > tbody > tr > td {
        font-size: 12px;
    }

    .ribbon {
        width: 100px;
        height: 100px;
        overflow: hidden;
        position: absolute !important;
    }
    .ribbon::before,
    .ribbon::after {
    position: absolute;
    z-index: -1;
    content: '';
    display: block;
    border: 5px solid #2980b9;
    }
    .ribbon span {
    position: absolute;
    display: block;
    width: 225px;
    padding: 5px 0;
    background-color: #00C91A;
    box-shadow: 0 5px 10px rgba(0,0,0,.1);
    color: #fff;
    font: 700 18px/1 'Lato', sans-serif;
    text-shadow: 0 1px 1px rgba(0,0,0,.2);
    text-transform: uppercase;
    text-align: center;
    font-size: 12px;
    }

    /* top left*/
    .ribbon-top-left {
    top: 0px;
    left: 0px;
    }
    .ribbon-top-left::before,
    .ribbon-top-left::after {
    border-top-color: transparent;
    border-left-color: transparent;
    }
    .ribbon-top-left::before {
    top: 0;
    right: 0;
    }
    .ribbon-top-left::after {
    bottom: 0;
    left: 0;
    }
    .ribbon-top-left span {
    right: -45px;
    top: 30px;
    transform: rotate(-45deg);
    }

    .card-jobs,
    .card-qrcode {
        height: 320px;
    }

</style>
@endpush
{{-- end::styles --}}

@section('content')

@php
    $userImage = true;
@endphp

<!--begin::Card-->
<div class="card mb-5">
    <div class="card-body p-3">
        <div class="d-flex justify-content-start">
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{ route('employees.index') }}" class="btn btn-light-primary">
                <!--begin::Svg Icon | path: icons/duotune/general/gen035.svg-->
                <i class="fas fa-chevron-left"></i>
                <!--end::Svg Icon-->Kembali</a>
                <!--end::Button-->
            </div>
            <!--end::Card toolbar-->
        </div>
    </div>
</div>
<!--end::Card-->

<div class="row">
    <div class="col-md-4">
        <!--begin::Card-->
        <div class="card mb-5 card-main-user">
            <div class="card-body">
                <!--begin::Input group-->
                <div class="d-flex justify-content-center">
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12">
                            <div class="">
                                <img src="{{ $user->photo ?? asset('images/blank.png') }}" style="border-radius: 12px; width: 250px; height: auto;" alt="">
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                </div>
                <!--end::Input group-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <div class="col-md-8">
        <!--begin::Card-->
        <div class="card mb-5 card-main-user">
            <div class="card-body">

                {{-- begin::vacinated-status --}}
                @if ($dosis3 != "")
                <div class="ribbon ribbon-top-left"><span>Vacinated</span></div>   
                @endif
                {{-- end::vacinated-status --}}

                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3>Identitas</h3>
                        <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah</a>
                    </div>
                </div>
                <table class="table table-employee-detail">
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td><b>{{ $user->name }}</b></td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td><b>{{ $user->nik }}</b></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><b>{{ $user->email }}</b></td>
                        </tr>
                        <tr>
                            <td>HP</td>
                            <td><b>{{ $user->phone }}</b></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td><b>{{ $user->gender == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</b></td>
                        </tr>
                        <tr>
                            <td>Tgl Lahir</td>
                            <td><b>{{ date('d F Y', strtotime($user->birth_date)) }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>

{{-- begin::row --}}
<div class="row mb-5">
    {{-- begin::col --}}
    <div class="col-md-8">
        <div class="card card-flush card-jobs">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3>Data Pekerjaan</h3>
                        <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah</a>
                    </div>
                </div>
                <table class="table table-employee-detail">
                    <tbody>
                        <tr>
                            <td>ID Karyawan</td>
                            <td><b>{{ $user->employee_id }}</b></td>
                        </tr>
                        <tr>
                            <td>Divisi</td>
                            <td><b>{{ $user->division->name }}</b></td>
                        </tr>
                        <tr>
                            <td>Posisi</td>
                            <td><b>{{ $user->position->name }}</b></td>
                        </tr>
                        <tr>
                            <td>Status Karyawan</td>
                            <td><b>{{ $user->employee_status == 1 ? 'KARYAWAN TETAP' : 'KONTRAK' }}</b></td>
                        </tr>
                        <tr>
                            <td>Tanggal Masuk (Magang)</td>
                            <td><b>{{ date('d F Y', strtotime($user->date_in_contract)) }}</b></td>
                        </tr>
                        @if ($user->employee_status == 1)
                        <tr>
                            <td>Tanggal Masuk (Kartap)</td>
                            <td><b>{{ date('d F Y', strtotime($user->date_in_permanent)) }}</b></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-flush card-qrcode">
            <div class="card-body">
                <h3 class="text-center">QRCode Karyawan</h3>
                <div class="qrcode d-flex align-items-center justify-content-center">
                    {!! QrCode::size(250)->generate('Haloo nama saya ' . $user->name . '. ID saya adalah ' . $user->employee_id); !!}
                </div>
            </div>
        </div>
    </div>
    {{-- end::col --}}
</div>
{{-- end::row --}}

<div class="row">
    <div class="col-md-6">
        <!--begin::Card-->
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3>Login</h3>
                        @if ($user->user != NULL)
                        <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah Password</a>
                        @endif
                    </div>
                </div>
                <table class="table table-employee-detail">
                    <tbody>
                        @if ($user->user == NULL)
                            <tr>
                                <td class="text-center">
                                    <button class="btn btn-primary" type="button">
                                        Daftartkan Sebagai Member
                                    </button>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>Username</td>
                                <td><b>{{ $user->user->username }}</b></td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td><b>********</b></td>
                            </tr>
                            <tr>
                                <td>Terakhir Login</td>
                                <td><b>02 Mei 2022</b></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <div class="col-md-6">
        <!--begin::Card-->
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3>Alamat</h3>
                        <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah</a>
                    </div>
                </div>
                <table class="table table-employee-detail">
                    <tbody>
                        <tr>
                            <td>
                                {{ $addressHelper != "" ? $addressHelper : '-' }}</br>
                                {{ $villageHelper != "" ? $villageHelper : '-' }}</br>
                                {{ $provinceHelper != "" ? $provinceHelper : '-' }} </br>
                                65146
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>

{{-- begin::accordion --}}
<div class="accordion mb-5" id="accordionFamily">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFamily" aria-expanded="true" aria-controls="collapseFamily">
                <h3>Keluarga</h3>
            </button>
        </h2>
        <div id="collapseFamily" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFamily">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        {{-- begin::table --}}
                        <table class="table table-employee-detail">
                            <tbody>
                                <tr>
                                    <td style="width: 200px;">Nama Wali</td>
                                    <td><b>{{ $user->wali_name == NULL ? '-' : strtoupper($user->wali_name) }}</b></td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;">Nomor Telfon Wali</td>
                                    <td><b>{{ $user->wali_phone == NULL ? '-' : strtoupper($user->wali_phone) }}</b></td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;">Alamat Wali</td>
                                    <td><b>{{ $user->wali_address == NULL ? '-' : strtoupper($user->wali_address) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        {{-- end::table --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end::accordion --}}

<!--begin::Card-->
<div class="card mb-5">
    <div class="card-body">
        <div class="row">
            <div class="d-flex justify-content-between">
                <h3>Riwayat Kesehatan</h3>
                <a href="" class="btn btn-light-primary btn-sm"><i class="fas fa-user-plus"></i>Tambah</a>
            </div>
        </div>
    </div>
</div>
<!--end::Card-->
{{-- begin::accordion --}}
<div class="row">
    <div class="col-md-6">
        <div class="accordion" id="accodionVaccine">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVaccine" aria-expanded="true" aria-controls="collapseVaccine">
                        <h3>Vaksin</h3>
                    </button>
                </h2>
                <div id="collapseVaccine" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accodionVaccine">
                    <div class="accordion-body">
                        <table class="table table-employee-detail">
                            <tbody>
                                <tr>
                                    <td style="width: 200px;">Jenis Vaksin</td>
                                    <td><b>{{ $user->userVaccine == NULL ? '-' : strtoupper($user->userVaccine->vaccine->name) }}</b></td>
                                </tr>
                                <tr>
                                    <td>Dosis I</td>
                                    <td><b>{!! $dosis1 != '' ? date('d F Y', strtotime($dosis1->vaccine_date)) : '<i class="fa fa-times text-danger"></i>' !!}</b></td>
                                </tr>
                                <tr>
                                    <td>Dosis II</td>
                                    <td><b>{!! $dosis2 != '' ? date('d F Y', strtotime($dosis2->vaccine_date)) : '<i class="fa fa-times text-danger"></i>' !!}</b></td>
                                </tr>
                                <tr>
                                    <td>Dosis III</td>
                                    <td><b>{!! $dosis3 != '' ? date('d F Y', strtotime($dosis3->vaccine_date)) : '<i class="fa fa-times text-danger"></i>' !!}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="accordion" id="accordionPermissionLeaveOffice">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLeaveOffice" aria-expanded="true" aria-controls="collapseLeaveOffice">
                        <h3>Izin Karyawan </h3>
                    </button>
                </h2>
                <div id="collapseLeaveOffice" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionPermissionLeaveOffice">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-9">
                                <table class="table table-employee-detail">
                                    <tbody>
                                        <tr>
                                            <td>Cuti Haid</td>
                                            <td>:</td>
                                            <td>
                                                <b>{{ $leaveMenstruation == 0 ? '-' : $leaveMenstruation }}x dalam bulan berjalan</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cuti Tahunan</td>
                                            <td>:</td>
                                            <td>
                                                <b>-</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cuti Melahirkan</td>
                                            <td>:</td>
                                            <td>
                                                <b>-</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Izin Meninggalkan Kantor</td>
                                            <td>:</td>
                                            <td>
                                                <b>{{ $permissionLeaveOffice == 0 ? '-' : $permissionLeaveOffice }}</b>
                                            </td>
                                            @if ($permissionLeaveOffice > 0)
                                                <td class="d-flex">
                                                    <i class="fas fa-info" style="font-size: 10px; cursor: pointer; color: #009ef7;" onclick="detailLeave({{ $user->id }})"></i>
                                                </td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end::accordion --}}

<div class="modal fade" id="modalLeaveOffice" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="targetDetailLeaveOffice">

            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('plugins/custom/filepond/filepond.js') }}"></script>
    <script src="{{ asset('plugins/custom/filepond/plugins-preview.js') }}"></script>
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        const userImage = document.getElementById('userImage');
        const pond = FilePond.create(userImage);

        function detailLeave(id) {
            $.ajax({
                type: "GET",
                url: "{{ url('/employees/detail/leave-office') }}" + "/" + id,
                dataType: "json",
                success: function(res) {
                    console.log(res);
                    let view = res.data.view;
                    $('#targetDetailLeaveOffice').html(view);
                    $('#modalLeaveOffice').modal('show');
                },
                error: function(err) {
                    handleError(err);
                }
            })
        }

        function detailLeaveMenstruation(id) {
            $.ajax({
                type: "GET",
                url: "{{ url('/employees/detail/leave-menstruation') }}" + "/" + id,
                dataType: "json",
                success: function(res) {
                    console.log(res);
                    let view = res.data.view;
                    $('#targetDetailLeaveOffice').html(view);
                    $('#modalLeaveOffice').modal('show');
                },
                error: function(err) {
                    handleError(err);
                }
            })
        }
    </script>
@endpush

