<?php
    include_once "./config/config.php";
    session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DeportesPro | Inicio</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
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

    .nav-links a:hover::after {
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

    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, var(--dark-bg) 0%, #2c2c2e 100%);
      padding: 150px 5% 100px;
      color: var(--text-light);
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 50%;
      height: 100%;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,107,53,0.1)"/></svg>');
      background-size: 300px;
      opacity: 0.3;
    }

    .hero-content {
      max-width: 1400px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      align-items: center;
      position: relative;
      z-index: 1;
    }

    .hero-text h1 {
      font-size: 3.5rem;
      margin-bottom: 1.5rem;
      line-height: 1.2;
    }

    .hero-text .highlight {
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .hero-text p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      color: #ccc;
    }

    .hero-actions {
      display: flex;
      gap: 1rem;
    }

    .hero-image {
      position: relative;
    }

    .sports-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }

    .sport-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      padding: 2rem;
      border-radius: 15px;
      text-align: center;
      transition: transform 0.3s;
    }

    .sport-card:hover {
      transform: translateY(-5px);
    }

    .sport-card i {
      font-size: 3rem;
      margin-bottom: 1rem;
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* Sección de Deportes */
    .sports-section {
      padding: 80px 5%;
      background: var(--light-bg);
    }

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

    .sports-container {
      max-width: 1400px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
    }

    .sport-item {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      transition: all 0.3s;
      position: relative;
    }

    .sport-item:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .sport-item-image {
      height: 200px;
      background: var(--gradient-secondary);
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .sport-item-image i {
      font-size: 5rem;
      color: rgba(255,255,255,0.9);
      z-index: 1;
    }

    .sport-item-content {
      padding: 2rem;
    }

    .sport-item-content h3 {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      color: var(--text-dark);
    }

    .sport-item-content p {
      color: #666;
      margin-bottom: 1.5rem;
    }

    /* Sección de Estadísticas */
    .stats-section {
      background: var(--gradient-secondary);
      padding: 80px 5%;
      color: var(--text-light);
    }

    .stats-container {
      max-width: 1400px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 3rem;
      text-align: center;
    }

    .stat-item {
      padding: 2rem;
    }

    .stat-item i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: var(--primary-color);
    }

    .stat-number {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
    }

    .stat-label {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    /* Eventos Destacados */
    .events-section {
      padding: 80px 5%;
      background: white;
    }

    .events-container {
      max-width: 1400px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 2rem;
    }

    .event-card {
      background: var(--light-bg);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      transition: all 0.3s;
    }

    .event-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .event-header {
      background: var(--gradient-primary);
      color: white;
      padding: 2rem;
      position: relative;
    }

    .event-date {
      background: rgba(255,255,255,0.2);
      display: inline-block;
      padding: 0.5rem 1rem;
      border-radius: 10px;
      font-weight: bold;
      margin-bottom: 1rem;
    }

    .event-body {
      padding: 2rem;
    }

    .event-body h3 {
      font-size: 1.3rem;
      margin-bottom: 1rem;
      color: var(--text-dark);
    }

    .event-info {
      display: flex;
      flex-direction: column;
      gap: 0.8rem;
      margin-bottom: 1.5rem;
    }

    .event-info-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #666;
    }

    .event-info-item i {
      color: var(--primary-color);
    }

    /* Footer */
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

    /* Responsive */
    @media (max-width: 768px) {
      .nav-links {
        display: none;
      }

      .hero-content {
        grid-template-columns: 1fr;
      }

      .hero-text h1 {
        font-size: 2.5rem;
      }

      .sports-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <?php include_once "header.php"; ?>

  <!-- Hero Section -->
  <section class="hero" id="inicio">
    <div class="hero-content">
      <div class="hero-text">
        <h1>Vive la <span class="highlight">Pasión</span> del Deporte</h1>
        <p>Accede a los mejores eventos deportivos: Fútbol, Básquet, Balonmano y Fútbol Sala. Compra tus entradas y vive experiencias únicas.</p>
        <div class="hero-actions">
          <a href="#eventos" class="btn btn-primary">Ver Eventos</a>
          <?php
            if (!isset($_SESSION['user_nom'])) {
                echo '<a href="register.php" class="btn btn-outline">Crear Cuenta</a>';
            }
          ?>
          
        </div>
      </div>
      <div class="hero-image">
        <div class="sports-grid">
          <div class="sport-card">
            <i class="fas fa-futbol"></i>
            <h3>Fútbol</h3>
          </div>
          <div class="sport-card">
            <i class="fas fa-basketball-ball"></i>
            <h3>Básquet</h3>
          </div>
          <div class="sport-card">
            <i class="fas fa-volleyball-ball"></i>
            <h3>Balonmano</h3>
          </div>
          <div class="sport-card">
            <i class="fas fa-running"></i>
            <h3>Fútbol Sala</h3>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Sección de Deportes -->
  <section class="sports-section">
    <div class="section-header">
      <h2>Nuestros Deportes</h2>
      <p>Disfruta de los mejores eventos en cada disciplina</p>
    </div>
    <div class="sports-container">
      <div class="sport-item">
        <div class="sport-item-image">
          <i class="fas fa-futbol"></i>
        </div>
        <div class="sport-item-content">
          <h3>Fútbol</h3>
          <p>Los mejores partidos de las ligas más importantes del mundo</p>
          <a href="portfolio.php" class="btn btn-primary">Ver Eventos</a>
        </div>
      </div>
      <div class="sport-item">
        <div class="sport-item-image" style="background: var(--gradient-primary);">
          <i class="fas fa-basketball-ball"></i>
        </div>
        <div class="sport-item-content">
          <h3>Básquet</h3>
          <p>Emociones a canasta limpia en cada encuentro</p>
          <a href="portfolio.php" class="btn btn-primary">Ver Eventos</a>
        </div>
      </div>
      <div class="sport-item">
        <div class="sport-item-image">
          <i class="fas fa-volleyball-ball"></i>
        </div>
        <div class="sport-item-content">
          <h3>Balonmano</h3>
          <p>La velocidad y técnica del balonmano profesional</p>
          <a href="portfolio.php" class="btn btn-primary">Ver Eventos</a>
        </div>
      </div>
      <div class="sport-item">
        <div class="sport-item-image" style="background: var(--gradient-primary);">
          <i class="fas fa-running"></i>
        </div>
        <div class="sport-item-content">
          <h3>Fútbol Sala</h3>
          <p>Acción rápida y goles en espacios reducidos</p>
          <a href="portfolio.php" class="btn btn-primary">Ver Eventos</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Estadísticas -->
  <section class="stats-section">
    <div class="stats-container">
      <div class="stat-item">
        <i class="fas fa-users"></i>
        <div class="stat-number">50K+</div>
        <div class="stat-label">Usuarios Registrados</div>
      </div>
      <div class="stat-item">
        <i class="fas fa-calendar-alt"></i>
        <div class="stat-number">500+</div>
        <div class="stat-label">Eventos al Año</div>
      </div>
      <div class="stat-item">
        <i class="fas fa-ticket-alt"></i>
        <div class="stat-number">100K+</div>
        <div class="stat-label">Entradas Vendidas</div>
      </div>
      <div class="stat-item">
        <i class="fas fa-star"></i>
        <div class="stat-number">4.8/5</div>
        <div class="stat-label">Valoración Media</div>
      </div>
    </div>
  </section>

  <!-- Eventos Destacados -->
  <section class="events-section" id="eventos">
    <div class="section-header">
      <h2>Últimas Noticias</h2>
      <p>No te pierdas las ultimas noticias sobre el deporte</p>
    </div>
    <div class="events-container">
      <?php
        include_once "./funciones/funciones.php";
        $noticias = llegirUltimesNoticies($mysqli);
        foreach ($noticias as $noticia) {
            echo '<div class="event-card">
                    <div class="event-header">
                      <div class="event-date">' . date("d M Y", strtotime($noticia['fecha_publicacion'])) . '</div>
                      <h3>' . htmlspecialchars($noticia['titulo']) . '</h3>
                    </div>
                    <div class="event-body">
                      <div class="event-info">
                        <div class="event-info-item">
                          <p>' . htmlspecialchars(substr($noticia['contenido'], 0, 100)) . '</p>
                        </div>
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