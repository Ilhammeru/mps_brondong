<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Jabatan';
        return view('position.index', compact('pageTitle'));
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
     * Get Position By Division ID
     * 
     * @return \Illuminate\Http\Response
     */
    public function getData($id) {
        try {
            $data = Position::where('division_id', $id)->get();
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
     * Get data for DataTable
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {
        $data = Position::with('division')
            ->orderBy('division_id', 'desc')->get();
        return DataTables::of($data)
            ->editColumn('name',function($data) {
                return ucwords($data->name);
            })
            ->editColumn('division', function($data) {
                $division = $data->division->name;
                return ucwords($division) ?? "-";
            })
            ->addColumn('action', function($data) {
                $param = $data->id . ", 'position'";
                return '<span class="text-info" style="cursor: pointer;" onclick="edit('. $param .')"><i class="fas fa-edit"></i></span>
                <span class="text-info" style="cursor: pointer;" onclick="deleteItem('. $param .')"><i class="fas fa-trash"></i></span>';
            })
            ->rawColumns(['action', 'name', 'division'])
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
        $name = $request->name;
        $division = $request->division_id;
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
            'division_id' => $division
        ];

        try {
            $position = Position::updateOrCreate(
                $payload, ['created_at' => Carbon::now()]
            );
            return sendResponse($position, 'SUCCESS', 201);
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
            $position = Position::with('division')->find($id);
            $division = Division::all();
            return sendResponse(['position' => $position, 'division' => $division], 'SUCCESS', 201);
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
        $position = Position::find($id);
        $name = $request->name;
        $division = $request->division_id;
        $rules = [
            'name' => 'required'
        ];
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
            $position->name = $name;
            $position->division_id = $division;
            $position->updated_at = Carbon::now();
            $position->save();
            return sendResponse([]);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
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
            $position = Position::with('users')->find($id);
    
            if (count($position->users) > 0) {
                return sendResponse(['error' => "Divisi $position->name masih mempunyai relasi pada karyawan"],
                'FAILED', 500);
            }

            $delete = Position::where('id', $id)->delete();
            return sendResponse($delete, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
        }
    }
}
