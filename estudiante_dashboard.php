<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'estudiante') {
    header('Location: index.php');
    exit();
}

$tareas = obtener_tareas_estudiante($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Estudiante - Gestor de Tareas</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --background-color: #f4f6f9;
            --text-color: #333;
            --card-background: #fff;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .navbar {
            background-color: var(--primary-color);
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
        }

        .user-menu {
            display: flex;
            align-items: center;
        }

        .user-menu a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: var(--transition);
        }

        .user-menu a:hover {
            opacity: 0.8;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .task-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .task-card {
            background-color: var(--card-background);
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .task-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .task-info {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .task-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-top: 10px;
        }

        .status-pending {
            background-color: #f39c12;
            color: #fff;
        }

        .status-submitted {
            background-color: var(--secondary-color);
            color: #fff;
        }

        .task-actions {
            margin-top: 15px;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: #fff;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            color: #fff;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="#" class="logo">Estudiante</a>
            <div class="user-menu">
                <a href="#"><i class="fas fa-user"></i> Mi Perfil</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Mis Tareas</h1>
        <div class="task-grid">
            <?php foreach ($tareas as $tarea): ?>
                <div class="task-card">
                    <h2 class="task-title"><?php echo htmlspecialchars($tarea['titulo']); ?></h2>
                    <p class="task-info"><i class="far fa-calendar-alt"></i> Fecha límite: <?php echo htmlspecialchars($tarea['fecha_entrega']); ?></p>
                    <p class="task-info"><i class="fas fa-graduation-cap"></i> Calificación: <?php echo $tarea['calificacion'] ? htmlspecialchars($tarea['calificacion']) : 'Sin calificar'; ?></p>
                    <span class="task-status <?php echo $tarea['estado'] == 'Entregada' ? 'status-submitted' : 'status-pending'; ?>">
                        <?php echo htmlspecialchars($tarea['estado']); ?>
                    </span>
                    <div class="task-actions">
                        <a href="ver_tarea.php?id=<?php echo $tarea['id']; ?>" class="btn btn-primary">Ver detalles</a>
                        <?php if ($tarea['estado'] == 'Pendiente'): ?>
                            <a href="entregar_tarea.php?id=<?php echo $tarea['id']; ?>" class="btn btn-secondary">Entregar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>