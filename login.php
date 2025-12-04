<?php
  include_once "./config/config.php";

  session_start();
  if (isset($_SESSION['user_id'])) {
      header('Location: index.php');
      exit();
  }else {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        //2. Recoger los datos del formulario
        $email = $_POST['email'];
        $password = $_POST['password'];

        //3. Preparar la consulta para obtener el usuario por email
        $smtp = $mysqli->prepare("SELECT usuario_id, nombre, apellido, email, contrasena_hash, role_id, foto FROM usuarios WHERE email = ?");

        //4. Comprobar que la preparacion tuvo exito
        if(!$smtp) {
            die('Error en la preparación: ' . $mysqli->error);
        }

        //5. Bindear los parametros
        $smtp->bind_param('s', $email);

        //6. Ejecutamos la consulta
        $smtp->execute();

        //7. Obtener el reusltado
        $result = $smtp->get_result();

        //8. Compruebo si se encontro un usuario
        if($result->num_rows === 1) {
            $user = $result->fetch_assoc();
        }

        //9. Verificar la contraseña
        if(password_verify($password, $user['contrasena_hash'])) {
            //10. Iniciar session y guardar datos en la sesion
            $_SESSION['user_id'] = $user['usuario_id'];
            $_SESSION['user_nom'] = $user['nombre'];
            $_SESSION['user_apellido'] = $user['apellido'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_rol'] = $user['role_id'];
            $_SESSION['user_imagen'] = $user['foto'];
            header('Location: index.php');
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    }
  }

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - DeportesPro</title>
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

    /* Header */
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

    /* Main Content */
    .login-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .login-wrapper {
      display: grid;
      grid-template-columns: 1fr 1fr;
      max-width: 1100px;
      width: 100%;
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .login-visual {
      background: var(--gradient-secondary);
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .login-visual::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
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
      color: var(--primary-color);
    }

    .visual-content h2 {
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    .visual-content p {
      font-size: 1.1rem;
      opacity: 0.9;
      line-height: 1.6;
    }

    .sports-icons {
      display: flex;
      gap: 1.5rem;
      margin-top: 2rem;
      justify-content: center;
    }

    .sports-icons i {
      font-size: 2rem;
      opacity: 0.7;
    }

    .login-form-section {
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
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

    .login-form {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
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

    .form-group input {
      padding: 1rem 1rem 1rem 3rem;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s;
      outline: none;
    }

    .form-group input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
    }

    .remember-me label {
      font-size: 0.9rem;
      color: #666;
      cursor: pointer;
    }

    .forgot-password {
      color: var(--primary-color);
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 600;
      transition: color 0.3s;
    }

    .forgot-password:hover {
      color: var(--secondary-color);
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

    .social-login {
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

    .register-link {
      text-align: center;
      margin-top: 1.5rem;
      color: #666;
    }

    .register-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 968px) {
      .login-wrapper {
        grid-template-columns: 1fr;
      }

      .login-visual {
        padding: 2rem;
        min-height: 250px;
      }

      .visual-content h2 {
        font-size: 1.5rem;
      }

      .visual-content i {
        font-size: 3rem;
      }
    }

    @media (max-width: 480px) {
      .login-form-section {
        padding: 2rem 1.5rem;
      }

      .social-login {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <nav>
      <a href="index.php" class="logo"><i class="fas fa-trophy"></i> DeportesPro</a>
      <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Volver al inicio
      </a>
    </nav>
  </header>

  <!-- Login Container -->
  <div class="login-container">
    <div class="login-wrapper">
      <!-- Visual Section -->
      <div class="login-visual">
        <div class="visual-content">
          <i class="fas fa-ticket-alt"></i>
          <h2>Bienvenido de vuelta</h2>
          <p>Accede a tu cuenta y disfruta de los mejores eventos deportivos</p>
          <div class="sports-icons">
            <i class="fas fa-futbol"></i>
            <i class="fas fa-basketball-ball"></i>
            <i class="fas fa-volleyball-ball"></i>
            <i class="fas fa-running"></i>
          </div>
        </div>
      </div>

      <!-- Form Section -->
      <div class="login-form-section">
        <div class="form-header">
          <h1>Iniciar Sesión</h1>
          <p>Accede a tu cuenta</p>
        </div>

        <form class="login-form" method="POST">
          <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <div class="input-wrapper">
              <i class="fas fa-envelope"></i>
              <input type="email" id="email" name="email" placeholder="tu@email.com" required>
            </div>
          </div>

          <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-wrapper">
              <i class="fas fa-lock"></i>
              <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
          </div>

          <div class="form-options">
            <div class="remember-me">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Recordarme</label>
            </div>
            <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
          </button>
        </form>

        <div class="divider">
          <span>O continúa con</span>
        </div>

        <div class="social-login">
          <button class="btn-social btn-google">
            <i class="fab fa-google"></i> Google
          </button>
          <button class="btn-social btn-facebook">
            <i class="fab fa-facebook-f"></i> Facebook
          </button>
        </div>

        <div class="register-link">
          ¿No tienes cuenta? <a href="registro.html">Regístrate aquí</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>