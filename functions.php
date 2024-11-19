<?php
require_once 'config.php';

function login($email, $contrasena) {
    global $conn;
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT id, nombre, tipo, contrasena FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);
        if (password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario;
        }
    }
    return false;
}
function registrar_usuario($nombre, $email, $contrasena, $tipo) {
    global $conn;
    $nombre = mysqli_real_escape_string($conn, $nombre);
    $email = mysqli_real_escape_string($conn, $email);
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $tipo = mysqli_real_escape_string($conn, $tipo);
    
    $query = "INSERT INTO usuarios (nombre, email, contrasena, tipo) VALUES ('$nombre', '$email', '$contrasena_hash', '$tipo')";
    return mysqli_query($conn, $query);
}

function obtener_tareas_estudiante($id_estudiante) {
    global $conn;
    $id_estudiante = mysqli_real_escape_string($conn, $id_estudiante);
    
    $query = "SELECT t.*, 
              e.id as entrega_id,
              e.fecha_entrega as fecha_entregada,
              e.archivo_adjunto as entrega_archivo,
              e.calificacion
              FROM tareas t
              LEFT JOIN entregas e ON t.id = e.id_tarea AND e.id_estudiante = $id_estudiante
              ORDER BY t.fecha_entrega ASC";
              
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error en la consulta: " . mysqli_error($conn));
    }
    
    $tareas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['estado'] = ($row['entrega_id']) ? 'Entregada' : 'Pendiente';
        $tareas[] = $row;
    }
    return $tareas;
}



function obtener_todas_tareas() {
    global $conn;
    $query = "SELECT * FROM tareas ORDER BY fecha_creacion DESC";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function crear_tarea($titulo, $descripcion, $fecha_entrega, $archivo_adjunto) {
    global $conn;
    $titulo = mysqli_real_escape_string($conn, $titulo);
    $descripcion = mysqli_real_escape_string($conn, $descripcion);
    $fecha_entrega = mysqli_real_escape_string($conn, $fecha_entrega);
    $archivo_adjunto = $archivo_adjunto ? mysqli_real_escape_string($conn, $archivo_adjunto) : null;
    
    $query = "INSERT INTO tareas (titulo, descripcion, fecha_entrega, archivo_adjunto) 
              VALUES ('$titulo', '$descripcion', '$fecha_entrega', " . ($archivo_adjunto ? "'$archivo_adjunto'" : "NULL") . ")";
    return mysqli_query($conn, $query);
}

function obtener_tarea($id_tarea, $id_estudiante = null) {
    global $conn;
    $id_tarea = mysqli_real_escape_string($conn, $id_tarea);
    
    $query = "SELECT t.*, 
              e.id as entrega_id,
              e.fecha_entrega,
              e.archivo_adjunto as entrega_archivo,
              e.calificacion
              FROM tareas t
              LEFT JOIN entregas e ON t.id = e.id_tarea";
    
    if ($id_estudiante) {
        $id_estudiante = mysqli_real_escape_string($conn, $id_estudiante);
        $query .= " AND e.id_estudiante = $id_estudiante";
    }
    
    $query .= " WHERE t.id = $id_tarea";
    
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error en la consulta: " . mysqli_error($conn));
    }
    
    if (mysqli_num_rows($result) > 0) {
        $tarea = mysqli_fetch_assoc($result);
        $tarea['estado'] = ($tarea['entrega_id']) ? 'Entregada' : 'Pendiente';
        $tarea['fecha_entregada'] = $tarea['fecha_entrega']; // Asegurarse de que la fecha de entrega se pase correctamente
        return $tarea;
    }
    return null;
}



function obtener_entrega($id_tarea, $id_estudiante) {
    global $conn;
    $id_tarea = mysqli_real_escape_string($conn, $id_tarea);
    $id_estudiante = mysqli_real_escape_string($conn, $id_estudiante);
    $query = "SELECT * FROM entregas WHERE id_tarea = $id_tarea AND id_estudiante = $id_estudiante";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function entregar_tarea($id_tarea, $id_estudiante, $archivo_adjunto) {
    global $conn;
    $id_tarea = mysqli_real_escape_string($conn, $id_tarea);
    $id_estudiante = mysqli_real_escape_string($conn, $id_estudiante);
    $archivo_adjunto = mysqli_real_escape_string($conn, $archivo_adjunto);

    // Verificar si ya existe una entrega para esta tarea y este estudiante
    $check_query = "SELECT id FROM entregas 
                   WHERE id_tarea = $id_tarea AND id_estudiante = $id_estudiante";
    $check_result = mysqli_query($conn, $check_query);
    
    if (!$check_result) {
        die("Error en la verificación: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($check_result) > 0) {
        // Ya existe una entrega, actualizar
        $query = "UPDATE entregas 
                 SET archivo_adjunto = '$archivo_adjunto', 
                     fecha_entrega = NOW() 
                 WHERE id_tarea = $id_tarea AND id_estudiante = $id_estudiante";
    } else {
        // No existe entrega, insertar nueva
        $query = "INSERT INTO entregas (id_tarea, id_estudiante, archivo_adjunto, fecha_entrega) 
                 VALUES ($id_tarea, $id_estudiante, '$archivo_adjunto', NOW())";
    }
    
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error al entregar la tarea: " . mysqli_error($conn));
    }
    return $result;
}


function obtener_entregas_tarea($id_tarea) {
    global $conn;
    $id_tarea = mysqli_real_escape_string($conn, $id_tarea);
    $query = "SELECT e.*, u.nombre AS nombre_estudiante
              FROM entregas e
              JOIN usuarios u ON e.id_estudiante = u.id
              WHERE e.id_tarea = $id_tarea";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function calificar_entrega($id_entrega, $calificacion) {
    global $conn;
    $id_entrega = mysqli_real_escape_string($conn, $id_entrega);
    $calificacion = mysqli_real_escape_string($conn, $calificacion);
    
    $query = "UPDATE entregas SET calificacion = $calificacion WHERE id = $id_entrega";
    return mysqli_query($conn, $query);
}

function subir_archivo($archivo) {
    $directorio_destino = "uploads/";
    $nombre_archivo = basename($archivo["name"]);
    $ruta_archivo = $directorio_destino . $nombre_archivo;
    
    if (move_uploaded_file($archivo["tmp_name"], $ruta_archivo)) {
        return $ruta_archivo;
    } else {
        return false;
    }
}
