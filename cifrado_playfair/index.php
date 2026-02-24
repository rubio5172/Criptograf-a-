<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cifrado-Playfair</title>
</head>

<body>
    <form action="cifrado_playfair.php" method="GET">
        <div>
            <label>Palabra</label>
            <input type="text" name="palabra">
        </div>
        <div>
            <label>Llave</label>
            <input type="text" name="llave">
        </div>


        <button type="submit">Cifrar</button>
    </form>
    <div>
    <p><strong>Reglas:</strong></p>
    <p>La letra ñ y j no son consideradas</p>
    <p>si la palabra o llave tiene la letra j es cambiada por una i</p>
    </div>
</body>

</html>