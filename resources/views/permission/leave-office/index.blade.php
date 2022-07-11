@extends('layouts.master')
@push('styles')
    <style>
        .sectionCardLeave {
            position: relative;
            z-index: 100;
        }
        
        .deleteSectionLeave {
            position: absolute;
            top: -10px;
            right: -5px;
            z-index: 101;
        }
    </style>
@endpush
{{-- begin::section --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-4">
        <div class="card-body p-3">
            <div class="text-end">
                {{-- begin::button-add --}}
                <a class="btn btn-light-primary" href="{{ route('leave-office.create') }}">
                    <i class="fa fa-plus me-3"></i>
                    Tambah
                </a>
                {{-- end::button-add --}}
            </div>
        </div>
    </div>
    {{-- end::card-action --}}

    {{-- begin::filter --}}
    {{-- <div class="card card-flush mb-4">
        <div class="card-body">
            <h3 style="margin-bottom: 35px;">Filter Data</h3>
            <div class="form-group mb-5 row">
                <div class="col-md-1">
                    <label for="divisionFilter" class="col-form-label">Divisi</label>
                </div>
                <div class="col-md-4">
                    <select name="division_filter" id="divisionFilter" class="form-select form-control">
                        <option value="">- Pilih Divisi -</option>
                        @foreach ($division as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group mb-5 row">
                <div class="col-md-1">
                    <label for="positionFilter" class="col-form-label">Posisi</label>
                </div>
                <div class="col-md-4">
                    <select name="position_filter" id="positionFilter" class="form-select form-control">
                        <option value="">- Pilih Posisi -</option>
                    </select>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- end::filter --}}

    {{-- begin::card-list --}}
    <div class="card card-flush">
        <div class="card-body">
            {{-- begin::table --}}
            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="dt_table">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th></th>
                        <th>Kode Tiket</th>
                        <th>Nama</th>
                        <th>Waktu Keluar</th>
                        <th>Alasan Keluar</th>
                        <th>Status</th>
                        <th>Persetujuan</th>
                        <th>Satpam</th>
                        <th></th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-bold text-gray-600">
                    
                </tbody>
                <!--end::Table body-->
            </table>
            {{-- end::table --}}
        </div>
    </div>
    {{-- end::card-list --}}

    {{-- begin::modal --}}
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" id="modalLeaveOffice">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="formLeaveOffice">
                        <div class="sectionCardLeave">
                            {{-- <span class="deleteSectionLeave"><i class="fas fa-times text-danger fa-2x"></i></span> --}}
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
                                                            <select name="employee[]" id="employeeName1" multiple="multiple" class="form-select form-control">
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
                                                            <input type="date" class="form-control" id="leaveDate" name="date" value="{{ date('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="number" class="form-control" name="hour" id="leaveHour" placeholder="Jam" aria-label="Username">
                                                                <span class="input-group-text">:</span>
                                                                <input type="number" class="form-control" placeholder="Menit" id="leaveMinute" name="minute" aria-label="Server">
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
                                                    <textarea name="notes" id="notes" cols="3" rows="3" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group row">
                        <div class="col">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button class="btn btn-primary" id="btnSave" onclick="save()" type="button">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end::modal --}}

    {{-- begin::modal-detail --}}
    <div class="modal fade" tabindex="-1" id="modalDetail">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="targetDetail">
                </div>
            </div>
        </div>
    </div>
    {{-- end::modal-detail --}}
@endsection
{{-- begin::end --}}

@push('scripts')
    <script>
        let editUrl = "{{ route('leave-office.edit', ':id') }}";
        let showUrl = "{{ route('leave-office.show', ':id') }}";
        let storeUrl = "{{ route('leave-office.store') }}";
        let detailUrl = "{{ route('leave-office.detail', ':id') }}";
        let deleteUrl = "{{ route('leave-office.destroy', ':id') }}";
        let updateUrl = "{{ route('leave-office.update', ':id') }}";

        var _columns = [{
            data: "id",
            visible: false
        },{
            data: "ticket_code"
        },{
            data: "employee",
            width: "12%"
        }, {
            data: "date_time",
            width: "15%"
        }, {
            data: "notes",
            width: "15%"
        }, {
            data: "status"
        }, {
            data: "approved_by"
        }, {
            data: "checked_by"
        }, {
            data: 'action'
        }];
    
        let dataTables = $("#dt_table").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [[ 0, "desc" ]],
            ajax: "{{ route('permission.leave-office.json') }}",
            columns: _columns,
        });

        const modalLeaveOffice = document.getElementById('modalLeaveOffice')
        modalLeaveOffice.addEventListener('hidden.bs.modal', event => {
            $('#btnSave').attr('disabled', false);
            $('#btnSave').text('Simpan');
            document.getElementById('formLeaveOffice').reset();
            $('#divisionId').html('');
            $('#targetRowForm').html('');
        })

        // variable
        let form = $('#formLeaveOffice');
        let elem = $('#btnSave');
        let modal = $('#modalLeaveOffice');

        // select2
        $('#divisionFilter').select2();
        $('#positionFilter').select2();

        $('#divisionFilter').on('change', function(e) {
            e.preventDefault();
            let val = $(this).val();
            let route = "{{ route('position.getData', ':id') }}";
            route = route.replace(':id', val);

            $.ajax({
                type: 'GET',
                url: route,
                success: function(res) {
                    let data = res.data;
                    let option = "<option value=''>- Pilih Posisi -</option>";
                    for (let a = 0; a < data.length; a++) {
                        option += `<option value="${data[a].id}">${data[a].name}</option>`;
                    }
                    $('#positionFilter').html(option);
                    $('#positionFilter').select2();
                }
            })
        })

        $('#btnAdd').on('click', function(e) {
            e.preventDefault();
            $('#employeeName1').select2({
                dropdownParent: $('#modalLeaveOffice')
            });
            $('#modalTitle').text('Tambah Data Izin');
            form.attr('action', storeUrl);
            form.attr('method', 'POST');
            modal.modal('show');
        });

        function detail(id) {
            detailUrl = detailUrl.replace(':id', id);
            $.ajax({
                type: "GET",
                url: detailUrl,
                dataType: "json",
                success: function(res) {
                    let view = res.data.view;
                    $('#targetDetail').html(view);
                    $('.modal-title').text('Tiket Keluar');
                    $('#modalDetail').modal('show');
                },
                error: function(err) {
                    handleError(err);
                }
            })
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

                    modal.modal("hide");
                    dataTables.ajax.reload();
                    document.getElementById('formLeaveOffice').reset();
                },
                error: function(err) {
                    handleError(err, elem);
                }
            })
        }

        function edit(id) {
            let editUrl = "{{ route('leave-office.edit', ':id') }}";
            let showUrl = "{{ route('leave-office.show', ':id') }}";
            let updateUrl = "{{ route('leave-office.update', ':id') }}";
            editUrl = editUrl.replace(':id', id);
            showUrl = showUrl.replace(':id', id);
            updateUrl = updateUrl.replace(':id', id);
            $.ajax({
                type: "GET",
                url: showUrl,
                dataType: 'json',
                success: function(res) {
                    console.log(res);
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    form.attr('action', updateUrl);
                    form.attr('method', 'PUT');
                    modal.modal('show');
                    let employees = res.data.employee;
                    let currentEmployee = res.data.currentEmployee;
                    let data = res.data.data;
                    let option = "<option value=''>- Pilih Karyawan -</option>";
                    let selected = "";
                    for (let a = 0; a < employees.length; a++) {
                        if (employees[a].id == data.employee_id) {
                            selected = 'selected';
                        } else {
                            selected = "";
                        }
                        option += `<option ${selected} value="${employees[a].id}">${employees[a].name} ( ${employees[a].position.name} )</option>`;
                    }
                    $('#employeeName1').html(option);
                    $('#employeeName1').val(currentEmployee);
                    $('#employeeName1').select2({
                        dropdownParent: $('#modalLeaveOffice')
                    });
                    $('#leaveHour').val(res.data.hour)
                    $('#leaveMinute').val(res.data.minute)
                    $('#leaveDate').val(res.data.date)
                    $('#notes').val(data.notes)
                    $('#modalTitle').text('Edit Data Izin');
                },
                error: function(err) {
                    console.log(err);
                    handleError(err, elem);
                }
            })
        }

        function deleteLeave(id) {
            let deleteUrl = "{{ route('leave-office.destroy', ':id') }}";
            Swal.fire({
                title: 'Apakah anda yakin ingin menghapus divisi ini?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya! Hapus',
                denyButtonText: `Batalkan`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    deleteUrl = deleteUrl.replace(':id', id);
                    $.ajax({
                        type: "DELETE",
                        url: deleteUrl,
                        success: function(res) {
                            iziToast['success']({
                                message: 'Posisi berhasil di simpan',
                                position: "topRight"
                            });

                            dataTables.ajax.reload();
                        },
                        error: function(err) {
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
            })
        }
    </script>
@endpush