<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Regency;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function getRegency($provinceId)
    {
        $region = Regency::select('id', 'name')->where('province_id', $provinceId)
            ->get();
        return sendResponse($region, 'SUCCESS', 201);
    }

    public function getDistrict($regencyId)
    {
        $district = District::select('id', 'name')
            ->where('regency_id', $regencyId)
            ->get();

        return sendResponse($district, 'SUCCESS', 201);
    }

    public function getVillage($districtId)
    {
        
    }
}
