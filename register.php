<?php
  include_once "./config/config.php";
  session_start();

  //Verificar si el formulario ha sido enviado

  if($_SERVER['REQUEST_METHOD'] === 'POST') {
      //1. Recoger los datos del formulario
      $nom = $_POST['nombre'];
      $apellido = $_POST['apellidos'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $confirmPassword = $_POST['confirm-password'];
      $imagen = $_POST['imagen'];

      //Comprobar que las contraseñas coinciden
      if($password !== $confirmPassword) {
          die('Las contraseñas no coinciden. <a href="register.php">Volver</a>');
      }

      //2. Hasheamos la contraseña antes de guardarla
      $password_hasheada = password_hash($password, PASSWORD_DEFAULT);

      //3. Preparamos la consulta para insertar al nuevo usuario
      $stmt = $mysqli->prepare("INSERT INTO usuarios (role_id, nombre, apellido, email, contrasena_hash, foto) VALUES (2,?,?,?,?,?)");

      //4. Comprobar que la preparación tuvo éxito
      if(!$stmt) {
          die('error en la preparación: ' . $mysqli->error);
      }

      //5. Bindeamos los parametros
      $stmt->bind_param('sssss', $nom, $apellido, $email, $password_hasheada, $imagen);

      //6. Ejecutamos la consulta
      if($stmt->execute()) {
          header('Location: index.php');
      }else {
          echo 'Error al registrar el usuario: ' . $stmt->error;
      }

      $_SESSION['user_nom'] = $nom;

      //7. Cerramos la conexion
      $stmt->close();
      $mysqli->close();
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - DeportesPro</title>
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
      background: var(--light-bg);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background: var(--dark-bg);
      padding: 1rem 5%;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
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
      text-decoration: none;
    }

    .back-link {
      color: var(--text-light);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: color 0.3s;
    }

    .back-link:hover {
      color: var(--primary-color);
    }

    .register-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .register-wrapper {
      display: grid;
      grid-template-columns: 1fr 1fr;
      max-width: 1200px;
      width: 100%;
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .register-visual {
      background: var(--gradient-primary);
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .register-visual::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    }

    .visual-content {
      position: relative;
      z-index: 1;
      text-align: center;
    }

    .visual-content i {
      font-size: 5rem;
      margin-bottom: 2rem;
    }

    .visual-content h2 {
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    .visual-content p {
      font-size: 1.1rem;
      opacity: 0.9;
      line-height: 1.6;
      margin-bottom: 2rem;
    }

    .benefits {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      margin-top: 2rem;
      text-align: left;
    }

    .benefit-item {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .benefit-item i {
      font-size: 1.5rem;
      width: 30px;
    }

    .register-form-section {
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      max-height: 90vh;
      overflow-y: auto;
    }

    .form-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .form-header h1 {
      font-size: 2rem;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
    }

    .form-header p {
      color: #666;
    }

    .register-form {
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .form-group label {
      font-weight: 600;
      color: var(--text-dark);
      font-size: 0.9rem;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
    }

    .form-group input,
    .form-group select {
      padding: 0.9rem 0.9rem 0.9rem 3rem;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      font-size: 0.95rem;
      transition: all 0.3s;
      outline: none;
    }

    .form-group input:focus,
    .form-group select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
    }

    .terms {
      display: flex;
      align-items: flex-start;
      gap: 0.5rem;
    }

    .terms input[type="checkbox"] {
      margin-top: 0.3rem;
      width: 18px;
      height: 18px;
      cursor: pointer;
    }

    .terms label {
      font-size: 0.9rem;
      color: #666;
      line-height: 1.5;
    }

    .terms a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
    }

    .terms a:hover {
      text-decoration: underline;
    }

    .btn {
      padding: 1rem;
      border: none;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-primary {
      background: var(--gradient-primary);
      color: white;
      box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin: 1.5rem 0;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #e0e0e0;
    }

    .divider span {
      color: #999;
      font-size: 0.9rem;
    }

    .social-register {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .btn-social {
      padding: 0.8rem;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-social:hover {
      border-color: var(--primary-color);
      background: var(--light-bg);
    }

    .btn-social i {
      font-size: 1.2rem;
    }

    .btn-google {
      color: #db4437;
    }

    .btn-facebook {
      color: #4267B2;
    }

    .login-link {
      text-align: center;
      margin-top: 1.5rem;
      color: #666;
    }

    .login-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    @media (max-width: 968px) {
      .register-wrapper {
        grid-template-columns: 1fr;
      }

      .register-visual {
        padding: 2rem;
        min-height: 300px;
      }

      .form-row {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 480px) {
      .register-form-section {
        padding: 2rem 1.5rem;
      }

      .social-register {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <header>
    <nav>
      <a href="index.php" class="logo"><i class="fas fa-trophy"></i> DeportesPro</a>
      <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Volver al inicio
      </a>
    </nav>
  </header>

  <div class="register-container">
    <div class="register-wrapper">
      <div class="register-visual">
        <div class="visual-content">
          <i class="fas fa-user-plus"></i>
          <h2>Únete a DeportesPro</h2>
          <p>Crea tu cuenta y accede a miles de eventos deportivos</p>
          
          <div class="benefits">
            <div class="benefit-item">
              <i class="fas fa-check-circle"></i>
              <span>Acceso a eventos exclusivos</span>
            </div>
            <div class="benefit-item">
              <i class="fas fa-check-circle"></i>
              <span>Gestión de tus tickets</span>
            </div>
            <div class="benefit-item">
              <i class="fas fa-check-circle"></i>
              <span>Ofertas y descuentos especiales</span>
            </div>
            <div class="benefit-item">
              <i class="fas fa-check-circle"></i>
              <span>Notificaciones de eventos</span>
            </div>
          </div>
        </div>
      </div>

      <div class="register-form-section">
        <div class="form-header">
          <h1>Crear Cuenta</h1>
          <p>Completa el formulario para registrarte</p>
        </div>

        <form class="register-form" method="POST">
          <div class="form-row">
            <div class="form-group">
              <label for="nombre">Nombre</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="nombre" name="nombre" placeholder="Juan" required>
              </div>
            </div>
            <div class="form-group">
              <label for="apellidos">Apellido</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="apellidos" name="apellidos" placeholder="Pérez" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <div class="input-wrapper">
              <i class="fas fa-envelope"></i>
              <input type="email" id="email" name="email" placeholder="tu@email.com" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="password">Contraseña</label>
              <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
              </div>
            </div>
            <div class="form-group">
              <label for="confirm-password">Confirmar Contraseña</label>
              <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="••••••••" required>
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="imagen">URL Imagen</label>
              <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="text" id="imagen" name="imagen" placeholder="https://d2u1z1lopyfwlx.cloudfront.net/thumbnails/43a9f51d-f1a4-5189-920c-8d9e2a47db9a/6ed47fe4-4084-5858-9ddf-a3ed2f2e0e0a.jpg" required>
              </div>
            </div>
          </div>

          <div class="terms">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">
              Acepto los <a href="#">Términos y Condiciones</a> y la <a href="#">Política de Privacidad</a>
            </label>
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Crear Cuenta
          </button>
        </form>

        <div class="divider">
          <span>O regístrate con</span>
        </div>

        <div class="social-register">
          <button class="btn-social btn-google">
            <i class="fab fa-google"></i> Google
          </button>
          <button class="btn-social btn-facebook">
            <i class="fab fa-facebook-f"></i> Facebook
          </button>
        </div>

        <div class="login-link">
          ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>