<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Department';
        return view('division.index', compact('pageTitle'));
    }
    
    /**
     * Get data for DataTable
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {
        $data = Division::all();
        return DataTables::of($data)
            ->editColumn('name',function($data) {
                return ucwords($data->name);
            })
            ->editColumn('department_id', function($data) {
                return ucwords($data->department->name);
            })
            ->addColumn('action', function($data) {
                $param = $data->id . ", 'division'";
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
        $name = $request->name;
        $departmentId = $request->department_id;
        $rules = [
            'name' => 'required'
        ];
        $validation = Validator::make(
            $request->all(),
            $rules,
            [
                'name.required' => 'Nama Harus Diisi'
            ]
        );
        if ($validation->fails()) {
            $error = $validation->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }
        $payload = [
            'name' => $name,
            'department_id' => $departmentId,
            'created_at' => Carbon::now()
        ];

        try {
            $division = Division::insert($payload);
            return sendResponse($division, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
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
        try {
            $division = Division::find($id);
            return sendResponse($division, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
        }
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
        $division = Division::find($id);
        $name = $request->name;
        $rules = [
            'name' => 'required'
        ];
        if (strtolower($division->name) != $name) {
            $rules['name'] = 'required|unique:divisions,name';
        }
        $validation = Validator::make(
            $request->all(),
            $rules,
            [
                'name.required' => 'Nama Harus Diisi',
                'name.unique' => 'Nama Sudah Terdaftar di Database'
            ]
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
            $division->name = $name;
            $division->department_id = $request->department_id;
            $division->updated_at = Carbon::now();
            $division->save();
            return sendResponse([]);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
        }
    }
    
    /**
     * Get all divsion data
     *
     * @return \Illuminate\Http\Response
     */
    public function getData() {
        $division = Division::all();
        return sendResponse($division, 'SUCCESS', 201);        
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
            $division = Division::with(['position'])->find($id);
            if ($division->position) {
                return sendResponse(['error' => "Masih Ada Jabatan yang Mempunya Divisi Ini"],
                'FAILED', 500);
            }

            $delete = $division->delete();
            return sendResponse($delete, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
        }
    }
}
