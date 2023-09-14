<?php

namespace App\Http\Controllers;

use App\Declarations\ApiError;
use App\Models\Cars;
use App\Http\Requests\StoreCarsRequest;
use App\Http\Requests\UpdateCarsRequest;
use Illuminate\Database\UniqueConstraintViolationException;

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
     * Store a newly created resource in storage.
     */
    public function store(StoreCarsRequest $request)
    {
        $data = $request->only('plate', 'categoryId');
        $user = $request->user();

        try {
            $entity = new Cars;
            $entity->plate = $data['plate'];
            $entity->categoryId = $data['categoryId'];
            $entity->userId = $user->id;
            $entity->save();
        } catch (UniqueConstraintViolationException $e) {
            return response(ApiError::invalidValueDuplicated('Plate'), 400);
        }

        return response()->json($entity);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entity = Cars::find($id);

        if(!$entity) {
            return response(ApiError::entityNotFound('Car', $id), 400);
        }

        return response()->json($entity);
    }

    /**
     * Search car by plate number
     */
    public function search($plate) {
        $result = Cars::where('plate', 'like', "$plate%")->get();

        return response()->json($result);
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
    public function destroy($id)
    {
        $entity = Cars::find($id);

        if(empty($entity)) {
            return response(ApiError::entityNotFound('Car', $id), 400);
        }

        $entity->delete();

        return response(null, 204);
    }
}
