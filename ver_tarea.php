<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$id_tarea = $_GET['id'];
$tarea = obtener_tarea($id_tarea);

if (!$tarea) {
    header('Location: ' . ($_SESSION['tipo_usuario'] == 'estudiante' ? 'estudiante_dashboard.php' : 'admin_dashboard.php'));
    exit();
}

$entrega = obtener_entrega($id_tarea, $_SESSION['usuario_id']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Tarea - <?php echo htmlspecialchars($tarea['titulo']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f39c12;
            --background-color: rgba(255, 255, 255, 0.9);
            --text-color: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background:url('images/imagen.jpg') no-repeat center center fixed;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background-color: var(--background-color);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            width: 100%;
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
            font-size: 2.5rem;
        }

        .task-info {
            margin-bottom: 30px;
        }

        .task-info p {
            margin-bottom: 10px;
        }

        .task-info strong {
            color: var(--primary-color);
        }

        .timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: var(--primary-color);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .timeline-item:last-child {
            border-bottom: none;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -36px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--primary-color);
            border: 2px solid #fff;
        }

        .timeline-item h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #3a7bd5;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--primary-color);
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($tarea['titulo']); ?></h1>

        <div class="task-info">
            <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($tarea['descripcion'])); ?></p>
            <p><strong>Fecha límite de entrega:</strong> <?php echo date('d/m/Y H:i', strtotime($tarea['fecha_entrega'])); ?></p>
        </div>

        <div class="timeline">
            <div class="timeline-item">
                <h3>Tarea Asignada</h3>
                <p><i class="fas fa-calendar-alt"></i> Fecha de asignación: <?php echo date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])); ?></p>
            </div>

            <?php if ($entrega): ?>
                <div class="timeline-item">
                    <h3>Tarea Entregada</h3>
                    <p><i class="fas fa-clock"></i> Fecha de entrega: <?php echo date('d/m/Y H:i', strtotime($entrega['fecha_entrega'])); ?></p>
                    <?php if ($entrega['archivo_adjunto']): ?>
                        <p><i class="fas fa-file-alt"></i> Archivo entregado: 
                            <a href="<?php echo htmlspecialchars($entrega['archivo_adjunto']); ?>" target="_blank">Ver archivo</a>
                        </p>
                    <?php endif; ?>
                </div>

                <?php if (isset($entrega['calificacion'])): ?>
                    <div class="timeline-item">
                        <h3>Tarea Calificada</h3>
                        <p><i class="fas fa-star"></i> Calificación: <?php echo htmlspecialchars($entrega['calificacion']); ?></p>
                    </div>
                <?php else: ?>
                    <div class="timeline-item">
                        <h3>Pendiente de Calificación</h3>
                        <p><i class="fas fa-hourglass-half"></i> El profesor aún no ha calificado esta tarea.</p>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="timeline-item">
                    <h3>Tarea Pendiente</h3>
                    <p><i class="fas fa-exclamation-circle"></i> La tarea aún no ha sido entregada.</p>
                    <?php if ($_SESSION['tipo_usuario'] == 'estudiante'): ?>
                        <a href="entregar_tarea.php?id=<?php echo $tarea['id']; ?>" class="btn">Entregar Tarea</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <a href="<?php echo ($_SESSION['tipo_usuario'] == 'estudiante' ? 'estudiante_dashboard.php' : 'admin_dashboard.php'); ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> Volver atras
        </a>
    </div>
</body>
</html>