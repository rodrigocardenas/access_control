<?php

namespace App\Http\Controllers;

use App\Building;
use App\Http\Resources\BuildingResource;
use App\Http\Requests\PostBuildingRequest;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buildings = Building::with('accesLogs')->filters($request);

        return BuildingResource::collection($buildings);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostBuildingRequest $request)
    {
        $building = new Building();
        $building->name = $request->name;
        $building->save();

        return new BuildingResource($building);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Building  $building
     * @return \Illuminate\Http\Response
     */
    public function show(Building $building)
    {
        return new BuildingResource($building);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Building  $building
     * @return \Illuminate\Http\Response
     */
    public function edit(Building $building)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Building  $building
     * @return \Illuminate\Http\Response
     */
    public function update(PostBuildingRequest $request, Building $building)
    {
        $building->name = $request->name;
        $building->update();

        return new BuildingResource($building);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Building  $building
     * @return \Illuminate\Http\Response
     */
    public function destroy(Building $building)
    {
        $building->delete();
        return response()->json(['message' => 'deleted'], 200);
    }
}
