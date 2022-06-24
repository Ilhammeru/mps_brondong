<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MenstruationLeave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class MenstruationLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Izin Keluar Kantor';
        return view('permission.leave-menstruation.index', compact('pageTitle'));
    }

    /**
     * Display data for DataTables
     * 
     * @return DataTables
     */
    public function json() {
        $role = Auth::user()->role;
        if ($role != 'satpam') {
            $data = MenstruationLeave::with(['employee', 'approvedBy', 'checkedBy'])->orderBy('id', 'desc')->get();
        } else if ($role == 'satpam') {
            $data = MenstruationLeave::with(['employee', 'approvedBy', 'checkedBy'])
                ->where('checked_by', null)
                ->where('employee_id', '!=', Auth::user()->employee_id)
                ->orderBy('id', 'desc')
                ->get();
        }
        return DataTables::of($data)
            ->addColumn('employee', function($data) {
                return '<span>'. ucwords($data->employee->name) .'</span>';
            })
            ->addColumn('date_time', function($data) {
                $date = date('d F Y', strtotime($data->leave_date_time));
                $time = date('H:i:s', strtotime($data->leave_date_time));
                return $date . ' (<b>' . $time . '</b>) ';
            })
            ->addColumn('status', function($data) {
                if ($data->checked_by) {
                    return '<span class="badge badge-success">Sudah Diizinkan</span>';
                } else {
                    return '<span class="badge badge-secondary">Belum Diizinkan</span>';
                }
            })
            ->editColumn('approved_by', function($data) {
                $name = $data->approvedBy->name;
                $split = explode(' ', $name);
                return ucwords($split[0]);
            })
            ->editColumn('checked_by', function($data) {
                if ($data->checkedBy != NULL) {
                    $name = $data->employee->name;
                    return ucwords($name);
                } else {
                    return '-';
                }
            })
            ->addColumn('action', function($data) use($role) {
                if ($role != 'satpam') {
                    if ($data->checked_by) {
                        return '<span class="text-info" onclick="detail('. $data->id .')" style="cursor:pointer;"><i class="fas fa-print"></i></span>';
                    } else {
                        return '<span class="text-info" onclick="deleteLeave('. $data->id .')" style="cursor:pointer;"><i class="fas fa-trash"></i></span>
                            <span class="text-info" onclick="detail('. $data->id .')" onclick="deleteLeave('. $data->id .')" style="cursor:pointer;"><i class="fas fa-print"></i></span>';
                    }
                } else {
                    return '<span class="text-info me-2" id="btnCheck'. $data->id .'" onclick="confirm('. $data->id .')" style="cursor:pointer;"><i class="fas fa-check text-success"></i></span>';
                }
            })
            ->rawColumns([
                'employee', 'date_time',
                'approved_by', 'checked_by', 
                'action', 'status'
            ])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Tambah Data Cuti Haid";
        $employees = Employee::where('id', '!=', Auth::id())->get();
        return view('permission.leave-menstruation.create', compact('pageTitle', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $employeeId = $request->employee_id;
            if (count($employeeId) == 0) {
                return sendResponse(
                    ['error' => 'Pastikan Anda Sudah Memilih Karyawan'],
                    'FAILED',
                    500
                );
            }

            $data = [];
            for ($a = 0; $a < count($employeeId); $a++) {
                $data[] = [
                    'leave_code' => generateRandomString(4) . '-' . date('Ymd'),
                    'employee_id' => $employeeId[$a],
                    'leave_date_time' => date('Y-m-d H:i:s'),
                    'approved_by' => Auth::id(),
                    'created_at' => Carbon::now()
                ];
            }
            MenstruationLeave::insert($data);
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
     * Display the specified resource.
     *
     * @param  \App\Models\MenstruationLeave  $menstruationLeave
     * @return \Illuminate\Http\Response
     */
    public function show(MenstruationLeave $menstruationLeave)
    {
        //
    }

    public function showConfirm() {
        $pageTitle = 'Data Izin Keluar Kantor';
        $data = MenstruationLeave::with(['employee.division'])
            ->where('checked_by', null)
            ->where('employee_id', '!=', Auth::id())
            ->get();

        return view('permission.leave-menstruation.confirm', compact('data', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MenstruationLeave  $menstruationLeave
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = "Tambah Data Cuti Haid";
        $employees = Employee::where('id', '!=', Auth::id())->get();
        return view('permission.leave-menstruation.create', compact('pageTitle', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MenstruationLeave  $menstruationLeave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenstruationLeave $menstruationLeave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MenstruationLeave  $menstruationLeave
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = MenstruationLeave::where('id', $id)->delete();
            return sendResponse([]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    public function detail($id) {
        $data = MenstruationLeave::with(['approvedBy','checkedBy','employee.division','employee.position'])
            ->find($id);
        $view = view('permission.leave-menstruation._detail', compact('data'))->render();

        return sendResponse(['view' => $view]);
    }

    public function confirmByBarcode($id) {
        $data = MenstruationLeave::where('leave_code', $id)->first();
        if ($data->checked_by) {
            $checkedBy = User::find($data->checked_by);
            return view('permission.leave-menstruation.is-already-confirmed', compact('data', 'checkedBy'));
        } else {
            return view('permission.leave-menstruation.confirm-barcode', compact('id'));
        }
    }

    public function confirmBarcode(Request $request, $id) {
        DB::beginTransaction();
        try {
            $username = $request->username;
            $password = $request->password;
            $check = User::where('username', $username)->first();
            if ($check) {
                if (Hash::check($password, $check->password)) {
                    $data = MenstruationLeave::with(['employee.division', 'employee.position'])->where('leave_code', $id)->first();
                    if ($check->employee_id == $data->employee_id) {
                        if ($check->role != 'admin') {
                            return sendResponse(
                                ['error' => 'Anda Tidak Bisa Mengizinkan Diri Anda Sendiri'],
                                'FAILED',
                                500
                            );
                        }
                    }
                    $data->checked_by = $check->id;
                    $data->updated_at = Carbon::now();
                    $data->save();

                    $content = [
                        'checkedBy' => $check->name,
                        'employeeName' => Employee::select('name')->where('id', $data->employee_id)->first()->name,
                        'data' => $data
                    ];
                    $data = [
                        'subject' => 'confirm-leave-office',
                        'receiver' => 'ranydesykurniasari@gmail.com',
                        'receiver_name' => 'Rany Desy Kurniasari',
                        'service' => 'confirm-leave-menstruation',
                        'content' => $content,
                    ];
        
                    sendEmail($data);
                } else {
                    return sendResponse(
                        ['error' => 'Username atau Password tidak sesuai'],
                        'FAILED',
                        500
                    );
                }
            } else {
                return sendResponse(
                    ['error' => 'Username atau Password tidak sesuai'],
                    'FAILED',
                    500
                );
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

    public function confirm(Request $request, $id) {
        try {
            $data = MenstruationLeave::with(['employee.position', 'employee.division'])->find($id);
            $data->checked_by = Auth::id();
            $data->updated_at = Carbon::now();
            $data->save();

            $content = [
                'checkedBy' => Auth::user()->name,
                'employeeName' => Employee::select('name')->where('id', $data->employee_id)->first()->name,
                'data' => $data
            ];
            $data = [
                'subject' => 'confirm-leave-office',
                'receiver' => 'ranydesykurniasari@gmail.com',
                'receiver_name' => 'Rany Desy Kurniasari',
                'service' => 'confirm-leave-menstruation',
                'content' => $content,
            ];

            sendEmail($data);
            return sendResponse($data);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    public function detailLeaveMenstruation($id) {
        try {
            $data = MenstruationLeave::with([
                    'employee.position', 'employee.division', 'approvedBy', 'checkedBy'
                ])
                ->where('employee_id', $id)
                ->get();
                
            $view = view('permission.leave-menstruation._detail-leave', compact('data'))->render();
    
            return sendResponse(['view' => $view]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }
}
