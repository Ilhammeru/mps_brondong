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
                            <button class="btn btn-light-primary">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <table class="table table-striped table-border" id="tableDepartment">
                            <thead>
                                <tr>
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
                        <button class="btn btn-light-primary">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                        <table class="table table-striped table-border" id="tableDivision">
                            <thead>
                                <tr>
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
                        <h3>Jabatan</h3>
                        <button class="btn btn-light-primary">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                        <table class="table table-striped table-border">
                            <thead>
                                <tr>
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
                        <h3>Status</h3>
                        <button class="btn btn-light-primary">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                        <table class="table table-striped table-border">
                            <thead>
                                <tr>
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
@endsection
{{-- end::section --}}

@push('scripts')
    <script>
        var _columnsDivision = [{
            data: "name"
        },{
            data: 'action'
        }];
    
        let tablesDivision = $("#tableDivision").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            pageLength: 10,
            ajax: "{{ route('division.json') }}",
            columns: _columnsDivision,
        });

        var _columnsDepartment = [{
            data: "name"
        },{
            data: 'action'
        }];
    
        let tablesDepartment = $("#tableDepartment").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            pageLength: 10,
            ajax: "{{ route('department.json') }}",
            columns: _columnsDepartment,
        });
    </script>
@endpush