<?php
namespace App\classes;
include_once(\ROOT_PATH . "/src/Connection.php");

class ApiNBP
{
    public const URL = "http://api.nbp.pl/api";
    
    public function getCurrencyData(string $tableName = "A", string $date = "today") : string|bool
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, sprintf("%s/exchangerates/tables/%s?format=json", self::URL, $tableName));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}

?>