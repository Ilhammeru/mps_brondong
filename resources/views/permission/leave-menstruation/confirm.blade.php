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
                        <th>Waktu Keluar</th>
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
            data: "date_time"
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
            ajax: "{{ route('permission.leave-menstruation.confirm.json') }}",
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
                        url: "{{ url('/leave-menstruation/confirm') }}" + "/" + id,
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