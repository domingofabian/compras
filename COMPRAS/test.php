<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test</title>
</head>
<body>
<h1>Prueba de PHP</h1>
<p>Si ves esto, PHP está funcionando.</p>
<?php
echo "<p>PHP está ejecutándose correctamente</p>";
echo "<p>GET parameters: user=" . (isset($_GET['user']) ? $_GET['user'] : 'NO') . ", pago=" . (isset($_GET['pago']) ? $_GET['pago'] : 'NO') . "</p>";
?>
</body>
</html>
