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
$rotacion = (int)($_POST['rot'] ?? 0);
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
    header('Location:cifrado_cesar.php');
    exit();
}
$palabra_original = str_split($palabra);
/*normalizando la palabra */
$palabra_limpia = str_replace(' ', '', strtolower($palabra)); // quitamos espacios de la palabra y la pasamos a minusculas
/*creamos un array de la palabra caracter a caracter */
$array_palabra = mb_str_split($palabra_limpia);
$array_palabra_cifrada = [];
$nueva_letra = 0;

/*iteramos sobre la palabra */
for ($i = 0; $i < count($array_palabra); $i++) {
    /*por cada letra buscamos en el abecedario */
    $letra_encontrada=false;
    for ($j = 0; $j < count($abecedario); $j++) {
        if ($array_palabra[$i] === $abecedario[$j]) {
            $nueva_letra = ($j + $rotacion) % count($abecedario); //obtenemos la posicion de letra por la que se intercambiara
            $array_palabra_cifrada[$i] = $abecedario[$nueva_letra];
            $letra_encontrada=true;
            echo "letra encontrada".$array_palabra[$i].'<br>';
        }

    }
    if(!$letra_encontrada){
        $array_palabra_cifrada[$i]=$array_palabra[$i];
        echo "letra no encontrada".$array_palabra[$i].'<br>';

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

$_SESSION['cifrado_cesar'] = $palabra_cifrada;
$_SESSION['palabra_original'] = $palabra;
$_SESSION['rotacion'] = $rotacion;
header('Location:cifrado_cesar.php');
