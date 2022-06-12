@extends('layouts.master')
{{-- begin::styles --}}
@push('styles')
    <style>
        .accordion-button:not(.collapsed) {
            background: #fff !important;
            font-size: 17px;
            font-weight: bold;
            color: #000;
            text-transform: capitalize;
        }
    </style>
@endpush
{{-- end::styles --}}
{{-- begin::content --}}
@section('content')
    {{-- begin::card --}}
    <div class="card card-flush mb-5">
        <div class="card-body p-4">
            <div class="text-start">
                <a class="btn btn-light-primary" href="{{ route('employee.index') }}">
                    <i class="fa fa-chevron-left me-3"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    {{-- end::card --}}

    {{-- begin::form --}}
    <form action="" id="formEmployee">
        {{-- BEGIN::DATA-PERSONAL --}}
        <div class="accordion mb-5" id="accordionPersonalData">
            <div class="accordion-item">
                <h3 class="accordion-header" id="headerPersonalData">
                    <button class="accordion-button text-center d-block" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonalData" aria-expanded="true" aria-controls="collapsePersonalData">
                        Data personal
                    </button>
                </h3>
                <div id="collapsePersonalData" class="accordion-collapse collapse show" aria-labelledby="headerPersonalData" data-bs-parent="#accordionPersonalData">
                    <div class="accordion-body">
                        <div class="form-group row mb-5">
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeName" class="col-form-label">Nama</label>
                                <input type="text" name="name" class="form-control" placeholder="Nama Karyawan" id="employeeName">
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeAliases" class="col-form-label">Nama Panggilan</label>
                                <input placeholder="Nama Panggilan" type="text" name="aliases" class="form-control" id="employeeAliases">
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeNik" class="col-form-label">NIK</label>
                                <input type="number" name="nik" placeholder="NIK KTP" class="form-control" id="employeeNik">
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-6 col-xl-6">
                                <label for="employeeEmail" class="col-form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email Aktif Karyawan" id="employeeEmail">
                            </div>
                            <div class="col-md-6 col-xl-6">
                                <label for="employeePhone" class="col-form-label">No. Telepon</label>
                                <input type="number" name="phone" class="form-control" id="employeePhone" placeholder="No Telepon">
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-6 col-xl-6">
                                <label for="employeeBirth" class="col-form-label">Tanggal Lahir</label>
                                <input type="date" name="birth_date" class="form-control" id="employeeBirth">
                            </div>
                            <div class="col-md-6 col-xl-6">
                                <label for="" class="col-form-label">Jenis Kelamin</label>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="L" name="gender" id="employeeGenderL">
                                            <label class="form-check-label" for="employeeGenderL">
                                                LAKI - LAKI
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="P" name="gender" id="employeeGenderP">
                                            <label class="form-check-label" for="employeeGenderP">
                                                PEREMPUAN
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-3">
                                <label for="employeeProvince" class="col-form-label">Provinsi</label>
                                <select name="province" class="form-select form-control" id="employeeProvince">
                                    <option value="">- Pilih Provinsi -</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="employeeRegency" class="col-form-label">Kota</label>
                                <select name="regency" class="form-select form-control" id="employeeRegency">
                                    <option value="">- Pilih Kota -</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="employeeDistrict" class="col-form-label">Kecamatan</label>
                                <select name="district" class="form-select form-control" id="employeeDistrict">
                                    <option value="">- Pilih Kecamatan -</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="employeeVillage" class="col-form-label">Kelurahan</label>
                                <select name="village" class="form-select form-control" id="employeeVillage">
                                    <option value="">- Pilih Kelurahan -</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col">
                                <label for="employeeAddress" class="col-form-label">Alamat</label>
                                <textarea name="address" class="form-control" id="employeeAddress" cols="3" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END::DATA-PERSONAL --}}

        {{-- BEGIN::DATA-FAMILY --}}
        <div class="accordion mb-5" id="accordionFamilyData">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFamilyData">
                    <button class="accordion-button text-center d-block" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFamilyData" aria-expanded="true" aria-controls="collapseFamilyData">
                        Data Keluarga
                    </button>
                </h2>
                <div id="collapseFamilyData" class="accordion-collapse collapse show" aria-labelledby="headingFamilyData" data-bs-parent="#accordionFamilyData">
                    <div class="accordion-body">
                        <div class="form-group row mb-5">
                            <div class="col-md-6 col-xl-6">
                                <label for="employeeWali" class="col-form-label">Wali</label>
                                <input placeholder="Nama Wali" type="text" class="form-control" name="wali" id="employeeWali">
                            </div>
                            <div class="col-md-6 col-xl-6">
                                <label for="employeeWaliPhone" class="col-form-label">Nomor Telfon Wali</label>
                                <input type="number" placeholder="No Telepon Wali" class="form-control" name="wali_number" id="employeeWaliPhone">
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col">
                                <label for="employeeWaliAddress" class="col-form-label">Alamat Wali</label>
                                <textarea name="wali_address" id="employeeWaliAddress" cols="3" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END::DATA-FAMILY --}}

        {{-- BEGIN::DATA-EDUCATION --}}
        <div class="accordion mb-5" id="accordionEducationData">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingEducationData">
                    <button class="accordion-button text-center d-block" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEducationData" aria-expanded="true" aria-controls="collapseEducationData">
                        Data Pendidikan
                    </button>
                </h2>
                <div id="collapseEducationData" class="accordion-collapse collapse show" aria-labelledby="headingEducationData" data-bs-parent="#accordionEducationData">
                    <div class="accordion-body">
                        <div class="form-group mb-5 row">
                            <div class="col-md-4 col-xl-4">
                                <label for="employeePrimarySchool" class="col-form-label">Sekolah Dasar</label>
                                <input type="text" name="primary_school" class="form-control" placeholder="Nama Sekolah Dasar" id="employeePrimarySchool">
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeePrimaryGraduate" class="col-form-label">Tahun Lulus</label>
                                <input type="month" name="primary_school_graduate" class="form-control" id="employeePrimaryGraduate">
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeePrimaryGpa" class="col-form-label">Nilai Kelulusan</label>
                                <input type="text" placeholder="Nilai Akhir" name="primary_school_gpa" class="form-control" id="employeePrimaryGpa">
                            </div>
                        </div>
                        <div class="form-group mb-5 row">
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeJuniorSchool" class="col-form-label">Sekolah Menengah Pertama</label>
                                <input type="text" name="junior_high_school" class="form-control" placeholder="Nama Sekolah Menengah Pertama" id="employeeJuniorSchool">
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeJuniorGraduate" class="col-form-label">Tahun Lulus</label>
                                <input type="month" name="junior_high_school_graduate" class="form-control" id="employeeJuniorGraduate">
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeJuniorGpa" class="col-form-label">Nilai Kelulusan</label>
                                <input type="text" placeholder="Nilai Akhir" name="junior_high_school_gpa" class="form-control" id="employeeJuniorGpa">
                            </div>
                        </div>
                        <div class="form-group mb-5 row">
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeHighSchool" class="col-form-label">Sekolah Menengah Atas</label>
                                <input type="text" name="high_school" class="form-control" placeholder="Nama Sekolah Menengah Atas" id="employeeHighSchool">
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeHighGraduate" class="col-form-label">Tahun Lulus</label>
                                <input type="month" name="high_school_graduate" class="form-control" id="employeeHighGraduate">
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeHighGpa" class="col-form-label">Nilai Kelulusan</label>
                                <input type="text" placeholder="Nilai Akhir" name="high_school_gpa" class="form-control" id="employeeHighGpa">
                            </div>
                        </div>
                        <div class="form-group mb-5 row">
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeUniversity" class="col-form-label">Universitas</label>
                                <input type="text" name="university" class="form-control" placeholder="Nama Universitas" id="employeeUniversity">
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeUniversityGraduate" class="col-form-label">Tahun Lulus</label>
                                <input type="month" name="university_graduate" class="form-control" id="employeeUniversityGraduate">
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeUniversityGpa" class="col-form-label">Nilai Kelulusan</label>
                                <input type="text" placeholder="Nilai Akhir" name="university_gpa" class="form-control" id="employeeUniversityGpa">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END::DATA-EDUCATION --}}

        {{-- BEGIN::DATA-EXPERIENCE --}}
        <div class="accordion mb-5" id="accordionExperience">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingExperience">
                    <button class="accordion-button text-center d-block" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExperience" aria-expanded="true" aria-controls="collapseExperience">
                        Data Pengalaman Kerja
                    </button>
                </h2>
                <div id="collapseExperience" class="accordion-collapse collapse show" aria-labelledby="headingExperience" data-bs-parent="#accordionExperience">
                    <div class="accordion-body">
                        <div class="row mb-5">
                            <div class="col">
                                <h4>Pengalaman Kerja I</h4>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-4">
                                <label for="employeeExperience1" class="col-form-label">Nama Tempat Bekerja</label>
                                <input type="text" name="work_experience_name_1" placeholder="Nama Kantor" class="form-control" id="employeeExperience1">
                            </div>
                            <div class="col-md-4">
                                <label for="employeeExperiencePosition1" class="col-form-label">Posisi Terakhir</label>
                                <input type="text" name="work_experience_position_1" placeholder="Jabatan Terkahir" class="form-control" id="employeeExperiencePosition1">
                            </div>
                            <div class="col-md-2">
                                <label for="employeeExperienceIn1" class="col-form-label">Tahun Masuk</label>
                                <input type="month" name="work_experience_in_1" placeholder="Jabatan Terkahir" class="form-control" id="employeeExperienceIn1">
                            </div>
                            <div class="col-md-2">
                                <label for="employeeExperienceOut1" class="col-form-label">Tahun Keluar</label>
                                <input type="month" name="work_experience_out_1" placeholder="Jabatan Terkahir" class="form-control" id="employeeExperienceOut1">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col">
                                <h4>Pengalaman Kerja II</h4>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-4">
                                <label for="employeeExperience2" class="col-form-label">Nama Tempat Bekerja</label>
                                <input type="text" name="work_experience_name_2" placeholder="Nama Kantor" class="form-control" id="employeeExperience2">
                            </div>
                            <div class="col-md-4">
                                <label for="employeeExperiencePosition2" class="col-form-label">Posisi Terakhir</label>
                                <input type="text" name="work_experience_position_2" placeholder="Jabatan Terkahir" class="form-control" id="employeeExperiencePosition2">
                            </div>
                            <div class="col-md-2">
                                <label for="employeeExperienceIn2" class="col-form-label">Tahun Masuk</label>
                                <input type="month" name="work_experience_in_2" placeholder="Jabatan Terkahir" class="form-control" id="employeeExperienceIn2">
                            </div>
                            <div class="col-md-2">
                                <label for="employeeExperienceOut2" class="col-form-label">Tahun Keluar</label>
                                <input type="month" name="work_experience_out_2" placeholder="Jabatan Terkahir" class="form-control" id="employeeExperienceOut2">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col">
                                <h4>Pengalaman Kerja III</h4>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-md-4">
                                <label for="employeeExperience3" class="col-form-label">Nama Tempat Bekerja</label>
                                <input type="text" name="work_experience_name_3" placeholder="Nama Kantor" class="form-control" id="employeeExperience3">
                            </div>
                            <div class="col-md-4">
                                <label for="employeeExperiencePosition3" class="col-form-label">Posisi Terakhir</label>
                                <input type="text" name="work_experience_position_3" placeholder="Jabatan Terkahir" class="form-control" id="employeeExperiencePosition3">
                            </div>
                            <div class="col-md-2">
                                <label for="employeeExperienceIn3" class="col-form-label">Tahun Masuk</label>
                                <input type="month" name="work_experience_in_3" placeholder="Jabatan Terkahir" class="form-control" id="employeeExperienceIn3">
                            </div>
                            <div class="col-md-2">
                                <label for="employeeExperienceOut3" class="col-form-label">Tahun Keluar</label>
                                <input type="month" name="work_experience_out_3" placeholder="Jabatan Terkahir" class="form-control" id="employeeExperienceOut3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END::DATA-EXPERIENCE --}}

        {{-- BEGIN::DATA-VACCINE --}}
        <div class="accordion mb-5" id="accordionVaccine">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingVaccine">
                    <button class="accordion-button text-center d-block" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVaccine" aria-expanded="true" aria-controls="collapseVaccine">
                        Data Vaksin
                    </button>
                </h2>
                <div id="collapseVaccine" class="accordion-collapse collapse show" aria-labelledby="headingVaccine" data-bs-parent="#accordionVaccine">
                    <div class="accordion-body">
                        <div class="form-group mb-5 row">
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeVaccineType" class="col-form-label">Dosis Pertama</label>
                                <select name="vaccine_type_1" id="employeeVaccineType" class="form-select form-control">
                                    @foreach ($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeVaccineDate" class="col-form-label">Tanggal Vaksin</label>
                                <input type="date" name="vaccine_date_1" class="form-control" id="employeeVaccineDate">
                            </div>
                        </div>
                        <div class="form-group mb-5 row">
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeVaccineType2" class="col-form-label">Dosis Kedua</label>
                                <select name="vaccine_type_2" id="employeeVaccineType2" class="form-select form-control">
                                    @foreach ($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeVaccineDate2" class="col-form-label">Tanggal Vaksin</label>
                                <input type="date" name="vaccine_date_2" class="form-control" id="employeeVaccineDate2">
                            </div>
                        </div>
                        <div class="form-group mb-5 row">
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeVaccineType3" class="col-form-label">Dosis Ketiga</label>
                                <select name="vaccine_type_3" id="employeeVaccineType3" class="form-select form-control">
                                    @foreach ($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <label for="employeeVaccineDate3" class="col-form-label">Tanggal Vaksin</label>
                                <input type="date" name="vaccine_date_3" class="form-control" id="employeeVaccineDate3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END::DATA-VACCINE --}}

        {{-- BEGIN::DATA-WORK --}}
        <div class="accordion" id="accordionWorkData">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingWorkData">
                    <button class="accordion-button text-center d-block" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWorkData" aria-expanded="true" aria-controls="collapseWorkData">
                        Data Pekerjaan
                    </button>
                </h2>
                <div id="collapseWorkData" class="accordion-collapse collapse show" aria-labelledby="headingWorkData" data-bs-parent="#accordionWorkData">
                    <div class="accordion-body">
                        <div class="form-group mb-5 row">
                            <div class="col-md-6 col-xl-6">
                                <label for="employeeDivision" class="col-form-label">Divisi</label>
                                <select name="division" id="employeeDivision" class="form-select form-control">
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}">{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-xl-6">
                                <label for="employeePosition" class="col-form-label">Posisi</label>
                                <select name="position" id="employeePosition" class="form-select form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END::DATA-WORK --}}

    </form>
@endsection
{{-- end::content --}}

{{-- begin::scripts --}}
@push('scripts')
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#employeeProvince').select2();

        $('#employeeProvince').on('change', function(e) {
            e.preventDefault();
            let val = $(this).val();
            
            $.ajax({
                type: "GET",
                url: "{{ url('/region/getRegency/') }}" + "/" + val,
                dataType: 'json',
                success: function(res) {
                    let data = res.data;
                    let option = `<option value="">- Pilih Kota -</option>`;

                    for (let a = 0; a < data.length; a++) {
                        option += `<option value="${data[a].id}">${data[a].name}</option>`;
                    }
                    $('#employeeRegency').html(option);
                    $('#employeeRegency').select2();
                }
            })
        });

        $('#employeeRegency').on('change', function(e) {
            e.preventDefault();
            let val = $(this).val();
            
            $.ajax({
                type: "GET",
                url: "{{ url('/region/getDistrict/') }}" + "/" + val,
                dataType: 'json',
                success: function(res) {
                    let data = res.data;
                    let option = `<option value="">- Pilih Kecamatan -</option>`;

                    for (let a = 0; a < data.length; a++) {
                        option += `<option value="${data[a].id}">${data[a].name}</option>`;
                    }
                    $('#employeeDistrict').html(option);
                    $('#employeeDistrict').select2();
                }
            })
        });

        $('#employeeDistrict').on('change', function(e) {
            e.preventDefault();
            let val = $(this).val();
            
            $.ajax({
                type: "GET",
                url: "{{ url('/region/getVillage/') }}" + "/" + val,
                dataType: 'json',
                success: function(res) {
                    let data = res.data;
                    let option = `<option value="">- Pilih Kelurahan -</option>`;

                    for (let a = 0; a < data.length; a++) {
                        option += `<option value="${data[a].id}">${data[a].name}</option>`;
                    }
                    $('#employeeVillage').html(option);
                    $('#employeeVillage').select2();
                }
            })
        });
    </script>
@endpush
{{-- end::scripts --}}