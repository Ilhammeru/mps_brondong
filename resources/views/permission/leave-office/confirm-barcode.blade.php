@extends('layouts.auth')

@section('content')
<!--begin::Content-->
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <!--begin::Logo-->
    <a href="{{ route('dashboard') }}" class="mb-12">
        <img alt="Logo" src="{{ asset('images/logo_mps.png') }}" class="h-100px" />
    </a>
    <!--end::Logo-->
    <!--begin::Wrapper-->
    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        <form class="form w-100" id="formConfirmBarcode" method="POST" action="{{ route('leave-office.confirm.barcode.store', $id) }}">
            @csrf
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">Login</h1>
                <!--end::Title-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bolder text-dark">Username</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-lg @error('username') is-invalid @enderror" value="{{ old('username') }}" type="text" id="username" name="username" autocomplete="off" required/>
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-2">
                    <!--begin::Label-->
                    <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                    <!--end::Label-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Input-->
                <input class="form-control form-control-lg " type="password" id="password" name="password" autocomplete="off" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button type="button" id="btnLogin" onclick="save()" class="btn btn-lg btn-primary w-100 mb-5">
                    <span class="indicator-label">Login</span>
                </button>
                <!--end::Submit button-->
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection

@push('scripts')
    <script>
        function save() {
            let url = $('#formConfirmBarcode').attr('action');
            let method = $('#formConfirmBarcode').attr('method');
            let data = $('#formConfirmBarcode').serialize();

            if ($('#username').val() == '' || $('#password').val() == '') {
                iziToast['error']({
                    message: 'Pastikan Username dan Password Terisi',
                    position: "topRight"
                });
            } else {
                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    beforeSend: function() {
                        $('#btnLogin').attr('disabled', true);
                        $('#btnLogin').text('Memproses data ...');
                    },
                    success: function(res) {
                        console.log(res);
                        $('#btnLogin').attr('disabled', false);
                        $('#btnLogin').text('Simpan');
                        iziToast['success']({
                            message: 'Izin Berhasil Disetujui',
                            position: "topRight"
                        });
                        document.getElementById('formConfirmBarcode').reset();
                    },
                    error: function(err) {
                        console.log(err);
                        $('#btnLogin').attr('disabled', false);
                        $('#btnLogin').text('Simpan');
                        let message = err.responseJSON.message;
                        if (message == 'FAILED') {
                            iziToast['error']({
                                message: err.responseJSON.data.error,
                                position: "topRight"
                            });
                        } else {
                            iziToast['error']({
                                message: message,
                                position: "topRight"
                            });
                        }
                    }
                })
            }
        }
    </script>
@endpush
