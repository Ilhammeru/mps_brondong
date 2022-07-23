@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<link rel="stylesheet" href="{{ asset('plugins/custom/filepond/filepond.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/custom/filepond/plugins-preview.css') }}">

<style>
    .btn-tags {
        border-radius: 30px !important;
        margin-right: 20px;
    }
</style>
@endpush

@section('content')
    <div class="row mb-5">
        <div class="col">
            <div class="card card-flush">
                <div class="card-body p-3">
                    <div class="text-start">
                        <a href="{{ route('trainings.index') }}" class="btn btn-light-primary">
                            <i class="fas fa-chevron-left me-3"></i>
                            Kembali
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
                    <form action="{{ route('trainings.store') }}" method="POST" id="formTraining" enctype="multipart/form-data">
                        <div class="form-group row mb-5">
                            <div class="col-md-3">
                                <label for="name" class="col-form-label required">Nama Training</label>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Training Kesehatan & HIV">
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-3">
                                <label for="pic" class="col-form-label p-0 required">Nama PIC</label>
                                <p class="mb-0" style="color: #A3A3A3;">
                                    PIC Bisa dipilih lebih dari 1 orang
                                </p>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <select id="pic" multiple="multiple" class="form-select form-control" name="pic[]">
                                    <option value="">-Pilih PIC -</option>
                                    @foreach ($employee as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-3">
                                <label for="trainingDate" class="col-form-label p-0 required">Waktu Pelaksanaan</label>
                                <p class="mb-0" style="color: #A3A3A3;">
                                    Tanggal dan Jam training
                                </p>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control" name="training_date" id="trainingDate">
                                    <span class="input-group-text">Jam</span>
                                    <input type="text" class="form-control timepicker" name="training_time">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-3">
                                <label for="description" class="col-form-label p-0">Deskripsi</label>
                                <p class="mb-0" style="color: #A3A3A3;">
                                    Deskripsi singkat tentang training yang akan di selenggarakan
                                </p>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <textarea name="description" placeholder="{!! $description !!}" id="description" cols="3" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-3">
                                <label for="trainingDate" class="col-form-label p-0">Tempat</label>
                                <p class="mb-0" style="color: #A3A3A3;">
                                    Templat dilaksankan training
                                </p>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <input type="text" placeholder="Aula Merdeka" class="form-control" name="venue">
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-3">
                                <label for="participant" class="col-form-label p-0">Peserta</label>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <select id="participant" multiple="multiple" class="form-select form-control" name="participant[]">
                                    <option value="">-Pilih Peserta -</option>
                                    @foreach ($employee as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-3">
                                <label for="material" class="col-form-label p-0">Materi</label>
                                <p class="mb-0" style="color: #A3A3A3;">
                                    Upload file pendukung / materi yang akan di berikan untuk training <br>
                                    Pastikan file yang akan di upload adalah .docx, .ppt, .xlsx, .xls, .pdf, .jpg, .png
                                </p>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="file" name="material" class="form-control" id="material1">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" name="material" class="form-control" id="material2">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" name="material" class="form-control" id="material3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-3">
                                <label for="tags" class="col-form-label p-0">Tag</label>
                                <p class="mb-0" style="color: #A3A3A3;">
                                    Pilih tag yang sesuai dengan topik training, untuk memudahkan pencarian training dan pembagian training
                                </p>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col">
                                        <div class="d-flex align-items-center w-100">
                                            <div style="overflow-wrap: break-word;" id="targetTag"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="text" name="tags" id="tagsInput" hidden>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="tags" class="col-form-label p-0">Kuesioner</label>
                                <p class="mb-0" style="color: #A3A3A3;">
                                    Hasil kuesioner akan masuk ke dalam data masing - masing karyawan
                                </p>
                                <p class="mb-0 text-warning" style="color: #A3A3A3;">
                                    <b>
                                        (Kuisioner bisa diisi pada detail training)
                                    </b>
                                </p>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="kuisioner" value="1">
                                    <label class="form-check-label" for="kuisioner"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-end">
                                <button class="btn btn-primary" type="button" id="btnSave" onclick="save()">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- begin::modal --}}
    <div class="modal fade" id="modalTag" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('trainings.tags.store') }}" method="POST" id="formTags">
                    <div class="modal-body">
                        <div class="form-group row mb-5">
                            <div class="col">
                                <label for="tag-create" class="col-form-label">Nama Tags</label>
                                <input type="text" class="form-control" name="tag" id="tag-create">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" type="button" onclick="saveTag()" id="btnSaveTag">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end::modal --}}
