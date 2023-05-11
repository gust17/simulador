<?php

use Carbon\Carbon;


if (!function_exists('format_currency')) {
    function format_currency($value, $currencyCode = 'BRL')
    {
        $formatter = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);

        //dd($formatter);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        return $formatter->formatCurrency($value, $currencyCode);
    }
}


if (!function_exists('format_porcentagem')) {
    function format_porcentagem($value)
    {
        $valor = round($value);
        return $valor;
    }
}

if (!function_exists('str_insert')) {
    function str_insert($str, $insert, $pos)
    {
        return substr($str, 0, $pos) . $insert . substr($str, $pos);
    }
}

if (!function_exists('limpa_corrige_cpf')) {
    function limpa_corrige_cpf($value)
    {

        //dd($value);
        $cpf = preg_replace('/[^a-zA-Z0-9\s]/', '', $value);

        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        return $cpf;
    }
}


if (!function_exists('validada_parcela')) {
    function valida_parcela($value)
    {
        // Verificar se o valor contém uma barra "/"
        if (strpos($value, '/') !== false) {
            // Dividir a string em um array com base no caractere "/"
            $array = explode('/', $value);

            // Obter o primeiro elemento do array, que é o valor antes da barra "/"
            $valor = array_shift($array);

            // Imprimir o valor para fins de depuração
            //dd($valor);

            // Retornar o valor antes da barra "/"
            return intval($valor);
        } else {
            // Se o valor não contiver uma barra "/", exibir o valor original


            // Retornar o valor original
            return intval($value);
        }
    }

}


if (!function_exists('corrige_dinheiro')) {
    function corrige_dinheiro($value)
    {

        //dd($value);
        $string = str_replace("R$ ", "", $value); // remove "R$ "

//        $valor_em_float = floatval(str_replace(',', '.', str_replace('.', '', substr($value, 3))));
        $string = str_replace(".", "", $string); // substitui ponto por espaço

        $string = str_replace(",", ".", $string);

        return floatval($string);
    }

}

if (!function_exists('corrige_dinheiro2')) {
    function corrige_dinheiro2($value)
    {


        $string = str_replace(".", "", $value); // substitui ponto por espaço
        $string = str_replace(",", ".", $string);

        //$valor_em_float = floatval(str_replace(',', '.', str_replace('.', '', substr($value, 3))));

        return floatval($string);
    }

}

if (!function_exists('valida_data')) {
    function valida_data($value)
    {

        $formats = ['Y-m-d H:i:s.u', 'd/m/Y H:i:s.u', 'Y-m-d\TH:i:s.u', 'Y-m-d H:i:s', 'd/m/Y', 'd-m-Y', 'Y-m-d', 'd/m/Y'];


        if ($value) {
            try {
                $data_carbon = Carbon::parse($value);
                return $data_carbon;
            } catch (\Exception $e) {
                // Tratar o erro aqui
            }
        }
        return \Carbon\Carbon::now();
    }

}

if (!function_exists('valida_contrato')) {
    function valida_contrato($value)
    {


        if ($value) {
            $valor = preg_replace('/[^a-zA-Z0-9\s]/', '', $value);
            return $valor;
        } else {
            return 0;
        }


    }

}
if (!function_exists('valida_nome')) {
    function valida_nome($value)
    {
        if ($value) {
            $nome = (preg_replace('/[^a-zA-Z0-9\s]/', '', $value));

        } else {
            $nome = "00000000000000000";
        }
        return $nome;
    }
}

if (!function_exists('get_porcentagem')) {
    function get_porcentagem($total,$valor)
    {
       return ($valor*100)/$total;
    }
}

if (!function_exists('validanovadata')){
    function validanovadata($valor){
        $dateObject = \Carbon\Carbon::createFromFormat('dMY', $valor);
        return ($dateObject->format('Ymd'));
    }

}
if (!function_exists('validanovadataQTD')){
    function validanovadataQTD($valor){
        $dateObject = \Carbon\Carbon::createFromFormat('dMY', $valor);
        return ($dateObject->diffInMonths()+1);
    }

}
