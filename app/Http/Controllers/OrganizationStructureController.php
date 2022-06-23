<?php

namespace App\Http\Controllers;

use App\Models\Department;
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
}
