<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Employee;
use App\Models\EmployeeVaccine;
use App\Models\Province;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Karyawan';
        return view('employee.index', compact('pageTitle'));
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
        return view('employee.create', compact('pageTitle', 'provinces', 'vaccines', 'divisions'));
    }

    public function json() {
        $data = Employee::with(['userVaccine.vaccine', 'division', 'position'])->where('is_active', TRUE)->get();
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return '<a href="'. route('employee.show', $data->id) .'">'. ucwords($data->name) .'</a>';
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
                return $data->current_vaccine_level == 3 ? '<span class="badge badge-success">Lengkap</span>' 
                : '<span class="badge badge-danger">Dosis '. $data->current_vaccine_level .' ('. $data->userVaccine->vaccine->name .')</span>'; 
            })
            ->addColumn('action', function($data) {
                return '<span class="text-info me-3"><i class="fa fa-edit"></i></span>';
            })
            ->rawColumns(['action', 'status_vaccine', 'name', 'position', 'working_status'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Employee::with(['division', 'position', 'userVaccine.vaccine', 'village.district.regency.province'])
            ->find($id);
        $userVaccine = EmployeeVaccine::where('user_id', $user->id)->get();
        $dosis1 = $userVaccine->where('vaccine_grade', 1)->first() ?? '';
        $dosis2 = $userVaccine->where('vaccine_grade', 2)->first() ?? '';
        $dosis3 = $userVaccine->where('vaccine_grade', 3)->first() ?? '';
        $pageTitle = 'Detail Karyawan';
        return view('employee.profile', compact(
            'user', 'pageTitle', 'userVaccine',
            'dosis1', 'dosis2', 'dosis3'

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
        //
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
        //
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
