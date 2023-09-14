<?php
namespace App\Models;

class ReportEntryDTO {
    public $entry;
    public $timeInDuringCycle;
    public $totalTimeIn;

    public $cycleCharges = 0;
    public $totalCharges = 0;
    public $willCharge = false;
}