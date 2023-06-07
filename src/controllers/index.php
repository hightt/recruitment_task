<?php
include_once(\ROOT_PATH . "/src/Connection.php");
use App\classes\CurrencyTable;

$currencies = $db->query("SELECT cr.currency_id as id, c.name, c.code, cr.value, MAX(cr.created_at) as created_at FROM currencies_rate cr JOIN currencies c ON cr.currency_id = c.id GROUP BY cr.currency_id")->fetchAll(PDO::FETCH_ASSOC);
$table = CurrencyTable::drawInfoTable($currencies);

require_once ROOT_PATH . '/views/index.php';
?>