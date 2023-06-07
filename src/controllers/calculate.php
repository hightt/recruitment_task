<?php
use App\classes\CurrencyCalculator;
use App\classes\Validator;

include_once(\ROOT_PATH . "/src/Connection.php");

$requestType = $_SERVER['REQUEST_METHOD'];
switch($requestType) {
    case "GET":
        $currencies = $db->query("SELECT cr.currency_id as id, c.name, c.code, cr.value, MAX(cr.created_at) as created_at FROM currencies_rate cr JOIN currencies c ON cr.currency_id = c.id GROUP BY cr.currency_id")->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . '/views/calculate.php';
    break;
    case "POST":
        $data = $_POST['formData'];
        header('Content-Type: application/json; charset=utf-8');

        $validator = new Validator();
        if($validator->is_float($data['amount'], "Kwota") && 
            $validator->is_integer($data['sourceCurrencyId'], "waluta źródłowa") && 
            $validator->is_integer($data['targetCurrencyId'], "waluta docelowa")) {
            echo json_encode(calculateCurrency($db, $data));
        } else {
            header('Status: ' . 400);
            echo json_encode($validator->getErrors());
        }
    break;
}

function calculateCurrency($db, $formData) : array
{
    try {
        $sourceCurrencyRate = $db->query("SELECT * FROM currencies_rate WHERE currency_id={$formData['sourceCurrencyId']} ORDER BY created_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $targetCurrencyRate = $db->query("SELECT * FROM currencies_rate WHERE currency_id={$formData['targetCurrencyId']} ORDER BY created_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
        return ['status' => false, 'msg' => sprintf("Wystąpił błąd: %s", $e->getMessage())];
    }

    $currencyCalculator = new CurrencyCalculator($sourceCurrencyRate['value'], $targetCurrencyRate['value']);

    $result = $formData['amount'] * $currencyCalculator->scale();
    $msg = createCurrencyConversionHistory($db, $sourceCurrencyRate['currency_id'], $targetCurrencyRate['currency_id'], (float)$result, $formData['amount']);
    
    return ['result' => number_format($result, 2, ',', ''), 'sourceCurrencyRate' => $sourceCurrencyRate, 'targetCurrencyRate' => $targetCurrencyRate];
}

function createCurrencyConversionHistory($db, int $sourceId, int $targetId, float $result, float $amount) : array
{
    try {
        $statement = $db->prepare('INSERT INTO currencies_conversion_history(currency_id_source, currency_id_target, amount, value) VALUES(:currency_id_source, :currency_id_target, :amount, :value)');
        $statement->execute([
            ':currency_id_source' => $sourceId,
            ':currency_id_target' => $targetId,
            ':amount' => $amount,
            ':value' => $result
        ]);
    } catch(Exception $e) {
        return ['status' => false, 'msg' => sprintf("Exception: %s", $e->getMessage())];
    }

    return ['status' => true, 'msg' => "Pomyślnie utworzono historię przewalutowań."];
}
?>