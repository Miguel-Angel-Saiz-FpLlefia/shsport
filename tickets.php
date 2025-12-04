<?php
session_start();
include_once "./config/config.php";
require_once 'funciones/funciones.php';

// Verificar si el usuario está logeado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Procesar reserva
$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservar'])) {
    $entrada_id = intval($_POST['entrada_id']);
    $cantidad = intval($_POST['cantidad']);
    $usuario_id = $_SESSION['user_id'];
    
    // Verificar stock disponible
    $checkStock = $mysqli->prepare("SELECT e.stock_disponible, e.precio, ev.nombre_evento 
                                    FROM entradas e 
                                    INNER JOIN eventos ev ON e.evento_id = ev.evento_id 
                                    WHERE e.entrada_id = ?");
    $checkStock->bind_param("i", $entrada_id);
    $checkStock->execute();
    $ticketData = $checkStock->get_result()->fetch_assoc();
    
    if ($ticketData && $ticketData['stock_disponible'] >= $cantidad && $cantidad > 0) {
        // Calcular total
        $total_monto = $ticketData['precio'] * $cantidad;
        
        // Insertar reserva
        $insertReserva = $mysqli->prepare("INSERT INTO reservas (usuario_id, entrada_id, total_monto, estado) VALUES (?, ?, ?, 'confirmada')");
        $insertReserva->bind_param("iid", $usuario_id, $entrada_id, $total_monto);
        
        if ($insertReserva->execute()) {
            // Reducir stock
            $nuevoStock = $ticketData['stock_disponible'] - $cantidad;
            $updateStock = $mysqli->prepare("UPDATE entradas SET stock_disponible = ? WHERE entrada_id = ?");
            $updateStock->bind_param("ii", $nuevoStock, $entrada_id);
            $updateStock->execute();
            
            $mensaje = "¡Reserva realizada con éxito! Has reservado $cantidad entrada(s) para '{$ticketData['nombre_evento']}'. Total: " . number_format($total_monto, 2) . "€";
            $tipoMensaje = 'success';
        } else {
            $mensaje = "Error al procesar la reserva. Inténtalo de nuevo.";
            $tipoMensaje = 'error';
        }
    } else if ($ticketData['stock_disponible'] == 0) {
        $mensaje = "Lo sentimos, no hay entradas disponibles para este evento.";
        $tipoMensaje = 'error';
    } else if ($cantidad > $ticketData['stock_disponible']) {
        $mensaje = "Solo hay {$ticketData['stock_disponible']} entradas disponibles.";
        $tipoMensaje = 'error';
    } else {
        $mensaje = "Error: Cantidad no válida.";
        $tipoMensaje = 'error';
    }
}

// Filtrar por deporte
$filtroDeporte = isset($_GET['deporte']) ? $mysqli->real_escape_string($_GET['deporte']) : '';

// Obtener todos los eventos activos con sus entradas
$query = "SELECT 
            ev.evento_id,
            ev.nombre_evento,
            ev.fecha_hora,
            ev.ubicacion,
            ev.deporte,
            ev.descripcion,
            ev.imagen_url,
            ev.es_activo,
            e.entrada_id,
            e.precio,
            e.stock_disponible,
            z.nombre_zona
          FROM eventos ev
          INNER JOIN entradas e ON ev.evento_id = e.evento_id
          INNER JOIN zonas z ON e.zona_id = z.zona_id
          WHERE ev.es_activo = 1";

if (!empty($filtroDeporte)) {
    $query .= " AND ev.deporte = '$filtroDeporte'";
}
$query .= " ORDER BY ev.fecha_hora ASC, e.precio ASC";

$tickets = $mysqli->query($query);

