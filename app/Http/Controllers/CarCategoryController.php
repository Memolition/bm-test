<?php

namespace App\Http\Controllers;

use App\Declarations\ApiError;
use App\Models\CarCategory;
use App\Http\Requests\StoreCarCategoryRequest;
use App\Http\Requests\UpdateCarCategoryRequest;
use Illuminate\Database\UniqueConstraintViolationException;

class CarCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CarCategory::all();

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarCategoryRequest $request)
    {
        $data = $request->only('name', 'chargePerMinute', 'chargedAt');
        $user = $request->user();

        try {
            $category = new CarCategory;
            $category->name = $data['name'];
            $category->chargePerMinute = $data['chargePerMinute'];
            $category->chargedAt = $data['chargedAt'];
            $category->userId = $user->id;
            $category->save();
        } catch (UniqueConstraintViolationException $e) {
            return response(ApiError::invalidValueDuplicated('Name'), 400);
        }

        return response()->json($category);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entity = CarCategory::find($id);

        return response()->json($entity);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarCategoryRequest $request, CarCategory $carCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $entity = CarCategory::find($id);

        if(empty($entity)) {
            return response(ApiError::entityNotFound('Car Category', $id), 400);
        }

        $entity->delete();

        return response(null, 204);
    }
}
