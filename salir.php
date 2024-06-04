<?php
/*
    Este archivo PHP gestiona el cierre de sesión de un usuario.
    La funcionalidad incluye:
    - Iniciar la sesión.
    - Destruir la sesión actual.
    - Redirigir al usuario a la página principal.
*/
session_start();
session_destroy();
header('Location: ./')
?>