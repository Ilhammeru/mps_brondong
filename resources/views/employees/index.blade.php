@extends('layouts.master')
{{-- begin::section --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-4">
        <div class="card-body p-3">
            <div class="text-end">
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
    {{-- end::modal --}}
@endsection
{{-- begin::end --}}

@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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
            ajax: "{{ route('employees.json') }}",
            columns: _columns,
        });

        const modalDivision = document.getElementById('modalDivision')
        modalDivision.addEventListener('hidden.bs.modal', event => {
            $('#btnSave').attr('disabled', false);
            $('#btnSave').text('Simpan');
            document.getElementById('formDivision').reset();
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

        function deleteDivision(id) {
            Swal.fire({
                title: 'Apakah anda yakin ingin menghapus divisi ini?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya! Hapus',
                denyButtonText: `Batalkan`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('/division/') }}" + "/" + id,
                        success: function(res) {
                            iziToast['success']({
                                message: 'Divisi berhasil di simpan',
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