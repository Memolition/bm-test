<?php

namespace App\Http\Controllers;

use App\Declarations\ApiError;
use DateTime;
use App\Models\Registry;
use App\Http\Requests\StoreRegistryRequest;
use App\Http\Requests\UpdateRegistryRequest;
use Illuminate\Http\Request;

class RegistryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registry = Registry::with("car")->get();

        return response()->json($registry);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegistryRequest $request)
    {
        $data = $request->only('carId', 'inAt');
        $user = $request->user();

        $inAt = empty($data['inAt']) ? new DateTime() : $data['inAt'];

        $entity = new Registry;
        $entity->inAt = $inAt;
        $entity->carId = $data['carId'];
        $entity->userId = $user->id;
        $entity->save();

        return response()->json($entity);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entity = Registry::find($id);

        if(!$entity) {
            return response(ApiError::entityNotFound('Registry', $id), 400);
        }

        return response()->json($entity);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegistryRequest $request, Registry $registry)
    {
        //
    }

    /**
     * Update registry entry to set outAt date
     */
    public function checkout(Request $request)
    {
        $plate = $request->input('plate');
        $entry = Registry::with('car.category')->whereHas('car', function ($query) use ($plate) {
            return $query->where('plate', '=', $plate);
        })
        ->where("outAt", null)
        ->orderBy("id", "desc")
        ->first();

        if(!$entry) {
            return response(ApiError::entryNotFound($plate), 400);
        }

        $entry->outAt = new DateTime();
        $entry->save();

        return response()->json($entry);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $entity = Registry::find($id);

        if(empty($entity)) {
            return response(ApiError::entityNotFound('Registry', $id), 400);
        }

        $entity->delete();

        return response(null, 204);
    }
}
