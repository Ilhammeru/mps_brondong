@extends('layouts.master')
{{-- begin::section --}}
@section('content')
    {{-- begin::card-list --}}
    <div class="card card-flush">
        <div class="card-body">
            {{-- begin::table --}}
            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="dt_table">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Waktu Keluar</th>
                        <th>Alasan Keluar</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="formLeaveOffice">
                    <div class="modal-body">
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
                                    <div class="col-md-4">
                                        <label for="employeeName" class="col-form-label">Nama</label>
                                        <select name="employee" id="employeeName" class="form-select form-control" onchange="changeEmployee()"></select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="divisionId" class="col-form-label">Divisi</label>
                                        <input id="divisionName" readonly class="form-control" />
                                        <input name="division_id" hidden id="divisionId" readonly class="form-control" />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="positionId" class="col-form-label">Posisi</label>
                                        <input readonly id="positionName" class="form-control" />
                                        <input name="position_id" hidden id="positionId" readonly class="form-control" />
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
                                        <input type="date" class="form-control" id="leaveDate" name="date">
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
                    <div class="modal-footer">
                        <div class="form-group row">
                            <div class="col">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button class="btn btn-primary" id="btnSave" onclick="save()" type="button">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
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
        var _columns = [{
            data: "employee"
        }, {
            data: "division"
        }, {
            data: "date_time"
        }, {
            data: "notes"
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
            ajax: "{{ route('permission.leave-office.confirm.json') }}",
            columns: _columns,
        });

        // variable
        let form = $('#formLeaveOffice');
        let elem = $('#btnSave');
        let modal = $('#modalLeaveOffice');

        function confirm(id) {
            Swal.fire({
                title: 'Konfirmasi izin keluar karyawan, apakah anda yakin?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya!',
                denyButtonText: `Batalkan`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "PUT",
                        url: "{{ url('/leave-office/confirm') }}" + "/" + id,
                        beforeSend: function() {
                            let loading = `<div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                    </div>`;
                            $('#btnCheck' + id).html(loading);
                        },
                        success: function(res) {
                            console.log(res);
                            $('#btnCheck' + id).html('<i class="fas fa-check text-success"></i>');
                            iziToast['success']({
                                message: 'Data berhasil di simpan',
                                position: "topRight"
                            });

                            dataTables.ajax.reload();
                        },
                        error: function(err) {
                            $('#btnCheck' + id).html('<i class="fas fa-check text-success"></i>');
                            handleError(err);
                        }
                    })
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
    </script>
@endpush