<?php

namespace App\Http\Controllers;

use App\Declarations\ApiError;
use DateTime;
use App\Models\Registry;
use App\Http\Requests\StoreRegistryRequest;
use App\Http\Requests\UpdateRegistryRequest;
use App\Models\CheckoutDTO;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RegistryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registry = Registry::with("car.category")->get();

        return response()->json($registry->map(function($entry) {
            return $this->getCheckout($entry);
        }));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegistryRequest $request)
    {
        $data = $request->only('carId', 'inAt');
        $user = $request->user();

        $inAt = empty($data['inAt']) ? new DateTime() : $data['inAt'];

        try {
            $entity = new Registry;
            $entity->inAt = $inAt;
            $entity->carId = $data['carId'];
            $entity->userId = $user->id;
            $entity->save();
        } catch(QueryException $e) {
            return response(ApiError::entityNotFound('Car', $data['carId']), 400);
        }

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

        $checkout = $this->getCheckout($entry);

        return response()->json($checkout);
    }

    private function getCheckout($entry) {
        $checkout = new CheckoutDTO;
        $checkout->entry = $entry;

        if(!$entry->outAt) return $checkout;

        $inDate = new Carbon($entry->inAt);
        $outDate = new Carbon($entry->outAt);
        $checkout->timeIn = $outDate->diffInMinutes($inDate);

        // Calculate charge based on checkout minutes
        // if category is set to be charged at checkout
        $entryCategory = $entry->car->first()->category->first();
        $checkout->willCharge = $entryCategory->chargedAt == config('constants.charged_at.checkout');
        $checkout->totalCharges = number_format($checkout->timeIn * $entryCategory->chargePerMinute, 2, '.');

        return $checkout;
    }

    /**
     * Return all cars without outAt datetime
     */
    public function pending() {
        $result = Registry::with('car')->whereNull('outAt')->get();

        return response()->json($result);
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
