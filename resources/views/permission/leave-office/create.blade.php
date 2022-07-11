@extends('layouts.master')
@section('content')
<div class="row mb-5">
    <div class="col">
        <div class="card card-flush">
            <div class="card-body p-3">
                <div class="text-start">
                    <a href="{{ route('leave-office.index') }}" class="btn btn-light-primary">
                        <i class="fas fa-chevron-left me-3"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('leave-office.store') }}" method="POST" id="formLeaveOffice">

    <div class="row mb-5 rowForm" id="rowForm1">
        <div class="col">
            <div class="card card-flush bg-secondary">
                <div class="card-body">
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
                                <div class="col-md-12">
                                    <label for="employeeName1" class="col-form-label">Nama</label>
                                    <select name="letter[0][employee][]" id="employeeName1" multiple="multiple" class="form-select form-control">
                                        <option value="">- Pilih Karyawan -</option>
                                        @foreach ($employee as $item)
                                            <option value="{{ $item->id }}">{{ $item->name . ' ( '. $item->position->name .' )' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-5 row" style="margin-top: 30px;">
                        <div class="col-md-4">
                            <label for="" class="col-form-label p-0">Tanggal / Jam Izin</label>
                            <p class="mb-0" style="color: #A3A3A3;">
                                Jam dalam format <b>24 jam</b> dan tidak boleh di awali dengan angka '0'
                            </p>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" class="form-control" id="leaveDate" name="letter[0][date]" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" name="letter[0][hour]" id="leaveHour" placeholder="Jam" aria-label="Username">
                                        <span class="input-group-text">:</span>
                                        <input type="number" class="form-control" placeholder="Menit" id="leaveMinute" name="letter[0][minute]" aria-label="Server">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-5 row" style="margin-top: 30px;">
                        <div class="col-md-4">
                            <label for="" class="col-form-label p-0">Alasan Izin</label>
                            <p class="mb-0" style="color: #A3A3A3;">
                                Alasan karyawan meninggalkan kantor
                            </p>
                        </div>
                        <div class="col-md-8">
                            <textarea name="letter[0][notes]" id="notes" cols="3" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="targetRowForm"></div>

    <div class="row mb-5">
        <div class="col">
            <div class="text-start">
                <button class="btn btn-sm btn-light-success" type="button" onclick="addForm()">
                    <i class="fas fa-plus me-3"></i>
                    Tambah
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="text-end">
                <button class="btn btn-primary" id="btnSave" onclick="save()" type="button">Simpan</button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
    <script>
        // variable
        let form = $('#formLeaveOffice');
        let elem = $('#btnSave');

        $('#employeeName1').select2();

        function addForm() {
            let row = $('.rowForm');
            let rowLen = row.length;
            let form = `<div style="position: relative;" id="rowForm${rowLen+1}">
                            <img src="{{ asset('images/delete-icon.png') }}"  onclick="deleteRowForm(${rowLen+1})"
                                style="width: 35px; height: auto; position: absolute; top: -10px; right: -10px; z-index: 100; cursor: pointer;" alt="">
                            <div class="row mb-5 rowForm">
                                <div class="col">
                                    <div class="card card-flush bg-secondary">
                                        <div class="card-body">
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
                                                        <div class="col-md-12">
                                                            <label for="employeeName${rowLen+1}" class="col-form-label">Nama</label>
                                                            <select name="letter[${rowLen}][employee][]" id="employeeName${rowLen+1}" multiple="multiple" class="form-select form-control">
                                                                <option value="">- Pilih Karyawan -</option>
                                                                @foreach ($employee as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->name . ' ( '. $item->position->name .' )' }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-5 row" style="margin-top: 30px;">
                                                <div class="col-md-4">
                                                    <label for="" class="col-form-label p-0">Tanggal / Jam Izin</label>
                                                    <p class="mb-0" style="color: #A3A3A3;">
                                                        Jam dalam format <b>24 jam</b> dan tidak boleh di awali dengan angka '0'
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="date" class="form-control" id="leaveDate" name="letter[${rowLen}][date]" value="{{ date('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="number" class="form-control" name="letter[${rowLen}][hour]" id="leaveHour" placeholder="Jam" aria-label="Username">
                                                                <span class="input-group-text">:</span>
                                                                <input type="number" class="form-control" placeholder="Menit" id="leaveMinute" name="letter[${rowLen}][minute]" aria-label="Server">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-5 row" style="margin-top: 30px;">
                                                <div class="col-md-4">
                                                    <label for="" class="col-form-label p-0">Alasan Izin</label>
                                                    <p class="mb-0" style="color: #A3A3A3;">
                                                        Alasan karyawan meninggalkan kantor
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                                                    <textarea name="letter[${rowLen}][notes]" id="notes" cols="3" rows="3" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

            $('#targetRowForm').append(form);
            $(`#employeeName${rowLen+1}`).select2();
        }

        function deleteRowForm(ids) {
            $(`#rowForm${ids}`).remove();
        }

        function save() {
            let data = $('#formLeaveOffice').serialize();
            let url = form.attr('action');
            let method = form.attr('method');
            $.ajax({
                type: method,
                url: url,
                data: data,
                dataType: "json",
                beforeSend: function() {
                    elem.attr('disabled', true);
                    elem.text('Menyimpan data ...');
                },
                success: function(res) {
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    iziToast['success']({
                        message: 'Divisi berhasil di simpan',
                        position: "topRight"
                    });
                    document.getElementById('formLeaveOffice').reset();
                    window.location.href = "{{ route('leave-office.index') }}";
                },
                error: function(err) {
                    handleError(err, elem);
                }
            })
        }
    </script>
@endpush