@extends('layouts.master')
{{-- begin::section --}}
@section('content')
    {{-- begin::card-department --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-flush cardStructure mb-5">
                <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <h3>Department</h3>
                            <button class="btn btn-light-primary" type="button" onclick="addDepartment()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <table class="table table-striped table-border" id="tableDepartment">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nama</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-flush cardStructure mb-5">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <h3>Divisi</h3>
                        <button class="btn btn-light-primary" type="button" onclick="addDivision()">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                        <table class="table table-striped table-border" id="tableDivision">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nama</th>
                                    <th>Department</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-flush cardStructure mb-5">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <h3>Jabatan</h3>
                        <button class="btn btn-light-primary">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                        <table class="table table-striped table-border" id="tablePosition">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Divisi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-flush cardStructure mb-5">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <h3>Status</h3>
                        <button class="btn btn-light-primary">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                        <table class="table table-striped table-border" id="tableEmployeeStatus">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nama</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
    {{-- end::card-department --}}

    {{-- begin::modal --}}
    <div class="modal" tabindex="-1" id="modalOrganizationStructure">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="formOrganizationStructure">
                    <div class="modal-body" id="targetBodyOrganization">
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
    {{-- end::modal --}}
@endsection
{{-- end::section --}}

@push('scripts')
    <script>
        var _columnsDivision = [{
            data: "id",
            visible: false,
            searchable: false
        },{
            data: "name"
        },{
            data: "department_id"
        },{
            data: 'action'
        }];
    
        let tablesDivision = $("#tableDivision").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [[0, 'desc']],
            pageLength: 10,
            ajax: "{{ route('division.json') }}",
            columns: _columnsDivision,
        });

        var _columnsDepartment = [{
            data: "id",
            visible: false,
            searchable: false
        },{
            data: "name"
        },{
            data: 'action'
        }];
    
        let tablesDepartment = $("#tableDepartment").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [[0, 'desc']],
            pageLength: 10,
            ajax: "{{ route('department.json') }}",
            columns: _columnsDepartment,
        });

        var _columnsPosition = [{
            data: "name"
        }, {
            data: "division"
        }, {
            data: 'action'
        }];
    
        let tablesPosition = $("#tablePosition").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [[1, 'desc']],
            ajax: "{{ route('position.json') }}",
            columns: _columnsPosition,
        });

        var _columnsEmployeeStatus = [{
            data: "id",
            visible: false,
            searchable: false
        }, {
            data: "name"
        }, {
            data: 'action'
        }];
    
        let tablesEmployeeStatus = $("#tableEmployeeStatus").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [[0, 'desc']],
            ajax: "{{ route('employee-status.json') }}",
            columns: _columnsEmployeeStatus,
        });

        let modalOrganization = $('#modalOrganizationStructure');
        let btnSave = $('#btnSave');
        let form = $('#formOrganizationStructure');

        function addDepartment() {
            $.ajax({
                type: "GET",
                url: "{{ url('/organization-structure/add-department') }}",
                success: function(res) {
                    let view = res.data.view;
                    $('#targetBodyOrganization').html(view);
                    $('#modalTitle').text('Tambah Department');
                    form.attr('action', "{{ route('department.store') }}");
                    form.attr('method', 'POST');
                    btnSave.attr('onclick', "save('department')")
                    modalOrganization.modal('show');
                },
                error: function(err) {
                    handleError(err);
                }
            })
        }

        function addDivision() {
            $.ajax({
                type: "GET",
                url: "{{ url('/organization-structure/add-division') }}",
                success: function(res) {
                    let view = res.data.view;
                    $('#targetBodyOrganization').html(view);
                    $('#departmentId').select2({
                        dropdownParent: modalOrganization
                    });
                    $('#modalTitle').text('Tambah Divisi');
                    form.attr('action', "{{ route('division.store') }}");
                    form.attr('method', 'POST');
                    btnSave.attr('onclick', "save('division')")
                    modalOrganization.modal('show');
                },
                error: function(err) {
                    handleError(err);
                }
            })
        }

        function save(structure) {
            let data = form.serialize();
            let url = form.attr('action');
            let method = form.attr('method');

            $.ajax({
                type: method,
                url: url,
                data: data,
                dataType: "json",
                beforeSend: function() {
                    btnSave.attr('disabled', true);
                    btnSave.text('Menyimpan data ...');
                },
                success: function(res) {
                    console.log(res);
                    btnSave.attr('disabled', false);
                    btnSave.text('Simpan');
                    iziToast['success']({
                        message: 'Divisi berhasil di simpan',
                        position: "topRight"
                    });

                    modalOrganization.modal("hide");
                    if (structure == 'department') {
                        tablesDepartment.ajax.reload();
                    } else if (structure == 'position') {
                        tablesPosition.ajax.reload();
                    } else if (structure == 'division') {
                        tablesDivision.ajax.reload();
                    } else {
                        tablesEmployeeStatus.ajax.reload();
                    }
                },
                error: function(err) {
                    handleError(err, btnSave);
                }
            })
        }
    </script>
@endpush