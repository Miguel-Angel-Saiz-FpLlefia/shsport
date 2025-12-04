<?php
    include_once "./config/config.php";
    session_start();
    include_once "funciones/funciones.php";

    $mensaje = '';
    $tipo_mensaje = '';

    // Procesar el formulario de nuevo testimonio
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_testimonio'])) {
        if (isset($_SESSION['user_id'])) {
            $usuario_id = $_SESSION['user_id'];
            
            // Verificar si ya tiene un testimonio
            if (!usuarioTieneTestimonio($mysqli, $usuario_id)) {
                $contenido = trim($_POST['contenido']);
                $puntuacion = isset($_POST['puntuacion']) ? intval($_POST['puntuacion']) : 5;
                $cargo = trim($_POST['cargo']);
                
                if (!empty($contenido) && $puntuacion >= 1 && $puntuacion <= 5) {
                    // es_aprobado = 1 para que se muestre directamente
                    $resultado = añadirTestimonio($mysqli, $usuario_id, '', $cargo, $contenido, $puntuacion, 1);
                    if ($resultado) {
                        $mensaje = '¡Gracias por tu testimonio! Ya está publicado.';
                        $tipo_mensaje = 'success';
                    } else {
                        $mensaje = 'Hubo un error al enviar tu testimonio. Inténtalo de nuevo.';
                        $tipo_mensaje = 'error';
                    }
                } else {
                    $mensaje = 'Por favor, completa todos los campos correctamente.';
                    $tipo_mensaje = 'error';
                }
            } else {
                $mensaje = 'Ya has enviado un testimonio anteriormente.';
                $tipo_mensaje = 'error';
            }
        }
    }

    // Verificar si el usuario logueado ya tiene testimonio
    $usuario_tiene_testimonio = false;
    if (isset($_SESSION['user_id'])) {
        $usuario_tiene_testimonio = usuarioTieneTestimonio($mysqli, $_SESSION['user_id']);
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeportesPro | Testimonios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Base (Copiado del index.html para mantener la consistencia) */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #ff6b35;
            --secondary-color: #004e89;
            --accent-color: #1a659e;
            --dark-bg: #1c1c1e;
            --light-bg: #f5f5f7;
            --text-dark: #1d1d1f;
            --text-light: #fff;
            --gradient-primary: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
            --gradient-secondary: linear-gradient(135deg, #004e89 0%, #1a659e 100%);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: white; /* Aseguramos un fondo claro para el contenido principal */
        }

        /* Header y Navegación */
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

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after { /* Clase 'active' para la página actual */
            width: 100%;
        }

        .user-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--text-light);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
        }

        .btn-outline {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: var(--text-light);
        }
        
        /* Estilos generales de sección copiados del index */
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .section-header p {
            font-size: 1.1rem;
            color: #666;
        }
        
        /* Footer (Copiado del index) */
        footer {
            background: var(--dark-bg);
            color: var(--text-light);
            padding: 60px 5% 20px;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.8rem;
        }

        .footer-section a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: var(--primary-color);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s;
        }

        .social-links a:hover {
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #888;
        }

        /* ESTILOS ESPECÍFICOS PARA TESTIMONIOS */

        .testimonials-hero {
            background: var(--gradient-secondary);
            padding: 150px 5% 50px; 
            text-align: center;
            color: var(--text-light);
        }

        .testimonials-hero h1 {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .testimonials-hero p {
            color: #ccc;
        }

        .testimonials-section {
            padding: 80px 5%;
            background: var(--light-bg);
        }

        .testimonials-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .testimonial-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .quote-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .testimonial-text {
            font-size: 1.1rem;
            color: var(--text-dark);
            font-style: italic;
            margin-bottom: 1.5rem;
        }

        .testimonial-rating {
            color: gold;
            margin-bottom: 1rem;
        }
        
        .testimonial-rating i {
            color: var(--primary-color);
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            margin-top: 1.5rem;
            border-top: 1px solid #eee;
            padding-top: 1rem;
        }

        .reviewer-avatar {
            width: 50px;
            height: 50px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 1.5rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .reviewer-details h4 {
            font-size: 1.1rem;
            margin: 0;
            color: var(--secondary-color);
        }

        .reviewer-details p {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
            line-height: 1.2;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .testimonials-hero {
                padding-top: 120px;
            }

            .testimonials-hero h1 {
                font-size: 2.5rem;
            }

            .testimonials-container {
                grid-template-columns: 1fr;
            }
        }

        /* Formulario de Nuevo Testimonio */
        .new-testimonial-section {
            padding: 60px 5%;
            background: white;
        }

        .new-testimonial-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .testimonial-form-card {
            background: var(--light-bg);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .testimonial-form-card h3 {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .testimonial-form-card > p {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.15);
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.15);
        }

        /* Estrellas de puntuación */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 0.3rem;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label {
            color: var(--primary-color);
        }

        .submit-testimonial-btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 25px;
            background: var(--gradient-primary);
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-testimonial-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.4);
        }

        /* Mensajes de alerta */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert i {
            font-size: 1.2rem;
        }

        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .login-prompt p {
            margin-bottom: 1rem;
            color: #666;
        }

        .already-submitted {
            text-align: center;
            padding: 2rem;
            background: #e8f4fc;
            border-radius: 8px;
            color: var(--secondary-color);
        }

        .already-submitted i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <?php include_once "header.php"; ?>

    <section class="testimonials-hero">
        <div class="section-header" style="margin-bottom: 0;">
            <h1>Opiniones de Nuestros Clientes</h1>
            <p>Lee lo que dicen los aficionados sobre sus experiencias al comprar entradas con DeportesPro.</p>
        </div>
    </section>

    <!-- Sección para crear nuevo testimonio -->
    <section class="new-testimonial-section">
        <div class="new-testimonial-container">
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <i class="fas fa-<?php echo $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (!$usuario_tiene_testimonio): ?>
                    <div class="testimonial-form-card">
                        <h3><i class="fas fa-pen" style="color: var(--primary-color); margin-right: 10px;"></i>Comparte tu Experiencia</h3>
                        <p>¿Has comprado entradas con nosotros? ¡Nos encantaría saber tu opinión!</p>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="crear_testimonio" value="1">
                            
                            <div class="form-group">
                                <label for="cargo">Tu Profesión / Ocupación (opcional)</label>
                                <input type="text" id="cargo" name="cargo" placeholder="Ej: Aficionado del fútbol, Empresario...">
                            </div>
                            
                            <div class="form-group">
                                <label for="contenido">Tu Testimonio *</label>
                                <textarea id="contenido" name="contenido" placeholder="Cuéntanos tu experiencia comprando entradas con DeportesPro..." required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Puntuación *</label>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="puntuacion" value="5" checked>
                                    <label for="star5" title="5 estrellas"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star4" name="puntuacion" value="4">
                                    <label for="star4" title="4 estrellas"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star3" name="puntuacion" value="3">
                                    <label for="star3" title="3 estrellas"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star2" name="puntuacion" value="2">
                                    <label for="star2" title="2 estrellas"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star1" name="puntuacion" value="1">
                                    <label for="star1" title="1 estrella"><i class="fas fa-star"></i></label>
                                </div>
                            </div>
                            
                            <button type="submit" class="submit-testimonial-btn">
                                <i class="fas fa-paper-plane"></i> Enviar Testimonio
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="already-submitted">
                        <i class="fas fa-check-circle"></i>
                        <h3>¡Ya has enviado tu testimonio!</h3>
                        <p>Gracias por compartir tu experiencia con nosotros. Solo se permite un testimonio por usuario.</p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="login-prompt">
                    <i class="fas fa-user-circle" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem; display: block;"></i>
                    <p>¿Quieres compartir tu experiencia?</p>
                    <a href="login.php" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Inicia sesión para dejar tu testimonio</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="testimonials-section">
        <div class="testimonials-container">
            
            <?php
                include_once "funciones/funciones.php";
                $testimonios = getTestimonios($mysqli);

                foreach ($testimonios as $testimonio) {
                    $iniciales = mb_strtoupper(mb_substr($testimonio['nombre'], 0, 1) . mb_substr($testimonio['apellido'], 0, 1));
                    
                    // Generar las estrellas
                    $estrellas = '';
                    for ($i = 0; $i < $testimonio['puntuacion']; $i++) {
                        $estrellas .= '<i class="fas fa-star"></i>';
                    }
                    
                    echo '<div class="testimonial-card">
                            <div class="quote-icon">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <div class="testimonial-rating">' . $estrellas . '</div>
                            <div class="testimonial-text">"' . htmlspecialchars($testimonio['testimonio']) . '"</div>
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">' . htmlspecialchars($iniciales) . '</div>
                                <div class="reviewer-details">
                                    <h4>' . htmlspecialchars($testimonio['nombre'] . ' ' . $testimonio['apellido']) . '</h4>
                                    <p>' . htmlspecialchars($testimonio['cargo']) . '</p>
                                </div>
                            </div>
                        </div>';
                }
            ?>

        </div>
    </section>

    <?php include_once "footer.php"; ?>
</body>
</html>