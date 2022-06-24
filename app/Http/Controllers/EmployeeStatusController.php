<?php

namespace App\Http\Controllers;

use App\Models\EmployeeStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EmployeeStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Get data for DataTable
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {
        $data = EmployeeStatus::all();
        return DataTables::of($data)
            ->editColumn('name',function($data) {
                return ucwords($data->name);
            })
            ->addColumn('action', function($data) {
                $param = $data->id . ", 'employeeStatus'";
                return '<span class="text-info" style="cursor: pointer;" onclick="edit('. $param .')"><i class="fas fa-edit"></i></span>
                <span class="text-info" style="cursor: pointer;" onclick="deleteItem('. $param .')"><i class="fas fa-trash"></i></span>';
            })
            ->rawColumns(['action', 'name'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            ['name' => 'required'],
            ['name.required' => 'Nama Harus Diisi']
        );
        if ($validation->fails()) {
            $error = $validation->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }
        $name = $request->name;
        $payload = [
            'name' => $name
        ];
        try {
            EmployeeStatus::updateOrCreate(
                $payload, ['created_at' => Carbon::now()]
            );
            return sendResponse([]);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeStatus  $employeeStatus
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeStatus $employeeStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeStatus  $employeeStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeStatus $employeeStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeStatus  $employeeStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employeeStatus = EmployeeStatus::find($id);
        $rules = ['name' => 'required'];
        if (strtolower($employeeStatus->name) != strtolower($request->name)) {
            $rules['name'] = 'required|unique:employee_status,name';
        }
        $validation = Validator::make(
            $request->all(),
            $rules,
            ['name.required' => 'Nama Harus Diisi', 'name.unique' => 'Nama Sudah Terdaftar di Database']
        );
        if ($validation->fails()) {
            $error = $validation->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }
        try {
            $employeeStatus->name = $request->name;
            $employeeStatus->updated_at = Carbon::now();
            $employeeStatus->save();

            return sendResponse([]);
        } catch (\Throwable $th) {
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
     * @param  \App\Models\EmployeeStatus  $employeeStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // check
            $employeeStatus = EmployeeStatus::find($id);
            $employeeStatus->delete();
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
