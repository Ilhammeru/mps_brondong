<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Division;
use App\Models\Employee;
use App\Models\EmployeeStatus;
use App\Models\EmployeeVaccine;
use App\Models\Position;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Vaccine;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

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

    public function json() {
        $data = Employee::with(['userVaccine.vaccine', 'division', 'position'])
            ->where('is_active', TRUE)
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
                return '<a href="'. route('employees.edit', $data->id) .'" class="text-info me-3"><i class="fa fa-edit"></i></a>';
            })
            ->rawColumns(['action', 'status_vaccine', 'name', 'position', 'working_status'])
            ->make(true);
    }

    /**
     * Display All Employee
     * 
     * @return \Illuminate\Http\Response
     */
    public function getData() {
        $id = Auth::id();
        $data = Employee::where(['is_active' => 1])->where('id', '!=', $id)->get();
        return sendResponse($data);
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
        return view('employees.profile', compact(
            'user', 'pageTitle', 'userVaccine',
            'dosis1', 'dosis2', 'dosis3', 'addressHelper',
            'villageHelper', 'provinceHelper'

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
        //
    }
}
