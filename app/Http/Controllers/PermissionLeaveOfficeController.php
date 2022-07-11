<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Employee;
use App\Models\PermissionLeaveOffice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PermissionLeaveOfficeController extends Controller
{
    private $rules;
    private $messageRules;

    public function __construct()
    {
        $this->rules = [
            'letter.*' => 'required',
        ];

        $this->messageRules = [
            'letter.*.required' => 'Semua Field harus terisi'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Izin Keluar Kantor';
        $division = Division::all();
        $employee = Employee::select(['name', 'id', 'position_id'])
            ->with('position:id,name')
            ->where('id', '<>', Auth::id())
            ->get();
        return view('permission.leave-office.index', compact('pageTitle', 'division', 'employee'));
    }

    /**
     * Display data for DataTables
     * 
     * @return DataTables
     */
    public function json() {
        $role = Auth::user()->role;
        if ($role != 'satpam') {
            $data = PermissionLeaveOffice::with('employee')->orderBy('id', 'desc')->get();
        } else if ($role == 'satpam') {
            $data = PermissionLeaveOffice::where('checked_by', null)->where('employee_id', '!=', Auth::user()->employee_id)->orderBy('id', 'desc')->get();
        }
        return DataTables::of($data)
            ->editColumn('id', function($data) {
                return '<span style="color: transparent;">{{ $data->id }}</span>';
            })
            ->addColumn('employee', function($data) {
                $employees = json_decode($data->employee_id, TRUE);
                $employee = [];
                for ($a = 0; $a < count($employees); $a++) {
                    $empData = Employee::select('name', 'position_id')
                        ->with('position')
                        ->where('id', $employees[$a])
                        ->first();
                    $employee[] = $empData->name;
                }
                return '<span>'. ucwords(implode(', ', $employee)) .'</span>';
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
                $user = User::select('name')->where('id', $data->approved_by)->first();
                $name = $user->name;
                $split = explode(' ', $name);
                return ucwords($split[0]);
            })
            ->editColumn('checked_by', function($data) {
                $user = User::select('name')->where('id', $data->checked_by)->first();
                if ($user) {
                    $name = $user->name;
                    return ucwords($name);
                } else {
                    return '-';
                }
            })
            ->addColumn('action', function($data) use($role) {
                if ($role != 'satpam') {
                    if ($data->checked_by) {
                        return '<span class="text-info" onclick="edit('. $data->id .')" style="cursor:pointer;"><i class="fas fa-edit"></i></span>
                            <span class="text-info" onclick="detail('. $data->id .')" style="cursor:pointer;"><i class="fas fa-print"></i></span>';
                    } else {
                        return '<span class="text-info" onclick="edit('. $data->id .')" style="cursor:pointer;"><i class="fas fa-edit"></i></span>
                            <span class="text-info" onclick="deleteLeave('. $data->id .')" style="cursor:pointer;"><i class="fas fa-trash"></i></span>
                            <span class="text-info" onclick="detail('. $data->id .')" onclick="deleteLeave('. $data->id .')" style="cursor:pointer;"><i class="fas fa-print"></i></span>';
                    }
                } else {
                    return '<span class="text-info me-2" id="btnCheck'. $data->id .'" onclick="confirm('. $data->id .')" style="cursor:pointer;"><i class="fas fa-check text-success"></i></span>';
                }
            })
            ->rawColumns(['employee', 'division', 'date_time', 'approved_by', 'checked_by', 'action', 'id', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Tambah Data';
        $employee = Employee::select('name', 'id', 'position_id')
            ->with(['position:id,name'])
            ->where("id", "<>", Auth::id())
            ->where('is_active', 1)
            ->get();
        return view('permission.leave-office.create', compact('pageTitle', 'employee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array_values($request->letter);
        $approvedBy = Auth::id();

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

        try {
            $dataLeave = [];
            $permissionData = PermissionLeaveOffice::select('id')->count();
            for ($a = 0; $a < count($data); $a++) {
                $permissionData = $permissionData + 1;
                $code = str_pad($permissionData, 3, '0', STR_PAD_LEFT);
                $ticketCode = 'LOP-' . $code . '-' . date('Ymd');
                $hour = $data[$a]['hour'];
                $minute = $data[$a]['minute'];
                $time = $hour . ':' . $minute;
                $dataLeave[] = [
                    'ticket_code' => $ticketCode,
                    'employee_id' => json_encode($data[$a]['employee']),
                    'leave_date_time' => date('Y-m-d H:i:s', strtotime($data[$a]['date'] . ' ' . $time)),
                    'notes' => $data[$a]['notes'],
                    'approved_by' => $approvedBy,
                    'created_at' => Carbon::now()
                ];
            }
            PermissionLeaveOffice::insert($dataLeave);
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
     * @param  \App\Models\PermissionLeaveOffice  $permissionLeaveOffice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::select('name', 'id', 'position_id')
            ->with(['position:id,name'])
            ->where("id", "!=", Auth::id())
            ->where('is_active', 1)
            ->get();
        $permissionLeaveOffice = PermissionLeaveOffice::find($id);
        $employees = json_decode($permissionLeaveOffice->employee_id, TRUE);
        $leaveDate = $permissionLeaveOffice->leave_date_time;
        $split = explode(' ', $leaveDate);
        $time = date("H:i:s", strtotime($split[1]));
        $splitTime = explode(':', $time);
        $hour = $splitTime[0];
        $minute = $splitTime[1];
        $date = $split[0];
        return sendResponse([
            'data' => $permissionLeaveOffice,
            'employee' => $employee,
            'currentEmployee' => $employees,
            'hour' => $hour,
            'minute' => $minute,
            'date' => $date
        ]);
    }

    public function detail($id) {
        $data = PermissionLeaveOffice::with(['approvedBy'])
            ->find($id);
        $employees = json_decode($data->employee_id, TRUE);
        $employee = [];
        for ($a = 0; $a < count($employees); $a++) {
            $dataEmp = Employee::select('id', 'name', 'position_id')
                ->with(['position:id,name'])
                ->where('id', $employees[$a])
                ->first();
            $employee[] = [
                'name' => $dataEmp->name,
                'position' => $dataEmp->position->name
            ];
        }
        $view = view('permission.leave-office._detail', compact('data', 'employee'))->render();

        return sendResponse(['view' => $view]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PermissionLeaveOffice  $permissionLeaveOffice
     * @return \Illuminate\Http\Response
     */
    public function edit(PermissionLeaveOffice $permissionLeaveOffice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PermissionLeaveOffice  $permissionLeaveOffice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = $request->employee;
        $date = $request->date;
        $hour = $request->hour;
        $minute = $request->minute;
        $time = $hour . ':' . $minute;
        $notes = $request->notes;
        $approvedBy = Auth::id();

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

        try {
            $permissionLeaveOffice = PermissionLeaveOffice::find($id);
            $permissionLeaveOffice->employee_id = json_encode($employee);
            $permissionLeaveOffice->leave_date_time = date('Y-m-d H:i:s', strtotime($date . ' ' . $time));
            $permissionLeaveOffice->approved_by = $approvedBy;
            $permissionLeaveOffice->notes = $notes;
            $permissionLeaveOffice->updated_at = Carbon::now();
            $permissionLeaveOffice->save();

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
     * @param  \App\Models\PermissionLeaveOffice  $permissionLeaveOffice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = PermissionLeaveOffice::where('id', $id)->delete();
            return sendResponse([]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    public function showConfirm() {
        $pageTitle = 'Data Izin Keluar Kantor';
        $data = PermissionLeaveOffice::where('checked_by', null)
            ->where('employee_id', '!=', Auth::id())
            ->get();

        return view('permission.leave-office.confirm', compact('data', 'pageTitle'));
    }

    public function confirm(Request $request, $id) {
        try {
            $data = PermissionLeaveOffice::with(['division', 'position'])->find($id);
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
                'service' => 'confirm-leave-office',
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

    public function confirmByBarcode($id) {
        $data = PermissionLeaveOffice::find($id);
        if ($data->checked_by) {
            $checkedBy = User::find($data->checked_by);
            return view('permission.leave-office.is-already-confirmed', compact('data', 'checkedBy'));
        } else {
            return view('permission.leave-office.confirm-barcode', compact('id'));
        }
    }

    public function confirmBarcode(Request $request, $id) {
        try {
            $username = $request->username;
            $password = $request->password;
            $check = User::where('username', $username)->first();
            if ($check) {
                if (Hash::check($password, $check->password)) {
                    $data = PermissionLeaveOffice::with(['approvedBy'])
                        ->where('id', $id)
                        ->first();
                    $employees = json_decode($data->employee_id, TRUE);
                    $names = [];
                    $employeeName = [];
                    for ($a = 0; $a < count($employees); $a++) {
                        if ($check->employee_id == $employees[$a]) {
                            if ($check->role != 'admin') {
                                return sendResponse(
                                    ['error' => 'Anda Tidak Bisa Mengizinkan Diri Anda Sendiri'],
                                    'FAILED',
                                    500
                                );
                            }
                        }

                        $employeeNameData = Employee::select('name', 'position_id')
                            ->with('position:id,name')
                            ->where('id', $employees[$a])
                            ->first();
                        $names[] = [
                            'name' => $employeeNameData->name,
                            'position' => $employeeNameData->position->name
                        ];
                        $employeeName[] = $employeeNameData->name;
                    }
                    $data->checked_by = $check->id;
                    $data->updated_at = Carbon::now();
                    $data->save();

                    $content = [
                        'checkedBy' => $check->name,
                        'employeeName' => implode(',', $employeeName),
                        'names' => $names,
                        'data' => $data
                    ];
                    $data = [
                        'subject' => 'confirm-leave-office',
                        'receiver' => 'ranydesykurniasari@gmail.com',
                        'receiver_name' => 'Rany Desy Kurniasari',
                        'service' => 'confirm-leave-office',
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
            
            return sendResponse([]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    public function detailLeaveOffice($id)
    {
        try {
            $data = PermissionLeaveOffice::with([
                    'employee', 'approvedBy', 'checkedBy'
                ])
                ->where('employee_id', $id)
                ->get();
                
            $view = view('permission.leave-office._detail-leave', compact('data'))->render();
    
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
