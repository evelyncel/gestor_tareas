<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header('Location: index.php');
    exit();
}

$tareas = obtener_todas_tareas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Gestor de Tareas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #34495e;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .create-task-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .create-task-btn:hover {
            background-color: #27ae60;
        }
        main {
            background-color: white;
            border-radius: 0 0 10px 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #34495e;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
            transition: background-color 0.3s;
        }
        .btn-53 {
            border: 1px solid;
            border-radius: 999px;
            display: block;
            font-weight: 900;
            padding: 1.2rem 3rem;
            position: relative;
            color: white;
            background-color: #3498db;
            text-transform: uppercase;
            overflow: hidden;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-53 .original {
            background: #fff;
            color: #000;
            display: grid;
            inset: 0;
            place-content: center;
            position: absolute;
            transition: transform 0.2s cubic-bezier(0.87, 0, 0.13, 1);
        }
        .btn-53:hover .original {
            transform: translateY(100%);
        }
        .btn-53 .letters {
            display: inline-flex;
        }
        .btn-53 span {
            opacity: 0;
            transform: translateY(-15px);
            transition: transform 0.2s cubic-bezier(0.87, 0, 0.13, 1), opacity 0.2s;
        }
        .btn-53 span:nth-child(2n) {
            transform: translateY(15px);
        }
        .btn-53:hover span {
            opacity: 1;
            transform: translateY(0);
        }
        .btn-53:hover span:nth-child(2) {
            transition-delay: 0.1s;
        }
        .btn-53:hover span:nth-child(3) {
            transition-delay: 0.2s;
        }
        .btn-53:hover span:nth-child(4) {
            transition-delay: 0.3s;
        }
        .btn-53:hover span:nth-child(5) {
            transition-delay: 0.4s;
        }
        .btn-53:hover span:nth-child(6) {
            transition-delay: 0.5s;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-user-cog"></i> Administrador</h1>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
        </header>
        <main>
            <a href="crear_tarea.php" class="create-task-btn"><i class="fas fa-plus"></i> Crear Nueva Tarea</a>
            <h2><i class="fas fa-list"></i> Todas las Tareas</h2>
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-file-alt"></i> Título</th>
                        <th><i class="far fa-calendar-plus"></i> Fecha de creación</th>
                        <th><i class="far fa-calendar-alt"></i> Fecha de entrega</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tareas as $tarea): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tarea['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($tarea['fecha_creacion']); ?></td>
                            <td><?php echo htmlspecialchars($tarea['fecha_entrega']); ?></td>
                            <td>
                                <a href="calificar_tarea.php?id=<?php echo $tarea['id']; ?>" class="btn-53">
                                    <div class="original">Calificar</div>
                                    <div class="letters">
                                        <span>C</span><span>A</span><span>L</span><span>I</span><span>F</span><span>I</span><span>C</span><span>A</span><span>R</span>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
