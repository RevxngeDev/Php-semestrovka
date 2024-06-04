<?php
/*
    Este archivo PHP maneja la eliminación de registros en una base de datos MySQL.
    La funcionalidad incluye:
    - Verificación de parámetros GET para determinar la acción y el ID del registro a eliminar.
    - Conexión a una base de datos MySQL.
    - Eliminación de productos o categorías según el parámetro de acción recibido.
    - Redirección a las páginas correspondientes después de la eliminación.
*/
if (isset($_GET)) {
    if (!empty($_GET['accion']) && !empty($_GET['id'])) {
        require_once "../config/conexion.php";
        $id = $_GET['id'];
        if ($_GET['accion'] == 'pro') {
            $query = mysqli_query($conexion, "DELETE FROM productos WHERE id = $id");
            if ($query) {
                header('Location: productos.php');
            }
        }
        if ($_GET['accion'] == 'cli') {
            $query = mysqli_query($conexion, "DELETE FROM categorias WHERE id = $id");
            if ($query) {
                header('Location: categorias.php');
            }
        }
    }
}
?>