<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header('Location: index.php');
    exit();
}

$id_tarea = $_GET['id'];
$entregas = obtener_entregas_tarea($id_tarea);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['calificaciones'] as $id_entrega => $calificacion) {
        calificar_entrega($id_entrega, $calificacion);
    }
    header('Location: admin_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar Tareas - Gestor de Tareas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Calificar Entregas</h1>
        <form action="" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Fecha de entrega</th>
                        <th>Archivo</th>
                        <th>Calificación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entregas as $entrega): ?>
                        <tr>
                            <td><?php echo $entrega['nombre_estudiante']; ?></td>
                            <td><?php echo $entrega['fecha_entrega']; ?></td>
                            <td>
                                <?php if ($entrega['archivo_adjunto']): ?>
                                    <a href="<?php echo $entrega['archivo_adjunto']; ?>" target="_blank">Ver archivo</a>
                                <?php else: ?>
                                    Sin archivo
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="number" name="calificaciones[<?php echo $entrega['id']; ?>]" min="0" max="10" step="0.1" value="<?php echo $entrega['calificacion']; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="form-group">
                <button type="submit">Guardar Calificaciones</button>
            </div>
        </form>
        <a href="admin_dashboard.php">Volver atras</a>
    </div>
</body>
</html>