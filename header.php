<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<style>
  .user-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .user-avatar-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #ff6b35;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
    transition: all 0.3s;
    text-decoration: none;
  }

  .user-avatar-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.4);
  }

  .user-avatar-btn img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .user-avatar-btn .avatar-initials {
    color: white;
    font-weight: bold;
    font-size: 1rem;
  }
</style>

<header>
    <nav>
      <div class="logo"><i class="fas fa-trophy"></i> DeportesPro</div>
      <ul class="nav-links">
        <li><a href="index.php">Inicio</a></li>
        <li><a href="noticias.php">Noticias</a></li>
        <li><a href="portfolio.php">Portfolio</a></li>
        <li><a href="testimonio.php">Testimonios</a></li>
        <li><a href="faqs.php">FAQs</a></li>
        <li><a href="contacto.php">Contacto</a></li>
        <?php
          if (isset($_SESSION['user_nom'])) {
              echo '<li><a href="tickets.php">Tickets</a></li>';
              echo '<li><a href="adminDashboard.php">Panel admin</a></li>';
          }
        ?>
      </ul>
      <?php
        if (isset($_SESSION['user_nom'])) {
            $avatarContent = '';
            if (!empty($_SESSION['user_imagen'])) {
                $avatarContent = '<img src="' . htmlspecialchars($_SESSION['user_imagen']) . '" alt="Avatar">';
            } else {
                $iniciales = strtoupper(substr($_SESSION['user_nom'], 0, 1) . substr($_SESSION['user_apellido'] ?? '', 0, 1));
                $avatarContent = '<span class="avatar-initials">' . htmlspecialchars($iniciales) . '</span>';
            }
            echo '<div class="user-actions">
                    <a href="perfil.php" class="user-avatar-btn">' . $avatarContent . '</a>
                    <a href="cerrarSesion.php" class="btn btn-primary">Cerrar Sesión</a>
                  </div>';
        } else {
            echo '<div class="user-actions">
                <a href="login.php" class="btn btn-outline">Iniciar Sesión</a>
                <a href="register.php" class="btn btn-primary">Registrarse</a>
            </div>';
        }
      ?>
    </nav>
  </header>