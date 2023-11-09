<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header('Location: inicio.php');
}else if(!isset($_GET['id'])){
    header('Location: principal.php');
} else{
    $id = $_GET['id'];
}

    try {
        include_once 'claseConectarBDD.php';
        $BD = new ConectarBD();
        $conn = $BD->getConexion();

        $stmt = $conn->prepare('SELECT * FROM jugadores WHERE id =' . $id);
        $stmt->execute();
        $informacion = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $ex) {
        print "¡Error!: " . $ex->getMessage() . "<br/>";
        exit;
    }
  
?>
<html>
    <head>
        <title>Informacion</title>
        <style>
        body {
            background-color: #f5f5f5; /* Fondo gris claro */
            font-family: Arial, sans-serif;
            text-align: center;
            color: #333; /* Texto gris oscuro */
        }

        a#cerrarSesion {
            background-color: #ff9900; /* Naranja claro */
            color: #fff; /* Texto blanco */
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        p {
            font-size: 18px;
            margin: 10px;
            color: #333; /* Texto gris oscuro */
        }

        img {
            max-width: 100%;
        }
    </style>
    </head>
    <body>
    <a id="cerrarSesion" href="cerrarSesion.php">Cerrar Sesión</a>
        <p>Nombre: <?php echo $informacion['nombre']; ?></p>
        <p>Equipo: <?php echo $informacion['equipo']; ?></p>
        <p>Altura: <?php echo $informacion['altura']; ?> cm</p>
        <p>Posicion: <?php echo $informacion['posicion']; ?></p>
        <?php echo'<img src="../'. $informacion['imagen'] .'"/>'?>
    </body>
</html>