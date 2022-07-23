@extends('layouts.master')

@push('styles')
    <style>
        @-webkit-keyframes slide-out-left {
            0% {
                -webkit-transform: translateX(0);
                        transform: translateX(0);
                opacity: 1;
            }
            100% {
                -webkit-transform: translateX(-1000px);
                        transform: translateX(-1000px);
                opacity: 0;
            }
            }
            @keyframes slide-out-left {
            0% {
                -webkit-transform: translateX(0);
                        transform: translateX(0);
                opacity: 1;
            }
            100% {
                -webkit-transform: translateX(-1000px);
                        transform: translateX(-1000px);
                opacity: 0;
            }
        }

        @-webkit-keyframes slide-in-right {
            0% {
                -webkit-transform: translateX(1000px);
                        transform: translateX(1000px);
                opacity: 0;
            }
            100% {
                -webkit-transform: translateX(0);
                        transform: translateX(0);
                opacity: 1;
            }
            }
            @keyframes slide-in-right {
            0% {
                -webkit-transform: translateX(1000px);
                        transform: translateX(1000px);
                opacity: 0;
            }
            100% {
                -webkit-transform: translateX(0);
                        transform: translateX(0);
                opacity: 1;
            }
        }

        .slide-out-left {
            -webkit-animation: slide-out-left 0.5s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
                    animation: slide-out-left 0.5s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
        }

        .slide-in-right {
            -webkit-animation: slide-in-right 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
                    animation: slide-in-right 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
        }

        .box {
            position: absolute;
            width: 300px;
            height: 100px;
            top: 0;
            left: 50%;
            margin: 30px 0 0 -150px;
        }

        .card-setup {
            height: 320px;
            transition: ease .5s;
        }
    </style>
@endpush

{{-- begin::content --}}
@section('content')
    <div class="row mb-5">
        <div class="col">
            {{-- begin::card --}}
            <div class="card card-flush">
                <div class="card-body p-3">
                    <div class="text-start">
                        <a href="{{ route('trainings.show', $trainingId) }}" class="btn btn-light-primary">
                            <i class="fas fa-chevron-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
            {{-- end::card --}}

        </div>
    </div>
    <div class="row">
        <div class="col">
            {{-- begin::card-form --}}
            <div class="card card-flush card-first-setup d-none">
                <div class="card-body">
                    <div class="text-center">
                        <h5>Mohon tidak merefresh setup ini</h5>

                        <img src="{{ asset('images/setup_questionnaire.jpg') }}" style="width: 250px; height: auto;" alt="">
                        <div>
                            <button class="btn btn-primary" type="button" onclick="goToSetup()">
                                Lanjutkan setup pertanyaan 
                                <i class="fas fa-chevron-right ms-3"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end::card-form --}}

            {{-- begin::card-first-setup --}}
            <div class="card card-flush">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="progressBar" id="progressBar" style="width: 80%; height: 100px;"></div>
                    </div>
                </div>
            </div>
            <div class="card card-flush card-setup-1 card-setup">
                <div class="card-body p-3">
                    
                    <div class="row rowType">
                        <div class="col">
                            <div class="master-box" style="position: relative;">
                                <div class="box">
                                    <div class="text-center">
                                        <h3>Tipe Jawaban dari Kuisioner</h3>
                                    </div>
                                    <div class="d-flex align-items-center ms-5 mt-5">
                                        <img src="{{ asset('images/radio_button_inactive.png') }}" onclick="chooseType('1')"
                                            style="width: 25px; height: auto; cursor: pointer;" alt="" id="imgType1" class="imgType">
                                        <p class="mb-0 ms-3" style="cursor: pointer;" onclick="chooseType('1')">Pilihan Ganda</p>
                                    </div>
                                    <div class="d-flex align-items-center ms-5 mt-1">
                                        <img src="{{ asset('images/radio_button_inactive.png') }}" onclick="chooseType('2')"
                                            style="width: 25px; height: auto; cursor: pointer;" alt="" id="imgType2" class="imgType">
                                        <p class="mb-0 ms-3" style="cursor: pointer;" onclick="chooseType('2')">Esai</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row rowNumber d-none">
                        <div class="col">
                            <div class="master-box" style="position: relative;">
                                <div class="box">
                                    <div class="text-center">
                                        <h3 class="titleQuestionNumber"></h3>
                                    </div>
                                    <input type="text" class="form-control" name="question_number" id="questionNumber" placeholder="20">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row rowPoin d-none rowPoinEsai">
                        <div class="col">
                            <div class="master-box" style="position: relative;">
                                <div class="box">
                                    <div class="text-center">
                                        <h3>Skema Poin Per Soal</h3>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" readonly id="currentQuestionNumber" placeholder="100" value="20">
                                        <label for="currentQuestionNumber">Jumlah Soal</label>
                                    </div>
                                    <div class="input-group mb-3">
                                        {{-- <span class="input-group-text"></span> --}}
                                        <div class="form-floating">
                                          <input type="text" class="form-control" id="maxScore" placeholder="100" onchange="setPoin()">
                                          <label for="maxScore">Skor Maksimal</label>
                                        </div>
                                        <select name="" id="scorePath" class="form-control" onchange="setPoin()">
                                            <option value="1">%</option>
                                            <option value="2">Poin</option>
                                        </select>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" readonly id="poinPerQuestion" placeholder="100">
                                        <label for="poinPerQuestion">Poin Per Soal</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row rowPoinMultiple d-none">
                        <div class="col">
                            <div class="master-box" style="position: relative;">
                                <div class="box">
                                    <div class="text-center">
                                        <h3>Skema Poin Per Jawaban</h3>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" readonly id="currentQuestionNumber" placeholder="100" value="20">
                                        <label for="currentQuestionNumber">Jumlah Soal</label>
                                    </div>
                                    <div class="d-flex align-items-center row-mulitple-choice">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control answerMultiple" id="answer0" placeholder="Ya">
                                            <label for="answer0">Jawaban</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control poinMultiple" id="poinMultiple0" placeholder="5">
                                            <label for="poinMultiple0">Poin</label>
                                        </div>
                                        <div class="d-flex align-items-center mb-2 ps-2" style="cursor: pointer;" onclick="addMultipleAnswer()"><i class="fas fa-plus text-primary"></i></div>
                                    </div>
                                    <div id="targetMultipleChoice"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-secondary p-3">
                    <div class="text-center">
                        <button class="btn btn-light-info" type="button" id="btnBack">
                            <i class="fas fa-chevron-left ms-2"></i>
                            Kembali
                        </button>
                        <button class="btn btn-light-success ms-3" type="button" id="btnNext">
                            Selanjutnya
                            <i class="fas fa-chevron-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
            {{-- end::card-first-setup --}}
        </div>
    </div>
