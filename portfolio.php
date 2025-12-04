<?php
    include_once "../shSpport/config/config.php";
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeportesPro | Portfolio</title>
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

        /* ESTILOS ESPECÍFICOS PARA PORTFOLIO */

        .portfolio-hero {
            background: var(--light-bg);
            padding: 150px 5% 50px; 
            text-align: center;
        }

        .portfolio-hero h1 {
            font-size: 3rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .portfolio-section {
            padding: 50px 5% 80px;
            background: white;
        }

        .portfolio-filters {
            text-align: center;
            margin-bottom: 3rem;
        }

        .portfolio-filters button {
            padding: 0.5rem 1.5rem;
            margin: 0.5rem;
            border: 2px solid var(--secondary-color);
            background: white;
            color: var(--secondary-color);
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .portfolio-filters button:hover,
        .portfolio-filters button.active {
            background: var(--secondary-color);
            color: var(--text-light);
            box-shadow: 0 5px 10px rgba(0, 78, 137, 0.3);
        }

        .portfolio-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
        }

        .project-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .project-image {
            height: 250px;
            width: 100%;
            background: var(--dark-bg);
            position: relative;
            overflow: hidden;
        }
        
        .project-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
            display: block;
        }
        
        .project-image i.fa-image {
            display: contents;
            font-size: 0;
        }
        
        .project-image i.fa-image::before {
            display: none;
        }

        .project-tag {
            position: absolute;
            bottom: 0;
            right: 0;
            background: var(--gradient-primary);
            color: var(--text-light);
            padding: 0.5rem 1rem;
            border-top-left-radius: 15px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .project-content {
            padding: 2rem;
        }

        .project-content h3 {
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }

        .project-content p {
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .project-content .meta {
            font-size: 0.9rem;
            color: #888;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .portfolio-hero {
                padding-top: 120px;
            }

            .portfolio-hero h1 {
                font-size: 2rem;
            }

            .portfolio-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include_once "header.php"; ?>

    <section class="portfolio-hero">
        <div class="section-header" style="margin-bottom: 0;">
            <h1>Nuestro Portfolio de Eventos</h1>
            <p>Descubre las ligas, torneos y asociaciones con las que colaboramos.</p>
        </div>
    </section>

    <section class="portfolio-section">
        <div class="section-header">
            <h2>Casos de Éxito y Coberturas</h2>
        </div>
        
        <div class="portfolio-filters">
            <button class="active" data-filter="all">Todos</button>
            <button data-filter="Fútbol">Fútbol</button>
            <button data-filter="Básquet">Básquet</button>
            <button data-filter="Balonmano">Balonmano</button>
            <button data-filter="Fútbol sala">Fútbol sala</button>
        </div>

        <div class="portfolio-container">
            
            <?php
                include_once "funciones/funciones.php";
                $row = getPortfolio($mysqli);
                foreach ($row as $proyecto) {
                    echo '<article class="project-card" data-category="' . htmlspecialchars($proyecto['tipo']) . '">
                            <div class="project-image">
                                <img src="' . htmlspecialchars($proyecto['imagen_url']) . '" alt="' . htmlspecialchars($proyecto['titulo']) . '">
                                <div class="project-tag">' . htmlspecialchars($proyecto['tipo']) . '</div>
                            </div>
                            <div class="project-content">
                                <h3>' . htmlspecialchars($proyecto['titulo']) . '</h3>
                                <p>' . htmlspecialchars($proyecto['descripcion']) . '</p>
                                <div class="meta">Fecha: ' . htmlspecialchars($proyecto['fecha_proyecto']) . '</div>
                            </div>
                        </article>';
                }
            ?>
            
        </div>
    </section>

    <?php include_once "footer.php"; ?>

    <script>
        // 1. Obtener todos los botones de filtro y los proyectos
        const filterButtons = document.querySelectorAll('.portfolio-filters button');
        const projectCards = document.querySelectorAll('.project-card');

        // 2. Agregar un escuchador de eventos a cada botón
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Eliminar la clase 'active' de todos los botones
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Añadir la clase 'active' al botón clickeado
                button.classList.add('active');
                
                // Obtener el filtro (categoría) a aplicar
                const filterValue = button.getAttribute('data-filter');

                // 3. Iterar sobre las tarjetas de proyecto
                projectCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    // Aplicar estilos de transición para un efecto suave
                    card.style.transition = 'opacity 0.5s, transform 0.5s';

                    if (filterValue === 'all' || filterValue === cardCategory) {
                        // Mostrar tarjeta: Si es 'all' o si la categoría coincide
                        card.style.display = 'block'; 
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        }, 50); // Pequeño retraso para que la transición de opacidad funcione
                    } else {
                        // Ocultar tarjeta: La categoría no coincide
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 500); // Ocultar después de que la transición termine (500ms)
                    }
                });
            });
        });
    </script>
</body>
</html>