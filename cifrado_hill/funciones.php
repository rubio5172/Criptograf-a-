<?php

function validarNoVacio(string $nombre, $input)
{

    if (empty($input)) {
        echo $nombre . ' no puede estar vacio';
        echo "<br><a href='index.php'>Regresar</a>";
        exit();
    }
}

function llaveValida($determinante):bool{
    $llave_valida=true;
    if($determinante===0){
        $llave_valida=false;
    }else if ($determinante%2===0 || $determinante%13===0){
    $llave_valida=false;
    }
    return $llave_valida;
}


function normalizarPalabra($palabra): array
{
    //quitamos espacios y pasamos a minuscula
    $palabra = str_replace(' ', '', strtolower($palabra));
    $array_palabra = str_split($palabra);
    //lo pasamos a un array
    return $array_palabra;
}

function buscarPosicionesAbecedario(array $abecedario, array $palabra): array
{
    $pos = [];
    for ($i = 0; $i < count($palabra); $i++) {
        $pos[] = $abecedario[$palabra[$i]];
    }
    return $pos;
}

function llenarMatrizConLlave(array $llave): array
{
    $contador = 0;
    for ($i = 0; $i < sqrt(count($llave)); $i++) {
        for ($j = 0; $j < sqrt(count($llave)); $j++) {
            $matriz[$i][$j] = $llave[$contador];
            $contador++;
        }
    }
    return $matriz;
}

function cifrar(array $matriz, int $modulo): array
{
    foreach ($matriz as $arreglo) {
        foreach ($arreglo as $valor) {
            $valores_cifrados[] = $valor % $modulo; //sacamos modulo para cifrar
        }
    }
    return $valores_cifrados;
}

function valorALetra($valores_cifrados,$array_buscar):string{
    $mensaje_cifrado='';
    for ($i = 0; $i < count($valores_cifrados); $i++) {
    $mensaje_cifrado .= array_search($valores_cifrados[$i], $array_buscar); //devolvemos las claves con los valores
}

return $mensaje_cifrado;

}

function inversoModular($numero){
    for($incremento = 1; $incremento <26; $incremento++){
        if(($numero * $incremento) % 26 === 1){
            return $incremento;
        }
    }
    return null; 
}

function validarDeterminanteNoPositivo($determinante): int{
    if($determinante<0){
        $determinante=$determinante+26;
    }
    return $determinante;


}