@endsection
{{-- end::content --}}

@push('scripts')
    <script src="{{ asset('plugins/custom/progress_step/progressStep.min.js') }}"></script>
    <script src="{{ asset('plugins/custom/progress_step/raphael.js') }}"></script>
    <script>
        var $progressDiv = $("#progressBar");  
        var $progressBar = $progressDiv.progressStep();  
        $progressBar.addStep("Tipe");  
        $progressBar.addStep("Jumlah");  
        $progressBar.addStep("Poin");  
        $progressBar.refreshLayout();  
        $progressBar.setCurrentStep(0);

        // variable
        let type, questionNumber, step;
        let btnNext = $('#btnNext');
        let btnBack = $('#btnBack');

        window.onbeforeunload = function(event) {
            return confirm('Apakah anda yakin?');
        }

        $(document).ready(function() {
            localStorage.setItem('step', 0);
            setButtonNext();
        })

        function addMultipleAnswer() {
            let row = $('.row-mulitple-choice');
            let rowLen = row.length;
            let item = `<div class="row-mulitple-choice" id="multipleChoiceRow${(rowLen+1)}">
                    <div class="d-flex align-items-center">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control answerMultiple" id="answer${(rowLen+1)}" placeholder="Ya">
                            <label for="answer${(rowLen+1)}">Jawaban</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control poinMultiple" id="poinMultiple${(rowLen+1)}" placeholder="5">
                            <label for="poinMultiple${(rowLen+1)}">Poin</label>
                        </div>
                        <div class="d-flex align-items-center mb-2 ps-2" style="cursor: pointer;" onclick="deleteMultipleChoice(${(rowLen+1)})"><i class="fas fa-times text-danger"></i></div>
                    </div>
                </div>`;
            if (rowLen <= 3) {
                $('#targetMultipleChoice').append(item);
            } else {
                iziToast['error']({
                    message: 'Maksimal hanya 4 jawaban',
                    position: "topRight"
                });
            }
        }

        function deleteMultipleChoice(ids) {
            $('#multipleChoiceRow' + ids).remove();
        }

        function setButtonNext() {
            // btnNext.attr('onclick', 'simpanSetup()');
            btnNext.attr('onclick', 'nextStep()');
            btnBack.attr('onclick', 'backStep()');
        }

        function nextStep() {
            step = localStorage.getItem('step');
            if (step == 0) {
                // go to 'Jumlah Pertanyaan'
                if (type == undefined) {
                    iziToast['error']({
                        message: 'Pastikan tipe jawaban sudah dipilih',
                        position: "topRight"
                    });
                } else {
                    if (type == 1) {
                        $('.titleQuestionNumber').html('Jumlah soal pilihan ganda');
                    } else {
                        $('.titleQuestionNumber').html('Jumlah soal esai');
                    }

                    // remove form type answer
                    $('.rowType').addClass('slide-to-left');
                    // hide form type answer
                    setTimeout(() => {
                        $('.rowType').addClass('d-none');
                    }, 505);

                    // insert form question number
                    setTimeout(() => {
                        $('.rowNumber').removeClass('d-none');
                    }, 508);
                    setTimeout(() => {
                        $('.rowNumber').addClass('slide-to-right');
                        $progressBar.setCurrentStep((Number(step) + 1));
                    }, 510);
                    localStorage.setItem('step', (Number(step) + 1));
                }
            } else if (step == 1) {
                // set value
                let val = $('#questionNumber').val();

                $('.rowNumber').removeClass('slide-to-right');
                if (val == "") {
                    iziToast['error']({
                        message: 'Jumlah soal harus diisi',
                        position: "topRight"
                    });
                } else {
                    localStorage.setItem('step', (Number(step) + 1));

                    // set button finish
                    btnNext.html('Mulai Buat Pertanyaan');
                    btnNext.attr('onclick', 'simpanSetup()');

                    // hide question number form
                    $('.rowNumber').addClass('slide-to-left');
                    setTimeout(() => {
                        $('.rowNumber').addClass('d-none');
                    }, 505);

                    if (type == 1) { // if type is multiple choice
                        $('.card-setup').css({
                            'height': '450px'
                        });
                        // show poin form
                        setTimeout(() => {
                            $('.rowPoinMultiple').removeClass('d-none');
                        }, 508);
                        setTimeout(() => {
                            $('.rowPoinMultiple').addClass('slide-to-right');
                            $progressBar.setCurrentStep((Number(step) + 1));
                        }, 510);
                    } else { // if type is essay
                        // show poin form
                        setTimeout(() => {
                            $('.rowPoin').removeClass('d-none');
                        }, 508);
                        setTimeout(() => {
                            $('.rowPoin').addClass('slide-to-right');
                            $progressBar.setCurrentStep((Number(step) + 1));
                        }, 510);
                    }
                }
            }
        }

        function setPoin() {
            let questionNum = $('#currentQuestionNumber').val();
            let maxScore = $('#maxScore').val();
            let poin = Number(questionNum) / Number(maxScore);
            $('#poinPerQuestion').val(poin);
        }

        function simpanSetup() {
            if (type == 1) {
                handleMultipleAnswer();
            } else {
                handleEssayAnswer();
            }
        }

        function handleMultipleAnswer() {
            let elem = $('.poinMultiple');
            let len = elem.length;
            let val = [];
            for (let a = 0; a < len; a++) {
                console.log('elem',elem[a].value);
            }
        }

        function handleEssayAnswer() {
            let poinPerQuestion = $('#poinPerQuestion').val();
            if (poinPerQuestion == "") {
                iziToast['error']({
                    message: 'Harap setting skema poin',
                    position: "topRight"
                });
            } else {
                                                     
            }
        }

        function nextStepp(next) {
            if (next == 1) {
                if (type != undefined) {
                    if (type == 1) {
                        $('.titleQuestionNumber').html('Jumlah soal pilihan ganda');
                    } else {
                        $('.titleQuestionNumber').html('Jumlah soal esai');
                    }

                    $('.rowType').addClass('slide-to-left');
                    setInterval(() => {
                        $('.rowType').addClass('d-none');
                    }, 505);
                    setInterval(() => {
                        $('.rowNumber').removeClass('d-none');
                    }, 508);
                    setInterval(() => {
                        $('.rowType').addClass('slide-to-right');
                        $progressBar.setCurrentStep(next);
                    }, 510);
                    localStorage.setItem('step', (next + 1));
                    btnNext.attr('onclick', `nextStep(${(next+1)})`);
                    questionNumber = $('#questionNumber').val();
                } else {
                    iziToast['error']({
                        message: 'Pastikan tipe jawaban sudah dipilih',
                        position: "topRight"
                    });
                }
            } else if (next == 2) {
                
            }
        }

        function chooseType(ids) {
            let active = "{{ asset('images/radio_button_active.png') }}";
            let inactive = "{{ asset('images/radio_button_inactive.png') }}";
            let img = $('.imgType');
            let imgLen = img.length;
            for (let a = 0; a < imgLen; a++) {
                img[a].setAttribute('src', inactive);
            }
            $('#imgType' + ids).attr('src', active);
            type = ids;
        }

        function goToSetup() {
            $('.card-first-setup').addClass('slide-out-left');
            setInterval(() => {
                $('.card-first-setup').addClass('d-none');
            }, 500);
            setInterval(() => {
                $('.card-setup-1').removeClass('d-none');
            }, 505);
            setInterval(() => {
                $('.card-setup-1').addClass('slide-in-right');
            }, 510);
        }
    </script>
@endpush