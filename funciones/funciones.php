<?php
    include_once __DIR__ . "/../config/config.php";
    
    function llegirNoticies($mysqli) {
        $sql = "SELECT 
                    n.*, 
                    u.nombre AS usuario_nombre,
                    u.apellido AS usuario_apellido
                FROM noticias n
                INNER JOIN usuarios u ON n.usuario_id = u.usuario_id
                ORDER BY n.fecha_publicacion DESC";

        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("ERROR EN PREPARE: " . $mysqli->error . "<br>SQL: " . $sql);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirUltimesNoticies($mysqli) {
        $sql = "SELECT * FROM noticias ORDER BY fecha_publicacion DESC LIMIT 3";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function getNoticiaById($id, $mysqli) {
        $sql = "SELECT 
                    n.*, 
                    u.nombre AS usuario_nombre,
                    u.apellido AS usuario_apellido
                FROM noticias n
                INNER JOIN usuarios u ON n.usuario_id = u.usuario_id
                WHERE noticia_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    function getTestimonios($mysqli) {
        $sql = "SELECT u.foto, u.nombre, u.apellido, t.contenido AS testimonio, t.puntuacion, t.cargo
            FROM testimonios t
            INNER JOIN usuarios u ON u.usuario_id = t.usuario_id
            WHERE t.es_aprobado = 1
            ORDER BY t.fecha_testimonio DESC";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function getPortfolio($mysqli) {
        $sql = "SELECT p.*, td.tipo 
            FROM portfolio p
            INNER JOIN tipoDeporte td ON p.id_tipoDeporte = td.id_tipoDeporte
            ORDER BY p.fecha_proyecto DESC";
            
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function getComentariosByNoticia($noticia_id, $mysqli) {
        $sql = "SELECT 
                    c.comentario_id,
                    c.contenido,
                    c.fecha_comentario,
                    c.parent_comentario_id,
                    u.usuario_id,
                    u.nombre,
                    u.apellido,
                    u.foto
                FROM comentarios c
                LEFT JOIN usuarios u ON c.usuario_id = u.usuario_id
                WHERE c.noticia_id = ?
                ORDER BY c.fecha_comentario ASC";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $noticia_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function getRespuestasComentario($parent_id, $mysqli) {
        $sql = "SELECT 
                    c.comentario_id,
                    c.contenido,
                    c.fecha_comentario,
                    c.parent_comentario_id,
                    u.usuario_id,
                    u.nombre,
                    u.apellido,
                    u.foto
                FROM comentarios c
                LEFT JOIN usuarios u ON c.usuario_id = u.usuario_id
                WHERE c.parent_comentario_id = ?
                ORDER BY c.fecha_comentario ASC";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function getFaqs($mysqli) {
        $sql = "SELECT * FROM faqs";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegitComentarisSencers($mysqli) {
        $sql = "SELECT * FROM comentarios";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirDatalleResercaSencer($mysqli) {
        $sql = "SELECT * FROM detalle_reserva";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirentradesSencer($mysqli) {
        $sql = "SELECT * FROM entradas";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirEventosSencer($mysqli) {
        $sql = "SELECT * FROM eventos";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirFaqsSencer($mysqli) {
        $sql = "SELECT * FROM faqs";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirNoticiasSencer($mysqli) {
        $sql = "SELECT * FROM noticias";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirPortfolioSencer($mysqli) {
        $sql = "SELECT * FROM portfolio";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirReservasSencer($mysqli) {
        $sql = "SELECT * FROM reservas";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirRolesSencer($mysqli) {
        $sql = "SELECT * FROM roles";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirTestimoniosSencer($mysqli) {
        $sql = "SELECT * FROM testimonios";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirTipoDeporteSencer($mysqli) {
        $sql = "SELECT * FROM tipoDeporte";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirUsuarisSencer($mysqli) {
        $sql = "SELECT * FROM usuarios";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function llegirZonasSencer($mysqli) {
        $sql = "SELECT * FROM zonas";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function contarTicketsPerPersona($mysqli, $id_usuario) {
        $sql = "SELECT COUNT(*) AS total_tickets FROM reservas where usuario_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total_tickets'];
    }

    function contarTicketsTerminados($mysqli, $id_usuario) {
        $sql = "SELECT COUNT(*) AS total_tickets_terminados 
                FROM reservas r
                INNER JOIN entradas e ON r.entrada_id = e.entrada_id
                Inner JOIN eventos ev ON e.evento_id = ev.evento_id
                WHERE r.usuario_id = ? AND ev.es_activo = 0";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total_tickets_terminados'];
    }

    function contarTicketsProximos($mysqli, $id_usuario) {
        $sql = "SELECT COUNT(*) AS total_tickets_proximos 
                FROM reservas r
                INNER JOIN entradas e ON r.entrada_id = e.entrada_id
                Inner JOIN eventos ev ON e.evento_id = ev.evento_id
                WHERE r.usuario_id = ? AND ev.es_activo = 1";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total_tickets_proximos'];
    }

    function getTicketsProximos($mysqli, $id_usuario){
        $sql = "SELECT r.*, 
                ev.nombre_evento AS evento_nombre, 
                DATE(ev.fecha_hora) AS evento_fecha,
                TIME(ev.fecha_hora) AS evento_hora,
                ev.ubicacion,
                ev.es_activo,
                td.tipo AS deporte,
                FROM reservas r
                INNER JOIN entradas e ON r.entrada_id = e.entrada_id
                INNER JOIN eventos ev ON e.evento_id = ev.evento_id
                LEFT JOIN tipoDeporte td ON ev.id_tipoDeporte = td.id_tipoDeporte
                WHERE r.usuario_id = ? AND ev.es_activo = 1
                ORDER BY ev.fecha_hora ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function getTicketsHistorico($mysqli, $id_usuario){
        $sql = "SELECT r.*, 
                ev.nombre_evento AS evento_nombre, 
                DATE(ev.fecha_hora) AS evento_fecha,
                TIME(ev.fecha_hora) AS evento_hora,
                ev.ubicacion,
                ev.es_activo,
                td.tipo AS deporte,
                FROM reservas r
                INNER JOIN entradas e ON r.entrada_id = e.entrada_id
                INNER JOIN eventos ev ON e.evento_id = ev.evento_id
                LEFT JOIN tipoDeporte td ON ev.id_tipoDeporte = td.id_tipoDeporte
                WHERE r.usuario_id = ?
                ORDER BY ev.fecha_hora ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function getTicketsFinalizados($mysqli, $id_usuario){
        $sql = "SELECT r.*, 
                ev.nombre_evento AS evento_nombre, 
                DATE(ev.fecha_hora) AS evento_fecha,
                TIME(ev.fecha_hora) AS evento_hora,
                ev.ubicacion,
                ev.es_activo,
                td.tipo AS deporte,
                FROM reservas r
                INNER JOIN entradas e ON r.entrada_id = e.entrada_id
                INNER JOIN eventos ev ON e.evento_id = ev.evento_id
                LEFT JOIN tipoDeporte td ON ev.id_tipoDeporte = td.id_tipoDeporte
                WHERE r.usuario_id = ? AND ev.es_activo = 0
                ORDER BY ev.fecha_hora ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // =====================================================
    // FUNCIONES CRUD - COMENTARIOS
    // =====================================================
    function añadirComentario($mysqli, $noticia_id, $usuario_id, $contenido, $parent_comentario_id = null) {
        $sql = "INSERT INTO comentarios (noticia_id, usuario_id, contenido, parent_comentario_id) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $parent = $parent_comentario_id ?: null;
        $stmt->bind_param("iisi", $noticia_id, $usuario_id, $contenido, $parent);
        return $stmt->execute();
    }

    function editarComentario($mysqli, $comentario_id, $noticia_id, $usuario_id, $contenido, $parent_comentario_id = null) {
        $sql = "UPDATE comentarios SET noticia_id = ?, usuario_id = ?, contenido = ?, parent_comentario_id = ? WHERE comentario_id = ?";
        $stmt = $mysqli->prepare($sql);
        $parent = $parent_comentario_id ?: null;
        $stmt->bind_param("iisii", $noticia_id, $usuario_id, $contenido, $parent, $comentario_id);
        return $stmt->execute();
    }

    function eliminarComentario($mysqli, $comentario_id) {
        $sql = "DELETE FROM comentarios WHERE comentario_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $comentario_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - DETALLE RESERVA
    // =====================================================
    function añadirDetalleReserva($mysqli, $reserva_id, $entrada_id, $precio_unidad_auditoria) {
        $sql = "INSERT INTO detalle_reserva (reserva_id, entrada_id, precio_unidad_auditoria) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iid", $reserva_id, $entrada_id, $precio_unidad_auditoria);
        return $stmt->execute();
    }

    function editarDetalleReserva($mysqli, $detalle_id, $reserva_id, $entrada_id, $precio_unidad_auditoria) {
        $sql = "UPDATE detalle_reserva SET reserva_id = ?, entrada_id = ?, precio_unidad_auditoria = ? WHERE detalle_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iidi", $reserva_id, $entrada_id, $precio_unidad_auditoria, $detalle_id);
        return $stmt->execute();
    }

    function eliminarDetalleReserva($mysqli, $detalle_id) {
        $sql = "DELETE FROM detalle_reserva WHERE detalle_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $detalle_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - ENTRADAS
    // =====================================================
    function añadirEntrada($mysqli, $evento_id, $zona_id, $precio, $stock_disponible) {
        $sql = "INSERT INTO entradas (evento_id, zona_id, precio, stock_disponible) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iidi", $evento_id, $zona_id, $precio, $stock_disponible);
        return $stmt->execute();
    }

    function editarEntrada($mysqli, $entrada_id, $evento_id, $zona_id, $precio, $stock_disponible) {
        $sql = "UPDATE entradas SET evento_id = ?, zona_id = ?, precio = ?, stock_disponible = ? WHERE entrada_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iidii", $evento_id, $zona_id, $precio, $stock_disponible, $entrada_id);
        return $stmt->execute();
    }

    function eliminarEntrada($mysqli, $entrada_id) {
        $sql = "DELETE FROM entradas WHERE entrada_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $entrada_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - EVENTOS
    // =====================================================
    function añadirEvento($mysqli, $nombre_evento, $fecha_hora, $ubicacion, $deporte, $descripcion, $imagen_url, $es_Activo) {
        $sql = "INSERT INTO eventos (nombre_evento, fecha_hora, ubicacion, deporte, descripcion, imagen_url, es_Activo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssssi", $nombre_evento, $fecha_hora, $ubicacion, $deporte, $descripcion, $imagen_url, $es_Activo);
        return $stmt->execute();
    }

    function editarEvento($mysqli, $evento_id, $nombre_evento, $fecha_hora, $ubicacion, $deporte, $descripcion, $imagen_url, $es_Activo) {
        $sql = "UPDATE eventos SET nombre_evento = ?, fecha_hora = ?, ubicacion = ?, deporte = ?, descripcion = ?, imagen_url = ?, es_Activo = ? WHERE evento_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssssii", $nombre_evento, $fecha_hora, $ubicacion, $deporte, $descripcion, $imagen_url, $es_Activo, $evento_id);
        return $stmt->execute();
    }

    function eliminarEvento($mysqli, $evento_id) {
        $sql = "DELETE FROM eventos WHERE evento_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $evento_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - FAQS
    // =====================================================
    function añadirFaq($mysqli, $pregunta, $respuesta) {
        $sql = "INSERT INTO faqs (pregunta, respuesta) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $pregunta, $respuesta);
        return $stmt->execute();
    }

    function editarFaq($mysqli, $faq_id, $pregunta, $respuesta) {
        $sql = "UPDATE faqs SET pregunta = ?, respuesta = ? WHERE faq_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssi", $pregunta, $respuesta, $faq_id);
        return $stmt->execute();
    }

    function eliminarFaq($mysqli, $faq_id) {
        $sql = "DELETE FROM faqs WHERE faq_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $faq_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - NOTICIAS
    // =====================================================
    function añadirNoticia($mysqli, $usuario_id, $titulo, $subtitulo, $contenido, $imagen_url) {
        $sql = "INSERT INTO noticias (usuario_id, titulo, subtitulo, contenido, imagen_url) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("issss", $usuario_id, $titulo, $subtitulo, $contenido, $imagen_url);
        return $stmt->execute();
    }

    function editarNoticia($mysqli, $noticia_id, $usuario_id, $titulo, $subtitulo, $contenido, $imagen_url) {
        $sql = "UPDATE noticias SET usuario_id = ?, titulo = ?, subtitulo = ?, contenido = ?, imagen_url = ? WHERE noticia_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("issssi", $usuario_id, $titulo, $subtitulo, $contenido, $imagen_url, $noticia_id);
        return $stmt->execute();
    }

    function eliminarNoticia($mysqli, $noticia_id) {
        $sql = "DELETE FROM noticias WHERE noticia_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $noticia_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - PORTFOLIO
    // =====================================================
    function añadirPortfolio($mysqli, $titulo, $descripcion, $cliente, $fecha_proyecto, $imagen_url, $es_destacado, $id_tipoDeporte) {
        $sql = "INSERT INTO portfolio (titulo, descripcion, cliente, fecha_proyecto, imagen_url, es_destacado, id_tipoDeporte) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssii", $titulo, $descripcion, $cliente, $fecha_proyecto, $imagen_url, $es_destacado, $id_tipoDeporte);
        return $stmt->execute();
    }

    function editarPortfolio($mysqli, $portfolio_id, $titulo, $descripcion, $cliente, $fecha_proyecto, $imagen_url, $es_destacado, $id_tipoDeporte) {
        $sql = "UPDATE portfolio SET titulo = ?, descripcion = ?, cliente = ?, fecha_proyecto = ?, imagen_url = ?, es_destacado = ?, id_tipoDeporte = ? WHERE portfolio_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssiii", $titulo, $descripcion, $cliente, $fecha_proyecto, $imagen_url, $es_destacado, $id_tipoDeporte, $portfolio_id);
        return $stmt->execute();
    }

    function eliminarPortfolio($mysqli, $portfolio_id) {
        $sql = "DELETE FROM portfolio WHERE portfolio_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $portfolio_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - RESERVAS
    // =====================================================
    function añadirReserva($mysqli, $usuario_id, $total_monto, $estado) {
        $sql = "INSERT INTO reservas (usuario_id, total_monto, estado) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ids", $usuario_id, $total_monto, $estado);
        return $stmt->execute();
    }

    function editarReserva($mysqli, $reserva_id, $usuario_id, $total_monto, $estado) {
        $sql = "UPDATE reservas SET usuario_id = ?, total_monto = ?, estado = ? WHERE reserva_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("idsi", $usuario_id, $total_monto, $estado, $reserva_id);
        return $stmt->execute();
    }

    function eliminarReserva($mysqli, $reserva_id) {
        $sql = "DELETE FROM reservas WHERE reserva_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $reserva_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - ROLES
    // =====================================================
    function añadirRol($mysqli, $nombre_rol) {
        $sql = "INSERT INTO roles (nombre_rol) VALUES (?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $nombre_rol);
        return $stmt->execute();
    }

    function editarRol($mysqli, $role_id, $nombre_rol) {
        $sql = "UPDATE roles SET nombre_rol = ? WHERE role_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $nombre_rol, $role_id);
        return $stmt->execute();
    }

    function eliminarRol($mysqli, $role_id) {
        $sql = "DELETE FROM roles WHERE role_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $role_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - TESTIMONIOS
    // =====================================================
    function usuarioTieneTestimonio($mysqli, $usuario_id) {
        $sql = "SELECT COUNT(*) as total FROM testimonios WHERE usuario_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] > 0;
    }

    function añadirTestimonio($mysqli, $usuario_id, $nombre_cliente, $cargo, $contenido, $puntuacion, $es_aprobado) {
        $sql = "INSERT INTO testimonios (usuario_id, nombre_cliente, cargo, contenido, puntuacion, es_aprobado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("isssii", $usuario_id, $nombre_cliente, $cargo, $contenido, $puntuacion, $es_aprobado);
        return $stmt->execute();
    }

    function editarTestimonio($mysqli, $testimonio_id, $usuario_id, $nombre_cliente, $cargo, $contenido, $puntuacion, $es_aprobado) {
        $sql = "UPDATE testimonios SET usuario_id = ?, nombre_cliente = ?, cargo = ?, contenido = ?, puntuacion = ?, es_aprobado = ? WHERE testimonio_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("isssiiii", $usuario_id, $nombre_cliente, $cargo, $contenido, $puntuacion, $es_aprobado, $testimonio_id);
        return $stmt->execute();
    }

    function eliminarTestimonio($mysqli, $testimonio_id) {
        $sql = "DELETE FROM testimonios WHERE testimonio_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $testimonio_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - TIPO DEPORTE
    // =====================================================
    function añadirTipoDeporte($mysqli, $tipo) {
        $sql = "INSERT INTO tipoDeporte (tipo) VALUES (?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $tipo);
        return $stmt->execute();
    }

    function editarTipoDeporte($mysqli, $id_tipoDeporte, $tipo) {
        $sql = "UPDATE tipoDeporte SET tipo = ? WHERE id_tipoDeporte = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $tipo, $id_tipoDeporte);
        return $stmt->execute();
    }

    function eliminarTipoDeporte($mysqli, $id_tipoDeporte) {
        $sql = "DELETE FROM tipoDeporte WHERE id_tipoDeporte = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id_tipoDeporte);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - USUARIOS
    // =====================================================
    function añadirUsuario($mysqli, $role_id, $nombre, $apellido, $email, $contrasenya, $foto) {
        $sql = "INSERT INTO usuarios (role_id, nombre, apellido, email, contrasena_hash, foto) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $contrasena_hash = password_hash($contrasenya, PASSWORD_DEFAULT);
        $stmt->bind_param("isssss", $role_id, $nombre, $apellido, $email, $contrasena_hash, $foto);
        return $stmt->execute();
    }

    function editarUsuario($mysqli, $usuario_id, $role_id, $nombre, $apellido, $email, $contrasenya, $foto) {
        if (!empty($contrasenya)) {
            $sql = "UPDATE usuarios SET role_id = ?, nombre = ?, apellido = ?, email = ?, contrasena_hash = ?, foto = ? WHERE usuario_id = ?";
            $stmt = $mysqli->prepare($sql);
            $contrasena_hash = password_hash($contrasenya, PASSWORD_DEFAULT);
            $stmt->bind_param("isssssi", $role_id, $nombre, $apellido, $email, $contrasena_hash, $foto, $usuario_id);
        } else {
            $sql = "UPDATE usuarios SET role_id = ?, nombre = ?, apellido = ?, email = ?, foto = ? WHERE usuario_id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("issssi", $role_id, $nombre, $apellido, $email, $foto, $usuario_id);
        }
        return $stmt->execute();
    }

    function eliminarUsuario($mysqli, $usuario_id) {
        $sql = "DELETE FROM usuarios WHERE usuario_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        return $stmt->execute();
    }

    // =====================================================
    // FUNCIONES CRUD - ZONAS
    // =====================================================
    function añadirZona($mysqli, $nombre_zona) {
        $sql = "INSERT INTO zonas (nombre_zona) VALUES (?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $nombre_zona);
        return $stmt->execute();
    }

    function editarZona($mysqli, $zona_id, $nombre_zona) {
        $sql = "UPDATE zonas SET nombre_zona = ? WHERE zona_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $nombre_zona, $zona_id);
        return $stmt->execute();
    }

    function eliminarZona($mysqli, $zona_id) {
        $sql = "DELETE FROM zonas WHERE zona_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $zona_id);
        return $stmt->execute();
    }

    // =====================================================
    // PROCESADOR DE ACCIONES CRUD
    // =====================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        $resultado = false;
        $mensaje = '';

        switch ($accion) {
            // COMENTARIOS
            case 'añadirComentario':
                $parent = !empty($_POST['parent_comentario_id']) ? intval($_POST['parent_comentario_id']) : null;
                $resultado = añadirComentario($mysqli, $_POST['noticia_id'], $_POST['usuario_id'], $_POST['contenido'], $parent);
                $mensaje = $resultado ? 'Comentario añadido correctamente' : 'Error al añadir comentario';
                break;

            case 'editarComentario':
                $parent = !empty($_POST['parent_comentario_id']) ? intval($_POST['parent_comentario_id']) : null;
                $resultado = editarComentario($mysqli, $_POST['comentario_id'], $_POST['noticia_id'], $_POST['usuario_id'], $_POST['contenido'], $parent);
                $mensaje = $resultado ? 'Comentario actualizado correctamente' : 'Error al actualizar comentario';
                break;

            // DETALLE RESERVA
            case 'añadirDetalleReserva':
                $resultado = añadirDetalleReserva($mysqli, $_POST['reserva_id'], $_POST['entrada_id'], $_POST['precio_unidad_auditoria']);
                $mensaje = $resultado ? 'Detalle de reserva añadido correctamente' : 'Error al añadir detalle de reserva';
                break;

            case 'editarDetalleReserva':
                $resultado = editarDetalleReserva($mysqli, $_POST['detalle_id'], $_POST['reserva_id'], $_POST['entrada_id'], $_POST['precio_unidad_auditoria']);
                $mensaje = $resultado ? 'Detalle de reserva actualizado correctamente' : 'Error al actualizar detalle de reserva';
                break;

            // ENTRADAS
            case 'añadirEntrada':
                $resultado = añadirEntrada($mysqli, $_POST['evento_id'], $_POST['zona_id'], $_POST['precio'], $_POST['stock_disponible']);
                $mensaje = $resultado ? 'Entrada añadida correctamente' : 'Error al añadir entrada';
                break;

            case 'editarEntrada':
                $resultado = editarEntrada($mysqli, $_POST['entrada_id'], $_POST['evento_id'], $_POST['zona_id'], $_POST['precio'], $_POST['stock_disponible']);
                $mensaje = $resultado ? 'Entrada actualizada correctamente' : 'Error al actualizar entrada';
                break;

            // EVENTOS
            case 'añadirEvento':
                $resultado = añadirEvento($mysqli, $_POST['nombre_evento'], $_POST['fecha_hora'], $_POST['ubicacion'], $_POST['deporte'], $_POST['descripcion'], $_POST['imagen_url'], $_POST['es_Activo']);
                $mensaje = $resultado ? 'Evento añadido correctamente' : 'Error al añadir evento';
                break;

            case 'editarEvento':
                $resultado = editarEvento($mysqli, $_POST['evento_id'], $_POST['nombre_evento'], $_POST['fecha_hora'], $_POST['ubicacion'], $_POST['deporte'], $_POST['descripcion'], $_POST['imagen_url'], $_POST['es_Activo']);
                $mensaje = $resultado ? 'Evento actualizado correctamente' : 'Error al actualizar evento';
                break;

            // FAQS
            case 'añadirFaq':
                $resultado = añadirFaq($mysqli, $_POST['pregunta'], $_POST['respuesta']);
                $mensaje = $resultado ? 'FAQ añadida correctamente' : 'Error al añadir FAQ';
                break;

            case 'editarFaq':
                $resultado = editarFaq($mysqli, $_POST['faq_id'], $_POST['pregunta'], $_POST['respuesta']);
                $mensaje = $resultado ? 'FAQ actualizada correctamente' : 'Error al actualizar FAQ';
                break;

            // NOTICIAS
            case 'añadirNoticia':
                $resultado = añadirNoticia($mysqli, $_POST['usuario_id'], $_POST['titulo'], $_POST['subtitulo'], $_POST['contenido'], $_POST['imagen_url']);
                $mensaje = $resultado ? 'Noticia añadida correctamente' : 'Error al añadir noticia';
                break;

            case 'editarNoticia':
                $resultado = editarNoticia($mysqli, $_POST['noticia_id'], $_POST['usuario_id'], $_POST['titulo'], $_POST['subtitulo'], $_POST['contenido'], $_POST['imagen_url']);
                $mensaje = $resultado ? 'Noticia actualizada correctamente' : 'Error al actualizar noticia';
                break;

            // PORTFOLIO
            case 'añadirPortfolio':
                $resultado = añadirPortfolio($mysqli, $_POST['titulo'], $_POST['descripcion'], $_POST['cliente'], $_POST['fecha_proyecto'], $_POST['imagen_url'], $_POST['es_destacado'], $_POST['id_tipoDeporte']);
                $mensaje = $resultado ? 'Portfolio añadido correctamente' : 'Error al añadir portfolio';
                break;

            case 'editarPortfolio':
                $resultado = editarPortfolio($mysqli, $_POST['portfolio_id'], $_POST['titulo'], $_POST['descripcion'], $_POST['cliente'], $_POST['fecha_proyecto'], $_POST['imagen_url'], $_POST['es_destacado'], $_POST['id_tipoDeporte']);
                $mensaje = $resultado ? 'Portfolio actualizado correctamente' : 'Error al actualizar portfolio';
                break;

            // RESERVAS
            case 'añadirReserva':
                $resultado = añadirReserva($mysqli, $_POST['usuario_id'], $_POST['total_monto'], $_POST['estado']);
                $mensaje = $resultado ? 'Reserva añadida correctamente' : 'Error al añadir reserva';
                break;

            case 'editarReserva':
                $resultado = editarReserva($mysqli, $_POST['reserva_id'], $_POST['usuario_id'], $_POST['total_monto'], $_POST['estado']);
                $mensaje = $resultado ? 'Reserva actualizada correctamente' : 'Error al actualizar reserva';
                break;

            // ROLES
            case 'añadirRol':
                $resultado = añadirRol($mysqli, $_POST['nombre_rol']);
                $mensaje = $resultado ? 'Rol añadido correctamente' : 'Error al añadir rol';
                break;

            case 'editarRol':
                $resultado = editarRol($mysqli, $_POST['role_id'], $_POST['nombre_rol']);
                $mensaje = $resultado ? 'Rol actualizado correctamente' : 'Error al actualizar rol';
                break;

            // TESTIMONIOS
            case 'añadirTestimonio':
                $resultado = añadirTestimonio($mysqli, $_POST['usuario_id'], $_POST['nombre_cliente'], $_POST['cargo'], $_POST['contenido'], $_POST['puntuacion'], $_POST['es_aprobado']);
                $mensaje = $resultado ? 'Testimonio añadido correctamente' : 'Error al añadir testimonio';
                break;

            case 'editarTestimonio':
                $resultado = editarTestimonio($mysqli, $_POST['testimonio_id'], $_POST['usuario_id'], $_POST['nombre_cliente'], $_POST['cargo'], $_POST['contenido'], $_POST['puntuacion'], $_POST['es_aprobado']);
                $mensaje = $resultado ? 'Testimonio actualizado correctamente' : 'Error al actualizar testimonio';
                break;

            // TIPO DEPORTE
            case 'añadirTipoDeporte':
                $resultado = añadirTipoDeporte($mysqli, $_POST['tipo']);
                $mensaje = $resultado ? 'Tipo de deporte añadido correctamente' : 'Error al añadir tipo de deporte';
                break;

            case 'editarTipoDeporte':
                $resultado = editarTipoDeporte($mysqli, $_POST['id_tipoDeporte'], $_POST['tipo']);
                $mensaje = $resultado ? 'Tipo de deporte actualizado correctamente' : 'Error al actualizar tipo de deporte';
                break;

            // USUARIOS
            case 'añadirUsuario':
                $resultado = añadirUsuario($mysqli, $_POST['role_id'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['contrasenya'], $_POST['foto']);
                $mensaje = $resultado ? 'Usuario añadido correctamente' : 'Error al añadir usuario';
                break;

            case 'editarUsuario':
                $resultado = editarUsuario($mysqli, $_POST['usuario_id'], $_POST['role_id'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['contrasenya'], $_POST['foto']);
                $mensaje = $resultado ? 'Usuario actualizado correctamente' : 'Error al actualizar usuario';
                break;

            // ZONAS
            case 'añadirZona':
                $resultado = añadirZona($mysqli, $_POST['nombre_zona']);
                $mensaje = $resultado ? 'Zona añadida correctamente' : 'Error al añadir zona';
                break;

            case 'editarZona':
                $resultado = editarZona($mysqli, $_POST['zona_id'], $_POST['nombre_zona']);
                $mensaje = $resultado ? 'Zona actualizada correctamente' : 'Error al actualizar zona';
                break;

            default:
                $mensaje = 'Acción no reconocida';
        }

        // Redirigir de vuelta al panel de administración con mensaje
        header("Location: ../adminDashboard.php?mensaje=" . urlencode($mensaje) . "&tipo=" . ($resultado ? 'success' : 'error'));
        exit;
    }

    // Procesar eliminaciones por GET
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion'])) {
        $accion = $_GET['accion'];
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $resultado = false;
        $mensaje = '';

        if ($id > 0) {
            switch ($accion) {
                case 'eliminarComentario':
                    $resultado = eliminarComentario($mysqli, $id);
                    $mensaje = $resultado ? 'Comentario eliminado correctamente' : 'Error al eliminar comentario';
                    break;

                case 'eliminarDetalleReserva':
                    $resultado = eliminarDetalleReserva($mysqli, $id);
                    $mensaje = $resultado ? 'Detalle de reserva eliminado correctamente' : 'Error al eliminar detalle de reserva';
                    break;

                case 'eliminarEntrada':
                    $resultado = eliminarEntrada($mysqli, $id);
                    $mensaje = $resultado ? 'Entrada eliminada correctamente' : 'Error al eliminar entrada';
                    break;

                case 'eliminarEvento':
                    $resultado = eliminarEvento($mysqli, $id);
                    $mensaje = $resultado ? 'Evento eliminado correctamente' : 'Error al eliminar evento';
                    break;

                case 'eliminarFaq':
                    $resultado = eliminarFaq($mysqli, $id);
                    $mensaje = $resultado ? 'FAQ eliminada correctamente' : 'Error al eliminar FAQ';
                    break;

                case 'eliminarNoticia':
                    $resultado = eliminarNoticia($mysqli, $id);
                    $mensaje = $resultado ? 'Noticia eliminada correctamente' : 'Error al eliminar noticia';
                    break;

                case 'eliminarPortfolio':
                    $resultado = eliminarPortfolio($mysqli, $id);
                    $mensaje = $resultado ? 'Portfolio eliminado correctamente' : 'Error al eliminar portfolio';
                    break;

                case 'eliminarReserva':
                    $resultado = eliminarReserva($mysqli, $id);
                    $mensaje = $resultado ? 'Reserva eliminada correctamente' : 'Error al eliminar reserva';
                    break;

                case 'eliminarRol':
                    $resultado = eliminarRol($mysqli, $id);
                    $mensaje = $resultado ? 'Rol eliminado correctamente' : 'Error al eliminar rol';
                    break;

                case 'eliminarTestimonio':
                    $resultado = eliminarTestimonio($mysqli, $id);
                    $mensaje = $resultado ? 'Testimonio eliminado correctamente' : 'Error al eliminar testimonio';
                    break;

                case 'eliminarTipoDeporte':
                    $resultado = eliminarTipoDeporte($mysqli, $id);
                    $mensaje = $resultado ? 'Tipo de deporte eliminado correctamente' : 'Error al eliminar tipo de deporte';
                    break;

                case 'eliminarUsuario':
                    $resultado = eliminarUsuario($mysqli, $id);
                    $mensaje = $resultado ? 'Usuario eliminado correctamente' : 'Error al eliminar usuario';
                    break;

                case 'eliminarZona':
                    $resultado = eliminarZona($mysqli, $id);
                    $mensaje = $resultado ? 'Zona eliminada correctamente' : 'Error al eliminar zona';
                    break;

                default:
                    $mensaje = 'Acción de eliminación no reconocida';
            }

            header("Location: ../adminDashboard.php?mensaje=" . urlencode($mensaje) . "&tipo=" . ($resultado ? 'success' : 'error'));
            exit;
        }
    }

    function contarComentariosPorNoticia($mysqli, $noticia_id) {
        $sql = "SELECT COUNT(*) AS total_comentarios 
                FROM comentarios 
                WHERE noticia_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $noticia_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total_comentarios'];
    }