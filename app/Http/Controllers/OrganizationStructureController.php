<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationStructureController extends Controller
{
    public function index()
    {
        $pageTitle = "Struktur Organisasi";
        return view('organization-structure.index', compact(
            'pageTitle'
        ));
    }
}
