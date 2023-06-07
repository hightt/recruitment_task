<?php
namespace App\classes;

class CurrencyTable
{
    
    public function __construct(private array $data, private string $table){}

    public static function drawInfoTable($currencies) : string
    {
        $table = '';
        $table .= '<table class="table table-striped mt-2">';
            $table .= '<thead>';
                $table .= '<tr>';
                    $table .= '<th scope="col">#</th>';
                    $table .= '<th scope="col">Waluta</th>';
                    $table .= '<th scope="col">Kod waluty</th>';
                $table .= '</tr>';
            $table .=' </thead>';
            $table .=' <tbody>';
                foreach($currencies as $currency) {
                    $table .= '<tr>';
                    $table .= '<th scope="row">' . $currency['id'] . '</th>';
                    $table .= '<td>'.$currency['name']. '</td>';
                    $table .= '<td>' . $currency['code'] . '</td>';
                    $table .= '</tr>';
                }
            $table .=  '</tbody>';
        $table .= '</table>';

        return $table;
    }

    public static function drawConversionHistoryTable($conversionHistory) : string
    {
        $table = '';
        $table .= '<table class="table table-striped mt-2">';
            $table .= '<thead>';
                $table .= '<tr>';
                    $table .= '<th scope="col">#</th>';
                    $table .= '<th scope="col">Waluta źródłowa</th>';
                    $table .= '<th scope="col">Waluta docelowa</th>';
                    $table .= '<th scope="col">Ilość</th>';
                    $table .= '<th scope="col">Wynik przewalutowania</th>';
                    $table .= '<th scope="col">Data przewalutowania</th>';
                $table .= '</tr>';
            $table .=' </thead>';
            $table .=' <tbody>';
                foreach($conversionHistory as $history) {
                    $table .= '<tr>';
                    $table .= sprintf('<th scope="row">%d</th>', $history['id']);
                    $table .= sprintf('<td>%s</td>', $history['name_source']);
                    $table .= sprintf('<td>%s</td>', $history['name_target']);
                    $table .= sprintf('<td>%.2f %s</td>', $history['amount'], $history['code_source']);
                    $table .= sprintf('<td>%.2f %s</td>', $history['value'], $history['code_target']);
                    $table .= sprintf('<td>%s</td>', $history['created_at']);
                    $table .= '</tr>';
                }
            $table .=  '</tbody>';
        $table .= '</table>';

        return $table;
    }

}


?>