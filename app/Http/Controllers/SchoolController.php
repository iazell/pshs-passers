<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Passer;
use DB;
use App\Http\Resources\Passer as PasserResource;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school = Passer::select(DB::raw('school, count(*) as total'))
                ->groupBy('camp_eligibility')
                ->orderBy('total', 'desc')
                ->get();
        return PasserResource::collection($school);
        
    }
}
