<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeTemplateExport;
use App\Imports\EmployeeImport;
use App\Models\District;
use App\Models\Division;
use App\Models\Employee;
use App\Models\EmployeeStatus;
use App\Models\EmployeeVaccine;
use App\Models\MenstruationLeave;
use App\Models\PermissionLeaveOffice;
use App\Models\Position;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Vaccine;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    private $rules;
    private $messageRules;

    public function __construct()
    {
        $this->rules = [
            'name' => 'required',
            'email' => 'required',
            'aliases' => 'required',
            'nik' => 'required',
            'phone' => 'required',
            'division_id' => 'required',
            'position_id' => 'required',
            'vaccine_date_1' => 'required_with:vaccine_type_1',
            'vaccine_date_2' => 'required_with:vaccine_type_2',
            'vaccine_date_3' => 'required_with:vaccine_type_3',
            'employee_id' => 'required'
        ];

        $this->messageRules = [
            'name.required' => 'Nama Karyawan Wajib Diisi',
            'email.required' => 'Email Wajib Diisi',
            'aliases.required' => 'Nama Panggilan Wajib Diisi',
            'nik.required' => 'NIK Wajib Diisi',
            'division_id.required' => 'Divisi Karyawan Wajib Diisi',
            'employee_id.required' => 'ID Karyawan Wajib Diisi',
            'position_id.required' => 'Posisi Karyawan Wajib Diisi',
            'vaccine_date_1.required_with' => 'Tanggal vaksin pertama harus diisi jika jenis vaksin terisi',
            'vaccine_date_2.required_with' => 'Tanggal vaksin kedua harus diisi jika jenis vaksin terisi',
            'vaccine_date_3.required_with' => 'Tanggal vaksin ketiga harus diisi jika jenis vaksin terisi'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Karyawan';
        return view('employees.index', compact('pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Tambah Karyawan';
        $provinces = Province::all();
        $vaccines = Vaccine::all();
        $divisions = Division::all();
        $employeeStatus = EmployeeStatus::all();
        return view('employees.create', compact('pageTitle', 'employeeStatus', 'provinces', 'vaccines', 'divisions'));
    }

    public function json($type) {
        $where = "is_active = $type";
        $data = Employee::with(['userVaccine.vaccine', 'division', 'position'])
            ->whereRaw($where)
            ->where('id', '!=', Auth::id())
            ->get();
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return '<a href="'. route('employees.show', $data->id) .'">'. ucwords($data->name) .'</a>';
            })
            ->addColumn('division', function($data) {
                return ucwords($data->division->name);
            })
            ->addColumn('position', function($data) {
                return ucwords($data->position->name);
            })
            ->addColumn('working_status', function($data) {
                $status = $data->employee_status == 0 ? '<span class="badge badge-warning">KONTRAK</span>' : '<span class="badge badge-success">TETAP</span>';
                return $status;
            })
            ->addColumn('status_vaccine', function($data) {
                if ($data->userVaccine != null) {
                    return $data->current_vaccine_level == 3 ? '<span class="badge badge-success">Lengkap</span>' 
                    : '<span class="badge badge-danger">Dosis '. $data->current_vaccine_level .' ('. $data->userVaccine->vaccine->name .')</span>'; 
                } else {
                    return '<span class="badge badge-danger">Belum Vaksin</span>'; 
                }
            })
            ->addColumn('action', function($data) {
                if ($data->is_active == 1) {
                    return '<a href="'. route('employees.edit', $data->id) .'" class="text-info me-3"><i class="fa fa-edit"></i></a>
                        <span style="cursor: pointer;" onclick="deleteData('. $data->id .')"><i class="fas fa-trash"></i></span>';
                }
            })
            ->rawColumns(['action', 'status_vaccine', 'name', 'position', 'working_status'])
            ->make(true);
    }

    public function downloadTemplate()
    {
        return Excel::download(new EmployeeTemplateExport, 'template.xlsx');
    }

    public function import(Request $request) {
        if ($request->has('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $name = 'import_' . date('YmdHis') . '.' . $ext;
            $file->storeAs('employee/import', $name, 'public');
            $sheet = 'Master';
            $filetype = 'Xlsx';
            /**  Create a new Reader of the type defined in $inputFileType  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($filetype);
            /**  Advise the Reader of which WorkSheets we want to load  **/
            $reader->setLoadSheetsOnly($sheet);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load('employee/import/' . $name);
            $arr = $spreadsheet->getActiveSheet()
                ->rangeToArray(
                    'A4:J10',
                    '',
                    TRUE,
                    TRUE,
                    FALSE,
                );
            try {
                $newArr = [];
                for ($a = 0; $a < count($arr); $a++) {
                    for ($b =0; $b < count($arr[$a]); $b++) {
                        if ($arr[$a][$b] != '') {
                            $name = $arr[$a][1];
                            $nickname = explode(' ', $name)[0];
                            $dataPosition = $arr[$a][5];
                            $dataDivision = $arr[$a][6];
                            $dataEmpStatus = $arr[$a][7];
                            $dataEmpId = $arr[$a][0];
                            $dataEmail = $arr[$a][2];
                            $dataNik = $arr[$a][4];
                            $dataPhone = $arr[$a][3];

                            // validation
                            if ($name == '' || $dataPosition == ''|| $dataDivision == ''
                                || $dataEmpStatus == '' || $dataEmpId == '' || $dataEmail == ''
                                || $dataNik == '' || $dataPhone == "") {
                                return sendResponse(
                                    ['error' => 'Pastikan semua kolom pada template sudah terisi'],
                                    'FAILED',
                                    500
                                );
                            }

                            $position = Position::select('id', 'name')
                                ->where('name', $arr[$a][5])
                                ->first();
                            if ($position == NULL) {
                                return sendResponse(
                                    ['error' => 'Nama Jabatan tidak terdaftar'],
                                    'FAILED',
                                    500
                                );
                            }
                            $division = Division::select('id', 'name', 'department_id')
                                ->where('name', $arr[$a][6])
                                ->first();
                            if ($division == NULL) {
                                return sendResponse(
                                    ['error' => 'Nama Divisi tidak terdaftar'],
                                    'FAILED',
                                    500
                                );
                            }
                            $employeeStatus = EmployeeStatus::select('id', 'name')
                                ->where('name', $arr[$a][7])
                                ->first();
                            if ($employeeStatus == NULL) {
                                return sendResponse(
                                    ['error' => 'Nama Status Karyawan tidak terdaftar'],
                                    'FAILED',
                                    500
                                );
                            }
                            $newArr[$a] = [
                                'employee_id' => (string)$arr[$a][0],   
                                'name'  => $arr[$a][1],
                                'aliases' => $nickname,
                                'email' => $arr[$a][2],
                                'nik' => $arr[$a][4],
                                'phone' => $arr[$a][3],
                                'position_id' => $position->id,
                                'division_id' => $division->id,
                                'department_id' => $division->department_id,
                                'employee_status_id' => $employeeStatus->id,
                                'bank_name' => $arr[$a][8],
                                'bank_account_number' => $arr[$a][9],
                                'bank_account_name' => $name
                            ];
                        }
                    }
                }
                // save to database
                $updatedColumn = ['name', 'aliases', 'email', 'nik', 'phone',
                'position_id', 'division_id', 'department_id', 'employee_status_id',
                'bank_name', 'bank_account_number', 'bank_account_name'];
                Employee::upsert(
                    $newArr,
                    ['employee_id'],
                    $updatedColumn
                );
                return sendResponse([]);
            } catch (\Throwable $th) {
                return sendResponse(
                    ['error' => $th->getMessage()],
                    'FAILED',
                    500
                );
            }
        }
    }

    /**
     * Display All Employee
     * 
     * @return \Illuminate\Http\Response
     */
    public function getData() {
        try {
            $id = Auth::id();
            $data = Employee::select(['id', 'name', 'position_id'])
                ->with(['position:id,name'])
                ->where(['is_active' => 1])
                ->where('id', '!=', $id)
                ->get();
            return sendResponse($data);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Display All Employee
     * 
     * @return \Illuminate\Http\Response
     */
    public function getDivision($id) {
        $data = Employee::with(['division', 'position'])->where(['is_active' => 1, 'id' => $id])->first();
        return sendResponse([
            'position' => [
                'name' => $data->position->name,
                'id' => $data->position->id
            ],
            'division' => [
                'name' => $data->division->name,
                'id' => $data->division->id
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // begin::validation
        $validation = Validator::make(
            $request->all(),
            $this->rules,
            $this->messageRules
        );
        if ($validation->fails()) {
            $error = $validation->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }
        // end::validation

        // begin::variable
        $photo = $request->photo;
        $employeeId = $request->employee_id;
        $name = $request->name;
        $aliases = $request->aliases;
        $nik = $request->nik;
        $email = $request->email;
        $phone = $request->phone;
        $birthDate = $request->birth_date;
        $gender = $request->gender;
        $provinceId = $request->province;
        $regencyId = $request->regency;
        $districtId = $request->district;
        $villageId = $request->village;
        $address = $request->address;
        $divisionId = $request->division_id;
        $departmentId = Division::find($divisionId)->department_id;
        $positionId = $request->position_id;
        $employeeStatus = $request->employee_status_id;
        $bankName = $request->bank_name;
        $bankAccount = $request->bank_account;
        $bankAccountName = $request->bank_account_name;
        $wali = $request->wali;
        $waliNumber = $request->wali_number;
        $waliAddress = $request->wali_address;
        $primarySchool = $request->primary_school;
        $primarySchoolGraduate = $request->primary_school_graduate;
        $primarySchoolGpa = $request->primary_school_gpa;
        $juniorHighSchool = $request->junior_high_school;
        $juniorHighSchoolGraduate = $request->junior_high_school_graduate;
        $juniorHighSchoolGpa = $request->junior_high_school_gpa;
        $highSchool = $request->high_school;
        $highSchoolGraduate = $request->high_school_graduate;
        $highSchoolGpa = $request->high_school_gpa;
        $university = $request->university;
        $universityGraduate = $request->university_graduate;
        $universityGpa = $request->university_gpa;
        $workExperienceName1 = $request->work_experience_name_1;
        $workExperiencePosition1 = $request->work_experience_postition_1;
        $workExperienceIn1 = $request->work_experience_in_1;
        $workExperienceOut1 = $request->work_experience_out_1;
        $workExperienceName2 = $request->work_experience_name_2;
        $workExperiencePosition2 = $request->work_experience_postition_2;
        $workExperienceIn2 = $request->work_experience_in_2;
        $workExperienceOut2 = $request->work_experience_out_2;
        $workExperienceName3 = $request->work_experience_name_3;
        $workExperiencePosition3 = $request->work_experience_postition_3;
        $workExperienceIn3 = $request->work_experience_in_3;
        $workExperienceOut3 = $request->work_experience_out_3;
        $vaccineType1 = $request->vaccine_type_1;
        $vaccineDate1 = $request->vaccine_date_1;
        $vaccineType2 = $request->vaccine_type_2;
        $vaccineDate2 = $request->vaccine_date_2;
        $vaccineType3 = $request->vaccine_type_3;
        $vaccineDate3 = $request->vaccine_date_3;
        // end::variable

        DB::beginTransaction();
        try {

            $dataEmployee = [
                'name' => $name,
                'aliases' => $aliases,
                'email' => $email,
                'nik' => $nik,
                'gender' => $gender,
                'birth_date' => $birthDate,
                'address' => $address,
                'province_id' => $provinceId,
                'regency_id' => $regencyId,
                'district_id' => $districtId,
                'village_id' => $villageId,
                'phone' => $phone,
                'primary_school' => $primarySchool,
                'primary_school_graduate' => $primarySchoolGraduate,
                'primary_school_gpa' => $primarySchoolGpa,
                'junior_high_school' => $juniorHighSchool,
                'junior_high_school_graduate' => $juniorHighSchoolGraduate,
                'junior_high_school_gpa' => $juniorHighSchoolGpa,
                'high_school' => $highSchool,
                'high_school_graduate' => $highSchoolGraduate,
                'high_school_gpa' => $highSchoolGpa,
                'university' => $university,
                'university_graduate' => $universityGraduate,
                'university_gpa' => $universityGpa,
                'work_experience_name_1' => $workExperienceName1,
                'work_experience_position_1' => $workExperiencePosition1,
                'work_experience_in_1' => $workExperienceIn1,
                'work_experience_out_1' => $workExperienceOut1,
                'work_experience_name_2' => $workExperienceName2,
                'work_experience_position_2' => $workExperiencePosition2,
                'work_experience_in_2' => $workExperienceIn2,
                'work_experience_out_2' => $workExperienceOut2,
                'work_experience_name_3' => $workExperienceName3,
                'work_experience_position_3' => $workExperiencePosition3,
                'work_experience_in_3' => $workExperienceIn3,
                'work_experience_out_3' => $workExperienceOut3,
                'wali_name' => $wali,
                'wali_phone' => $waliNumber,
                'wali_address' => $waliAddress,
                'department_id' => $departmentId,
                'division_id' => $divisionId,
                'position_id' => $positionId,
                'employee_status_id' => $employeeStatus,
                'bank_name' => $bankName,
                'bank_account_number' => $bankAccount,
                'bank_account_name' => $bankAccountName,
                'employee_id' => $employeeId
            ];
            if ($vaccineType1 != null && $vaccineType2 != null && $vaccineType3 != null) {
                $dataEmployee['current_vaccine_level'] = 3;
            } else if ($vaccineType1 != null && $vaccineType2 != null && $vaccineType3 == null) {
                $dataEmployee['current_vaccine_level'] = 2;
            } else if ($vaccineType1 != null && $vaccineType2 == null) {
                $dataEmployee['current_vaccine_level'] = 1;
            }
            $employee = Employee::insertGetId($dataEmployee);

            $dataVaccine = [];
            if ($vaccineType1 != null) {
                $arr1 = [
                    'user_id' => $employee,
                    'vaccine_id' => $vaccineType1,
                    'vaccine_grade' => 1,
                    'vaccine_date' => $vaccineDate1
                ];
                array_push($dataVaccine, $arr1);
            }
            if ($vaccineType2 != null) {
                $arr2 = [
                    'user_id' => $employee,
                    'vaccine_id' => $vaccineType2,
                    'vaccine_grade' => 2,
                    'vaccine_date' => $vaccineDate2
                ];
                array_push($dataVaccine, $arr2);
            }
            if ($vaccineType3 != null) {
                $arr3 = [
                    'user_id' => $employee,
                    'vaccine_id' => $vaccineType3,
                    'vaccine_grade' => 3,
                    'vaccine_date' => $vaccineDate3
                ];
                array_push($dataVaccine, $arr3);
            }
            EmployeeVaccine::insert($dataVaccine);
            if ($employee) {
                $secondName = $photo->getClientOriginalName();
                if ($secondName != 'blob') {
                    $ext = $photo->getClientOriginalExtension();
                    $fileName = $employee . '-' . date('Ymd'). '-' . $positionId . '.' . $ext;
                    $photo->storeAs('employee/photo', $fileName, 'public');
                    Employee::where('id', $employee)->update([
                        'photo' => asset('employee/photo/' . $fileName)
                    ]);
                }
            }
            DB::commit();
            return sendResponse(['vaccine' => $dataVaccine]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Employee::with(['division', 'user', 'position', 'userVaccine.vaccine', 'village.district.regency.province'])
            ->find($id);
        $userVaccine = EmployeeVaccine::where('user_id', $user->id)->get();
        $dosis1 = $userVaccine->where('vaccine_grade', 1)->first() ?? '';
        $dosis2 = $userVaccine->where('vaccine_grade', 2)->first() ?? '';
        $dosis3 = $userVaccine->where('vaccine_grade', 3)->first() ?? '';
        $pageTitle = 'Detail Karyawan';
        $villageHelper = "";
        $provinceHelper = "";
        $addressHelper = "";
        if ($user->province_id != null) {
            $villageHelper = $user->village->name . ', ' . $user->village->district->name . ', ' . $user->village->district->regency->name;
            $provinceHelper = $user->village->district->regency->province->name;
            $addressHelper = $user->address;
        }
        $permissionLeaveOffice = PermissionLeaveOffice::where('employee_id', $id)
            ->count();
        $leaveMenstruation = MenstruationLeave::where('employee_id', $id)
            ->count();
        return view('employees.profile', compact(
            'user', 'pageTitle', 'userVaccine',
            'dosis1', 'dosis2', 'dosis3', 'addressHelper',
            'villageHelper', 'provinceHelper', 'permissionLeaveOffice',
            'leaveMenstruation'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Tambah Karyawan';
        $employee = Employee::find($id);
        $provinces = Province::all();
        $regency = Regency::where('province_id', $employee->province_id)->get();
        $district = District::where('regency_id', $employee->regency_id)->get();
        $village = Village::where("district_id", $employee->district_id)->get();
        $vaccines = Vaccine::all();
        $divisions = Division::all();
        $selectedDivison = $employee->division_id;
        $position = Position::where('division_id', $selectedDivison)->get();
        $vaccineType1 = EmployeeVaccine::where('vaccine_grade', 1)
            ->where('user_id', $employee->id)
            ->first();
        $vaccineType2 = EmployeeVaccine::where('vaccine_grade', 2)
            ->where('user_id', $employee->id)
            ->first();
        $vaccineType3 = EmployeeVaccine::where('vaccine_grade', 3)
            ->where('user_id', $employee->id)
            ->first();
        $vaccineId1 = $vaccineType1 != null ? $vaccineType1->vaccine_id : '';
        $vaccineId2 = $vaccineType2 != null ? $vaccineType2->vaccine_id : '';
        $vaccineId3 = $vaccineType3 != null ? $vaccineType3->vaccine_id : '';
        $vaccineDate1 = $vaccineType1 != null ? $vaccineType1->vaccine_date : '';
        $vaccineDate2 = $vaccineType2 != null ? $vaccineType2->vaccine_date : '';
        $vaccineDate3 = $vaccineType3 != null ? $vaccineType3->vaccine_date : '';
        return view('employees.edit', compact(
            'pageTitle', 'provinces', 'vaccines', 'position',
            'divisions', 'employee', 'vaccineId1', 'vaccineId2', 'vaccineId3',
            'vaccineDate1', 'vaccineDate2', 'vaccineDate3', 'regency', 'district', 'village'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // begin::validation
        $validation = Validator::make(
            $request->all(),
            $this->rules,
            $this->messageRules
        );
        if ($validation->fails()) {
            $error = $validation->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }
        // end::validation

        // begin::variable
        $photo = $request->photo;
        $employeeId = $request->employee_id;
        $name = $request->name;
        $aliases = $request->aliases;
        $nik = $request->nik;
        $email = $request->email;
        $phone = $request->phone;
        $birthDate = $request->birth_date;
        $gender = $request->gender;
        $provinceId = $request->province;
        $regencyId = $request->regency;
        $districtId = $request->district;
        $villageId = $request->village;
        $address = $request->address;
        $divisionId = $request->division_id;
        $positionId = $request->position_id;
        $bankName = $request->bank_name;
        $bankAccount = $request->bank_account;
        $bankAccountName = $request->bank_account_name;
        $wali = $request->wali;
        $waliNumber = $request->wali_number;
        $waliAddress = $request->wali_address;
        $primarySchool = $request->primary_school;
        $primarySchoolGraduate = $request->primary_school_graduate;
        $primarySchoolGpa = $request->primary_school_gpa;
        $juniorHighSchool = $request->junior_high_school;
        $juniorHighSchoolGraduate = $request->junior_high_school_graduate;
        $juniorHighSchoolGpa = $request->junior_high_school_gpa;
        $highSchool = $request->high_school;
        $highSchoolGraduate = $request->high_school_graduate;
        $highSchoolGpa = $request->high_school_gpa;
        $university = $request->university;
        $universityGraduate = $request->university_graduate;
        $universityGpa = $request->university_gpa;
        $workExperienceName1 = $request->work_experience_name_1;
        $workExperiencePosition1 = $request->work_experience_postition_1;
        $workExperienceIn1 = $request->work_experience_in_1;
        $workExperienceOut1 = $request->work_experience_out_1;
        $workExperienceName2 = $request->work_experience_name_2;
        $workExperiencePosition2 = $request->work_experience_postition_2;
        $workExperienceIn2 = $request->work_experience_in_2;
        $workExperienceOut2 = $request->work_experience_out_2;
        $workExperienceName3 = $request->work_experience_name_3;
        $workExperiencePosition3 = $request->work_experience_postition_3;
        $workExperienceIn3 = $request->work_experience_in_3;
        $workExperienceOut3 = $request->work_experience_out_3;
        $vaccineType1 = $request->vaccine_type_1;
        $vaccineDate1 = $request->vaccine_date_1;
        $vaccineType2 = $request->vaccine_type_2;
        $vaccineDate2 = $request->vaccine_date_2;
        $vaccineType3 = $request->vaccine_type_3;
        $vaccineDate3 = $request->vaccine_date_3;
        // end::variable

        DB::beginTransaction();
        try {

            $dataEmployee = [
                'name' => $name,
                'aliases' => $aliases,
                'email' => $email,
                'nik' => $nik,
                'gender' => $gender,
                'birth_date' => $birthDate,
                'address' => $address,
                'province_id' => $provinceId,
                'regency_id' => $regencyId,
                'district_id' => $districtId,
                'village_id' => $villageId,
                'phone' => $phone,
                'primary_school' => $primarySchool,
                'primary_school_graduate' => $primarySchoolGraduate,
                'primary_school_gpa' => $primarySchoolGpa,
                'junior_high_school' => $juniorHighSchool,
                'junior_high_school_graduate' => $juniorHighSchoolGraduate,
                'junior_high_school_gpa' => $juniorHighSchoolGpa,
                'high_school' => $highSchool,
                'high_school_graduate' => $highSchoolGraduate,
                'high_school_gpa' => $highSchoolGpa,
                'university' => $university,
                'university_graduate' => $universityGraduate,
                'university_gpa' => $universityGpa,
                'work_experience_name_1' => $workExperienceName1,
                'work_experience_position_1' => $workExperiencePosition1,
                'work_experience_in_1' => $workExperienceIn1,
                'work_experience_out_1' => $workExperienceOut1,
                'work_experience_name_2' => $workExperienceName2,
                'work_experience_position_2' => $workExperiencePosition2,
                'work_experience_in_2' => $workExperienceIn2,
                'work_experience_out_2' => $workExperienceOut2,
                'work_experience_name_3' => $workExperienceName3,
                'work_experience_position_3' => $workExperiencePosition3,
                'work_experience_in_3' => $workExperienceIn3,
                'work_experience_out_3' => $workExperienceOut3,
                'wali_name' => $wali,
                'wali_phone' => $waliNumber,
                'wali_address' => $waliAddress,
                'bank_name' => $bankName,
                'bank_account_number' => $bankAccount,
                'bank_account_name' => $bankAccountName,
                'division_id' => $divisionId,
                'position_id' => $positionId,
                'employee_id' => $employeeId
            ];
            if ($vaccineType1 != null && $vaccineType2 != null && $vaccineType3 != null) {
                $dataEmployee['current_vaccine_level'] = 3;
            } else if ($vaccineType1 != null && $vaccineType2 != null && $vaccineType3 == null) {
                $dataEmployee['current_vaccine_level'] = 2;
            } else if ($vaccineType1 != null && $vaccineType2 == null) {
                $dataEmployee['current_vaccine_level'] = 1;
            }
            $update = Employee::where('id', $id)->update($dataEmployee);

            $dataVaccine = [];
            if ($vaccineType1 != null) {
                $arr1 = [
                    'user_id' => $id,
                    'vaccine_id' => $vaccineType1,
                    'vaccine_grade' => 1,
                    'vaccine_date' => $vaccineDate1
                ];
                array_push($dataVaccine, $arr1);
            }
            if ($vaccineType2 != null) {
                $arr2 = [
                    'user_id' => $id,
                    'vaccine_id' => $vaccineType2,
                    'vaccine_grade' => 2,
                    'vaccine_date' => $vaccineDate2
                ];
                array_push($dataVaccine, $arr2);
            }
            if ($vaccineType3 != null) {
                $arr3 = [
                    'user_id' => $id,
                    'vaccine_id' => $vaccineType3,
                    'vaccine_grade' => 3,
                    'vaccine_date' => $vaccineDate3
                ];
                array_push($dataVaccine, $arr3);
            }
            EmployeeVaccine::where('user_id', $id)->delete();
            EmployeeVaccine::insert($dataVaccine);
            
            if ($update) {
                $secondName = $photo->getClientOriginalName();
                if ($secondName != 'blob') {
                    $ext = $photo->getClientOriginalExtension();
                    $fileName = $id . '-' . date('Ymd'). '-' . $positionId . '.' . $ext;
                    $photo->storeAs('employee/photo', $fileName, 'public');
                    Employee::where('id', $id)->update([
                        'photo' => asset('employee/photo/' . $fileName)
                    ]);
                }
            }
            
            DB::commit();
            return sendResponse([]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Employee::where('id', $id)
                ->update([
                    'deleted_at' => Carbon::now(),
                    'is_active' => 0
                ]);
            return sendResponse([]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }
}
