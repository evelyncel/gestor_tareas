<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_entrega = $_POST['fecha_entrega'];
    
    if (empty($titulo) || empty($descripcion) || empty($fecha_entrega)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        $archivo_adjunto = null;
        if (isset($_FILES['archivo_adjunto']) && $_FILES['archivo_adjunto']['error'] == 0) {
            $archivo_adjunto = subir_archivo($_FILES['archivo_adjunto']);
        }
        
        if (crear_tarea($titulo, $descripcion, $fecha_entrega, $archivo_adjunto)) {
            header('Location: admin_dashboard.php');
            exit();
        } else {
            $error = "Error al crear la tarea";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tarea - Gestor de Tareas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Crear Nueva Tarea</h1>
            <a href="admin_dashboard.php" class="btn-back">Volver atras</a>
        </header>
        <main>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required><?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="fecha_entrega">Fecha de entrega:</label>
                    <input type="datetime-local" id="fecha_entrega" name="fecha_entrega" required value="<?php echo isset($_POST['fecha_entrega']) ? htmlspecialchars($_POST['fecha_entrega']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="archivo_adjunto">Archivo adjunto:</label>
                    <input type="file" id="archivo_adjunto" name="archivo_adjunto">
                </div>
                <div class="form-group">
                    <button type="submit">Crear Tarea</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>