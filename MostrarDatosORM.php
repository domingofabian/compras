<?php
    require_once(__DIR__ . '/ORM/conexion.php');
    require_once(__DIR__ . '/ORM/Orm.php'); 
    require_once(__DIR__ . '/ORM/escuelaORM.php');
    require_once(__DIR__ . '/ORM/alumnosORM.php');

    $database = new conexion();
    $coneccion = $database->getConnection();

    $escuelasModel = new Escuela($coneccion);
    $escuelas = $escuelasModel->getAll();
    
    $alumnosModel = new Alumno($coneccion);
    $alumnos = $alumnosModel->getAll();

    echo 'Escuelas';
    echo '<br>';

    echo '<pre>';
        var_dump($escuelas);
    echo '</pre>';
    echo '<br>';
    echo '<br>';

    echo 'Alumnos';
    echo '<br>';

    echo '<pre>';
        var_dump($alumnos);
    echo '</pre>';
?>