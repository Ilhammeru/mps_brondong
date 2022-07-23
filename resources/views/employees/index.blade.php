@extends('layouts.master')
{{-- begin::section --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-4">
        <div class="card-body p-3">
            <div class="text-end">
                {{-- begin::button-import --}}
                <button class="btn btn-light-success" type="button" onclick="importData()">
                    <i class="fas fa-file-import"></i>
                    Import
                </button>
                {{-- end::button-import --}}
                {{-- begin::button-add --}}
                <a class="btn btn-light-primary" href="{{ route('employees.create') }}">
                    <i class="fa fa-plus me-3"></i>
                    Tambah
                </a>
                {{-- end::button-add --}}
            </div>
        </div>
    </div>
    {{-- end::card-action --}}

    {{-- begin::card-filter --}}
    <div class="card card-flush mb-4">
        <div class="card-body">
            <h3 class="mb-5">Filter Data</h3>
            <div class="form-group row">
                <div class="col-md-2">
                    <label for="filterStatus" class="col-form-label">Status Karyawan</label>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-4">
                    <select name="filterStatus" id="filterStatus" class="form-select form-control">
                        <option value="">- Pilih Status -</option>
                        <option value="1" selected>Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    {{-- end::card-filter --}}

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
                        <th>NIK</th>
                        <th>No. Telepon</th>
                        <th>Status Karyawan</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Vaksin</th>
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
    <div class="modal" tabindex="-1" id="modalDivision">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="formDivision">
                    <div class="modal-body">
                        <div class="form-group mb-5 row">
                            <label for="divisionName" class="col-form-label">Nama</label>
                            <input type="text" class="form-control" id="divisionName" name="name" value="{{ isset($division) ? $division->name : '' }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group row">
                            <div class="col">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button class="btn btn-primary" id="btnSave" onclick="save()">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="modalImport">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Import Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('employees.import') }}" method="POST" id="formImport" enctype="multipart/form-data">
                    <div class="modal-body">
                        
                        <div class="form-group mb-5 row">
                            <div class="input-group">
                                <input type="file" class="form-control" id="file" name="file">
                                <a class="input-group-text" style="cursor: pointer;" href="{{ route('employees.download.template') }}">Template</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group row">
                            <div class="col">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button class="btn btn-primary" id="btnImport" type="button" onclick="doImport()">Proses</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end::modal --}}
@endsection
{{-- begin::end --}}

@push('scripts')
    <script>
        $('#filterStatus').select2();

        var _columns = [{
            data: "name"
        },{
            data: "nik"
        },{
            data: 'phone'
        },{
            data: 'working_status'
        },{
            data: 'division'
        },{
            data: 'position'
        },{
            data: 'status_vaccine'
        },{
            data: "action"
        }];
    
        let dataTables = $("#dt_table").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: "{{ url('/employees/data/json') }}" + "/" + 1,
            columns: _columns,
        });

        const modalDivision = document.getElementById('modalDivision')
        modalDivision.addEventListener('hidden.bs.modal', event => {
            $('#btnSave').attr('disabled', false);
            $('#btnSave').text('Simpan');
            document.getElementById('formDivision').reset();
        })

        const modalImport = document.getElementById('modalImport')
        modalImport.addEventListener('hidden.bs.modal', event => {
            $('#btnImport').attr('disabled', false);
            $('#btnImport').text('Proses');
            document.getElementById('formImport').reset();
        })

        // variable
        let form = $('#formDivision');
        let elem = $('#btnSave');
        let modal = $('#modalDivision');
        $('#btnAdd').on('click', function(e) {
            e.preventDefault();

            $('#modalTitle').text('Tambah Divisi');
            form.attr('action', "{{ route('division.store') }}");
            form.attr('method', 'POST');
            modal.modal('show');
        });

        $('#filterStatus').on('change', function(e) {
            e.preventDefault();
            let val = $(this).val();
            let route = "{{ route('employees.json', ':type') }}";
            route = route.replace(':type', val);
            dataTables.destroy();
            dataTables = $("#dt_table").DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: route,
                columns: _columns,
            });
            console.log(route);
        });

        function importData() {
            $('#modalImport').modal('show');
        }

        function doImport() {
            let btn = $('#btnImport');
            let form = $('#formImport');
            let url = form.attr('action');
            let method = form.attr('method');
            let data = new FormData($('#formImport')[0]);
            $.ajax({
                type: method,
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    btn.prop('disabled', true);
                    btn.text('Upload data ...');
                },
                success: function(res) {
                    btn.prop('disabled', false);
                    btn.text('Proses');
                    dataTables.ajax.reload();
                    $('#modalImport').modal('hide');
                    iziToast['success']({
                        message: 'Import data berhasil',
                        position: "topRight"
                    });
                },
                error: function(err) {
                    btn.prop('disabled', false);
                    btn.text('Proses');
                    handleError(err);
                }
            })
        }

        function save() {
            let data = $('#formDivision').serialize();
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
                    document.getElementById('formDivision').reset();
                },
                error: function(err) {
                    elem.attr('disabled', false);
                    elem.text('Simpan');
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

        function edit(id) {
            let url = '{{ url('/division/') }}' + '/' + id;

            $.ajax({
                type: "GET",
                url: "{{ url("/division/") }}" + "/" + id,
                dataType: 'json',
                success: function(res) {
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    form.attr('action', url);
                    form.attr('method', 'POST');
                    modal.modal('show');
                    $('#divisionName').val(res.data.name);
                    $('#modalTitle').text('Edit Divisi');
                },
                error: function(err) {
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    let message = err.responseJSON.message;
                    if (message == 'FAILED') {
                        iziToast['error']({
                            message: response.responseJSON.data.error,
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

        function deleteData(id) {
            Swal.fire({
                title: 'Data Karyawan akan di hapus secara permanen setelah 3 bulan',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya! Nonaktifkan',
                denyButtonText: `Batalkan`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('/employees/') }}" + "/" + id,
                        success: function(res) {
                            iziToast['success']({
                                message: 'Data berhasil di non-aktifkan',
                                position: "topRight"
                            });

                            dataTables.ajax.reload();
                        },
                        error: function(err) {
                            handleError(err);
                        }
                    })
                }
            })
        }
    </script>
@endpush