// Obtener deportes únicos para el filtro
$deportes = $mysqli->query("SELECT DISTINCT deporte FROM eventos WHERE es_activo = 1 ORDER BY deporte");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets - DeportesPro</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Header Navigation */
        header {
            background: linear-gradient(135deg, #07085d 0%, #0d0e8a 100%);
            padding: 1rem 5%;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
        }

        .logo i {
            color: #f7463a;
            margin-right: 0.5rem;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #f7463a;
        }

        /* Page Banner */
        .page-banner {
            background: linear-gradient(135deg, #07085d 0%, #0d0e8a 100%);
            padding: 150px 0 80px;
            text-align: center;
            color: #fff;
        }

        .page-banner h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #fff;
        }

        .page-banner p {
            font-size: 1.2rem;
            opacity: 0.9;
            color: #fff;
        }

        /* Tickets Section */
        .tickets-section {
            padding: 60px 5%;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .tickets-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Filter Bar */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 40px;
            padding: 20px 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .filter-bar h3 {
            margin: 0;
            color: #07085d;
        }

        .filter-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #e0e0e0;
            background: #fff;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: linear-gradient(37deg, rgb(180, 62, 121) 1%, rgb(247, 70, 58) 100%);
            color: #fff;
            border-color: transparent;
        }

        /* Mensaje de alerta */
        .alert {
            padding: 20px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #fff;
        }

        .alert-error {
            background: linear-gradient(135deg, #dc3545 0%, #f7463a 100%);
            color: #fff;
        }

        .alert i {
            font-size: 1.5rem;
        }

        /* Tickets Grid */
        .tickets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 30px;
        }

        /* Ticket Card */
        .ticket-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
            position: relative;
        }

        .ticket-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }

        .ticket-card.sold-out {
            opacity: 0.7;
        }

        .ticket-card.sold-out::after {
            content: 'AGOTADO';
            position: absolute;
            top: 20px;
            right: -35px;
            background: #dc3545;
            color: #fff;
            padding: 8px 50px;
            font-weight: bold;
            transform: rotate(45deg);
            font-size: 0.9rem;
            z-index: 10;
        }

        .ticket-image {
            height: 200px;
            background: linear-gradient(135deg, #07085d 0%, #0d0e8a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .ticket-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ticket-image .sport-icon {
            font-size: 4rem;
            color: rgba(255,255,255,0.3);
        }

        .sport-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(37deg, rgb(180, 62, 121) 1%, rgb(247, 70, 58) 100%);
            color: #fff;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .zona-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0,0,0,0.7);
            color: #fff;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .ticket-content {
            padding: 25px;
        }

        .ticket-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #07085d;
            margin-bottom: 10px;
        }

        .ticket-description {
            color: #6c6c86;
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .ticket-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .ticket-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6c6c86;
        }

        .ticket-detail i {
            width: 20px;
            color: #f7463a;
        }

        .ticket-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .ticket-price {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(37deg, rgb(180, 62, 121) 1%, rgb(247, 70, 58) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .ticket-stock {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .stock-label {
            font-size: 0.8rem;
            color: #6c6c86;
        }

        .stock-number {
            font-size: 1.2rem;
            font-weight: 700;
            color: #28a745;
        }

        .stock-number.low {
            color: #ffc107;
        }

        .stock-number.out {
            color: #dc3545;
        }

        /* Reservar Button */
        .btn-reservar {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background: linear-gradient(37deg, rgb(180, 62, 121) 1%, rgb(247, 70, 58) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-reservar:hover:not(:disabled) {
            transform: scale(1.02);
            box-shadow: 0 10px 30px rgba(247, 70, 58, 0.4);
        }

        .btn-reservar:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            position: relative;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c6c86;
            transition: color 0.3s;
        }

        .modal-close:hover {
            color: #f7463a;
        }

        .modal-title {
            font-size: 1.5rem;
            color: #07085d;
            margin-bottom: 25px;
            padding-right: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #f7463a;
        }

        .form-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-info p {
            margin: 5px 0;
            color: #6c6c86;
        }

        .form-info strong {
            color: #07085d;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(37deg, rgb(180, 62, 121) 1%, rgb(247, 70, 58) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            box-shadow: 0 10px 30px rgba(247, 70, 58, 0.4);
        }

        /* Footer styles */
        footer {
            background: linear-gradient(135deg, #07085d 0%, #0d0e8a 100%);
            color: #fff;
            padding: 60px 5% 20px;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .footer-section h3 {
            color: #fff;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .footer-section p {
            color: rgba(255,255,255,0.7);
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section ul a:hover {
            color: #f7463a;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: #f7463a;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .footer-bottom p {
            color: rgba(255,255,255,0.5);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tickets-grid {
                grid-template-columns: 1fr;
            }

            .filter-bar {
                flex-direction: column;
                text-align: center;
            }

            .page-banner h1 {
                font-size: 2rem;
            }

            .nav-links {
                display: none;
            }
        }

        /* No tickets message */
        .no-tickets {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .no-tickets i {
            font-size: 4rem;
            color: #e0e0e0;
            margin-bottom: 20px;
        }

        .no-tickets h3 {
            color: #07085d;
            margin-bottom: 10px;
        }

        .no-tickets p {
            color: #6c6c86;
        }

        /* User info in modal */
        .user-info-badge {
            background: linear-gradient(135deg, #07085d 0%, #0d0e8a 100%);
            color: #fff;
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info-badge i {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Page Banner -->
    <section class="page-banner">
        <h1><i class="fas fa-ticket-alt"></i> Entradas Disponibles</h1>
        <p>Reserva tus entradas para los mejores eventos deportivos</p>
    </section>

    <!-- Tickets Section -->
    <section class="tickets-section">
        <div class="tickets-container">
            
            <?php if (!empty($mensaje)): ?>
            <div class="alert alert-<?php echo $tipoMensaje; ?>">
                <i class="fas <?php echo $tipoMensaje === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <span><?php echo $mensaje; ?></span>
            </div>
            <?php endif; ?>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <h3><i class="fas fa-filter"></i> Filtrar por deporte</h3>
                <div class="filter-options">
                    <a href="tickets.php" class="filter-btn <?php echo empty($filtroDeporte) ? 'active' : ''; ?>">
                        Todos
                    </a>
                    <?php while ($deporte = $deportes->fetch_assoc()): ?>
                    <a href="tickets.php?deporte=<?php echo urlencode($deporte['deporte']); ?>" 
                       class="filter-btn <?php echo $filtroDeporte === $deporte['deporte'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($deporte['deporte']); ?>
                    </a>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Tickets Grid -->
            <?php if ($tickets && $tickets->num_rows > 0): ?>
            <div class="tickets-grid">
                <?php while ($ticket = $tickets->fetch_assoc()): ?>
                <div class="ticket-card <?php echo $ticket['stock_disponible'] == 0 ? 'sold-out' : ''; ?>">
                    <div class="ticket-image">
                        <span class="sport-badge"><?php echo htmlspecialchars($ticket['deporte']); ?></span>
                        <span class="zona-badge"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($ticket['nombre_zona']); ?></span>
                        <?php if (!empty($ticket['imagen_url'])): ?>
                            <img src="<?php echo htmlspecialchars($ticket['imagen_url']); ?>" alt="<?php echo htmlspecialchars($ticket['nombre_evento']); ?>">
                        <?php else: ?>
                            <?php 
                            $iconClass = 'fa-futbol';
                            $deporteLower = strtolower($ticket['deporte']);
                            if (strpos($deporteLower, 'basket') !== false || strpos($deporteLower, 'baloncesto') !== false) {
                                $iconClass = 'fa-basketball';
                            } elseif (strpos($deporteLower, 'balonmano') !== false) {
                                $iconClass = 'fa-hand-fist';
                            } elseif (strpos($deporteLower, 'tenis') !== false) {
                                $iconClass = 'fa-baseball';
                            }
                            ?>
                            <i class="fas <?php echo $iconClass; ?> sport-icon"></i>
                        <?php endif; ?>
                    </div>
                    <div class="ticket-content">
                        <h3 class="ticket-title"><?php echo htmlspecialchars($ticket['nombre_evento']); ?></h3>
                        <p class="ticket-description"><?php echo htmlspecialchars($ticket['descripcion'] ?? 'Evento deportivo emocionante'); ?></p>
                        
                        <div class="ticket-details">
                            <div class="ticket-detail">
                                <i class="fas fa-calendar"></i>
                                <span><?php echo date('d/m/Y', strtotime($ticket['fecha_hora'])); ?></span>
                            </div>
                            <div class="ticket-detail">
                                <i class="fas fa-clock"></i>
                                <span><?php echo date('H:i', strtotime($ticket['fecha_hora'])); ?>h</span>
                            </div>
                            <div class="ticket-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($ticket['ubicacion']); ?></span>
                            </div>
                        </div>

                        <div class="ticket-footer">
                            <span class="ticket-price"><?php echo number_format($ticket['precio'], 2); ?>€</span>
                            <div class="ticket-stock">
                                <span class="stock-label">Disponibles</span>
                                <span class="stock-number <?php 
                                    echo $ticket['stock_disponible'] == 0 ? 'out' : ($ticket['stock_disponible'] <= 10 ? 'low' : ''); 
                                ?>">
                                    <?php echo $ticket['stock_disponible']; ?> entradas
                                </span>
                            </div>
                        </div>

                        <?php if ($ticket['stock_disponible'] > 0): ?>
                        <button class="btn-reservar" onclick="openModal(<?php echo $ticket['entrada_id']; ?>, '<?php echo addslashes($ticket['nombre_evento']); ?>', '<?php echo addslashes($ticket['nombre_zona']); ?>', <?php echo $ticket['precio']; ?>, <?php echo $ticket['stock_disponible']; ?>)">
                            <i class="fas fa-shopping-cart"></i> Reservar Entrada
                        </button>
                        <?php else: ?>
                        <button class="btn-reservar" disabled>
                            <i class="fas fa-times-circle"></i> Agotado
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="no-tickets">
                <i class="fas fa-ticket-alt"></i>
                <h3>No hay eventos disponibles</h3>
                <p>Vuelve pronto para ver nuevos eventos deportivos.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Modal de Reserva -->
    <div class="modal-overlay" id="reservaModal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <h2 class="modal-title"><i class="fas fa-ticket-alt"></i> Reservar Entrada</h2>
            
            <form method="POST" action="tickets.php">
                <input type="hidden" name="entrada_id" id="modal_entrada_id">
                
                <div class="user-info-badge">
                    <i class="fas fa-user"></i>
                    <span>Reservando como: <strong><?php echo htmlspecialchars($_SESSION['user_nom'] . ' ' . ($_SESSION['user_apellido'] ?? '')); ?></strong></span>
                </div>
                
                <div class="form-info">
                    <p><strong>Evento:</strong> <span id="modal_evento_nombre"></span></p>
                    <p><strong>Zona:</strong> <span id="modal_zona_nombre"></span></p>
                    <p><strong>Precio por entrada:</strong> <span id="modal_precio"></span>€</p>
                    <p><strong>Disponibles:</strong> <span id="modal_stock"></span> entradas</p>
                </div>

                <div class="form-group">
                    <label for="cantidad">Cantidad de Entradas</label>
                    <select id="cantidad" name="cantidad" required>
                        <!-- Las opciones se generarán dinámicamente -->
                    </select>
                </div>

                <div class="form-info">
                    <p><strong>Total a pagar:</strong> <span id="modal_total">0.00</span>€</p>
                </div>

                <button type="submit" name="reservar" class="btn-submit">
                    <i class="fas fa-check-circle"></i> Confirmar Reserva
                </button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        let precioActual = 0;
        let stockActual = 0;

        function openModal(entradaId, nombre, zona, precio, stock) {
            precioActual = precio;
            stockActual = stock;
            
            document.getElementById('modal_entrada_id').value = entradaId;
            document.getElementById('modal_evento_nombre').textContent = nombre;
            document.getElementById('modal_zona_nombre').textContent = zona;
            document.getElementById('modal_precio').textContent = precio.toFixed(2);
            document.getElementById('modal_stock').textContent = stock;
            
            // Generar opciones de cantidad
            const selectCantidad = document.getElementById('cantidad');
            selectCantidad.innerHTML = '';
            const maxEntradas = Math.min(stock, 10); // Máximo 10 entradas por reserva
            
            for (let i = 1; i <= maxEntradas; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i + (i === 1 ? ' entrada' : ' entradas');
                selectCantidad.appendChild(option);
            }
            
            actualizarTotal();
            document.getElementById('reservaModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('reservaModal').classList.remove('active');
        }

        function actualizarTotal() {
            const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
            const total = precioActual * cantidad;
            document.getElementById('modal_total').textContent = total.toFixed(2);
        }

        // Event listeners
        document.getElementById('cantidad').addEventListener('change', actualizarTotal);

        // Cerrar modal al hacer clic fuera
        document.getElementById('reservaModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>

<?php $mysqli->close(); ?>
