@extends('layouts.master')

{{-- begin::styles --}}
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
                <a href="{{ route('employee.index') }}" class="btn btn-light-primary">
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
        <div class="card mb-5">
            <div class="card-body">
                <!--begin::Input group-->
                <div class="d-flex justify-content-center">
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('images/blank.png') }})">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-250px h-250px" style="background-image: url( {{ asset('images/blank.png') }})"></div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ganti Foto">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input type="file" id="inputUserImage" name="avatar" accept="image/jpeg, image/x-png" />
                                    <input type="hidden" name="avatar_remove" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                <!--begin::Cancel-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batal">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <!--end::Cancel-->
                                @if($userImage)
                                <!--begin::Remove-->
                                <a href="#" data-toggle="delete-profile-image">
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus Foto">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </a>
                                <!--end::Remove-->
                                @endif
                            </div>
                            <!--end::Image input-->
                            <!--begin::Hint-->
                            <div class="form-text">Tipe file yang diperbolehkan: png, jpg, jpeg.</div>
                            <!--end::Hint-->
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
                        <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah Password</a>
                    </div>
                </div>
                <table class="table table-employee-detail">
                    <tbody>
                        <tr>
                            <td>Username</td>
                            <td><b>userusername</b></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><b>********</b></td>
                        </tr>
                        <tr>
                            <td>Terakhir Login</td>
                            <td><b>02 Mei 2022</b></td>
                        </tr>
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
{{-- end::accordion --}}

<div class="modal fade" id="userImageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body pb-5">
                <div class="image-cropper">
                    <div id="userImageCropper" style="width: 320px; height: 320px;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" data-toggle="upload-image" data-username="userusername">Terapkan</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script>
        var userImage = null;

        function readUserImageFile(input) {
        if (input.files && input.files[0]) {
            $('#userImageModal').modal('show');
            var reader = new FileReader();

            reader.onload = function (e) {
            setTimeout(function () {
                userImage = new Croppie(document.getElementById('userImageCropper'), {
                viewport: {
                    width: 240,
                    height: 240,
                    type: 'square'
                },
                boundary: {
                    width: 320,
                    height: 320
                },
                url: e.target.result,
                enableExif: true
                });
            }, 500);
            };

            reader.readAsDataURL(input.files[0]);
        }
        }

        $('#inputUserImage').on('change', function () {
            readUserImageFile(this);
        });
        $('#userImageModal').on('hide.bs.modal', function (e) {
            userImage.destroy();
            $('#inputUserImage').val('');
        });
        $('#userImageModal [data-toggle="crop-image"]').on('click', function (e) {
            userImage.result({
                type: 'base64',
                format: 'jpeg',
                size: {
                width: 320,
                height: 320
                }
            }).then(function (resp) {
                $('#userImagePreview img').attr({
                    src: resp,
                    'data-upload': true,
                    'data-filename': $('#inputUserImage')[0].files[0].name
                });
                $('[data-toggle="reset-user-image"]').removeClass('d-none');
                $('#userImageModal').modal('hide');
            });
        });

        function resetUserImage() {
            var $imgTag = $('#userImagePreview img');
            $imgTag.attr({
                src: $imgTag.data('original'),
                'data-upload': false,
                'data-filename': '',
                'data-delete': false
            });
            $('[data-toggle="reset-user-image"]').addClass('d-none');
            $('[data-toggle="delete-user-image"]').removeClass('d-none');
        }

        function deleteUserImage() {
            var $imgTag = $('#userImagePreview img');
            $imgTag.attr({
                src: $imgTag.data('placeholder'),
                'data-upload': false,
                'data-filename': '',
                'data-delete': true
            });
            $('[data-toggle="reset-user-image"]').removeClass('d-none');
            $('[data-toggle="delete-user-image"]').addClass('d-none');
        }

        $('[data-toggle="reset-user-image"]').click(resetUserImage);
        $('[data-toggle="delete-user-image"]').click(deleteUserImage);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#userImageModal [data-toggle="upload-image"]').on('click', function (e) {
            var $this = $(this);
            userImage.result({
                type: 'blob',
                format: 'jpeg',
                size: {
                width: 320,
                height: 320
                }
            }).then(function (blob) {
                var formData = new FormData();
                formData.append('user_image', blob, $('#inputUserImage')[0].files[0].name);
                $.ajax({
                url:  "",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                dataType: 'json',
                error: function error(response) {
                    if (response.responseJSON.message) {
                        iziToast['error']({
                            message: response.responseJSON.message,
                            position: "topRight"
                        });
                    }
                },
                success: function success(response) {
                    Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data berhasil disimpan'
                    }).then(function (result) {
                    window.location.reload();
                    });
                },
                });
                $('#userImageModal').modal('hide');
            });
        });

        $('[data-toggle="delete-profile-image"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            Swal.fire({
                title: "Hapus gambar ini?",
                text: "Gambar akan dihapus selamanya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batalkan',
                customClass: {
                confirmButton: 'btn btn-danger mr-2',
                cancelButton: 'btn btn-secondary ml-2'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.isConfirmed) {
                $.ajax({
                    url: $this.attr('href'),
                    method: 'POST',
                    dataType: 'json',
                    error: function error(response) {
                        if (response.responseJSON.message) {
                            iziToast['error']({
                                message: response.responseJSON.message,
                                position: "topRight"
                            });
                        }
                    },
                    success: function success(data, status, xhr) {
                    window.location.reload();
                    },
                });
                }
                if(result.isDismissed){
                    window.location.reload();
                }
            });
        });
    </script>
@endpush

