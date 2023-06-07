<?php
use App\classes\ApiNBP;
include_once(\ROOT_PATH . "/src/Connection.php");

/* Get specific data from NBP API and push it to $result array */
function getApiCurrencyData(string $date) : array
{
    $api = new ApiNBP();
    $apiTableNames = ['A', 'B'];
    
    $result = [];
    
    foreach($apiTableNames as $apiTableName)
    {
        $data = json_decode($api->getCurrencyData($apiTableName, $date), true);
        $result[] = $data[0]['rates'];
    }

    return $result;
}

/* Insert API data (if does not exist in DB) to 'currency' table into DB */
function saveCurrencyData($db, $apiData) : array
{
    foreach ($apiData as $currency) {
        try {
            $statement = $db->prepare('INSERT INTO currencies(name, code) VALUES(:name, :code)');
            $statement->execute([
                ':name' => $currency['currency'],
                ':code' => $currency['code']
            ]);
        } catch(Exception $e) {
            return ['status' => false, 'msg' => sprintf("Currency: %s, %s | Exception: %s", $currency['currency'], $currency['code'], $e->getMessage())];
        }
    }

    return ['status' => true, 'msg' => "Pomyślnie dodano dane walutowe."];
}

/* Create daily price history of all currencies and save it into DB */
function createCurrencyPriceHistory($db, $apiData)
{
    foreach ($apiData as $currency) {
        /* Find currency ID in DB */
        try {
            $statement = $db->prepare('SELECT * FROM currencies WHERE code = ?');
            $statement->execute([$currency['code']]);
            $currencyId = $statement->fetch()['id'];
        } catch(Exception $e) {
           return ['status' => false, 'msg' => sprintf("Currency: %s, %s | Exception: %s", $currency['currency'], $currency['code'], $e->getMessage())];
        }

        try {
            $statement = $db->prepare('INSERT INTO currencies_rate(currency_id, value) VALUES(:currency_id, :value)');
            $statement->execute([
                ':currency_id' => $currencyId,
                ':value' => $currency['mid']
            ]);
        } catch(Exception $e) {
            return ['status' => false, 'msg' => sprintf("Currency: %s, %s | Exception: %s", $currency['currency'], $currency['code'], $e->getMessage())];
        }


    }
    return ['status' => true, 'msg' => "Pomyślnie dodano dane walutowe."];
}

$data = getApiCurrencyData("today");

/* Merge currency data from API tables A, B into one */
$temp = [];
foreach($data as $currencies) {
    $temp = array_merge($temp, $currencies);
}

$data = array_values($temp);
// $msg = saveCurrencyData($db, $data);
$test = createCurrencyPriceHistory($db, $data);
?>