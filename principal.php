<?php

    session_start();
    if (!isset($_SESSION['nombre'])) {
        header('Location: inicio.php');
    }
    
?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>Principal</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>

    a#cerrarSesion {
        background-color: #fca311; 
        color: #FFF;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    h1#titulo {
        color: #fca311; /* Naranja */
    }

    table {
        background-color: #14213d; /* Amarillo claro */
        width: 80%;
        margin: 25px auto;
        border-left: 1px solid #ccc;
        border-right: 1px solid #ccc;
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        font-family: helvetica, arial, sans-serif;
        font-weight: normal;
        text-transform: capitalize;
    }

    thead td {
        background-color: #14213d; /* Amarillo claro */
    }

    table th {
        background-color: #14213d; /* Azul oscuro */
        color: #FFF;
        font-weight: 900;
        font-size: 15px;
        padding: 15px;
        text-align: center;
    }

    table td {
        height: 25px;
        text-align: center;
    }

    table tr:nth-child(odd) {
        background-color: #ddf1f5; /* Amarillo claro */
    }

    table tr:nth-child(even) {
        background: #edf2f4;
    }

    table tr:nth-child(odd) {
        /* para los colores de las filas impares */
        background-color: #ddf1f5; /* Amarillo claro */
    }

    table tr:nth-child(even) {
        /* para los colores de las filas pares */
        background: #edf2f4;
    }

    #informacion,
    #btnImagen {
        display: block;
        width: 90%;
        height: 40%;
        margin: 5px;
        text-decoration: none;
        background-color: #14213d; /* Azul oscuro */
        color: #edf2f4;
        padding: 5px 10px;
        border-radius: 5px;
    }
    #insercion{
        display: block;
        text-decoration: none;
        width: 100%;
        height: 40px;
        background-color: #14213d; /* Azul oscuro */
        color: #edf2f4;
        padding: 5px 10px;
        border-radius: 5px;
    }

</style>
</head>

<body>
    <a id="cerrarSesion" href="cerrarSesion.php">Cerrar Sesión</a>
    <table>
    <thead><td colspan="7"><h1 id="titulo">LOS JUGADORES DE HAIKYUU</h1></td></thead>
    <tr><th>Nombre</th><th>Equipo</th><th>Altura</th><th>Posicion</th><th>Imagen</th><th>Imagen2</th><th>Enlaces</th></tr>

<?php

    try {
        include_once 'claseConectarBDD.php';
        $BD = new ConectarBD();
        $conn = $BD->getConexion();

        $stmt = $conn->prepare('SELECT * FROM jugadores order by equipo');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        while ($jugad = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>".$jugad['nombre']."</td>
            <td>".$jugad['equipo']."</td>
            <td>". $jugad['altura']."</td>
            <td>".$jugad['posicion'].'</td>
            <td><img src="' . $jugad['imagen'] . '"/></td>'. //imagen por ruta 
            '<td><img src="data:image/jpeg;base64,' .  $jugad['imagen2'] . '"></td>'. //imagen blob
            '<td><a id="informacion" href="jugador/' .$jugad['id'].'">mas Informacion</a>'.//boton para ir a la pagina de detalles
            '<a id="btnImagen" href="' .$jugad['imagen'].'">Abrir Imagen</a></td>';//boton para abrir la imagen
            echo "</tr>";
        }

    } catch (PDOException $ex) {
        print "¡Error!: " . $ex->getMessage() . "<br/>";
        exit;
    }
      
?>
<tfoot>
<tr><td colspan="7"><?php if($_SESSION['permisos']=='administrador'){ echo '<a href="insercion.php" id="insercion">Insertar datos</a>';}?></td></tr>
</tfoot>
</body>

</html>