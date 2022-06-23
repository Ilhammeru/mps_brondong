<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
                return '<span class="text-info" style="cursor: pointer;" onclick="edit('. $data->id .')"><i class="fas fa-edit"></i></span>
                <span class="text-info" style="cursor: pointer;" onclick="deleteDivision('. $data->id .')"><i class="fas fa-trash"></i></span>';
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
        $name = strtolower($request->name);
        $payload = [
            'id' => $id
        ];

        try {
            $division = Division::updateOrCreate(
                $payload, 
                ['updated_at' => Carbon::now(), 'name' => $name]
            );
            return sendResponse($division, 'SUCCESS', 201);
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
            $division = Division::with('users')->find($id);
    
            if (count($division->users) > 0) {
                return sendResponse(['error' => "Divisi $division->name masih mempunyai relasi pada karyawan"],
                'FAILED', 500);
            }

            $delete = Division::where('id', $id)->delete();
            return sendResponse($delete, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
        }
    }
}
