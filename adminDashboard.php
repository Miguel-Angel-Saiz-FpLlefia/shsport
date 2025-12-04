<?php
    include_once "./config/config.php";
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeportesPro | Gestión de Base de Datos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Variables y Estilos Base (Copiados del admin_dashboard.html) */
        :root {
            --primary-color: #ff6b35; /* Naranja/Destacado */
            --secondary-color: #004e89; /* Azul Corporativo */
            --sidebar-bg: #2c3e50; /* Azul Oscuro (Típico Admin) */
            --sidebar-hover: #34495e;
            --header-bg: #ffffff;
            --card-bg: #ffffff;
            --text-dark: #2c3e50;
            --text-light: #ecf0f1;
            --border-color: #e0e0e0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f4f7f6;
            color: var(--text-dark);
        }

        /* Diseño Principal (Grid) */
        .dashboard-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            height: 100vh;
        }

        /* --- Sidebar (Barra Lateral) --- */
        .sidebar {
            background: var(--sidebar-bg);
            color: var(--text-light);
            padding: 1rem 0;
            position: fixed;
            height: 100%;
            width: 250px;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            text-align: center;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 1rem 1.5rem;
            color: var(--text-light);
            text-decoration: none;
            font-size: 1rem;
            transition: background 0.3s, color 0.3s;
            display: flex;
            align-items: center;
        }

        .sidebar-menu a i {
            margin-right: 0.8rem;
            width: 20px;
        }

        .sidebar-menu a:hover {
            background: var(--sidebar-hover);
            color: var(--primary-color);
            border-left: 4px solid var(--primary-color);
        }

        /* --- Main Content (Contenido Principal) --- */
        .main-content {
            grid-column: 2 / 3;
        }

        .content-header {
            background: var(--header-bg);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 500;
            box-shadow: 0 1px 5px rgba(0,0,0,0.05);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }

        .logout-btn {
            color: var(--secondary-color);
            text-decoration: none;
            margin-left: 1rem;
            transition: color 0.3s;
        }

        .logout-btn:hover {
            color: var(--primary-color);
        }

        /* Contenido Principal (Gestión de DB) */
        .content-body {
            padding: 2rem;
        }

        .db-management-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .db-management-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid var(--border-color);
        }

        .db-management-header h3 {
            font-size: 1.5rem;
            color: var(--secondary-color);
        }

        /* Estilos de la Tabla */
        .data-table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.95rem;
        }

        .data-table th {
            background-color: var(--light-bg);
            color: var(--text-dark);
            font-weight: 600;
            text-transform: uppercase;
        }

        .data-table tbody tr:hover {
            background-color: #fafafa;
        }

        .data-table .action-btns {
            display: flex;
            gap: 0.5rem;
        }

        .action-btns button {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 0.85rem;
        }

        .btn-edit {
            background: #f39c12;
            color: white;
        }
        .btn-edit:hover { background: #e67e22; }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        .btn-delete:hover { background: #c0392b; }

        /* Estilos de Estado */
        .status-badge {
            padding: 0.3rem 0.7rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-completed { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-refunded { background-color: #f8d7da; color: #721c24; }
        
        /* Botones Generales (similares a los del sitio) */
        .btn-add {
            background: var(--gradient-primary);
            padding: 0.8rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        .btn-add:hover { opacity: 0.9; }


        /* Modal Overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-header h3 {
            color: var(--secondary-color);
            font-size: 1.3rem;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            transition: color 0.3s;
        }

        .modal-close:hover {
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn-submit {
            width: 100%;
            padding: 0.8rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: #e55a2b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-layout {
                grid-template-columns: 1fr;
            }
            
            .sidebar { display: none; }
            
            .content-header { position: static; }
        }

        /* Estilos para notificaciones */
        .notificacion {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 2000;
            transform: translateX(120%);
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notificacion.show {
            transform: translateX(0);
        }

        .notificacion.success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .notificacion.error {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
        }

        .notificacion i {
            font-size: 1.2rem;
        }

    </style>
</head>
<?php if(!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 1) {
    header("Location: index.php");
    exit();
} ?>
<body>
    
    <div class="dashboard-layout">
        
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Admin <span style="color: var(--primary-color);">Pro</span></h2>
            </div>
            <nav class="sidebar-menu">
                <ul>
                    <li><a href="#comentarios" class="active"><i class="fas fa-database"></i> Comentarios</a></li>
                    <li><a href="#detalleReserva"><i class="fas fa-ticket-alt"></i> Detalles de las reservas</a></li>
                    <li><a href="#entradas"><i class="fas fa-futbol"></i> Entradas</a></li>
                    <li><a href="#eventos"><i class="fas fa-users"></i> Eventos</a></li>
                    <li><a href="#faqs"><i class="fas fa-file-invoice-dollar"></i> Faqs</a></li>
                    <li><a href="#noticias"><i class="fas fa-chart-line"></i> Noticias</a></li>
                    <li><a href="#portfolio"><i class="fas fa-cog"></i> Portfolio</a></li>
                    <li><a href="#reservas"><i class="fas fa-cog"></i> Reservas</a></li>
                    <li><a href="#roles"><i class="fas fa-cog"></i> Roles</a></li>
                    <li><a href="#testimonios"><i class="fas fa-cog"></i> Testimonios</a></li>
                    <li><a href="#tipoDeporte"><i class="fas fa-cog"></i> Tipo deporte</a></li>
                    <li><a href="#usuarios"><i class="fa fa-users" aria-hidden="true"></i> Usuarios</a></li>
                    <li><a href="#zonas"><i class="fas fa-cog"></i> Zonas</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            
            <header class="content-header">
                <h1>Gestión de Base de Datos</h1>
                <div class="user-profile">
                    <?php echo '<img src="'.$_SESSION["user_imagen"].'" alt="Admin Avatar">'; ?>
                    <span>Administrador <?php echo $_SESSION["user_nom"]; ?></span>
                    <a href="index.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Salir</a>
                </div>
            </header>

            <div class="content-body">

                <!-- Modal para añadir comentario -->
                <div class="modal-overlay" id="modalComentario">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nuevo Comentario</h3>
                            <button class="modal-close" onclick="cerrarModal('modalComentario')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirComentario">
                            <div class="form-group">
                                <label for="noticia_id_com">Noticia ID</label>
                                <input type="number" id="noticia_id_com" name="noticia_id" required placeholder="ID de la noticia">
                            </div>
                            <div class="form-group">
                                <label for="usuario_id_com">Usuario ID</label>
                                <input type="number" id="usuario_id_com" name="usuario_id" required placeholder="ID del usuario">
                            </div>
                            <div class="form-group">
                                <label for="contenido_com">Contenido</label>
                                <input type="text" id="contenido_com" name="contenido" required placeholder="Contenido del comentario">
                            </div>
                            <div class="form-group">
                                <label for="parent_comentario_id">Parent Comentario ID (opcional)</label>
                                <input type="number" id="parent_comentario_id" name="parent_comentario_id" placeholder="ID del comentario padre">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Comentario</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar comentario -->
                <div class="modal-overlay" id="modalEditarComentario">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Comentario</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarComentario')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarComentario">
                            <input type="hidden" id="edit_comentario_id" name="comentario_id">
                            <div class="form-group">
                                <label for="edit_noticia_id_com">Noticia ID</label>
                                <input type="number" id="edit_noticia_id_com" name="noticia_id" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_usuario_id_com">Usuario ID</label>
                                <input type="number" id="edit_usuario_id_com" name="usuario_id" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_contenido_com">Contenido</label>
                                <input type="text" id="edit_contenido_com" name="contenido" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_parent_comentario_id">Parent Comentario ID (opcional)</label>
                                <input type="number" id="edit_parent_comentario_id" name="parent_comentario_id">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Comentario</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="comentarios">
                    <div class="db-management-header">
                        <h3>Tabla: Comentarios</h3>
                        <button class="btn-add" onclick="abrirModal('modalComentario')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>comentario_id</th>
                                    <th>noticia_id</th>
                                    <th>usuario_id</th>
                                    <th>contenido</th>
                                    <th>fecha_comentario</th>
                                    <th>parent_comentario_id</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $comentaris = llegitComentarisSencers($mysqli);
                                    //var_dump($comentaris);
                                    foreach($comentaris as $comentari) {
                                        echo "<tr>
                                            <td>".$comentari['comentario_id']."</td>
                                            <td>".$comentari['noticia_id']."</td>
                                            <td>".$comentari['usuario_id']."</td>
                                            <td>".$comentari['contenido']."</td>
                                            <td>".$comentari['fecha_comentario']."</td>
                                            ";
                                        if ($comentari['parent_comentario_id'] === NULL) {
                                            echo "<td>NULL</td>";
                                        }else {
                                            echo "<td>".$comentari['parent_comentario_id']."</td>";
                                        }
                                        $parentId = $comentari['parent_comentario_id'] === NULL ? '' : $comentari['parent_comentario_id'];
                                        echo '<td class="action-btns">
                                                <button class="btn-edit" onclick="editarComentario('.$comentari['comentario_id'].', '.$comentari['noticia_id'].', '.$comentari['usuario_id'].', \''.addslashes($comentari['contenido']).'\', \''.$parentId.'\')">Editar</button>
                                                <button class="btn-delete" onclick="confirmarEliminar(\'comentario\', '.$comentari['comentario_id'].')">Borrar</button>
                                            </td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir detalle reserva -->
                <div class="modal-overlay" id="modalDetalleReserva">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Detalle Reserva</h3>
                            <button class="modal-close" onclick="cerrarModal('modalDetalleReserva')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirDetalleReserva">
                            <div class="form-group">
                                <label for="reserva_id_det">Reserva ID</label>
                                <input type="number" id="reserva_id_det" name="reserva_id" required placeholder="ID de la reserva">
                            </div>
                            <div class="form-group">
                                <label for="entrada_id_det">Entrada ID</label>
                                <input type="number" id="entrada_id_det" name="entrada_id" required placeholder="ID de la entrada">
                            </div>
                            <div class="form-group">
                                <label for="precio_unidad_auditoria">Precio Unidad Auditoria</label>
                                <input type="number" step="0.01" id="precio_unidad_auditoria" name="precio_unidad_auditoria" required placeholder="Precio por unidad">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Detalle</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar detalle reserva -->
                <div class="modal-overlay" id="modalEditarDetalleReserva">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Detalle Reserva</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarDetalleReserva')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarDetalleReserva">
                            <input type="hidden" id="edit_detalle_id" name="detalle_id">
                            <div class="form-group">
                                <label for="edit_reserva_id_det">Reserva ID</label>
                                <input type="number" id="edit_reserva_id_det" name="reserva_id" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_entrada_id_det">Entrada ID</label>
                                <input type="number" id="edit_entrada_id_det" name="entrada_id" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_precio_unidad_auditoria">Precio Unidad Auditoria</label>
                                <input type="number" step="0.01" id="edit_precio_unidad_auditoria" name="precio_unidad_auditoria" required>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Detalle</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="detalleReserva">
                    <div class="db-management-header">
                        <h3>Tabla: detalle_reserva</h3>
                        <button class="btn-add" onclick="abrirModal('modalDetalleReserva')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>detalle_id</th>
                                    <th>reserva_id</th>
                                    <th>entrada_id</th>
                                    <th>precio_unidad_auditoria</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $detallesReservas = llegirDatalleResercaSencer($mysqli);
                                    foreach($detallesReservas as $detalleReserva) {
                                        echo "<tr>
                                            <td>".$detalleReserva['detalle_id']."</td>
                                            <td>".$detalleReserva['reserva_id']."</td>
                                            <td>".$detalleReserva['entrada_id']."</td>
                                            <td>".$detalleReserva['precio_unidad_auditoria']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarDetalleReserva(".$detalleReserva['detalle_id'].", ".$detalleReserva['reserva_id'].", ".$detalleReserva['entrada_id'].", ".$detalleReserva['precio_unidad_auditoria'].")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"detalle_reserva\", ".$detalleReserva['detalle_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir entrada -->
                <div class="modal-overlay" id="modalEntrada">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nueva Entrada</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEntrada')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirEntrada">
                            <div class="form-group">
                                <label for="evento_id">Evento ID</label>
                                <input type="number" id="evento_id" name="evento_id" required placeholder="ID del evento">
                            </div>
                            <div class="form-group">
                                <label for="zona_id">Zona ID</label>
                                <input type="number" id="zona_id" name="zona_id" required placeholder="ID de la zona">
                            </div>
                            <div class="form-group">
                                <label for="precio">Precio</label>
                                <input type="number" step="0.01" id="precio" name="precio" required placeholder="Precio de la entrada">
                            </div>
                            <div class="form-group">
                                <label for="stock_disponible">Stock Disponible</label>
                                <input type="number" id="stock_disponible" name="stock_disponible" required placeholder="Cantidad disponible">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Entrada</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar entrada -->
                <div class="modal-overlay" id="modalEditarEntrada">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Entrada</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarEntrada')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarEntrada">
                            <input type="hidden" id="edit_entrada_id" name="entrada_id">
                            <div class="form-group">
                                <label for="edit_evento_id">Evento ID</label>
                                <input type="number" id="edit_evento_id" name="evento_id" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_zona_id">Zona ID</label>
                                <input type="number" id="edit_zona_id" name="zona_id" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_precio">Precio</label>
                                <input type="number" step="0.01" id="edit_precio" name="precio" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_stock_disponible">Stock Disponible</label>
                                <input type="number" id="edit_stock_disponible" name="stock_disponible" required>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Entrada</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="entradas">
                    <div class="db-management-header">
                        <h3>Tabla: Entradas</h3>
                        <button class="btn-add" onclick="abrirModal('modalEntrada')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>entrada_id</th>
                                    <th>evento_id</th>
                                    <th>zona_id</th>
                                    <th>precio</th>
                                    <th>stock_disponible</th>
                                    <th>acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $entrades = llegirentradesSencer($mysqli);
                                    foreach($entrades as $entrada) {
                                        echo "<tr>
                                            <td>".$entrada['entrada_id']."</td>
                                            <td>".$entrada['evento_id']."</td>
                                            <td>".$entrada['zona_id']."</td>
                                            <td>".$entrada['precio']."</td>
                                            <td>".$entrada['stock_disponible']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarEntrada(".$entrada['entrada_id'].", ".$entrada['evento_id'].", ".$entrada['zona_id'].", ".$entrada['precio'].", ".$entrada['stock_disponible'].")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"entrada\", ".$entrada['entrada_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir evento -->
                <div class="modal-overlay" id="modalEvento">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nuevo Evento</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEvento')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirEvento">
                            <div class="form-group">
                                <label for="nombre_evento">Nombre Evento</label>
                                <input type="text" id="nombre_evento" name="nombre_evento" required placeholder="Nombre del evento">
                            </div>
                            <div class="form-group">
                                <label for="fecha_hora">Fecha y Hora</label>
                                <input type="datetime-local" id="fecha_hora" name="fecha_hora" required>
                            </div>
                            <div class="form-group">
                                <label for="ubicacion">Ubicación</label>
                                <input type="text" id="ubicacion" name="ubicacion" required placeholder="Ubicación del evento">
                            </div>
                            <div class="form-group">
                                <label for="deporte">Deporte</label>
                                <input type="text" id="deporte" name="deporte" required placeholder="Tipo de deporte">
                            </div>
                            <div class="form-group">
                                <label for="descripcion_evento">Descripción</label>
                                <input type="text" id="descripcion_evento" name="descripcion" placeholder="Descripción del evento">
                            </div>
                            <div class="form-group">
                                <label for="imagen_url_evento">Imagen URL</label>
                                <input type="text" id="imagen_url_evento" name="imagen_url" placeholder="URL de la imagen">
                            </div>
                            <div class="form-group">
                                <label for="es_Activo">Es Activo</label>
                                <select id="es_Activo" name="es_Activo" required>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Evento</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar evento -->
                <div class="modal-overlay" id="modalEditarEvento">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Evento</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarEvento')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarEvento">
                            <input type="hidden" id="edit_evento_id_ev" name="evento_id">
                            <div class="form-group">
                                <label for="edit_nombre_evento">Nombre Evento</label>
                                <input type="text" id="edit_nombre_evento" name="nombre_evento" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_fecha_hora">Fecha y Hora</label>
                                <input type="datetime-local" id="edit_fecha_hora" name="fecha_hora" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_ubicacion">Ubicación</label>
                                <input type="text" id="edit_ubicacion" name="ubicacion" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_deporte">Deporte</label>
                                <input type="text" id="edit_deporte" name="deporte" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_descripcion_evento">Descripción</label>
                                <input type="text" id="edit_descripcion_evento" name="descripcion">
                            </div>
                            <div class="form-group">
                                <label for="edit_imagen_url_evento">Imagen URL</label>
                                <input type="text" id="edit_imagen_url_evento" name="imagen_url">
                            </div>
                            <div class="form-group">
                                <label for="edit_es_Activo">Es Activo</label>
                                <select id="edit_es_Activo" name="es_Activo" required>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Evento</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="eventos">
                    <div class="db-management-header">
                        <h3>Tabla: Eventos</h3>
                        <button class="btn-add" onclick="abrirModal('modalEvento')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>evento_id</th>
                                    <th>nombre_evento</th>
                                    <th>fecha_hora</th>
                                    <th>ubicacion</th>
                                    <th>deporte</th>
                                    <th>descripcion</th>
                                    <th>imagen_url</th>
                                    <th>es_Activo</th>
                                    <th>acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $eventos = llegirEventosSencer($mysqli);
                                    foreach($eventos as $evento) {
                                        echo "<tr>
                                            <td>".$evento['evento_id']."</td>
                                            <td>".$evento['nombre_evento']."</td>
                                            <td>".$evento['fecha_hora']."</td>
                                            <td>".$evento['ubicacion']."</td>
                                            <td>".$evento['deporte']."</td>
                                            <td>".$evento['descripcion']."</td>
                                            <td>".$evento['imagen_url']."</td>
                                            <td>".$evento['es_activo']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarEvento(".$evento['evento_id'].", \"".addslashes($evento['nombre_evento'])."\", \"".str_replace(' ', 'T', $evento['fecha_hora'])."\", \"".addslashes($evento['ubicacion'])."\", \"".addslashes($evento['deporte'])."\", \"".addslashes($evento['descripcion'])."\", \"".addslashes($evento['imagen_url'])."\", ".$evento['es_activo'].")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"evento\", ".$evento['evento_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir FAQ -->
                <div class="modal-overlay" id="modalFaq">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nueva FAQ</h3>
                            <button class="modal-close" onclick="cerrarModal('modalFaq')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirFaq">
                            <div class="form-group">
                                <label for="pregunta">Pregunta</label>
                                <input type="text" id="pregunta" name="pregunta" required placeholder="Escriba la pregunta">
                            </div>
                            <div class="form-group">
                                <label for="respuesta">Respuesta</label>
                                <input type="text" id="respuesta" name="respuesta" required placeholder="Escriba la respuesta">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar FAQ</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar FAQ -->
                <div class="modal-overlay" id="modalEditarFaq">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar FAQ</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarFaq')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarFaq">
                            <input type="hidden" id="edit_faq_id" name="faq_id">
                            <div class="form-group">
                                <label for="edit_pregunta">Pregunta</label>
                                <input type="text" id="edit_pregunta" name="pregunta" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_respuesta">Respuesta</label>
                                <input type="text" id="edit_respuesta" name="respuesta" required>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar FAQ</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="faqs">
                    <div class="db-management-header">
                        <h3>Tabla: Faqs</h3>
                        <button class="btn-add" onclick="abrirModal('modalFaq')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>faq_id</th>
                                    <th>pregunta</th>
                                    <th>respuesta</th>
                                    <th>acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $faqs = llegirFaqsSencer($mysqli);
                                    foreach($faqs as $faq) {
                                        echo "<tr>
                                            <td>".$faq['faq_id']."</td>
                                            <td>".$faq['pregunta']."</td>
                                            <td>".$faq['respuesta']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarFaq(".$faq['faq_id'].", \"".addslashes($faq['pregunta'])."\", \"".addslashes($faq['respuesta'])."\")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"faq\", ".$faq['faq_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir noticia -->
                <div class="modal-overlay" id="modalNoticia">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nueva Noticia</h3>
                            <button class="modal-close" onclick="cerrarModal('modalNoticia')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirNoticia">
                            <div class="form-group">
                                <label for="usuario_id_not">Usuario ID</label>
                                <input type="number" id="usuario_id_not" name="usuario_id" required placeholder="ID del usuario">
                            </div>
                            <div class="form-group">
                                <label for="titulo">Título</label>
                                <input type="text" id="titulo" name="titulo" required placeholder="Título de la noticia">
                            </div>
                            <div class="form-group">
                                <label for="subtitulo">Subtítulo</label>
                                <input type="text" id="subtitulo" name="subtitulo" placeholder="Subtítulo de la noticia">
                            </div>
                            <div class="form-group">
                                <label for="contenido_not">Contenido</label>
                                <input type="text" id="contenido_not" name="contenido" required placeholder="Contenido de la noticia">
                            </div>
                            <div class="form-group">
                                <label for="imagen_url_not">Imagen URL</label>
                                <input type="text" id="imagen_url_not" name="imagen_url" placeholder="URL de la imagen">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Noticia</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar noticia -->
                <div class="modal-overlay" id="modalEditarNoticia">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Noticia</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarNoticia')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarNoticia">
                            <input type="hidden" id="edit_noticia_id" name="noticia_id">
                            <div class="form-group">
                                <label for="edit_usuario_id_not">Usuario ID</label>
                                <input type="number" id="edit_usuario_id_not" name="usuario_id" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_titulo">Título</label>
                                <input type="text" id="edit_titulo" name="titulo" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_subtitulo">Subtítulo</label>
                                <input type="text" id="edit_subtitulo" name="subtitulo">
                            </div>
                            <div class="form-group">
                                <label for="edit_contenido_not">Contenido</label>
                                <input type="text" id="edit_contenido_not" name="contenido" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_imagen_url_not">Imagen URL</label>
                                <input type="text" id="edit_imagen_url_not" name="imagen_url">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Noticia</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="noticias">
                    <div class="db-management-header">
                        <h3>Tabla: Noticias</h3>
                        <button class="btn-add" onclick="abrirModal('modalNoticia')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>noticia_id</th>
                                    <th>usuario_id</th>
                                    <th>titulo</th>
                                    <th>subtitulo</th>
                                    <th>contenido</th>
                                    <th>fecha_publicacion</th>
                                    <th>imagen_url</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $noticias = llegirNoticiasSencer($mysqli);
                                    foreach($noticias as $noticia) {
                                        echo "<tr>
                                            <td>".$noticia['noticia_id']."</td>
                                            <td>".$noticia['usuario_id']."</td>
                                            <td>".$noticia['titulo']."</td>
                                            <td>".$noticia['subtitulo']."</td>
                                            <td>".$noticia['contenido']."</td>
                                            <td>".$noticia['fecha_publicacion']."</td>
                                            <td>".$noticia['imagen_url']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarNoticia(".$noticia['noticia_id'].", ".$noticia['usuario_id'].", \"".addslashes($noticia['titulo'])."\", \"".addslashes($noticia['subtitulo'])."\", \"".addslashes($noticia['contenido'])."\", \"".addslashes($noticia['imagen_url'])."\")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"noticia\", ".$noticia['noticia_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir portfolio -->
                <div class="modal-overlay" id="modalPortfolio">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nuevo Portfolio</h3>
                            <button class="modal-close" onclick="cerrarModal('modalPortfolio')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirPortfolio">
                            <div class="form-group">
                                <label for="titulo_port">Título</label>
                                <input type="text" id="titulo_port" name="titulo" required placeholder="Título del proyecto">
                            </div>
                            <div class="form-group">
                                <label for="descripcion_port">Descripción</label>
                                <input type="text" id="descripcion_port" name="descripcion" placeholder="Descripción del proyecto">
                            </div>
                            <div class="form-group">
                                <label for="cliente">Cliente</label>
                                <input type="text" id="cliente" name="cliente" placeholder="Nombre del cliente">
                            </div>
                            <div class="form-group">
                                <label for="fecha_proyecto">Fecha Proyecto</label>
                                <input type="date" id="fecha_proyecto" name="fecha_proyecto" required>
                            </div>
                            <div class="form-group">
                                <label for="imagen_url_port">Imagen URL</label>
                                <input type="text" id="imagen_url_port" name="imagen_url" placeholder="URL de la imagen">
                            </div>
                            <div class="form-group">
                                <label for="es_destacado">Es Destacado</label>
                                <select id="es_destacado" name="es_destacado" required>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_tipoDeporte_port">Tipo Deporte ID</label>
                                <input type="number" id="id_tipoDeporte_port" name="id_tipoDeporte" required placeholder="ID del tipo de deporte">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Portfolio</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar portfolio -->
                <div class="modal-overlay" id="modalEditarPortfolio">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Portfolio</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarPortfolio')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarPortfolio">
                            <input type="hidden" id="edit_portfolio_id" name="portfolio_id">
                            <div class="form-group">
                                <label for="edit_titulo_port">Título</label>
                                <input type="text" id="edit_titulo_port" name="titulo" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_descripcion_port">Descripción</label>
                                <input type="text" id="edit_descripcion_port" name="descripcion">
                            </div>
                            <div class="form-group">
                                <label for="edit_cliente">Cliente</label>
                                <input type="text" id="edit_cliente" name="cliente">
                            </div>
                            <div class="form-group">
                                <label for="edit_fecha_proyecto">Fecha Proyecto</label>
                                <input type="date" id="edit_fecha_proyecto" name="fecha_proyecto" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_imagen_url_port">Imagen URL</label>
                                <input type="text" id="edit_imagen_url_port" name="imagen_url">
                            </div>
                            <div class="form-group">
                                <label for="edit_es_destacado">Es Destacado</label>
                                <select id="edit_es_destacado" name="es_destacado" required>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_id_tipoDeporte_port">Tipo Deporte ID</label>
                                <input type="number" id="edit_id_tipoDeporte_port" name="id_tipoDeporte" required>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Portfolio</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="portfolio">
                    <div class="db-management-header">
                        <h3>Tabla: Portfolio</h3>
                        <button class="btn-add" onclick="abrirModal('modalPortfolio')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>portfolio_id</th>
                                    <th>titulo</th>
                                    <th>descripcion</th>
                                    <th>cliente</th>
                                    <th>fecha_proyecto</th>
                                    <th>imagen_url</th>
                                    <th>es_destacado</th>
                                    <th>id_tipoDeporte</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $portfolios = llegirPortfolioSencer($mysqli);
                                    foreach($portfolios as $portfolio) {
                                        echo "<tr>
                                            <td>".$portfolio['portfolio_id']."</td>
                                            <td>".$portfolio['titulo']."</td>
                                            <td>".$portfolio['descripcion']."</td>
                                            <td>".$portfolio['cliente']."</td>
                                            <td>".$portfolio['fecha_proyecto']."</td>
                                            <td>".$portfolio['imagen_url']."</td>
                                            <td>".$portfolio['es_destacado']."</td>
                                            <td>".$portfolio['id_tipoDeporte']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarPortfolio(".$portfolio['portfolio_id'].", \"".addslashes($portfolio['titulo'])."\", \"".addslashes($portfolio['descripcion'])."\", \"".addslashes($portfolio['cliente'])."\", \"".$portfolio['fecha_proyecto']."\", \"".addslashes($portfolio['imagen_url'])."\", ".$portfolio['es_destacado'].", ".$portfolio['id_tipoDeporte'].")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"portfolio\", ".$portfolio['portfolio_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir reserva -->
                <div class="modal-overlay" id="modalReserva">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nueva Reserva</h3>
                            <button class="modal-close" onclick="cerrarModal('modalReserva')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirReserva">
                            <div class="form-group">
                                <label for="usuario_id_res">Usuario ID</label>
                                <input type="number" id="usuario_id_res" name="usuario_id" required placeholder="ID del usuario">
                            </div>
                            <div class="form-group">
                                <label for="total_monto">Total Monto</label>
                                <input type="number" step="0.01" id="total_monto" name="total_monto" required placeholder="Monto total">
                            </div>
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select id="estado" name="estado" required>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="confirmada">Confirmada</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Reserva</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar reserva -->
                <div class="modal-overlay" id="modalEditarReserva">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Reserva</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarReserva')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarReserva">
                            <input type="hidden" id="edit_reserva_id" name="reserva_id">
                            <div class="form-group">
                                <label for="edit_usuario_id_res">Usuario ID</label>
                                <input type="number" id="edit_usuario_id_res" name="usuario_id" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_total_monto">Total Monto</label>
                                <input type="number" step="0.01" id="edit_total_monto" name="total_monto" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_estado">Estado</label>
                                <select id="edit_estado" name="estado" required>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="confirmada">Confirmada</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Reserva</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="reservas">
                    <div class="db-management-header">
                        <h3>Tabla: reservas</h3>
                        <button class="btn-add" onclick="abrirModal('modalReserva')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>reserva_id</th>
                                    <th>usuario_id</th>
                                    <th>fecha_reserva</th>
                                    <th>total_monto</th>
                                    <th>estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $reservas = llegirReservasSencer($mysqli);
                                    foreach($reservas as $reserva) {
                                        echo "<tr>
                                            <td>".$reserva['reserva_id']."</td>
                                            <td>".$reserva['usuario_id']."</td>
                                            <td>".$reserva['fecha_reserva']."</td>
                                            <td>".$reserva['total_monto']."</td>
                                            <td>".$reserva['estado']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarReserva(".$reserva['reserva_id'].", ".$reserva['usuario_id'].", ".$reserva['total_monto'].", \"".$reserva['estado']."\")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"reserva\", ".$reserva['reserva_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir rol -->
                <div class="modal-overlay" id="modalRol">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nuevo Rol</h3>
                            <button class="modal-close" onclick="cerrarModal('modalRol')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirRol">
                            <div class="form-group">
                                <label for="nombre_rol">Nombre del Rol</label>
                                <input type="text" id="nombre_rol" name="nombre_rol" required placeholder="Nombre del rol">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Rol</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar rol -->
                <div class="modal-overlay" id="modalEditarRol">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Rol</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarRol')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarRol">
                            <input type="hidden" id="edit_role_id" name="role_id">
                            <div class="form-group">
                                <label for="edit_nombre_rol">Nombre del Rol</label>
                                <input type="text" id="edit_nombre_rol" name="nombre_rol" required>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Rol</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="roles">
                    <div class="db-management-header">
                        <h3>Tabla: Roles</h3>
                        <button class="btn-add" onclick="abrirModal('modalRol')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>role_id</th>
                                    <th>nombre_rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $roles = llegirRolesSencer($mysqli);
                                    foreach($roles as $rol) {
                                        echo "<tr>
                                            <td>".$rol['role_id']."</td>
                                            <td>".$rol['nombre_rol']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarRol(".$rol['role_id'].", \"".addslashes($rol['nombre_rol'])."\")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"rol\", ".$rol['role_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir testimonio -->
                <div class="modal-overlay" id="modalTestimonio">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nuevo Testimonio</h3>
                            <button class="modal-close" onclick="cerrarModal('modalTestimonio')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirTestimonio">
                            <div class="form-group">
                                <label for="usuario_id_test">Usuario ID</label>
                                <input type="number" id="usuario_id_test" name="usuario_id" required placeholder="ID del usuario">
                            </div>
                            <div class="form-group">
                                <label for="nombre_cliente">Nombre Cliente</label>
                                <input type="text" id="nombre_cliente" name="nombre_cliente" required placeholder="Nombre del cliente">
                            </div>
                            <div class="form-group">
                                <label for="cargo">Cargo</label>
                                <input type="text" id="cargo" name="cargo" placeholder="Cargo del cliente">
                            </div>
                            <div class="form-group">
                                <label for="contenido_test">Contenido</label>
                                <input type="text" id="contenido_test" name="contenido" required placeholder="Contenido del testimonio">
                            </div>
                            <div class="form-group">
                                <label for="puntuacion">Puntuación (1-5)</label>
                                <input type="number" id="puntuacion" name="puntuacion" min="1" max="5" required placeholder="Puntuación">
                            </div>
                            <div class="form-group">
                                <label for="es_aprobado">Es Aprobado</label>
                                <select id="es_aprobado" name="es_aprobado" required>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Testimonio</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar testimonio -->
                <div class="modal-overlay" id="modalEditarTestimonio">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Testimonio</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarTestimonio')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarTestimonio">
                            <input type="hidden" id="edit_testimonio_id" name="testimonio_id">
                            <div class="form-group">
                                <label for="edit_usuario_id_test">Usuario ID</label>
                                <input type="number" id="edit_usuario_id_test" name="usuario_id" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_nombre_cliente">Nombre Cliente</label>
                                <input type="text" id="edit_nombre_cliente" name="nombre_cliente" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_cargo">Cargo</label>
                                <input type="text" id="edit_cargo" name="cargo">
                            </div>
                            <div class="form-group">
                                <label for="edit_contenido_test">Contenido</label>
                                <input type="text" id="edit_contenido_test" name="contenido" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_puntuacion">Puntuación (1-5)</label>
                                <input type="number" id="edit_puntuacion" name="puntuacion" min="1" max="5" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_es_aprobado">Es Aprobado</label>
                                <select id="edit_es_aprobado" name="es_aprobado" required>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Testimonio</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="testimonios">
                    <div class="db-management-header">
                        <h3>Tabla: testimonios</h3>
                        <button class="btn-add" onclick="abrirModal('modalTestimonio')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>testimonio_id</th>
                                    <th>usuario_id</th>
                                    <th>nombre_cliente</th>
                                    <th>cargo</th>
                                    <th>contenido</th>
                                    <th>fecha_testimonio</th>
                                    <th>puntuacion</th>
                                    <th>es_aprobado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $testimonios = llegirTestimoniosSencer($mysqli);
                                    foreach($testimonios as $testimonio) {
                                        echo "<tr>
                                            <td>".$testimonio['testimonio_id']."</td>
                                            <td>".$testimonio['usuario_id']."</td>
                                            <td>".$testimonio['nombre_cliente']."</td>
                                            <td>".$testimonio['cargo']."</td>
                                            <td>".$testimonio['contenido']."</td>
                                            <td>".$testimonio['fecha_testimonio']."</td>
                                            <td>".$testimonio['puntuacion']."</td>
                                            <td>".$testimonio['es_aprobado']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarTestimonio(".$testimonio['testimonio_id'].", ".$testimonio['usuario_id'].", \"".addslashes($testimonio['nombre_cliente'])."\", \"".addslashes($testimonio['cargo'])."\", \"".addslashes($testimonio['contenido'])."\", ".$testimonio['puntuacion'].", ".$testimonio['es_aprobado'].")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"testimonio\", ".$testimonio['testimonio_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir tipo deporte -->
                <div class="modal-overlay" id="modalTipoDeporte">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Tipo Deporte</h3>
                            <button class="modal-close" onclick="cerrarModal('modalTipoDeporte')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirTipoDeporte">
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <input type="text" id="tipo" name="tipo" required placeholder="Tipo de deporte">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Tipo Deporte</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar tipo deporte -->
                <div class="modal-overlay" id="modalEditarTipoDeporte">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Tipo Deporte</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarTipoDeporte')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarTipoDeporte">
                            <input type="hidden" id="edit_id_tipoDeporte" name="id_tipoDeporte">
                            <div class="form-group">
                                <label for="edit_tipo">Tipo</label>
                                <input type="text" id="edit_tipo" name="tipo" required>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Tipo Deporte</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="tipoDeporte">
                    <div class="db-management-header">
                        <h3>Tabla: tipo deporte</h3>
                        <button class="btn-add" onclick="abrirModal('modalTipoDeporte')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>id_tipoDeporte</th>
                                    <th>tipo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $tiposDeporte = llegirTipoDeporteSencer($mysqli);
                                    foreach($tiposDeporte as $tipoDeporte) {
                                        echo "<tr>
                                            <td>".$tipoDeporte['id_tipoDeporte']."</td>
                                            <td>".$tipoDeporte['tipo']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarTipoDeporte(".$tipoDeporte['id_tipoDeporte'].", \"".addslashes($tipoDeporte['tipo'])."\")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"tipo_deporte\", ".$tipoDeporte['id_tipoDeporte'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para añadir usuario -->
                <div class="modal-overlay" id="modalUsuario">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nuevo Usuario</h3>
                            <button class="modal-close" onclick="cerrarModal('modalUsuario')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirUsuario">
                            <div class="form-group">
                                <label for="role_id_user">Role ID</label>
                                <input type="number" id="role_id_user" name="role_id" required placeholder="ID del rol">
                            </div>
                            <div class="form-group">
                                <label for="nombre_user">Nombre</label>
                                <input type="text" id="nombre_user" name="nombre" required placeholder="Nombre del usuario">
                            </div>
                            <div class="form-group">
                                <label for="apellido_user">Apellido</label>
                                <input type="text" id="apellido_user" name="apellido" required placeholder="Apellido del usuario">
                            </div>
                            <div class="form-group">
                                <label for="email_user">Email</label>
                                <input type="email" id="email_user" name="email" required placeholder="Email del usuario">
                            </div>
                            <div class="form-group">
                                <label for="contrasenya">Contraseña</label>
                                <input type="password" id="contrasenya" name="contrasenya" required placeholder="Contraseña">
                            </div>
                            <div class="form-group">
                                <label for="foto_user">Foto URL</label>
                                <input type="text" id="foto_user" name="foto" placeholder="URL de la foto">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Usuario</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar usuario -->
                <div class="modal-overlay" id="modalEditarUsuario">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Usuario</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarUsuario')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarUsuario">
                            <input type="hidden" id="edit_usuario_id" name="usuario_id">
                            <div class="form-group">
                                <label for="edit_role_id_user">Role ID</label>
                                <input type="number" id="edit_role_id_user" name="role_id" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_nombre_user">Nombre</label>
                                <input type="text" id="edit_nombre_user" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_apellido_user">Apellido</label>
                                <input type="text" id="edit_apellido_user" name="apellido" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_email_user">Email</label>
                                <input type="email" id="edit_email_user" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_contrasenya">Nueva Contraseña (dejar vacío para no cambiar)</label>
                                <input type="password" id="edit_contrasenya" name="contrasenya" placeholder="Nueva contraseña">
                            </div>
                            <div class="form-group">
                                <label for="edit_foto_user">Foto URL</label>
                                <input type="text" id="edit_foto_user" name="foto">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Usuario</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="usuarios">
                    <div class="db-management-header">
                        <h3>Tabla: usuarios</h3>
                        <button class="btn-add" onclick="abrirModal('modalUsuario')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>usuario_id</th>
                                    <th>role_id</th>
                                    <th>nombre</th>
                                    <th>apellido</th>
                                    <th>email</th>
                                    <th>contrasenya_hash</th>
                                    <th>foto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $usuarios = llegirUsuarisSencer($mysqli);
                                    foreach($usuarios as $usuario) {
                                        echo "<tr>
                                            <td>".$usuario['usuario_id']."</td>
                                            <td>".$usuario['role_id']."</td>
                                            <td>".$usuario['nombre']."</td>
                                            <td>".$usuario['apellido']."</td>
                                            <td>".$usuario['email']."</td>
                                            <td>".$usuario['contrasena_hash']."</td>
                                            <td>".$usuario['foto']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarUsuario(".$usuario['usuario_id'].", ".$usuario['role_id'].", \"".addslashes($usuario['nombre'])."\", \"".addslashes($usuario['apellido'])."\", \"".addslashes($usuario['email'])."\", \"".addslashes($usuario['foto'])."\")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"usuario\", ".$usuario['usuario_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal de confirmación para eliminar -->
                <div class="modal-overlay" id="modalConfirmarEliminar">
                    <div class="modal-content" style="max-width: 400px;">
                        <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                            <h3><i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación</h3>
                            <button class="modal-close" onclick="cerrarModal('modalConfirmarEliminar')">&times;</button>
                        </div>
                        <div style="padding: 20px; text-align: center;">
                            <i class="fas fa-trash-alt" style="font-size: 48px; color: #dc3545; margin-bottom: 15px;"></i>
                            <p style="font-size: 16px; margin-bottom: 10px;">¿Estás seguro que quieres eliminar este registro?</p>
                            <p style="font-size: 14px; color: #666;" id="deleteItemInfo"></p>
                            <div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                                <button class="btn-submit" style="background: #6c757d;" onclick="cerrarModal('modalConfirmarEliminar')"><i class="fas fa-times"></i> Cancelar</button>
                                <button class="btn-submit" style="background: #dc3545;" id="btnConfirmarEliminar"><i class="fas fa-trash"></i> Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para añadir zona -->
                <div class="modal-overlay" id="modalZona">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-plus-circle"></i> Añadir Nueva Zona</h3>
                            <button class="modal-close" onclick="cerrarModal('modalZona')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="añadirZona">
                            <div class="form-group">
                                <label for="nombre_zona">Nombre Zona</label>
                                <input type="text" id="nombre_zona" name="nombre_zona" required placeholder="Nombre de la zona">
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Zona</button>
                        </form>
                    </div>
                </div>

                <!-- Modal para editar zona -->
                <div class="modal-overlay" id="modalEditarZona">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><i class="fas fa-edit"></i> Editar Zona</h3>
                            <button class="modal-close" onclick="cerrarModal('modalEditarZona')">&times;</button>
                        </div>
                        <form action="funciones/funciones.php" method="POST">
                            <input type="hidden" name="accion" value="editarZona">
                            <input type="hidden" id="edit_zona_id" name="zona_id">
                            <div class="form-group">
                                <label for="edit_nombre_zona">Nombre Zona</label>
                                <input type="text" id="edit_nombre_zona" name="nombre_zona" required>
                            </div>
                            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Actualizar Zona</button>
                        </form>
                    </div>
                </div>

                <div class="db-management-card" id="zonas">
                    <div class="db-management-header">
                        <h3>Tabla: zonas</h3>
                        <button class="btn-add" onclick="abrirModal('modalZona')"><i class="fas fa-plus"></i> Añadir Nueva Entrada</button>
                    </div>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>zona_id</th>
                                    <th>nombre_zona</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once "./funciones/funciones.php";
                                    $zonas = llegirZonasSencer($mysqli);
                                    foreach($zonas as $zona) {
                                        echo "<tr>
                                            <td>".$zona['zona_id']."</td>
                                            <td>".$zona['nombre_zona']."</td>
                                            <td class='action-btns'>
                                                <button class='btn-edit' onclick='editarZona(".$zona['zona_id'].", \"".addslashes($zona['nombre_zona'])."\")'>Editar</button>
                                                <button class='btn-delete' onclick='confirmarEliminar(\"zona\", ".$zona['zona_id'].")'>Borrar</button>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        
    </div>

    <script>
        function abrirModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function cerrarModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Variables para almacenar información de eliminación
        let deleteType = '';
        let deleteId = '';

        // Mapeo de tipos a nombres de acción
        const accionesEliminar = {
            'comentario': 'eliminarComentario',
            'detalle_reserva': 'eliminarDetalleReserva',
            'entrada': 'eliminarEntrada',
            'evento': 'eliminarEvento',
            'faq': 'eliminarFaq',
            'noticia': 'eliminarNoticia',
            'portfolio': 'eliminarPortfolio',
            'reserva': 'eliminarReserva',
            'rol': 'eliminarRol',
            'testimonio': 'eliminarTestimonio',
            'tipo_deporte': 'eliminarTipoDeporte',
            'usuario': 'eliminarUsuario',
            'zona': 'eliminarZona'
        };

        // Función para mostrar el modal de confirmación de eliminación
        function confirmarEliminar(tipo, id) {
            deleteType = tipo;
            deleteId = id;
            
            // Nombres amigables para mostrar
            const nombres = {
                'comentario': 'Comentario',
                'detalle_reserva': 'Detalle de Reserva',
                'entrada': 'Entrada',
                'evento': 'Evento',
                'faq': 'FAQ',
                'noticia': 'Noticia',
                'portfolio': 'Portfolio',
                'reserva': 'Reserva',
                'rol': 'Rol',
                'testimonio': 'Testimonio',
                'tipo_deporte': 'Tipo de Deporte',
                'usuario': 'Usuario',
                'zona': 'Zona'
            };
            
            document.getElementById('deleteItemInfo').textContent = nombres[tipo] + ' con ID: ' + id;
            abrirModal('modalConfirmarEliminar');
        }

        // Configurar el botón de confirmar eliminación
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
                const accion = accionesEliminar[deleteType];
                if (accion) {
                    window.location.href = 'funciones/funciones.php?accion=' + accion + '&id=' + deleteId;
                }
                cerrarModal('modalConfirmarEliminar');
            });

            // Mostrar mensaje si existe
            const urlParams = new URLSearchParams(window.location.search);
            const mensaje = urlParams.get('mensaje');
            const tipo = urlParams.get('tipo');
            
            if (mensaje) {
                mostrarNotificacion(mensaje, tipo);
                // Limpiar URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        // Función para mostrar notificaciones
        function mostrarNotificacion(mensaje, tipo) {
            const notificacion = document.createElement('div');
            notificacion.className = 'notificacion ' + tipo;
            notificacion.innerHTML = '<i class="fas fa-' + (tipo === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + mensaje;
            document.body.appendChild(notificacion);
            
            setTimeout(() => {
                notificacion.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                notificacion.classList.remove('show');
                setTimeout(() => notificacion.remove(), 300);
            }, 3000);
        }

        // Cerrar modal al hacer clic fuera del contenido
        document.querySelectorAll('.modal-overlay').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        // Cerrar modal con la tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(function(modal) {
                    modal.classList.remove('active');
                });
            }
        });

        // Funciones de edición
        function editarComentario(id, noticiaId, usuarioId, contenido, parentId) {
            document.getElementById('edit_comentario_id').value = id;
            document.getElementById('edit_noticia_id_com').value = noticiaId;
            document.getElementById('edit_usuario_id_com').value = usuarioId;
            document.getElementById('edit_contenido_com').value = contenido;
            document.getElementById('edit_parent_comentario_id').value = parentId;
            abrirModal('modalEditarComentario');
        }

        function editarDetalleReserva(id, reservaId, entradaId, precio) {
            document.getElementById('edit_detalle_id').value = id;
            document.getElementById('edit_reserva_id_det').value = reservaId;
            document.getElementById('edit_entrada_id_det').value = entradaId;
            document.getElementById('edit_precio_unidad_auditoria').value = precio;
            abrirModal('modalEditarDetalleReserva');
        }

        function editarEntrada(id, eventoId, zonaId, precio, stock) {
            document.getElementById('edit_entrada_id').value = id;
            document.getElementById('edit_evento_id').value = eventoId;
            document.getElementById('edit_zona_id').value = zonaId;
            document.getElementById('edit_precio').value = precio;
            document.getElementById('edit_stock_disponible').value = stock;
            abrirModal('modalEditarEntrada');
        }

        function editarEvento(id, nombre, fechaHora, ubicacion, deporte, descripcion, imagenUrl, esActivo) {
            document.getElementById('edit_evento_id_ev').value = id;
            document.getElementById('edit_nombre_evento').value = nombre;
            document.getElementById('edit_fecha_hora').value = fechaHora;
            document.getElementById('edit_ubicacion').value = ubicacion;
            document.getElementById('edit_deporte').value = deporte;
            document.getElementById('edit_descripcion_evento').value = descripcion;
            document.getElementById('edit_imagen_url_evento').value = imagenUrl;
            document.getElementById('edit_es_Activo').value = esActivo;
            abrirModal('modalEditarEvento');
        }

        function editarFaq(id, pregunta, respuesta) {
            document.getElementById('edit_faq_id').value = id;
            document.getElementById('edit_pregunta').value = pregunta;
            document.getElementById('edit_respuesta').value = respuesta;
            abrirModal('modalEditarFaq');
        }

        function editarNoticia(id, usuarioId, titulo, subtitulo, contenido, imagenUrl) {
            document.getElementById('edit_noticia_id').value = id;
            document.getElementById('edit_usuario_id_not').value = usuarioId;
            document.getElementById('edit_titulo').value = titulo;
            document.getElementById('edit_subtitulo').value = subtitulo;
            document.getElementById('edit_contenido_not').value = contenido;
            document.getElementById('edit_imagen_url_not').value = imagenUrl;
            abrirModal('modalEditarNoticia');
        }

        function editarPortfolio(id, titulo, descripcion, cliente, fechaProyecto, imagenUrl, esDestacado, tipoDeporteId) {
            document.getElementById('edit_portfolio_id').value = id;
            document.getElementById('edit_titulo_port').value = titulo;
            document.getElementById('edit_descripcion_port').value = descripcion;
            document.getElementById('edit_cliente').value = cliente;
            document.getElementById('edit_fecha_proyecto').value = fechaProyecto;
            document.getElementById('edit_imagen_url_port').value = imagenUrl;
            document.getElementById('edit_es_destacado').value = esDestacado;
            document.getElementById('edit_id_tipoDeporte_port').value = tipoDeporteId;
            abrirModal('modalEditarPortfolio');
        }

        function editarReserva(id, usuarioId, totalMonto, estado) {
            document.getElementById('edit_reserva_id').value = id;
            document.getElementById('edit_usuario_id_res').value = usuarioId;
            document.getElementById('edit_total_monto').value = totalMonto;
            document.getElementById('edit_estado').value = estado;
            abrirModal('modalEditarReserva');
        }

        function editarRol(id, nombreRol) {
            document.getElementById('edit_role_id').value = id;
            document.getElementById('edit_nombre_rol').value = nombreRol;
            abrirModal('modalEditarRol');
        }

        function editarTestimonio(id, usuarioId, nombreCliente, cargo, contenido, puntuacion, esAprobado) {
            document.getElementById('edit_testimonio_id').value = id;
            document.getElementById('edit_usuario_id_test').value = usuarioId;
            document.getElementById('edit_nombre_cliente').value = nombreCliente;
            document.getElementById('edit_cargo').value = cargo;
            document.getElementById('edit_contenido_test').value = contenido;
            document.getElementById('edit_puntuacion').value = puntuacion;
            document.getElementById('edit_es_aprobado').value = esAprobado;
            abrirModal('modalEditarTestimonio');
        }

        function editarTipoDeporte(id, tipo) {
            document.getElementById('edit_id_tipoDeporte').value = id;
            document.getElementById('edit_tipo').value = tipo;
            abrirModal('modalEditarTipoDeporte');
        }

        function editarUsuario(id, roleId, nombre, apellido, email, foto) {
            document.getElementById('edit_usuario_id').value = id;
            document.getElementById('edit_role_id_user').value = roleId;
            document.getElementById('edit_nombre_user').value = nombre;
            document.getElementById('edit_apellido_user').value = apellido;
            document.getElementById('edit_email_user').value = email;
            document.getElementById('edit_foto_user').value = foto;
            abrirModal('modalEditarUsuario');
        }

        function editarZona(id, nombreZona) {
            document.getElementById('edit_zona_id').value = id;
            document.getElementById('edit_nombre_zona').value = nombreZona;
            abrirModal('modalEditarZona');
        }
    </script>
    
</body>
</html>