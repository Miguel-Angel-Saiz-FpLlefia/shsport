<?php
/**
 * Generador de códigos QR para tickets
 * 
 * Uso:
 * - generarQR.php?ticket=BCN-2024-001234 (muestra QR del ticket)
 * - Incluir en otros archivos: include 'generarQR.php'; echo generarQRHtml($codigo);
 */

session_start();

/**
 * Genera la URL de un código QR
 */
function generarQR($texto, $size = 200) {
    $texto = urlencode($texto);
    return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$texto}";
}

/**
 * Genera HTML con el código QR embebido
 */
function generarQRHtml($texto, $size = 200, $alt = "Código QR") {
    $url = generarQR($texto, $size);
    return "<img src='{$url}' alt='" . htmlspecialchars($alt) . "' width='{$size}' height='{$size}'>";
}

// Si se accede directamente al archivo con un código de ticket
if (basename($_SERVER['PHP_SELF']) == 'generarQR.php') {
    
    // Obtener código del ticket desde la URL
    $ticketCode = isset($_GET['ticket']) ? $_GET['ticket'] : '';
    $size = isset($_GET['size']) ? intval($_GET['size']) : 250;
    
    // Si no hay ticket, redirigir al perfil
    if (empty($ticketCode)) {
        header('Location: perfil.php');
        exit;
    }
    
    // Generar datos del QR (incluye información del ticket)
    $qrData = json_encode([
        'ticket' => $ticketCode,
        'usuario' => $_SESSION['user_nom'] ?? 'Usuario',
        'fecha_generacion' => date('Y-m-d H:i:s'),
        'validacion' => md5($ticketCode . 'DeportesPro2024')
    ]);
    
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket QR - <?php echo htmlspecialchars($ticketCode); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #004e89;
            --dark-bg: #1c1c1e;
            --light-bg: #f5f5f7;
            --gradient-primary: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
            --gradient-secondary: linear-gradient(135deg, #004e89 0%, #1a659e 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--light-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .ticket-qr-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            max-width: 400px;
            width: 100%;
        }

        .ticket-header {
            background: var(--gradient-secondary);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .ticket-header h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .ticket-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .qr-container {
            padding: 2rem;
            text-align: center;
            background: white;
        }

        .qr-container img {
            border-radius: 15px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.1);
            border: 5px solid var(--light-bg);
        }

        .ticket-code {
            margin-top: 1.5rem;
            padding: 1rem;
            background: var(--light-bg);
            border-radius: 10px;
        }

        .ticket-code span {
            font-size: 0.8rem;
            color: #666;
            display: block;
            margin-bottom: 0.3rem;
        }

        .ticket-code strong {
            font-size: 1.2rem;
            color: var(--secondary-color);
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
        }

        .ticket-info {
            padding: 1.5rem;
            border-top: 1px dashed #ddd;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 0.8rem;
            color: #555;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-item i {
            color: var(--primary-color);
            width: 20px;
        }

        .actions {
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            border-top: 1px solid #eee;
        }

        .btn {
            flex: 1;
            padding: 0.8rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-outline {
            border: 2px solid var(--secondary-color);
            background: white;
            color: var(--secondary-color);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .valid-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #d4edda;
            color: #155724;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 1rem;
        }

        @media print {
            body {
                background: white;
            }
            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-qr-card">
        <div class="ticket-header">
            <h1><i class="fas fa-ticket-alt"></i> Tu Entrada</h1>
            <p>Presenta este código en el acceso</p>
        </div>
        
        <div class="qr-container">
            <?php echo generarQRHtml($qrData, $size, "QR Ticket: " . $ticketCode); ?>
            
            <div class="ticket-code">
                <span>Código de ticket</span>
                <strong><?php echo htmlspecialchars($ticketCode); ?></strong>
            </div>
            
            <div class="valid-badge">
                <i class="fas fa-check-circle"></i>
                Ticket Válido
            </div>
        </div>
        
        <div class="ticket-info">
            <div class="info-item">
                <i class="fas fa-user"></i>
                <span><?php echo htmlspecialchars($_SESSION['user_nom'] ?? 'Usuario') . ' ' . htmlspecialchars($_SESSION['user_apellido'] ?? ''); ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-calendar"></i>
                <span>Generado: <?php echo date('d/m/Y H:i'); ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-shield-alt"></i>
                <span>Código verificado</span>
            </div>
        </div>
        
        <div class="actions">
            <a href="<?php echo generarQR($qrData, 400); ?>" download="ticket-<?php echo $ticketCode; ?>.png" class="btn btn-primary">
                <i class="fas fa-download"></i> Descargar
            </a>
            <button onclick="window.print()" class="btn btn-outline">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </div>
    
    <p style="margin-top: 2rem; color: #888; font-size: 0.85rem;">
        <a href="perfil.php" style="color: var(--primary-color); text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Volver a mi perfil
        </a>
    </p>
</body>
</html>
<?php
}
?>