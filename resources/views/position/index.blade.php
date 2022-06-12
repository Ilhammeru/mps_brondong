@extends('layouts.master')
{{-- begin::section --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-4">
        <div class="card-body p-3">
            <div class="text-end">
                {{-- begin::button-add --}}
                <button class="btn btn-light-primary" id="btnAdd" type="button">
                    <i class="fa fa-plus me-3"></i>
                    Tambah
                </button>
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
                        <th>Divisi</th>
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
    <div class="modal" tabindex="-1" id="modalPosition">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="formPosition">
                    <div class="modal-body">
                        <div class="form-group mb-5 row">
                            <label for="positionName" class="col-form-label">Nama</label>
                            <input type="text" class="form-control" id="positionName" name="name" value="{{ isset($position) ? $position->name : '' }}">
                        </div>
                        <div class="form-group mb-5 row">
                            <label for="divisionId" class="col-form-label">Divisi</label>
                            <select name="division_id" id="divisionId" class="form-select form-control"></select>
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
        }, {
            data: "division"
        }, {
            data: 'action'
        }];
    
        let dataTables = $("#dt_table").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: "{{ route('position.json') }}",
            columns: _columns,
        });

        const modalPosition = document.getElementById('modalPosition')
        modalPosition.addEventListener('hidden.bs.modal', event => {
            $('#btnSave').attr('disabled', false);
            $('#btnSave').text('Simpan');
            document.getElementById('formPosition').reset();
            $('#divisionId').html('');
        })

        // variable
        let form = $('#formPosition');
        let elem = $('#btnSave');
        let modal = $('#modalPosition');

        $('#btnAdd').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                type: "GET",
                url: "{{ route('division.getData') }}",
                dataType: 'json',
                success: function(res) {
                    let option = "<option value=''>- Pilih Divisi -</option>";
                    for (let a = 0; a < res.data.length; a++) {
                        option += `<option value="${res.data[a].id}">${res.data[a].name}</option>`;
                    }
                    modal.modal('show');
                    $('#divisionId').html(option);
                    $('#modalTitle').text('Tambah Posisi');
                    form.attr('action', "{{ route('position.store') }}");
                    form.attr('method', 'POST');
                }
            });
        });

        function save() {
            let data = $('#formPosition').serialize();
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
                    document.getElementById('formPosition').reset();
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
            let url = '{{ url('/position/') }}' + '/' + id;

            $.ajax({
                type: "GET",
                url: "{{ url("/position/") }}" + "/" + id,
                dataType: 'json',
                success: function(res) {
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    form.attr('action', url);
                    form.attr('method', 'POST');
                    modal.modal('show');
                    let option = "<option value=''>- Pilih Divisi -</option>";
                    for (let a = 0; a < res.data.division.length; a++) {
                        option += `<option value="${res.data.division[a].id}">${res.data.division[a].name}</option>`;
                    }
                    $('#divisionId').html(option);
                    $('#positionName').val(res.data.position.name);
                    $('#divisionId').val(res.data.position.division.id);
                    $('#modalTitle').text('Edit Divisi');
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
                        url: "{{ url('/position/') }}" + "/" + id,
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