<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'estudiante') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: estudiante_dashboard.php');
    exit();
}

$id_tarea = $_GET['id'];
$tarea = obtener_tarea($id_tarea);

if (!$tarea) {
    header('Location: estudiante_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descripción de la Tarea - Gestor de Tareas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos CSS internos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f5f7;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 800px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 1.8em;
            color: #4a90e2;
        }

        .btn-back {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
            font-size: 1em;
        }

        .tarea-header h2 {
            font-size: 1.6em;
            color: #333;
            margin-bottom: 10px;
        }

        /* Nueva tabla estilizada */
        .tarea-status {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .tarea-status div {
            background-color: #f4f5f7;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .tarea-status div h4 {
            font-size: 1em;
            color: #333;
            margin-bottom: 5px;
        }

        .tarea-status div p {
            font-size: 1.1em;
            font-weight: 500;
        }

        .fecha {
            background-color: #d8e7ff;
            color: #3367d6;
        }

        .estado {
            background-color: #ffdede;
            color: #d63636;
        }

        .calificacion {
            background-color: #d9f7d4;
            color: #29a329;
        }

        .tarea-descripcion,
        .tarea-archivo,
        .tarea-entrega {
            margin-bottom: 20px;
        }

        h3 {
            font-size: 1.2em;
            color: #4a90e2;
            margin-bottom: 8px;
        }

        .btn-action,
        .btn-primary {
            display: inline-block;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .btn-action {
            background-color: #f4f5f7;
            color: #4a90e2;
            border: 1px solid #4a90e2;
        }

        .btn-action:hover {
            background-color: #e0e0e0;
            color: #4a90e2;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: #fff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #357abd;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            .tarea-header h2 {
                font-size: 1.4em;
            }

            header h1, h3 {
                font-size: 1em;
            }

            .tarea-status {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Descripción de la Tarea</h1>
            <a href="estudiante_dashboard.php" class="btn-back">&larr; Volver atrás</a>
        </header>
        <main>
            <div class="tarea-header">
                <h2><?php echo htmlspecialchars($tarea['titulo']); ?></h2>
                <div class="tarea-status">
                    <div class="fecha">
                        <h4>Fecha de entrega</h4>
                        <p><?php echo htmlspecialchars($tarea['fecha_entrega']); ?></p>
                    </div>
                    <div class="estado">
                        <h4>Estado</h4>
                        <p><?php echo htmlspecialchars($tarea['estado']); ?></p>
                    </div>
                    <div class="calificacion">
                        <h4>Calificación</h4>
                        <p><?php echo $tarea['calificacion'] ? htmlspecialchars($tarea['calificacion']) : 'Sin calificar'; ?></p>
                    </div>
                </div>
            </div>
            <div class="tarea-descripcion">
                <h3>Descripción:</h3>
                <p><?php echo nl2br(htmlspecialchars($tarea['descripcion'])); ?></p>
            </div>
            <?php if ($tarea['archivo_adjunto']): ?>
                <div class="tarea-archivo">
                    <h3>Archivo adjunto de la tarea:</h3>
                    <a href="<?php echo htmlspecialchars($tarea['archivo_adjunto']); ?>" target="_blank" class="btn-action">Ver archivo de la tarea</a>
                </div>
            <?php endif; ?>
            <?php if ($tarea['entrega_archivo']): ?>
                <div class="tarea-entrega">
                    <h3>Tu entrega:</h3>
                    <p><strong>Fecha de entrega:</strong> <?php echo htmlspecialchars($tarea['fecha_entregada']); ?></p>
                    <a href="<?php echo htmlspecialchars($tarea['entrega_archivo']); ?>" target="_blank" class="btn-action">Ver tu archivo entregado</a>
                </div>
            <?php endif; ?>
            <?php if ($tarea['estado'] == 'Pendiente'): ?>
                <div class="tarea-acciones">
                    <a href="entregar_tarea.php?id=<?php echo $tarea['id']; ?>" class="btn-primary">Entregar Tarea</a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
