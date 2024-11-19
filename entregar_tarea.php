<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'estudiante') {
    header('Location: index.php');
    exit();
}

$id_tarea = $_GET['id'];
$tarea = obtener_tarea($id_tarea);

if (!$tarea) {
    header('Location: estudiante_dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['archivo_adjunto']) && $_FILES['archivo_adjunto']['error'] == 0) {
        $archivo_adjunto = subir_archivo($_FILES['archivo_adjunto']);
        if ($archivo_adjunto) {
            if (entregar_tarea($id_tarea, $_SESSION['usuario_id'], $archivo_adjunto)) {
                $success = "Tarea entregada con éxito.";
            } else {
                $error = "Error al entregar la tarea.";
            }
        } else {
            $error = "Error al subir el archivo.";
        }
    } else {
        $error = "Por favor, selecciona un archivo para entregar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entregar Tarea - Gestor de Tareas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .float { animation: float 3s ease-in-out infinite; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {"50":"#eff6ff","100":"#dbeafe","200":"#bfdbfe","300":"#93c5fd","400":"#60a5fa","500":"#3b82f6","600":"#2563eb","700":"#1d4ed8","800":"#1e40af","900":"#1e3a8a","950":"#172554"}
                    },
                    fontFamily: {
                        'body': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-body">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Entregar Tarea
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    <?php echo htmlspecialchars($tarea['titulo']); ?>
                </p>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Éxito!</strong>
                    <span class="block sm:inline"><?php echo $success; ?></span>
                </div>
            <?php else: ?>
                <form class="mt-8 space-y-6" action="" method="POST" enctype="multipart/form-data">
                    <div class="rounded-md shadow-sm -space-y-px">
                        <div class="mb-4">
                            <label for="archivo_adjunto" class="sr-only">Archivo adjunto</label>
                            <div class="relative border-2 border-gray-300 border-dashed rounded-md p-6 transition-all duration-300 ease-in-out hover:border-primary-500 focus-within:border-primary-500">
                                <input id="archivo_adjunto" name="archivo_adjunto" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 float" fill="none" stroke="currentColor" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-600">
                                        <span class="font-medium text-primary-600 hover:text-primary-500">
                                            Selecciona un archivo
                                        </span>
                                        o arrastra y suelta
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        PDF, DOC, DOCX hasta 10MB
                                    </p>
                                </div>
                                <p class="text-xs text-gray-500 mt-2" id="file-name"></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-primary-500 group-hover:text-primary-400 transition-all duration-300 ease-in-out" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Entregar Tarea
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            Información de la tarea
                        </span>
                    </div>
                </div>

                <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Detalles
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                        <dl class="sm:divide-y sm:divide-gray-200">
                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Fecha límite
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <?php echo date('d/m/Y H:i', strtotime($tarea['fecha_entrega'])); ?>
                                </dd>
                            </div>
                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Fecha actual
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" id="current-time">
                                    <!-- JavaScript will update this -->
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center mt-6">
                <a href="estudiante_dashboard.php" class="text-sm font-medium text-primary-600 hover:text-primary-500 transition-all duration-300 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i>Volver atras
                </a>
            </div>
        </div>
    </div>

    <script>
        function updateCurrentTime() {
            const now = new Date();
            const options = { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: false
            };
            const formattedDate = now.toLocaleString('es-ES', options);
            document.getElementById('current-time').textContent = formattedDate;
        }

        setInterval(updateCurrentTime, 1000);
        updateCurrentTime();

        const fileInput = document.getElementById('archivo_adjunto');
        const fileNameDisplay = document.getElementById('file-name');

        fileInput.addEventListener('change', (event) => {
            const fileName = event.target.files[0].name;
            fileNameDisplay.textContent = `Archivo seleccionado: ${fileName}`;
        });
    </script>
</body>
</html>