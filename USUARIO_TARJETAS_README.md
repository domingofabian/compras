# Tabla usuario_tarjetas - Documentación

## Descripción
La tabla `usuario_tarjetas` relaciona usuarios con sus medios de pago (tarjetas). Permite especificar qué tarjetas posee cada usuario.

## Estructura de la tabla

```sql
CREATE TABLE `usuario_tarjetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` int(11) NOT NULL,        -- Referencia a usuarios(id)
  `tarjeta` int(2) NOT NULL,            -- Referencia a formas_pago(tarjeta)
  `activa` tinyint(1) DEFAULT 1,        -- 1 = activa, 0 = inactiva
  `fecha_agregada` timestamp,           -- Cuándo se agregó
  `notas` varchar(255) DEFAULT NULL,    -- Notas adicionales
  UNIQUE KEY `usuario_tarjeta` (usuario_id, tarjeta),
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`tarjeta`) REFERENCES `formas_pago`(`tarjeta`) ON DELETE CASCADE
)
```

## Relaciones

- **usuario_tarjetas.usuario_id** → **usuarios.id**
- **usuario_tarjetas.tarjeta** → **formas_pago.tarjeta**

## Ejemplos de uso

### 1. Obtener todas las tarjetas de un usuario

```php
<?php
include("gestionar_tarjetas.php");

$usuario_id = 8; // Fabian
$tarjetas = obtener_tarjetas_usuario($usuario_id, $link);

foreach($tarjetas as $tarjeta) {
    echo "Tarjeta: " . $tarjeta['descripcion'] . " (";
    echo ($tarjeta['activa'] == 1) ? "Activa" : "Inactiva";
    echo ")<br>";
}
?>
```

### 2. Agregar una tarjeta a un usuario

```php
<?php
$resultado = agregar_tarjeta_usuario(8, 27, 'Visa Credito Personal', $link);

if($resultado['success']) {
    echo "Tarjeta agregada: " . $resultado['id'];
} else {
    echo "Error: " . $resultado['message'];
}
?>
```

### 3. Cambiar estado de una tarjeta

```php
<?php
// Desactivar tarjeta
$resultado = cambiar_estado_tarjeta(1, 0, $link); // id=1, desactiva

// Activar tarjeta
$resultado = cambiar_estado_tarjeta(1, 1, $link); // id=1, activa
?>
```

### 4. Eliminar una tarjeta de un usuario

```php
<?php
$resultado = eliminar_tarjeta_usuario(1, $link);

if($resultado['success']) {
    echo $resultado['message'];
}
?>
```

### 5. Obtener todas las tarjetas disponibles

```php
<?php
$todas_tarjetas = obtener_todas_tarjetas($link);

foreach($todas_tarjetas as $tarjeta) {
    echo $tarjeta['tarjeta'] . " - " . $tarjeta['descripcion'] . "<br>";
}
?>
```

## Datos de ejemplo

La tabla se precarga con datos de ejemplo:

- **Fabian (usuario_id=8)**: Cabal Crédito, Cabal Débito, Visa Crédito
- **Denise (usuario_id=5)**: Cuenta DNI, Mercado Pago
- **Pabla (usuario_id=6)**: Visa Débito, Cabal Crédito, Mercado Pago, Crédito Sencosur
- **Lucia (usuario_id=7)**: Cuenta DNI, Mercado Pago

## Instalación

1. Ejecuta el script SQL en tu base de datos:
   ```bash
   mysql -u usuario -p base_datos < usuario_tarjetas.sql
   ```

2. O copia y pega el contenido en phpMyAdmin

## Usos en la aplicación

### En modal.php
Puedes filtrar las tarjetas disponibles según el usuario:

```php
<?php
$user_tarjetas = obtener_tarjetas_usuario($usuario_id, $link);
?>
<select name="pago">
    <?php foreach($user_tarjetas as $t): ?>
        <option value="<?php echo $t['tarjeta']; ?>">
            <?php echo $t['descripcion']; ?>
        </option>
    <?php endforeach; ?>
</select>
```

### En fabian.php, Denise.php, etc
Mostrar solo las tarjetas del usuario:

```php
<?php
$tarjetas = obtener_tarjetas_usuario($usuario_id, $link);
echo json_encode($tarjetas);
?>
```

## Notas importantes

- Cada usuario puede tener múltiples tarjetas
- El constraint UNIQUE previene que se agregue la misma tarjeta dos veces a un usuario
- Al eliminar un usuario, se eliminarán automáticamente sus tarjetas (ON DELETE CASCADE)
- Al eliminar una forma de pago, se eliminarán automáticamente sus asociaciones (ON DELETE CASCADE)
