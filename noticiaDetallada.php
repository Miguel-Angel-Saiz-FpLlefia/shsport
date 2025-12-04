<?php
    include_once "./config/config.php";
    session_start();
    include_once "./funciones/funciones.php";

    $noticia_id_redirect = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Procesar el envío de comentarios (nuevo o respuesta)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario_contenido'])) {
        $noticia_id = isset($_POST['noticia_id']) ? intval($_POST['noticia_id']) : 0;
        $contenido = trim($_POST['comentario_contenido']);
        $parent_id = isset($_POST['parent_comentario_id']) && $_POST['parent_comentario_id'] !== '' ? intval($_POST['parent_comentario_id']) : null;
        
        // Verificar si el usuario está logueado
        if (isset($_SESSION['user_id']) && !empty($contenido) && $noticia_id > 0) {
            $usuario_id = $_SESSION['user_id'];
            añadirComentario($mysqli, $noticia_id, $usuario_id, $contenido, $parent_id);
        }
        
        // Redirigir para evitar reenvío del formulario
        header("Location: noticiaDetallada.php?id=" . $noticia_id);
        exit();
    }

    // Procesar edición de comentario
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_comentario'])) {
        $comentario_id = isset($_POST['comentario_id']) ? intval($_POST['comentario_id']) : 0;
        $noticia_id = isset($_POST['noticia_id']) ? intval($_POST['noticia_id']) : 0;
        $contenido = trim($_POST['comentario_contenido_edit']);
        
        if (isset($_SESSION['user_id']) && !empty($contenido) && $comentario_id > 0) {
            // Verificar que el usuario es el dueño del comentario
            $comentarios_usuario = getComentariosByNoticia($noticia_id, $mysqli);
            foreach ($comentarios_usuario as $com) {
                if ($com['comentario_id'] == $comentario_id && $com['usuario_id'] == $_SESSION['user_id']) {
                    $parent_id = $com['parent_comentario_id'];
                    editarComentario($mysqli, $comentario_id, $noticia_id, $_SESSION['user_id'], $contenido, $parent_id);
                    break;
                }
            }
        }
        
        header("Location: noticiaDetallada.php?id=" . $noticia_id);
        exit();
    }

    // Procesar eliminación de comentario
    if (isset($_GET['eliminar_comentario'])) {
        $comentario_id = intval($_GET['eliminar_comentario']);
        $noticia_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (isset($_SESSION['user_id']) && $comentario_id > 0) {
            // Verificar que el usuario es el dueño del comentario
            $comentarios_usuario = getComentariosByNoticia($noticia_id, $mysqli);
            foreach ($comentarios_usuario as $com) {
                if ($com['comentario_id'] == $comentario_id && $com['usuario_id'] == $_SESSION['user_id']) {
                    eliminarComentario($mysqli, $comentario_id);
                    break;
                }
            }
        }
        
        header("Location: noticiaDetallada.php?id=" . $noticia_id);
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticia Detalle | DeportesPro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Variables y Estilos Base (Copiados del index.html para consistencia) */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #ff6b35;
            --secondary-color: #004e89;
            --dark-bg: #1c1c1e;
            --light-bg: #f5f5f7;
            --text-dark: #1d1d1f;
            --text-light: #fff;
            --gradient-primary: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.7;
            color: var(--text-dark);
            background: var(--light-bg);
        }

        /* Header y Navegación (Copiados del index.html) */
        header {
            background: var(--dark-bg);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }
        
        /* Footer (Copiado del index.html) */
        footer {
            background: var(--dark-bg);
            color: var(--text-light);
            padding: 40px 5% 20px;
        }
        /* ... (Otros estilos de navegación y footer omitidos por brevedad) ... */


        /* ESTILOS ESPECÍFICOS DE LA NOTICIA */

        .article-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 100px 20px 50px 20px; /* Padding superior para el fixed header */
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            min-height: 80vh;
        }
        
        .article-header h1 {
            font-size: 2.8rem;
            line-height: 1.2;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .article-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .article-meta span {
            margin-right: 15px;
        }

        .article-meta i {
            color: var(--primary-color);
            margin-right: 5px;
        }
        
        .article-image {
            width: 100%;
            height: 450px;
            background: var(--dark-bg); /* Placeholder de imagen */
            margin-bottom: 2rem;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .article-image i {
            font-size: 5rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .article-content p {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            text-align: justify;
        }
        
        .article-content h2 {
            font-size: 1.8rem;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        blockquote {
            background: var(--light-bg);
            border-left: 5px solid var(--primary-color);
            padding: 1rem 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            font-size: 1.2rem;
            color: #555;
        }
        
        /* --- Sección de Comentarios --- */

.comments-section {
    margin-top: 4rem;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

.comments-section h2 {
    font-size: 2rem;
    margin-bottom: 2rem;
    color: var(--secondary-color);
}

/* Formulario de Comentarios (Mantener si no ha cambiado) */
.comment-form textarea {
    width: 100%;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    resize: vertical;
    min-height: 100px;
    margin-bottom: 1rem;
}

.comment-form input[type="text"],
.comment-form input[type="email"] {
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    margin-right: 1rem;
    width: 40%;
}

.comment-form button {
    padding: 0.8rem 1.5rem;
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    background: var(--gradient-primary);
    color: var(--text-light);
    transition: opacity 0.3s;
}

.comment-form button:hover {
    opacity: 0.9;
}

.comment-form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

/* Lista de Comentarios */
.comment-list {
    list-style: none;
    padding: 0;
}

.comment-item {
    display: flex;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    border-left: 3px solid var(--secondary-color);
}

.comment-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary-color);
    color: var(--text-light);
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.comment-body h4 {
    margin-top: 0;
    margin-bottom: 0.5rem;
    font-size: 1rem;
    color: var(--secondary-color);
}

.comment-body .comment-date {
    font-size: 0.8rem;
    color: #888;
    margin-bottom: 0.5rem;
    display: block;
}

.comment-body p {
    font-size: 1rem;
    margin: 0;
    line-height: 1.6;
}

/* Acciones y Botón de Respuesta (MEJORADO) */
.comment-actions {
    margin-top: 0.8rem;
    padding-top: 0.5rem;
    border-top: 1px solid #f9f9f9;
}

.comment-actions .reply-btn {
    /* Estilo de Botón Tipo Enlace Limpio */
    background: none;
    border: none;
    color: #888; /* Color neutro para que no distraiga */
    font-size: 0.9rem;
    cursor: pointer;
    font-weight: 500;
    transition: color 0.2s, background-color 0.2s, transform 0.2s;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
}

.comment-actions .reply-btn i {
    color: var(--primary-color); /* Icono con color de marca */
    margin-right: 5px;
}

.comment-actions .reply-btn:hover {
    color: var(--secondary-color); /* Texto cambia a azul corporativo */
    background-color: #f0f0f0;
    transform: translateY(-1px); /* Efecto 3D sutil */
}

/* Botones de Editar y Eliminar */
.comment-actions .edit-btn,
.comment-actions .delete-btn {
    background: none;
    border: none;
    font-size: 0.9rem;
    cursor: pointer;
    font-weight: 500;
    transition: color 0.2s, background-color 0.2s, transform 0.2s;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    margin-left: 0.5rem;
}

.comment-actions .edit-btn {
    color: #888;
}

.comment-actions .edit-btn i {
    color: var(--secondary-color);
    margin-right: 5px;
}

.comment-actions .edit-btn:hover {
    color: var(--secondary-color);
    background-color: #e8f4fc;
}

.comment-actions .delete-btn {
    color: #888;
}

.comment-actions .delete-btn i {
    color: #dc3545;
    margin-right: 5px;
}

.comment-actions .delete-btn:hover {
    color: #dc3545;
    background-color: #fde8ea;
}

/* Formulario de edición inline */
.edit-form-container {
    margin-top: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #e8f4fc 0%, #d6eaf8 100%);
    border-radius: 8px;
    border: 1px solid #aed6f1;
    animation: slideDown 0.3s ease-out;
}

.edit-form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.8rem;
    font-size: 0.9rem;
    color: #555;
}

.edit-form-header i {
    color: var(--secondary-color);
    margin-right: 5px;
}

.edit-form textarea {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #aed6f1;
    border-radius: 6px;
    resize: vertical;
    min-height: 80px;
    font-family: inherit;
    font-size: 0.95rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.edit-form textarea:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(0, 78, 137, 0.15);
}

.edit-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.8rem;
    margin-top: 0.8rem;
}

.save-edit-btn {
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 20px;
    background: var(--secondary-color);
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.save-edit-btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* Respuestas Anidadas */
.comment-replies {
    list-style: none;
    padding-left: 0;
    margin-top: 1.5rem;
    border-left: 2px solid #ddd;
    padding-left: 1rem;
}

.comment-replies .comment-item {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #fcfcfc;
    border-left: none;
    box-shadow: 0 1px 5px rgba(0,0,0,0.03);
}

/* Formulario de Respuesta Inline */
.reply-form-container {
    margin-top: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    border: 1px solid #dee2e6;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.reply-form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.8rem;
    font-size: 0.9rem;
    color: #555;
}

.reply-form-header i {
    color: var(--primary-color);
    margin-right: 5px;
}

.close-reply-btn {
    background: none;
    border: none;
    color: #888;
    cursor: pointer;
    font-size: 1rem;
    padding: 0.3rem;
    border-radius: 50%;
    transition: all 0.2s;
}

.close-reply-btn:hover {
    background: rgba(0,0,0,0.1);
    color: #333;
}

.reply-form textarea {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    resize: vertical;
    min-height: 80px;
    font-family: inherit;
    font-size: 0.95rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.reply-form textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.15);
}

.reply-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.8rem;
    margin-top: 0.8rem;
}

.cancel-reply-btn {
    padding: 0.6rem 1rem;
    border: 1px solid #ddd;
    border-radius: 20px;
    background: white;
    color: #666;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.cancel-reply-btn:hover {
    background: #f5f5f5;
    border-color: #ccc;
}

.submit-reply-btn {
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 20px;
    background: var(--gradient-primary);
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.submit-reply-btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.submit-reply-btn i {
    margin-right: 5px;
}

        /* Responsive */
        @media (max-width: 768px) {
            .article-container {
                padding-top: 80px;
            }
            .article-header h1 {
                font-size: 2rem;
            }
            .article-image {
                height: 250px;
            }
            .comment-form-footer {
                flex-direction: column;
                align-items: flex-start;
            }
            .comment-form input[type="text"],
            .comment-form input[type="email"] {
                width: 100%;
                margin-bottom: 1rem;
            }
            /* --- Nuevos estilos para anidación de comentarios --- */
            .comment-replies {
                list-style: none;
                padding-left: 0;
                margin-top: 1.5rem;
                /* Indentación para las respuestas */
                border-left: 2px solid #ddd; 
                padding-left: 1rem;
            }

            .comment-replies .comment-item {
                margin-bottom: 1.5rem;
                padding: 1rem;
                background: #fcfcfc; /* Fondo ligeramente diferente para diferenciar */
                border-left: none;
                box-shadow: 0 1px 5px rgba(0,0,0,0.03);
            }

            /* Botón de Respuesta */
            .comment-actions {
                margin-top: 0.5rem;
            }

            .comment-actions .reply-btn {
                background: none;
                border: none;
                color: var(--primary-color);
                font-size: 0.9rem;
                cursor: pointer;
                font-weight: 600;
                transition: color 0.3s;
            }

            .comment-actions .reply-btn:hover {
                color: var(--secondary-color);
            }
        }
    </style>
</head>
<body>
    <?php include_once "header.php"; ?>

    <div class="article-container">
        
    <?php
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $noticia = getNoticiaById($id, $mysqli);

        if ($noticia) {
            echo '<div class="article-header">
                    <h1>' . htmlspecialchars($noticia['titulo']) . '</h1>
                    <div class="article-meta">
                        <span><i class="fas fa-user"></i> ' . htmlspecialchars($noticia['usuario_nombre']) . ' ' . htmlspecialchars($noticia['usuario_apellido']) . '</span>
                        <span><i class="fas fa-calendar-alt"></i> ' . date("d M Y", strtotime($noticia['fecha_publicacion'])) . '</span>
                    </div>
                </div>
                <div class="article-image">';
            if (!empty($noticia['imagen_url'])) {
                echo '<img src="' . htmlspecialchars($noticia['imagen_url']) . '" alt="' . htmlspecialchars($noticia['titulo']) . '" style="width:100%; height:100%; object-fit:cover;">';
            } else {
                echo '<i class="fas fa-image"></i>';
            }
            echo '</div>
                <div class="article-content">
                    ' . nl2br(htmlspecialchars($noticia['contenido'])) . '
                </div>';
        } else {
            echo '<p>Lo sentimos, la noticia que buscas no existe.</p>';
        }
    ?>
        
    <div class="comments-section">
        <h2>Comentarios (<?php echo contarComentariosPorNoticia($mysqli, $id); ?>)</h2>
        
        <?php 
        $usuario_logueado = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        if ($usuario_logueado): ?>
        <form class="comment-form" method="POST" action="">
            <input type="hidden" name="noticia_id" value="<?php echo $id; ?>">
            <input type="hidden" name="parent_comentario_id" value="">
            <textarea name="comentario_contenido" placeholder="Deja tu opinión sobre este traspaso..." required></textarea>
            <div class="comment-form-footer">
                <button type="submit"><i class="fas fa-comment-dots"></i> Publicar Comentario</button>
            </div>
        </form>
        <?php else: ?>
        <p style="padding: 1rem; background: #f8f9fa; border-radius: 6px; margin-bottom: 2rem;">
            <i class="fas fa-info-circle" style="color: var(--primary-color);"></i> 
            <a href="login.php" style="color: var(--secondary-color); font-weight: 600;">Inicia sesión</a> para dejar un comentario.
        </p>
        <?php endif; ?>
            <?php
                $comentarios = getComentariosByNoticia($id, $mysqli);
                foreach ($comentarios as $comentario) {
                    if($comentario['parent_comentario_id'] === null) {
                        $es_propietario = ($usuario_logueado && $comentario['usuario_id'] == $usuario_logueado);
                        echo '<div class="comment-item" data-comment-id="' . $comentario['comentario_id'] . '">
                                <div class="comment-avatar">';
                        if (!empty($comentario['foto'])) {
                            echo '<img src="' . htmlspecialchars($comentario['foto']) . '" alt="' . htmlspecialchars($comentario['nombre']) . '" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">';
                        } else {
                            echo strtoupper(substr($comentario['nombre'], 0, 1));
                        }
                        echo '</div>
                                <div class="comment-body">
                                    <h4>' . htmlspecialchars($comentario['nombre']) . ' ' . htmlspecialchars($comentario['apellido']) . '</h4>
                                    <span class="comment-date">' . date("d M Y H:i", strtotime($comentario['fecha_comentario'])) . '</span>
                                    <p class="comment-text">' . nl2br(htmlspecialchars($comentario['contenido'])) . '</p>
                                    <div class="comment-actions">';
                        // Botón responder para usuarios logueados
                        if ($usuario_logueado) {
                            echo '<button class="reply-btn" data-parent-id="' . $comentario['comentario_id'] . '"><i class="fas fa-reply"></i> Responder</button>';
                        }
                        // Botones editar y eliminar solo para el propietario
                        if ($es_propietario) {
                            echo '<button class="edit-btn" data-comment-id="' . $comentario['comentario_id'] . '" data-comment-content="' . htmlspecialchars($comentario['contenido'], ENT_QUOTES) . '"><i class="fas fa-edit"></i> Editar</button>';
                            echo '<a href="noticiaDetallada.php?id=' . $id . '&eliminar_comentario=' . $comentario['comentario_id'] . '" class="delete-btn" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este comentario?\');"><i class="fas fa-trash"></i> Eliminar</a>';
                        }
                        echo '</div>
                                </div>
                            </div>';
                        
                        // Mostrar respuestas de este comentario
                        $respuestas = getRespuestasComentario($comentario['comentario_id'], $mysqli);
                        foreach ($respuestas as $respuesta) {
                            $es_propietario_respuesta = ($usuario_logueado && $respuesta['usuario_id'] == $usuario_logueado);
                            echo '<div class="comment-item" style="margin-left: 40px; background-color: #f9f9f9;" data-comment-id="' . $respuesta['comentario_id'] . '">
                                    <div class="comment-avatar">';
                            if (!empty($respuesta['foto'])) {
                                echo '<img src="' . htmlspecialchars($respuesta['foto']) . '" alt="' . htmlspecialchars($respuesta['nombre']) . '" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">';
                            } else {
                                echo strtoupper(substr($respuesta['nombre'], 0, 1));
                            }
                            echo '</div>
                                    <div class="comment-body">
                                        <h4>' . htmlspecialchars($respuesta['nombre']) . ' ' . htmlspecialchars($respuesta['apellido']) . '</h4>
                                        <span class="comment-date">' . date("d M Y H:i", strtotime($respuesta['fecha_comentario'])) . '</span>
                                        <p class="comment-text">' . nl2br(htmlspecialchars($respuesta['contenido'])) . '</p>
                                        <div class="comment-actions">';
                            // Botón responder para usuarios logueados (responde al comentario padre)
                            if ($usuario_logueado) {
                                echo '<button class="reply-btn" data-parent-id="' . $comentario['comentario_id'] . '"><i class="fas fa-reply"></i> Responder</button>';
                            }
                            // Botones editar y eliminar solo para el propietario de la respuesta
                            if ($es_propietario_respuesta) {
                                echo '<button class="edit-btn" data-comment-id="' . $respuesta['comentario_id'] . '" data-comment-content="' . htmlspecialchars($respuesta['contenido'], ENT_QUOTES) . '"><i class="fas fa-edit"></i> Editar</button>';
                                echo '<a href="noticiaDetallada.php?id=' . $id . '&eliminar_comentario=' . $respuesta['comentario_id'] . '" class="delete-btn" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este comentario?\');"><i class="fas fa-trash"></i> Eliminar</a>';
                            }
                            echo '</div>
                                    </div>
                                </div>';
                        }
                    }
                }
            ?>
    </div>

    <footer>
        <div style="text-align: center; max-width: 1400px; margin: 0 auto;">
            <p>&copy; 2024 DeportesPro. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el ID de la noticia desde la URL
            const urlParams = new URLSearchParams(window.location.search);
            const noticiaId = urlParams.get('id');
            
            // Obtener todos los botones de responder
            const replyButtons = document.querySelectorAll('.reply-btn');
            
            replyButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    // Cerrar cualquier formulario de respuesta abierto
                    const openForms = document.querySelectorAll('.reply-form-container');
                    openForms.forEach(function(form) {
                        form.remove();
                    });
                    
                    // Obtener el comment-body padre y el ID del comentario padre
                    const commentBody = this.closest('.comment-body');
                    const commentActions = this.closest('.comment-actions');
                    const parentCommentId = this.getAttribute('data-parent-id');
                    
                    // Verificar si ya existe un formulario en este comentario
                    if (commentBody.querySelector('.reply-form-container')) {
                        return;
                    }
                    
                    // Obtener el nombre del usuario al que se responde
                    const userName = commentBody.querySelector('h4').textContent;
                    
                    // Crear el formulario de respuesta con campos ocultos para el POST
                    const replyFormHTML = `
                        <div class="reply-form-container">
                            <form class="reply-form" method="POST" action="">
                                <input type="hidden" name="noticia_id" value="${noticiaId}">
                                <input type="hidden" name="parent_comentario_id" value="${parentCommentId}">
                                <div class="reply-form-header">
                                    <span><i class="fas fa-reply"></i> Respondiendo a <strong>${userName}</strong></span>
                                    <button type="button" class="close-reply-btn"><i class="fas fa-times"></i></button>
                                </div>
                                <textarea name="comentario_contenido" placeholder="Escribe tu respuesta..." required></textarea>
                                <div class="reply-form-actions">
                                    <button type="button" class="cancel-reply-btn">Cancelar</button>
                                    <button type="submit" class="submit-reply-btn"><i class="fas fa-paper-plane"></i> Enviar Respuesta</button>
                                </div>
                            </form>
                        </div>
                    `;
                    
                    // Insertar el formulario después de comment-actions
                    commentActions.insertAdjacentHTML('afterend', replyFormHTML);
                    
                    // Focus en el textarea
                    const newTextarea = commentBody.querySelector('.reply-form textarea');
                    newTextarea.focus();
                    
                    // Event listener para cerrar el formulario
                    const closeBtn = commentBody.querySelector('.close-reply-btn');
                    const cancelBtn = commentBody.querySelector('.cancel-reply-btn');
                    
                    closeBtn.addEventListener('click', function() {
                        commentBody.querySelector('.reply-form-container').remove();
                    });
                    
                    cancelBtn.addEventListener('click', function() {
                        commentBody.querySelector('.reply-form-container').remove();
                    });
                });
            });

            // Manejar botones de editar
            const editButtons = document.querySelectorAll('.edit-btn');
            
            editButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    // Cerrar cualquier formulario abierto
                    const openForms = document.querySelectorAll('.reply-form-container, .edit-form-container');
                    openForms.forEach(function(form) {
                        form.remove();
                    });
                    
                    const commentBody = this.closest('.comment-body');
                    const commentActions = this.closest('.comment-actions');
                    const commentId = this.getAttribute('data-comment-id');
                    const commentContent = this.getAttribute('data-comment-content');
                    const commentText = commentBody.querySelector('.comment-text');
                    
                    // Crear el formulario de edición
                    const editFormHTML = `
                        <div class="edit-form-container">
                            <form class="edit-form" method="POST" action="">
                                <input type="hidden" name="editar_comentario" value="1">
                                <input type="hidden" name="comentario_id" value="${commentId}">
                                <input type="hidden" name="noticia_id" value="${noticiaId}">
                                <div class="edit-form-header">
                                    <span><i class="fas fa-edit"></i> Editando comentario</span>
                                    <button type="button" class="close-reply-btn close-edit-btn"><i class="fas fa-times"></i></button>
                                </div>
                                <textarea name="comentario_contenido_edit" required>${commentContent}</textarea>
                                <div class="edit-form-actions">
                                    <button type="button" class="cancel-reply-btn cancel-edit-btn">Cancelar</button>
                                    <button type="submit" class="save-edit-btn"><i class="fas fa-save"></i> Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    `;
                    
                    // Ocultar el texto original y mostrar el formulario
                    commentText.style.display = 'none';
                    commentActions.insertAdjacentHTML('afterend', editFormHTML);
                    
                    // Focus en el textarea
                    const newTextarea = commentBody.querySelector('.edit-form textarea');
                    newTextarea.focus();
                    newTextarea.setSelectionRange(newTextarea.value.length, newTextarea.value.length);
                    
                    // Event listeners para cerrar
                    const closeBtn = commentBody.querySelector('.close-edit-btn');
                    const cancelBtn = commentBody.querySelector('.cancel-edit-btn');
                    
                    const closeEditForm = function() {
                        commentBody.querySelector('.edit-form-container').remove();
                        commentText.style.display = 'block';
                    };
                    
                    closeBtn.addEventListener('click', closeEditForm);
                    cancelBtn.addEventListener('click', closeEditForm);
                });
            });
        });
    </script>
</body>
</html>