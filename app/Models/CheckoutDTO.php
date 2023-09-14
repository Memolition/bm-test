<?php
namespace App\Models;

class CheckoutDTO {
    public $entry;
    public $timeIn = 0;
    public $totalCharges = 0;
    public $willCharge = false;
}