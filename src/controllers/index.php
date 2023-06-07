<?php
include_once(\ROOT_PATH . "/src/Connection.php");
use App\classes\CurrencyTable;

$currencies = $db->query("SELECT * FROM currencies")->fetchAll();
$table = CurrencyTable::drawInfoTable($currencies);

require_once ROOT_PATH . '/views/index.php';
?>