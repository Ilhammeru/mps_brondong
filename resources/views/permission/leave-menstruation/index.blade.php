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
                <a href="{{ route('leave-menstruation.create') }}" class="btn btn-light-primary" id="btnAdd" type="button">
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
                        <th>Nama</th>
                        <th>Waktu Keluar</th>
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
                        <div class="sectionCardLeave" id="sectionCardLeave1">
                            {{-- <span class="deleteSectionLeave" onclick="deleteRow(1)"><i class="fas fa-times text-danger fa-2x"></i></span> --}}
                            <div class="row mb-5">
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
                                                        <div class="col">
                                                            <label for="employeeName" class="col-form-label">Nama</label>
                                                            <select name="employee_id[]" multiple="multiple" id="employeeName" class="form-select form-control" onchange="changeEmployee()"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="targetSection"></div>

                        <div class="row">
                            <div class="col">
                                <div class="text-start">
                                    <button type="button" onclick="addSection()" class="btn btn-light-success" id="btnAddSection">
                                        <i class="fas fa-plus me-3"></i>
                                        Tambah
                                    </button>
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
        var _columns = [{
            data: "id",
            orderable: false,
            visible: false
        },{
            data: "employee"
        }, {
            data: "date_time",
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
            ajax: "{{ route('permission.leave-menstruation.json') }}",
            columns: _columns,
        });

        function detail(id) {
            $.ajax({
                type: "GET",
                url: "{{ url('/permission/leave-menstruation/detail') }}" + "/" + id,
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

        function deleteLeave(id) {
            Swal.fire({
                title: 'Apakah anda yakin ingin menghapus Data Cuti ini?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya! Hapus',
                denyButtonText: `Batalkan`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('/permission/leave-menstruation/') }}" + "/" + id,
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