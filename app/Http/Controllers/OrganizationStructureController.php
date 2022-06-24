<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\EmployeeStatus;
use App\Models\Position;
use Illuminate\Http\Request;

class OrganizationStructureController extends Controller
{
    private $viewPath;

    public function __construct()
    {
        $this->viewPath = 'organization-structure';
    }

    public function index()
    {
        $pageTitle = "Struktur Organisasi";
        return view($this->viewPath . '.index', compact(
            'pageTitle'
        ));
    }

    public function addDepartment() {
        $view = view("$this->viewPath._department-form")->render();

        return sendResponse(['view' => $view]);
    }

    public function addDivision() {
        $department = Department::all();
        $view = view("$this->viewPath._division-form", compact('department'))->render();

        return sendResponse(['view' => $view]);
    }

    public function addForm($type) {
        if ($type == 'department') {
            $form = view("$this->viewPath._department-form")->render();
        } else if ($type == 'division') {
            $department = Department::all();
            $form = view("$this->viewPath._division-form", compact('department'))->render();
        } else if ($type == 'position') {
            $division = Division::all();
            $form = view("$this->viewPath._position-form", compact('division'))->render();
        } else {
            $form = view("$this->viewPath._employee-status-form")->render();
        }

        return sendResponse(['view' => $form]);
    }

    public function edit($id, $type) {
        $division = [];
        $department = [];
        if ($type == 'department') {
            $data = Department::find($id);
            $form = view("$this->viewPath._department-form", compact('data'))->render();
        } else if ($type == 'division') {
            $department = Department::all();
            $data = Division::with('department')->find($id);
            $form = view("$this->viewPath._division-form", compact('data', 'department'))->render();
        } else if ($type == 'position') {
            $division = Division::all();
            $data = Position::with('division')->find($id);
            $form = view("$this->viewPath._position-form", compact('data', 'division'))->render();
        } else {
            $data = EmployeeStatus::find($id);
            $form = view("$this->viewPath._employee-status-form", compact('data'))->render();
        }

        return sendResponse(['view' => $form]);
    }
}
