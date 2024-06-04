<?php
/*
    Este archivo PHP maneja las solicitudes AJAX para buscar información sobre los productos en el carrito de compras.
    La funcionalidad incluye:
    - Conexión a una base de datos MySQL.
    - Procesamiento de solicitudes POST para la acción 'buscar'.
    - Recuperación de información de productos (ID, nombre y precio rebajado) desde la base de datos.
    - Cálculo del total de los precios de los productos en el carrito.
    - Envío de la información de los productos y el total como respuesta en formato JSON.
*/
require_once "config/conexion.php";
if (isset($_POST)) {
    if ($_POST['action'] == 'buscar') {
        $array['datos'] = array();
        $total = 0;
        for ($i=0; $i < count($_POST['data']); $i++) { 
            $id = $_POST['data'][$i]['id'];
            $query = mysqli_query($conexion, "SELECT * FROM productos WHERE id = $id");
            $result = mysqli_fetch_assoc($query);
            $data['id'] = $result['id'];
            $data['precio'] = $result['precio_rebajado'];
            $data['nombre'] = $result['nombre'];
            $total = $total + $result['precio_rebajado'];
            array_push($array['datos'], $data);
        }
        $array['total'] = $total;
        echo json_encode($array);
        die();
    }
}

?>