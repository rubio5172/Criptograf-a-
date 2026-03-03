<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cifrado Hill</title>
</head>
<body>
    <form action="cifrado.php" method="GET">
        <div>
            <label>Mensaje</label>
            <input type="text" name="mensaje">
        </div>
        <div>
            <label>Llave</label>
            <input type="text" name="llave">
        </div>
        <button type="submit">Enviar</button>
    </form>

    <?php if (!empty($_SESSION['mensaje_cifrado'])): ?>
        <p><strong>Mensaje cifrado: </strong><?= $_SESSION['mensaje_cifrado'] ?></p>
        <?php unset($_SESSION['mensaje_cifrado'])?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['mensaje_descifrado'])): ?>
        <p><strong>Mensaje descifrado: </strong><?= $_SESSION['mensaje_descifrado'] ?></p>
        <?php unset($_SESSION['mensaje_descifrado'])?>
    <?php endif; ?>
    <?php session_destroy();?>
</body>
</html>