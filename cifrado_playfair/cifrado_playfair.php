<?php

use MathPHP\LinearAlgebra\MatrixFactory;

$palabra = $_GET['palabra'] ?? '';
$llave = $_GET['llave'] ?? '';

if (str_contains($palabra, 'ñ') || str_contains($llave, 'ñ')) {
    echo "No se puede tener ñ";
    echo '<br><a href="index.php">Regresar</a>';
    exit();
}
/*normalizando palabra */
$palabra = str_replace('j', 'i', strtolower($palabra));
$palabra=str_replace(' ','',$palabra);
$palabra_array = str_split($palabra); //generaos el array

/*si tenemos una palabra impar entonces le agregamos una x al final  */
if (count($palabra_array) % 2 === 1) {
    array_push($palabra_array, 'x');
}
$palabra_divida = array_chunk($palabra_array, 2); /*dividimos el array en pares  */ 
foreach ($palabra_divida as $indiceGrupo => $grupo) {
    if($grupo[0]===$grupo[1]){
       $palabra_divida[$indiceGrupo][1] = 'x';
    }
}

$palabra_nueva = implode('', $palabra_array);

$llave = str_replace('j', 'i', strtolower($llave)); //pasamos la llave a minusculas y si la llave tiene j la sustituimos por i
$llave = str_replace(' ', '', $llave);
$llave_array = str_split($llave);
$letras_abecedario = str_replace('j', 'i', range('a', 'z')); //creamos las letras del abecedario en un array y sustituimos la j por la i

$todas_las_letras = array_unique(array_merge($llave_array, $letras_abecedario)); //jusntamos todas las letras tanto las de la llave como las del abecedario y creamos un nuevo array con valores unicos

$todas_las_letras = array_values($todas_las_letras); //con array values lo que hacemos es reiniciar los indicens ya que el array_uniques deja los indices que tienen las letras desde el inicio 


$matriz = [];
$contador = 0;

// Rellenamos la matriz
for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 5; $j++) {
        $matriz[$i][$j] = $todas_las_letras[$contador];
        $contador++;
    }
}

// mostramos la matriz
echo "<table border='1'  text-align: center; '>";
foreach ($matriz as $fila) {
    echo "<tr>";
    foreach ($fila as $letra) {
        if (in_array($letra, $llave_array)) {
            echo "<td style='padding: 10px; background-color: blue; color: white;'>$letra</td>";
        } else {
            echo "<td style='padding: 10px;'>$letra</td>";
        }
    }
    echo "</tr>";
}
echo "</table>";



$pos = [];

foreach ($palabra_divida as $indiceGrupo => $grupo) {

    foreach ($grupo as $letra) {
        //obtenemos las posiciones de cada letra dentro de la matriz
        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 5; $j++) {
                if ($matriz[$i][$j] === $letra) {
                    // echo 'la letra ' . $letra . ' tiene posicion: ' . $i . ',' . $j . '<br>';
                    $pos[$letra] = ['r' => (int)$i, 'c' => (int)$j];
                }
            }
        }
    }
}
$palabra_cifrada = ''; //aqui vamos a guardar la palabra cifrada
//validamos para cifrar
/*condiciones 
si estan en la misma fila cada una se reemplaza por la letra a su derecha
si etsa en la misma columna , cada una se reemplaza por la letra debajo
Si forman las esquinas de un rectángulo, cada letra se reemplaza por la letra en la misma fila pero en la columna de la otra letra. */
foreach ($palabra_divida as $indiceGrupo => $grupo) {

    if ($pos[$grupo[0]]['r'] === $pos[$grupo[1]]['r']) {

        //echo 'la letra '.$grupo[0].' y '.$grupo[1].' estan en la misma fila';
        $palabra_cifrada .= $matriz[$pos[$grupo[0]]['r']][($pos[$grupo[0]]['c'] + 1)%count($matriz)] . $matriz[$pos[$grupo[1]]['r']][($pos[$grupo[1]]['c'] + 1)%count($matriz)];
        //echo $palabra_cifrada;

    } else if ($pos[$grupo[0]]['c'] === $pos[$grupo[1]]['c']) {
        // echo 'la letra '.$grupo[0].' y '.$grupo[1].' estan en la misma COLUMNA';
        $palabra_cifrada .= $matriz[($pos[$grupo[0]]['r'] + 1)%count($matriz)][$pos[$grupo[0]]['c']] . $matriz[($pos[$grupo[1]]['r'] + 1)%count($matriz)][$pos[$grupo[1]]['c']];
        //echo $palabra_cifrada;
    } else {
        $palabra_cifrada .= $matriz[$pos[$grupo[0]]['r']][$pos[$grupo[1]]['c']] . $matriz[$pos[$grupo[1]]['r']][$pos[$grupo[0]]['c']];
    }
}

echo '<strong>Palabra: </strong>' . $palabra . '<br>';
echo '<strong>Llave: </strong>' . $llave . '<br>';
echo '<strong>Palabra cifrada: </strong>' . $palabra_cifrada;

echo '<div id="mensaje" style="display: none;"><strong>Palabra Descifrada: </strong>' . $palabra_nueva . '</div>';
echo '<br><button onclick="mostrar()">Descifrar</button>';
?>

<script>
function mostrar() {
   
    document.getElementById('mensaje').style.display = 'block';
}
</script>
