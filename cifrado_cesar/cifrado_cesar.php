<?php
session_start(); ?>
<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cifrado Cesar</title>
</head>

<body>

    <form action="cifrado.php" method="POST">
        <div>
            <label>Ingresa la frase</label>
            <input type="text" name="palabra">
        </div>

        <div>
            <label>Rotación</label>
            <input type='number' name='rot' min='0' max='26' value=0>
        </div>
      
    <button type="submit" >Cifrar</button>
    <?php if (!empty($_SESSION['cifrado_cesar'])): ?>
        <p>Palabra original: <?= $_SESSION['palabra_original'] ?></p>
        <p>Rotacion: <?= $_SESSION['rotacion'] ?></p>
        <p>Palabra cifrada: <?= $_SESSION['cifrado_cesar']; ?> </p>
        <?php unset($_SESSION['palabra_original']);
        unset($_SESSION['cifrado_cesar'])
        ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <?php foreach ($_SESSION['error'] as $error): ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</body>

</html>