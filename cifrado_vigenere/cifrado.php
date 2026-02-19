<?php
session_start();

$abecedario = [
    'a',
    'b',
    'c',
    'd',
    'e',
    'f',
    'g',
    'h',
    'i',
    'j',
    'k',
    'l',
    'm',
    'n',
    'ñ',
    'o',
    'p',
    'q',
    'r',
    's',
    't',
    'u',
    'v',
    'w',
    'x',
    'y',
    'z'
];

$_SESSION['error'] = [];
$palabra = $_POST['palabra'];
$llave=$_POST['llave'];

if (empty($palabra)) {
    $_SESSION['error'][] = 'La palabra no puede estar vacia';
}
if ($rotacion === 0) {
    $_SESSION['error'][] = 'La rotacion es 0, la palabra no se cifra';
    $_SESSION['cifrado_cesar'] = $palabra;
    $_SESSION['palabra_original'] = $palabra;
    $_SESSION['rotacion'] = $rotacion;
}
if (!empty($_SESSION['error'])) {
    header('Location:cifrado_vigenere.php');
    exit();
}
$palabra_original = str_split($palabra);
/*normalizando la palabra */
$palabra_limpia = str_replace(' ', '', strtolower($palabra)); // quitamos espacios de la palabra y la pasamos a minusculas
$llave_limpia=str_replace(' ', '', strtolower($llave));
/*creamos un array de la palabra caracter a caracter */
$array_palabra = mb_str_split($palabra_limpia);
$array_llave=mb_str_split($llave_limpia);
$array_palabra_cifrada = [];
$nueva_letra = 0;
$rotaciones=[];
$indice_llave=0;
/*obtenemos las posiciones de la llave */
for($i=0; $i<count($array_llave); $i++){
    for($j=0; $j<count($abecedario); $j++){
        if($array_llave[$i]===$abecedario[$j]){
            $rotaciones[]=$j;   
        }
    }
}

/*iteramos sobre la palabra */
for ($i = 0; $i < count($array_palabra); $i++) {
    /*por cada letra buscamos en el abecedario */
    $letra_encontrada=false;
    for ($j = 0; $j < count($abecedario); $j++) {
        if ($array_palabra[$i] === $abecedario[$j]) {
            if($indice_llave>=count($array_llave)){
                $indice_llave=0;
                
            }
            $nueva_letra = ($j + $rotaciones[$indice_llave]) % count($abecedario); //obtenemos la posicion de letra por la que se intercambiara
            $array_palabra_cifrada[$i] = $abecedario[$nueva_letra];
            $letra_encontrada=true;
            $indice_llave++;

        
        }

    }
    if(!$letra_encontrada){
        $array_palabra_cifrada[$i]=$array_palabra[$i];
        

    }
}

$palabra_cifrada = '';
$indice_cifrada = 0;
foreach ($palabra_original as $i => $caracter_original) {

    if ($caracter_original === ' ') {
        $palabra_cifrada .= ' '; // reinsertamos el espacio
    } else {
        // si era mayuscula la convertimos a mayuscula
        if (ctype_upper($caracter_original)) {
            $palabra_cifrada .= strtoupper($array_palabra_cifrada[$indice_cifrada]);
        } else {
            $palabra_cifrada .= $array_palabra_cifrada[$indice_cifrada];
        }
        
        $indice_cifrada++;
        
    }
}
echo $palabra_cifrada;
$_SESSION['cifrado_cesar'] = $palabra_cifrada;
$_SESSION['palabra_original'] = $palabra;
$_SESSION['llave'] = $llave;
header('Location:cifrado_vigenere.php');
