<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cifrado-Playfair</title>
    <link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="preload" href="css/Styles.css" as="style"> <!--Se utiliza lo siguiente para mayor performas, es mas rapido y de buena practica-->
    <link rel="stylesheet" href="css/Styles.css"> <!--Enlazamos la hoja de stilos con la etiqueta link-->
</head>

<body>
    <section > 
        <h1>Cifrado Playfair </h1> 
        <form action="cifrado_playfair.php" method="GET"  class="formulario">
            <fieldset>
                <legend> Reglas: </legend> 
                <legend> La letra ñ no es considerada </legend> 
                <legend> La letra j e i comparten el mismo campo </legend>
                <legend>Letra repedita en mismo digrafo la segunda se sustituye por x</legend>
                <div class="campo">
                    <label>Palabra</label>
                    <input type="text" name="palabra">
                </div>
                <div class="campo">
                    <label>Llave</label>
                    <input type="text" name="llave">
                </div>

                    <button type="submit" class ="boton">Cifrar</button>

            </fieldset>
        </form>
    </section>    
</body>

</html>