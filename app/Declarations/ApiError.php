<?php

namespace App\Declarations;

class ApiError {
    static public function fieldIsRequired($fieldName) {
        return "Field $fieldName is required and cannot be empty.";
    }

    static public function invalidField($fieldName) {
        return "Field $fieldName is invalid and not part of this request content.";
    }

    static public function invalidValue($fieldName) {
        return "Value provided for field $fieldName is invalid.";
    }

    static public function invalidValueDuplicated($fieldName) {
        return "Value provided for field $fieldName is invalid as it already exists in our database.";
    }
    
    static public function fieldMinimumNotMet($fieldName, $requiredLength) {
        return "Value provided for field $fieldName is invalid, a minimum length of $requiredLength characters is required.";
    }

    static public function entityNotFound($model, $id) {
        return "$model $id couldn't be found.";
    }

    static public function entryNotFound($plate) {
        return "No open entry could be found for vehicle $plate";
    }

    static public function invalidPassword($id) {
        return "Provided password for $id is invalid.";
    }


}