<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';

define("ROOT_PATH", __DIR__);

function dd($array)
{
    echo "<pre>";
        print_r($array);
    echo "</pre>";
    die;
}



require_once dirname(__FILE__) . '/router.php';
?>