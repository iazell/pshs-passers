<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Passer;
use App\Http\Resources\Passer as PasserResource;

class PasserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $passers = Passer::orderBy('name', 'asc')->paginate(50);
        return PasserResource::collection($passers);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $passer = $request->isMethod('put') ? Passer::findOrFail($request->passer_id) : new Passer;

        $passer->id = $request->input('passer_id');
        $passer->name = $request->input('name');
        $passer->camp_eligibility = $request->input('camp_eligibility');
        $passer->school = $request->input('school');
        $passer->division = $request->input('division');

        if($passer->save()) {
            return new PasserResource($passer);
        }
        
    }
}
