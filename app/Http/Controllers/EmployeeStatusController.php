<?php

namespace App\Http\Controllers;

use App\Models\EmployeeStatus;
use Illuminate\Http\Request;
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
        //
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
    public function update(Request $request, EmployeeStatus $employeeStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeStatus  $employeeStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeStatus $employeeStatus)
    {
        //
    }
}
