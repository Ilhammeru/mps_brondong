@extends('layouts.master')
@section('content')
    {{-- begin::card --}}
    <div class="card card-flush mb-5">
        <div class="card-body p-3">
            <div class="text-start">
                <a href="{{ route('leave-menstruation.index') }}" class="btn btn-light-primary">
                    <i class="fas fa-chevron-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    {{-- end::card --}}

    {{-- begin::card --}}
    <div class="card card-flush mb-5">
        <div class="card-body">
            <form action="{{ route('leave-menstruation.store') }}" id="formMenstruationLeave" method="POST">
                <div class="form-group mb-5 row">
                    <div class="col-md-4 d-flex justify-content-start align-items-center">
                        <div>
                            <label for="" class="col-form-label p-0">Data Karyawan</label>
                            <p class="mb-0" style="color: #A3A3A3;">
                                Pilih Nama Karyawan. <br> Divisi dan Posisi akan otomatis terisi
                            </p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col">
                                <label for="employeeName" class="col-form-label">Nama</label>
                                <select name="employee_id[]" multiple="multiple" id="employeeName" class="form-select form-control">
                                    @foreach ($employees as $item)
                                        <option value="{{ $item->id }}">{{ $item->employee_id . ' - ' . $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="text-end">
                        <button class="btn btn-primary" id="btnSave" type="button" onclick="save()">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- end::card --}}
@endsection

@push('scripts')
    <script>
        $('#employeeName').select2();

        let form = $('#formMenstruationLeave');
        let btnSave = $('#btnSave');

        function save() {
            let data = form.serialize();
            let url = form.attr('action');
            let method = form.attr('method');

            $.ajax({
                type: method,
                url: url,
                data: data,
                dataType: "json",
                beforeSend: function() {
                    btnSave.text('Menyimpan data ...');
                    btnSave.prop('disabled', true);
                },
                success: function(res) {
                    console.log(res);
                    btnSave.text('Simpan');
                    btnSave.prop('disabled', false);
                    iziToast['success']({
                        message: 'Data Cuti Haid berhasil di simpan',
                        position: "topRight"
                    });
                    window.location.href = "{{ route('leave-menstruation.index') }}";
                },
                error: function(err) {
                    handleError(err);
                }
            })
        }
    </script>
@endpush