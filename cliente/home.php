<?php
include_once("../servidor/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'agregar_visita') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellidoV'];
    $precio = $_POST['precioV'];
    $fecha = date('Y-m-d'); 
    date_default_timezone_set('America/Mexico_City');
    $hora = date('H:i:s'); 

    $query = "INSERT INTO visitas (Nombre, Apellido, Fecha, HoraE, Precio) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $apellido, $fecha, $hora, $precio);

    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "Visita agregada exitosamente.";
    } else {
        $mensaje = "Error al agregar la visita: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['filtro'])) {
    $filtro = $_GET['filtro'];
    $response = [];

    if ($filtro == 'semana') {
        $query = "SELECT COUNT(*) AS totalVisitasHoy FROM visitas WHERE Fecha >= CURDATE() - INTERVAL 7 DAY";
    } elseif ($filtro == 'mes') {
        $query = "SELECT COUNT(*) AS totalVisitasHoy FROM visitas WHERE Fecha >= CURDATE() - INTERVAL 1 MONTH";
    } else {
        $query = "SELECT COUNT(*) AS totalVisitasHoy FROM visitas WHERE Fecha = CURDATE()";
    }

    $result = mysqli_query($conexion, $query);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        $response['totalVisitasHoy'] = $data['totalVisitasHoy'] ?? 0;
    } else {
        $response['totalVisitasHoy'] = 0;
    }

    $queryMiembros = "SELECT COUNT(*) AS totalMiembrosHoy FROM visitas WHERE EstadoMembresia = 'Activo' AND Fecha = CURDATE()";
    $resultMiembros = mysqli_query($conexion, $queryMiembros);

    if ($resultMiembros) {
        $dataMiembros = mysqli_fetch_assoc($resultMiembros);
        $response['totalMiembrosHoy'] = $dataMiembros['totalMiembrosHoy'] ?? 0;
    } else {
        $response['totalMiembrosHoy'] = 0;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$cone = mysqli_query($conexion, "SELECT COUNT(*) AS totalVisitasHoy FROM visitasDia WHERE fechaVisita = CURDATE() AND Estado_Membresia = 'Visita';");

if ($cone) {
    $visitasHoy = mysqli_fetch_assoc($cone); 
    $totalVisitasHoy = $visitasHoy['totalVisitasHoy'];
} else {
    $totalVisitasHoy = 0; 
    echo "Error en la consulta de visitas: " . mysqli_error($conexion);
}

$cone = mysqli_query($conexion, "SELECT COUNT(*) AS totalMiembrosHoy FROM visitasDia WHERE fechaVisita = CURDATE() AND Estado_Membresia = 'Activo';");

if ($cone) {
    $miembrosHoy = mysqli_fetch_assoc($cone); 
    $totalMiembrosHoy = $miembrosHoy['totalMiembrosHoy'];
} else {
    $totalMiembrosHoy = 0; 
    echo "Error en la consulta de miembros: " . mysqli_error($conexion);
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Dragons Gym</title>
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <!-- =============== Barra de navegacion ================ -->
    <div class="container">
        <div class="navigation">
             <?php
                include_once("include/encabezado.php")
            ?>     
        </div>

        <!-- ========================= Contenido principal ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <div class="search">
                    <label>
                        <input type="text" placeholder="Buscar miembro">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div>
                <div class="contenedor">
                    <div class="notificacion"  onclick="toggleNotifi()">
                        <ion-icon name="file-tray-full-outline"></ion-icon>
                    </div>
                    <div class="usuario">
                        <img src="https://i.pinimg.com/originals/a0/14/7a/a0147adf0a983ab87e86626f774785cf.gif" alt="">
                    </div>
                    <div class="notifi-box" id="box">
                        <p class="calendario"></p>
                        <div class="notifi-item">
                            <div class="text">
                                <h4>Notificaciones</h4>
                            </div>
                            <div class="calend">
                                <div class="calend">
                                    <div class="calendar">
                                        <div class="calendar-header">
                                            <button id="prev">&lt;</button>
                                            <h3></h3>
                                            <button id="next">&gt;</button>
                                        </div>
                                        <ul class="weekdays">
                                            <li>Dom</li>
                                            <li>Lun</li>
                                            <li>Mar</li>
                                            <li>Mié</li>
                                            <li>Jue</li>
                                            <li>Vie</li>
                                            <li>Sáb</li>
                                        </ul>
                                        <ul class="dates"></ul> 
                                    </div>
                                </div>
                            </div>
                            <div class="noti">

                    
                                <table>
                                    <tr>
                                        <td width="60px">
                                            <div class="imgBx">
                                                <img src="https://i.pinimg.com/originals/f1/b7/93/f1b7935c804bd8349332c922be171c11.gif" alt="">
                                            </div>
                                        </td>
                                        <td>
                                            <h4>Venta Proteina <br><span>14:03</span></h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="60px">
                                            <div class="imgBx"><img src="https://i.pinimg.com/originals/20/81/89/20818965bf4c0505b4ef63ec329813c5.gif" alt=""></div>
                                        </td>
                                        <td>
                                            <h4>Pago membresia <br> <span>18:08</span></h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="60px">
                                            <div class="imgBx"><img src="https://i.pinimg.com/originals/20/81/89/20818965bf4c0505b4ef63ec329813c5.gif" alt=""></div>
                                        </td>
                                        <td>
                                            <h4>Pago membresia <br> <span>18:08</span></h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="60px">
                                            <div class="imgBx"><img src="https://i.pinimg.com/originals/ec/46/54/ec465408972e4dc8bfd212c67af58c37.gif" alt=""></div>
                                        </td>
                                        <td>
                                            <h4>Pago membresia <br> <span>18:08</span></h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="60px">
                                            <div class="imgBx"><img src="imgs/customer01.jpg" alt=""></div>
                                        </td>
                                        <td>
                                            <h4>Pago membresia <br> <span>18:08</span></h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="60px">
                                            <div class="imgBx"><img src="imgs/Notificacion.gif" alt=""></div>
                                        </td>
                                        <td>
                                            <h4>Pago membresia <br> <span>18:08</span></h4>
                                        </td>
                                    </tr>   
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- ======================= Contadores ================== -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $totalVisitasHoy; ?></div>
                        <div class="cardName">Visitas</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="eye-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $totalMiembrosHoy; ?></div>
                        <div class="cardName">Miembros</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="eye-outline"></ion-icon>
                    </div>
                </div>
                <div class="botonV">
                    <button class="agregarV" id="agregarVisitaBtn"><span>Agregar visita</span></button>

                </div>
                
            </div>
            <!-- ================ Modal de visita ================= -->
            <div id="openModal" class="modal">
                <div class="modal-content">
                    <span id="closeModal" style="cursor:pointer;" class="close">&times;</span>
                    <h2>Agregar visita</h2>
                    <form id="myForm" action="" method="POST">
                        <input type="hidden" name="action" value="agregar_visita"> 
                        
                        <div class="imagenV">
                            <img src="imgs/Notificacion.gif" alt="Visita">
                        </div>
                        
                        <div class="contenidoIn">
                            <div class="input-group">
                                <input type="text" id="nombre" name="nombre" required>
                                <label for="nombre">Nombre</label>
                            </div>
                            
                            <div class="input-group">
                                <input type="text" id="apellidoV" name="apellidoV" required>
                                <label for="apellidoV">Apellidos</label>
                            </div>
                            
                            <div class="input-group">
                                <input type="number" id="precioV" name="precioV" required>
                                <label for="precioV">Precio</label>
                            </div>
                        </div>
                        
                        <div class="botonV">
                            <button class="agregarV" id="agregarVisitaBtn" type="submit"><span>Agregar visita</span></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================ Tabla de usuarios ================= -->
            <div class="details">
                <div class="registro">
                <div class="cardHeader">
                    <h2>Lista de visitas</h2>
                    <a href="#" id="estadisticasBtn" class="btn">Gráfica</a>
                </div>
                <div id="modalEstadisticas" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span id="closeEstadisticas" style="cursor:pointer;" class="close">&times;</span>
                        <h2>Estadísticas de Visitas y Miembros</h2>
                        <canvas id="myChart"></canvas>
                        <div>
                            <label for="filtro">Filtrar por:</label>
                            <select id="filtro">
                                <option value="dia">Hoy</option>
                                <option value="semana">Semana</option>
                                <option value="mes">Mes</option>
                            </select>
                            <button id="cargarDatos">Cargar Datos</button>
                        </div>
                    </div>
                </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Nombre</td>
                                <td>Apellidos</td>
                                <td>Hora Entrada</td>
                                <td>Hora Salida</td>
                                <td>Estado</td>
                            </tr>
                        </thead>

                        <tbody>
                        <?php include_once("../servidor/conexion.php");
                            $con = mysqli_query(
                            $conexion,
                            
                            "SELECT Nombre, Apellidos,horaIngreso,horaSalida, Estado_Membresia FROM visitasDia;"
                            );
                            $res = mysqli_num_rows($con);
                            while ($datos = mysqli_fetch_assoc($con)) {   
                        ?>

                            <tr>
                                <td><?php echo $datos['Nombre'];?></td>
                                <td><?php echo $datos['Apellidos'];?></td>
                                <td><?php echo $datos['HoraEntrada'];?></td>
                                <td><?php echo $datos['HoraSalida'];?></td>

                                <td><span class="estado activo"><?php echo $datos['Estado_Membresia'];?></span></td>
                            </tr>
                            <?php   } ?>
                        </tbody>
                    </table>
                </div>

                <!-- ================= Miembros ================ -->
                <div class="miembros">
                    <div class="titulo">
                        <h2>Miembros</h2>
                        <div class="huella">
                            <ion-icon name="finger-print-outline"></ion-icon>
                        </div>
                        
                    </div>
                    <div class="fotoM">
                        
                    </div>
                    <div class="contenidoM">
                        <p>#32</p>
                        <h3>Jorge Solis</h2>
                        <p>2 Meses continuos</p>
                        <div class="fechas">
                            <div class="fechaI">
                                24/9/2024
                            </div>
                            <div class="fechaF">
                                24/10/2024
                            </div>
                        </div>
                        <div class="estadoM">
                            Membresía Activa
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>


    <script src="js/notificaciones.js"></script>
    <script src="js/main.js"></script>
    <script src="js/calendario.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/graficas.js"></script>
</body>

</html>