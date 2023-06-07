<?php
include_once(\ROOT_PATH . "/src/Connection.php");
use App\classes\CurrencyTable;

$conversionHistory = $db->query("
    SELECT cch.id, c1.name as name_source, c1.code as code_source, c2.name as name_target, c2.code as code_target, cch.amount, cch.value, cch.created_at 
    from currencies_conversion_history cch 
    JOIN currencies c1 ON cch.currency_id_source = c1.id 
    JOIN currencies c2 ON cch.currency_id_target = c2.id 
    GROUP BY cch.id ORDER BY cch.created_at DESC;"
)->fetchAll(PDO::FETCH_ASSOC);

$table = CurrencyTable::drawConversionHistoryTable($conversionHistory);

require_once ROOT_PATH . '/views/index.php';
?>