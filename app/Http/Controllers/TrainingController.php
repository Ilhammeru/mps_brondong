<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Tag;
use App\Models\Training;
use App\Models\TrainingMaterial;
use App\Models\TrainingTag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TrainingController extends Controller
{
    private $rules;
    private $messageRules;

    public function __construct()
    {
        $this->rules = [
            'name' => 'required',
            'pic.*' => 'required',
            'training_date' => 'required',
        ];
        $this->messageRules = [
            'name.required' => 'Nama Harus Diisi',
            'pic.required' => 'Nama PIC Harus Diisi',
            'training_date.required' => 'Tanggal Training Harus Diisi',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Training';
        return view('training.index', compact('pageTitle'));
    }

    public function json() {
        $data = Training::all();
        return DataTables::of($data)
            ->editColumn('training_date', function($data) {
                return formatIndonesiaDate($data->training_date);
            })
            ->addColumn('training_time', function($data) {
                return date('H:i', strtotime($data->training_date));
            })
            ->editColumn('status', function($data) {
                $text = "";
                $status = $data->status;
                if ($status == 1) {
                    $text = '<span class="badge badge-success">Aktif</span>';
                } else if ($status == 2) {
                    $text = '<span class="badge badge-primary">Berjalan</span>';
                } else {
                    $text = '<span class="badge badge-secondary">Selesai</span>';
                }
                return $text;
            })
            ->editColumn('name', function($data) {
                return '<a href="'. route('trainings.show', $data->id) .'">'. $data->name .'</a>';
            })
            ->editColumn('pic', function($data) {
                $pic = json_decode($data->pic, TRUE);
                $name = [];
                for ($a = 0; $a < count($pic); $a++) {
                    $raw = Employee::select('name')
                        ->where('id', $pic[$a])
                        ->first();
                    $name[] = $raw->name;
                }

                return implode(', ', $name);
            })
            ->addColumn('action', function($data) {
                return '<a href="'. route('trainings.edit', $data->id) .'" class="text-info me-3"><i class="fa fa-edit"></i></a>
                <span class="text-info me-3" onclick="deleteTraining('. $data->id .')"><i class="fa fa-trash"></i></span>';
            })
            ->rawColumns(['status', 'name', 'pic', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Tambah Training';
        $employee = Employee::select('id', 'name')
            ->where('is_active', 1)->get();
        $tag = Tag::all();
        $description = "Pelatihan yang bertujuan untuk:&#10;&#10;- Meningkatkan kesadaran akan pentingnya berpendapat&#10;- Dapat menghargai pendapat setiap karyawan";
        return view('training.create', compact(
            'tag', 'employee', 'pageTitle', 'description'
        ));
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

        DB::beginTransaction();
        try {
            $name = $request->name;
            $pic = json_encode($request->pic);
            $date = $request->training_date;
            $time = $request->training_time;
            $splitTime = explode(' ', $time);
            $time = $splitTime[0] . ':00';
            $trainingDate = $date . ' ' . $time;
            $description = $request->description;
            $venue = $request->venue;
            $questionnaire = $request->questionnaire;
            $questionnaire = $questionnaire != 1 ? FALSE : TRUE;
            $participant = json_encode($request->participant);
            $tags = $request->tags;
            if ($tags != NULL) {
                $splitTag = explode(',', $tags);
                $tags = [];
                for ($t = 0; $t < count($splitTag); $t++) {
                    $tags[] = [
                        'tag_id' => $splitTag[$t]
                    ];
                }
            }
            
            $data = [
                'name' => $name,
                'pic' => $pic,
                'training_date' => $trainingDate,
                'description' => $description,
                'venue' => $venue,
                'participant' => $participant,
                'is_questionnaire' => $questionnaire,
                'created_at' => Carbon::now()
            ];
            if ($request->has('material1')) {
                $material1 = $this->uploadMaterial($request, 'material1');
                $data['material_1'] = $material1;
            }
            if ($request->has('material2')) {
                $material2 = $this->uploadMaterial($request, 'material2');
                $data['material_2'] = $material2;
            }
            if ($request->has('material3')) {
                $material3 = $this->uploadMaterial($request, 'material3');
                $data['material_3'] = $material3;
            }

            $trainingId = Training::insertGetId($data);

            if ($tags != null) {
                $tags = collect($tags)->map(function($item) use($trainingId) {
                    $item['training_id'] = $trainingId;
                    $item['created_at'] = Carbon::now();
                    return $item;
                })->all();

                TrainingTag::insert($tags);
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

    public function uploadMaterial(Request $request, $type, $id = "") {
        $name = $request->name;
        $name = implode('', explode(' ', $name));
        $file = $request->$type;
        $defaultName = $file->getClientOriginalName();
        if ($defaultName != 'blob') {
            $filename = "$type-$name-" . date('Ymd', strtotime($request->training_date));
            $ext = $file->getClientOriginalExtension();
            $name = $filename . '.' . $ext;
            $path = 'training/' . date('Y-m-d', strtotime($request->training_date));
            $file->storeAs($path, $name, 'public');
            return $path . '/' . $name;
        } else {
            if ($id != "") {
                switch ($type) {
                    case 'material1':
                        $newType = 'material_1';
                        break;

                    case 'material2':
                        $newType = 'material_2';
                        break;

                    case 'material3':
                        $newType = 'material_3';
                        break;
                    
                    default:
                        $newType = NULL;
                        break;
                }
                if ($newType != NULL) {
                    $data = Training::select($newType)->where('id', $id)->first();
                    return $data->$newType;
                }
            }
            return NULL;
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
        $training = Training::with('tags')->find($id);
        $pic = json_decode($training->pic, TRUE);
        $picList = [];
        for ($a = 0; $a < count($pic); $a++) {
            $list = Employee::select('id', 'name')
                ->where('id', $pic[$a])
                ->first();
            $picList[] = $list->name;
        }
        $pic = implode(',', $picList);
        $pageTitle = 'Detail Training';
        return view('training.show', compact('training', 'pageTitle', 'pic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $training = Training::with('tags')->find($id);
        $pic = json_decode($training->pic, TRUE);
        $pageTitle = 'Edit Training';
        $employee = Employee::select('id', 'name')
            ->where('is_active', 1)->get();
        $participant = json_decode($training->participant, TRUE);
        $employee = collect($employee)->map(function($item) use($pic, $participant) {
            for ($a = 0; $a < count($pic); $a++) {
                if ($item['id'] == $pic[$a]) {
                    $item['selected'] = true;
                }
            }

            $item['selectedPart'] = false;
            if ($participant != NULL) {
                for ($b = 0; $b < count($participant); $b++) {
                    if ($participant[$b] == $item['id']) {
                        $item['selectedPart'] = true;
                    }
                }
            }
            return $item;
        })->all();
        $tag = Tag::all();
        $description = "Pelatihan yang bertujuan untuk:&#10;&#10;- Meningkatkan kesadaran akan pentingnya berpendapat&#10;- Dapat menghargai pendapat setiap karyawan";
        return view('training.edit', compact(
            'tag', 'employee', 'pageTitle', 'description','training',
            'pic'
        ));
    }

    public function listTag($id) {
        $data = Tag::all();
        if ($id == 0) {
            $data = collect($data)->map(function($item){
                $item['class'] = 'btn-secondary';
                return $item;
            })->all();
            $trainingTag = [];
        } else {
            $trainingTag = TrainingTag::where('training_id', $id)->get();
            $data = collect($data)->map(function($item) use ($trainingTag) {
                $item['class'] = 'btn-secondary';
                $item['training_tag_id'] = "";
                $item['data_status'] = 0; 
                for ($a = 0; $a < count($trainingTag); $a++) {
                    if ($item->id == $trainingTag[$a]['tag_id']) {
                        $item['class'] = 'btn-light-info';
                        $item['training_tag_id'] = $trainingTag[$a]['id'];
                        $item['data_status'] = 1; 
                    }
                }
                return $item;
            })->all();
        }
        return sendResponse(['tag' => $data, 'trainingTag' => $trainingTag]);
    }

    public function storeTag(Request $request) {
        try {
            $name = $request->tag;
            Tag::insert(['name' => $name, 'slug' => implode('', explode(' ', strtolower($name))), 'created_at' => Carbon::now()]);

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
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

        DB::beginTransaction();
        try {
            $name = $request->name;
            $pic = json_encode($request->pic);
            $date = $request->training_date;
            $time = $request->training_time;
            $splitTime = explode(' ', $time);
            $time = $splitTime[0] . ':00';
            $trainingDate = $date . ' ' . $time;
            $description = $request->description;
            $venue = $request->venue;
            $participant = json_encode($request->participant);
            $tags = json_decode($request->tags, TRUE);
            
            $data = [
                'name' => $name,
                'pic' => $pic,
                'training_date' => $trainingDate,
                'description' => $description,
                'venue' => $venue,
                'participant' => $participant,
                'updated_at' => Carbon::now()
            ];
            if ($request->has('material1')) {
                $material1 = $this->uploadMaterial($request, 'material1', $id);
                $data['material_1'] = $material1;
            }
            if ($request->has('material2')) {
                $material2 = $this->uploadMaterial($request, 'material2', $id);
                $data['material_2'] = $material2;
            }
            if ($request->has('material3')) {
                $material3 = $this->uploadMaterial($request, 'material3', $id);
                $data['material_3'] = $material3;
            }

            $training = Training::where('id', $id)->update($data);

            if ($tags != null) {
                $newTag = [];
                foreach ($tags as $t) {
                    if ($t['id'] != 'NULL' && $t['is_delete']) {
                        TrainingTag::where('id', $t['id'])->delete();
                    } else if ($t['id'] != 'NULL' && !$t['is_delete']) {
                        TrainingTag::where('id', $t['id'])->update([
                            'tag_id' => $t['tag_id'],
                            'updated_at' => Carbon::now()
                        ]);
                    } else if($t['id'] == 'NULL' && !$t['is_delete']) {
                        $newTag[] = [
                            'tag_id' => $t['tag_id'],
                            'training_id' => $id,
                            'created_at' => Carbon::now()
                        ];
                    }
                }

                if (count($newTag) > 0) {
                    TrainingTag::insert($newTag);
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
     * Show form questionnaire
     */
    public function showFormQuestionnaire($trainingId)
    {
        $pageTitle = 'Kuisioner';
        return view('training.questionnaire', compact('pageTitle', 'trainingId'));
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
