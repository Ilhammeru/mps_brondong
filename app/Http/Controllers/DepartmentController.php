<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
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

    public function json() {
        $data = Department::all();

        return DataTables::of($data)
            ->editColumn('name',function($data) {
                return ucwords($data->name);
            })
            ->addColumn('action', function($data) {
                $param = $data->id . ", 'department'";
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
            Department::updateOrCreate(
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
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        $rules = ['name' => 'required'];
        if (strtolower($department->name) != strtolower($request->name)) {
            $rules['name'] = 'required|unique:department,name';
        }
        $validation = Validator::make(
            $request->all(),
            $rules,
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
        try {
            $department->name = $request->name;
            $department->updated_at = Carbon::now();
            $department->save();

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
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // check
            $department = Department::with('divisions')->find($id);
            $check = $department->divisions;
            if (count($check) > 0) {
                return sendResponse(
                    ['error' => 'Masih Ada Divisi yang Mempunyai Departmen Ini'],
                    'FAILED',
                    500
                );
            }
            $department->delete();
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
