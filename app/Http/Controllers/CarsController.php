<?php

namespace App\Http\Controllers;

use ApiError;
use App\Models\Cars;
use App\Http\Requests\StoreCarsRequest;
use App\Http\Requests\UpdateCarsRequest;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Cars::all();

        return response()->json($cars);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarsRequest $request)
    {
        $plate = $request->input('plate');

        $entity = new Cars;
        $entity->plate = $plate;
        $entity->save();

        return response()->json($entity);

    }

    /**
     * Display the specified resource.
     */
    public function show(Cars $cars)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cars $cars)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarsRequest $request, Cars $cars)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cars $cars)
    {
        //
    }
}
