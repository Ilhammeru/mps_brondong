@extends('layouts.master')
@section('content')
    <div class="row mb-5">
        <div class="col">
            <div class="card card-flush">
                <div class="card-body p-3">
                    <div class="text-end">
                        <a href="{{ route('trainings.create') }}" class="btn btn-light-primary">
                            <i class="fas fa-plus me-3"></i>
                            Tambah
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card card-flush">
                <div class="card-body">
                    <table class="table" id="dt_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama</th>
                                <th>Pelaksanaan</th>
                                <th>Tempat</th>
                                <th>PIC</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var _columns = [{
            data: "id",
            visible: false
        },{
            data: "name"
        },{
            data: 'training_date'
        },{
            data: 'venue'
        },{
            data: 'pic'
        },{
            data: 'status'
        },{
            data: 'action'
        }];
    
        let dataTables = $("#dt_table").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [[ 0, "desc" ]],
            ajax: "{{ route('trainings.json') }}",
            columns: _columns,
        });
    </script>
@endpush