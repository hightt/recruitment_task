<?php
    namespace App\classes;

    class Validator 
    {
        private $errors = [];

        public function is_string($val, $name = "", $minLength = 0, $maxLength = 255) : bool
        {
            if(is_string($val) && strlen($val) >= $minLength && strlen($val) <= $maxLength)
                return true;

            $this->errors[] = sprintf("Wprowadzono błędną wartość w polu: %s. Uzupełnij poprawnie dane i spróbuj ponownie.", $name);
            return false;
        }

        public function is_float($val, $name = "") : bool
        {
            if (is_float(filter_var($val, FILTER_VALIDATE_FLOAT)))
                return true;

            $this->errors[] = sprintf("Wprowadzono błędną wartość w polu: %s. Uzupełnij poprawnie dane i spróbuj ponownie.", $name);
            return false;
        }

        public function is_integer($val, $name = "") : bool
        {
            if (is_numeric($val) && (int) $val == $val)
                return true;

            $this->errors[] = sprintf("Wprowadzono błędną wartość w polu: %s. Uzupełnij poprawnie dane i spróbuj ponownie.", $name);
            return false;
        }

        public function getErrors() : array
        {
            return $this->errors;
        }
    }