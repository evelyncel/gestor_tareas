<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];
    $tipo = $_POST['tipo'];
    
    if (registrar_usuario($nombre, $email, $contrasena, $tipo)) {
        header('Location: index.php');
        exit();
    } else {
        $error = "Error al registrar el usuario";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Gestor de Tareas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Registro</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de usuario:</label>
                <select id="tipo" name="tipo" required>
                    <option value="estudiante">Estudiante</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Registrarse</button>
            </div>
        </form>
        <div class="text-center" style="margin-top: 15px;">
        <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a></p>
        </div>
    </div>
    <style>
        .text-center {
            text-align: center;
        }
    </style>
</body>
</html>