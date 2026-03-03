<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/funciones.php';

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

$abecedario = [
    'a' => 0,
    'b' => 1,
    'c' => 2,
    'd' => 3,
    'e' => 4,
    'f' => 5,
    'g' => 6,
    'h' => 7,
    'i' => 8,
    'j' => 9,
    'k' => 10,
    'l' => 11,
    'm' => 12,
    'n' => 13,
    'o' => 14,
    'p' => 15,
    'q' => 16,
    'r' => 17,
    's' => 18,
    't' => 19,
    'u' => 20,
    'v' => 21,
    'w' => 22,
    'x' => 23,
    'y' => 24,
    'z' => 25
];
/*obtenemos datos del formulario */
$mensaje = $_GET['mensaje'] ?? '';
$llave = $_GET['llave'] ?? '';

/*validaciones */
validarNoVacio('mensaje', $mensaje);
validarNoVacio('llave', $llave);

/*validamos tamaños de la llave */
$tamaño_llave = strlen($llave);
$tamaños_especificos = [4, 9];
if (!in_array($tamaño_llave, $tamaños_especificos)) {
    echo "tamaño invalido";
    echo "<br><a href='index.php'>Regresar</a>";
    exit();
}

/*normalizando palabras y pasando de string a array*/
$llave = normalizarPalabra($llave);
$mensaje = normalizarPalabra($mensaje);

$pos_llave = buscarPosicionesAbecedario($abecedario, $llave);
$pos_mensaje = buscarPosicionesAbecedario($abecedario, $mensaje);

$array_dividido = array_chunk($pos_mensaje, sqrt(count($llave))); //dividmos en arrays
$ultimo_array = count($array_dividido) - 1;
//si nuestro ultimo array no se completa lo rellenamos con x
if (count($array_dividido[$ultimo_array]) < sqrt(count($llave))) {
    $array_dividido[$ultimo_array] = array_pad($array_dividido[$ultimo_array], sqrt(count($llave)), 23);
}

//llenamos la matriz
$matriz = llenarMatrizConLlave($pos_llave);
//pasamos la matriz llena a una matriz de Mathphp
$matriz_llave = MatrixFactory::create($matriz);
//obtenemos el determinante de la matriz
$det_m_ll = validarDeterminanteNoPositivo($matriz_llave->det());

$det_m_ll_modulo = validarDeterminanteNoPositivo($det_m_ll % 26);

if ($det_m_ll_modulo < 0) {
    echo "La llave no es valida, intenta otra";
    echo "<br><a href='index.php'>Regresar</a>";
    exit();
}
//validamos que la llave sea valida
if (!llaveValida($det_m_ll)) {
    echo "La llave no es valida, intenta otra";
    echo "<br><a href='index.php'>Regresar</a>";
    exit();
}

//convertimos cada array en vector 
foreach ($array_dividido as $array) {
    $vec_mensaje[] = new Vector($array);
}

$r = [];
//multiplicamos la matriz por cada vector 
foreach ($vec_mensaje as $vector) {
    $r[] = $matriz_llave->multiply($vector);
}
//cada vector dado de la multiplicamos  lo pasamos a getMatriz para tratarlo como array 
foreach ($r as $matriz) {
    $resultados[] = $matriz->getMatrix();
}
//todos los resultados los convertimos a un solo array 
$resultados = array_merge(...$resultados);

$valores_cifrados = [];
//obtenemos los valores
$valores_cifrados = cifrar($resultados, 26);
//obtenemos el mensaje final
$mensaje_cifrado = valorALetra($valores_cifrados, $abecedario);

$_SESSION['mensaje_cifrado'] = $mensaje_cifrado;


//descifrar
/*Para descifrar primero necesitamos obtener el determinante de la matriz
el determinante se hace modulo con 26
a este determinante hay que sacarle el inverso modular este caso del modulo 26
para obtener la matriz que sera multiplicada por el vector de palabras cifrada es 
el inversomodulo * matriz adjunta %26
k^-1 es la matriz inversa modular
*/
//obtemos el numero inverso modular 
$numero_inverso_modular = inversoModular($det_m_ll_modulo);

//obtenemos matriz adjunta
//matriz de cofactores
$matriz_cofactores_llave = $matriz_llave->cofactorMatrix();
//obtenemos matriz transpuesta para tener la adjunta
$matriz_adjunta_llave = $matriz_cofactores_llave->transpose();
//pasamos a un array todos los valores
$matriz_adjunta_llave = $matriz_adjunta_llave->getMatrix();
$matriz_adjunta_llave = array_merge(...$matriz_adjunta_llave);

//obtenemos matriz final 
$array_llave_inversa = [];
foreach ($matriz_adjunta_llave as $valor) {
    // el inverso modular por cada valor %26
    $valor_matriz = ($numero_inverso_modular * $valor) % 26;
    //si el valor es negativo entonces le sumamos 26
    if ($valor_matriz < 0) {
        $valor_matriz = $valor_matriz + 26;
    }
    $array_llave_inversa[] = $valor_matriz;
}

//pasamos el array de los valores de la nueva palabra a vector de la libreria
$array_cifrado_dividido = array_chunk($valores_cifrados, sqrt(count($llave))); //dividmos en arrays
$ultimo_array = count($array_cifrado_dividido) - 1;
//si nuestro ultimo array no se completa lo rellenamos con x
if (count($array_cifrado_dividido[$ultimo_array]) < sqrt(count($llave))) {
    $array_cifrado_dividido[$ultimo_array] = array_pad($array_cifrado_dividido[$ultimo_array], sqrt(count($llave)), 23);
}
$valores_cifrados_vec = [];
foreach ($array_cifrado_dividido as $array_cifrado) {
    $valores_cifrados_vec[] = new Vector($array_cifrado);
}


//obtenemos la matriz final para multiplicar
$matriz_llave_inversa = MatrixFactory::create(array_chunk($array_llave_inversa, sqrt($tamaño_llave)));

//multiplicamos matriz por el vector
$matriz_descifrada = [];
foreach ($valores_cifrados_vec as $valor_cifrado_vec) {
    $matriz_descifrada[] = $matriz_llave_inversa->multiply($valor_cifrado_vec);
}
$array_resultante_descifrado = [];
//cada vector dado de la multiplicamos  lo pasamos a getMatriz para tratarlo como array 
foreach ($matriz_descifrada as $vector_descifrado) {
    $array_resultante_descifrado[] = $vector_descifrado->getMatrix();
}
$array_resultante_descifrado = array_merge(...$array_resultante_descifrado);
$mensaje_descifrado = '';
$valores_descifrados = [];
//sacamos el modulo de cada valor de los arrays
foreach ($array_resultante_descifrado as  $array_descifrado) {
    foreach ($array_descifrado as $valor_descifrado) {
        $valores_descifrados[] = $valor_descifrado % 26;
    }
}

$mensaje_descifrado = valorALetra($valores_descifrados, $abecedario);
$_SESSION['mensaje_descifrado'] = $mensaje_descifrado;
header('Location:index.php');