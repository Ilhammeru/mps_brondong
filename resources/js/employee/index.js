console.log(process.env.DB_PORT);
var _columns = [{
    data: "id",
    width: "0.5%",
    orderable: false,
    visible: false
},{
    data: "employee"
}, {
    data: "division"
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

    $.ajax({
        type: 'GET',
        url: "{{ url('/position/getData') }}" + "/" + val,
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

    $.ajax({
        type: "GET",
        url: "{{ route('employees.getData') }}",
        dataType: 'json',
        success: function(res) {
            let data = res.data;
            let option = "<option value=''>- Pilih Karyawan -</option>";
            for (let a = 0; a < data.length; a++) {
                option += `<option value="${data[a].id}">${data[a].name} ( ${data[a].position.name} )</option>`;
            }
            modal.modal('show');
            $('#employeeName').html(option);
            $('#employeeName').select2({
                dropdownParent: $('#modalLeaveOffice')
            });
            $('#modalTitle').text('Tambah Data Izin');
            form.attr('action', "{{ route('leave-office.store') }}");
            form.attr('method', 'POST');
        },
        error: function(err) {
            handleError(err);
        }
    });
});

function detail(id) {
    $.ajax({
        type: "GET",
        url: "{{ url('/permission/leave-office/detail') }}" + "/" + id,
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

function changeEmployee() {
    let val = $('#employeeName').val();
    if (val == ""){
        $('#divisionId').val('');
        $('#divisionName').val('');
        $('#positionId').val('');
        $('#positionName').val('');
    } else {
        $.ajax({
            type: "GET",
            url: "{{ url('/employees/getDivision') }}" + "/" + val,
            dataType: "json",
            success: function(res) {
                console.log(res);
                $('#divisionId').val(res.data.division.id);
                $('#divisionName').val(res.data.division.name);
                $('#positionId').val(res.data.position.id);
                $('#positionName').val(res.data.position.name);
            },
            error: function(err) {
                console.error('error', err);
                handleError(err);
            }
        });
    }
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

function edit(id, url) {
    $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        success: function(res) {
            console.log(res);
            elem.attr('disabled', false);
            elem.text('Simpan');
            form.attr('action', url);
            form.attr('method', 'PUT');
            modal.modal('show');
            let employees = res.data.employee;
            let data = res.data.data;
            let option = "<option value=''>- Pilih Karyawan -</option>";
            let selected = "";
            for (let a = 0; a < employees.length; a++) {
                if (employees[a].id == data.employee_id) {
                    selected = 'selected';
                } else {
                    selected = "";
                }
                console.log(selected);
                option += `<option ${selected} value="${employees[a].id}">${employees[a].name}</option>`;
            }
            $('#employeeName').html(option);
            $('#employeeName').select2({
                dropdownParent: $('#modalLeaveOffice')
            });
            $('#divisionId').val(data.division.id);
            $('#divisionName').val(data.division.name);
            $('#positionId').val(data.position.id);
            $('#positionName').val(data.position.name);
            $('#leaveHour').val(res.data.hour)
            $('#leaveMinute').val(res.data.minute)
            $('#leaveDate').val(res.data.date)
            $('#notes').val(data.notes)
            $('#modalTitle').text('Edit Data Izin');
        },
        error: function(err) {
            handleError(err, elem);
        }
    })
}

function deleteLeave(id) {
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
                url: "{{ url('/permission/leave-office/') }}" + "/" + id,
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