@endsection

@push('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script src="{{ asset('plugins/custom/filepond/filepond.js') }}"></script>
    <script src="{{ asset('plugins/custom/filepond/plugins-preview.js') }}"></script>
    <script>
        $('#pic').select2();
        $('#participant').select2();
        $('.timepicker').timepicker({
            timeFormat: 'h:mm p',
            interval: 15,
            minTime: '08',
            maxTime: '17:00',
            defaultTime: '10',
            startTime: '08:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
        FilePond.registerPlugin(FilePondPluginImagePreview);
        const material1 = document.getElementById('material1');
        const pond1 = FilePond.create(material1);
        const material2 = document.getElementById('material2');
        const pond2 = FilePond.create(material2);
        const material3 = document.getElementById('material3');
        const pond3 = FilePond.create(material3);

        let form = $('#formTraining');
        let btnSave = $('#btnSave');
        let tags = [];

        getTag();
        
        function getTag() {
            let route = "{{ route('trainings.tags.list', ':id') }}";
            route = route.replace(':id', 0);
            $.ajax({
                type: "GET",
                url: route,
                dataType: 'json',
                success: function(res) {
                    console.log(res);
                    let data = res.data.tag;
                    let col = '';
                    if (data.length) {
                        for (let a = 0; a < data.length; a++) {
                            col += `<button class="btn ${data[a].class} btn-tags" data-status="${data[a].data_status}" id="btn-tags${data[a].id}" type="button" onclick="selectTag(${data[a].id}, '${data[a].id}')">${data[a].name}</button>`;
                        }
                    }
                    col += `<button class="btn btn-secondary btn-tags" id="btn-tags" type="button" onclick="addTag()"><i class="fas fa-plus"></i> Tags</button>`;
                    $('#targetTag').html(col);
                }
            })
        }

        function save() {
            let url = form.attr('action');
            let method = form.attr('method');
            let data = new FormData($('#formTraining')[0]);
            let materials = [];
            let matFile1 = pond1.getFile();
            if (matFile1) {
                matFile1 = matFile1.file;
                data.append('material1', matFile1);
            }
            let matFile2 = pond2.getFile();
            if (matFile2) {
                matFile2 = matFile2.file;
                data.append('material2', matFile2);
            }
            let matFile3 = pond3.getFile();
            if (matFile3) {
                matFile3 = matFile3.file;
                data.append('material3', matFile3);
            }
            let isQuestionnaire = $('input[id="kuisioner"]:checked').val();
            data.append('questionnaire', isQuestionnaire);

            $.ajax({
                type: method,
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    btnSave.prop('disabled', true);
                    btnSave.text('Menyimpan data ...');
                },
                success: function(res) {
                    iziToast['success']({
                        message: 'Berhasil menyimpan data',
                        position: "topRight"
                    });
                    btnSave.prop('disabled', false);
                    btnSave.text('Simpan');
                    window.location.href = "{{ route('trainings.index') }}";
                },
                error: function(err) {
                    btnSave.prop('disabled', false);
                    btnSave.text('Simpan');
                    handleError(err, btnSave);
                }
            })
        }

        function selectTag(ids, val) {
            let elem = $('#btn-tags' + ids);
            let status = elem.attr('data-status');
            if (status == '1') {
                elem.attr('data-status', 0);
                elem.removeClass('btn-light-info');
                elem.addClass('btn-secondary')
                tags.find((o, i) => {
                    if (o === val) {
                        tags.splice(i, 1);
                    }
                });
                if (tags.length == 0) {
                    tags = [];
                }
            } else {
                elem.attr('data-status', 1);
                elem.removeClass('btn-secondary')
                elem.addClass('btn-light-info');
                tags.push(val);
            }
            $('#tagsInput').val(tags);
        }

        function addTag() {
            document.getElementById('formTags').reset();
            $('#modalTag').modal('show');
        }

        function saveTag() {
            let form = $('#formTags');
            let btn = $('#btnSaveTag');
            let url = form.attr('action');
            let data = form.serialize();

            $.ajax({
                type: "POST",
                url: url,
                data: data,
                beforeSend: function() {
                    btn.prop('disabled', true);
                    btn.text('Menyimpan data ...');
                },
                success: function(res) {
                    $('#modalTag').modal('hide');
                    btn.prop('disabled', false);
                    btn.text('Simpan');
                    getTag();
                },
                error: function(err) {
                    handleError(err, btn);
                }
            })
        }
    </script>
@endpush