<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];
    
    $usuario = login($email, $contrasena);
    
    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['tipo_usuario'] = $usuario['tipo'];
        
        if ($usuario['tipo'] == 'estudiante') {
            header('Location: estudiante_dashboard.php');
        } else {
            header('Location: admin_dashboard.php');
        }
        exit();
    } else {
        $error = "Email o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Gestor de Tareas</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-container {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group input {
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-login {
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: scale(1.05);
        }

        .text-center a {
            position: relative;
            transition: all 0.3s ease;
        }

        .text-center a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #007bff;
            visibility: hidden;
            transform: scaleX(0);
            transition: all 0.3s ease-in-out;
        }

        .text-center a:hover::after {
            visibility: visible;
            transform: scaleX(1);
        }
    </style>
</head>
<body class="login-page">
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </div>
        </form>
        <div class="text-center" style="margin-top: 15px;">
        <p>¿No tienes una cuenta?<a href="registro.php">Regístrate aquí</a>
        </div>
    </div>
</body>
</html>