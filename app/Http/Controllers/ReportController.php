<?php

namespace App\Http\Controllers;

use App\Declarations\ApiError;
use App\Models\Report;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Registry;
use App\Models\ReportDetails;
use App\Models\ReportEntryDTO;
use Carbon\Carbon;
use DateTime;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::all();

        return response()->json($reports);
    }

    private function calculateTotalParkingTime($report) {
        $latestCycles = Report::orderBy('created_at', 'desc')->where('created_at', '<=', $report->created_at)->take(2)->get();

        $previousDate = count($latestCycles) == 2 ? $latestCycles[1]['created_at'] : new DateTime('2023-01-01T00:00:00');
        
        $journalEntries = Registry::where(function ($q) use ($previousDate, $report) {
            $q->Where('outAt', '>', $previousDate)->where('inAt', '<=', $report->created_at);
        })->orWhere(function ($q) use ($report) {
            $q->whereNull('outAt')->where('inAt', '<=', $report->created_at);
        })->get();

        $details = new ReportDetails;
        $details->startDate = $previousDate;
        $details->endDate = $report->created_at;
        $details->journal = array_map(function($entry) use ($previousDate, $report) {
            $cycleStartDate = Carbon::parse($previousDate);
            $cycleEndDate = Carbon::parse($report->created_at);
            $entryInAtDate = Carbon::parse($entry['inAt']);

            $newVal = new ReportEntryDTO;
            $newVal->entry = $entry;

            $entryStartDate = $entryInAtDate >= $cycleStartDate ? $entryInAtDate : $cycleStartDate;

            $newVal->timeInDuringCycle =  $entryStartDate->diffInMinutes($cycleEndDate);

            $newVal->totalTimeIn = $entry["outAt"] != null ? $entryInAtDate->diffInMinutes(new Carbon()) : null;

            return $newVal;
        }, $journalEntries->toArray());
        $details->reports = $latestCycles;

        return response()->json($details);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        $user = $request->user();

        $entity = new Report;
        $entity->userId = $user->id;
        $entity->save();

        $report = $this->calculateTotalParkingTime($entity);

        return response()->json($report);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entity = Report::find($id);

        if(!$entity) {
            return response(ApiError::entityNotFound('Registry', $id), 400);
        }

        $report = $this->calculateTotalParkingTime($entity);

        return response()->json($report);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $entity = Report::find($id);

        if(empty($entity)) {
            return response(ApiError::entityNotFound('Registry', $id), 400);
        }

        $entity->delete();

        return response(null, 204);
    }
}
