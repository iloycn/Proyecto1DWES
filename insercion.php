<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header('Location: inicio.php');
}else if ($_SESSION['permisos'] != 'administrador') {
    header('Location: principal.php');
}

$errorNombre ="";
$errorEquipo ="";
$errorAltura ="";
$errorPosicion ="";
$errorImagen ="";

if (!empty($_POST)) {
    if(!isset($_POST['nombre']) || $_POST['nombre'] === ""){
        $errorNombre ="Error: Nombre invalido";
    } else {
        $nombre = $_POST["nombre"];
    }

    if(!isset($_POST['equipo']) || $_POST['equipo'] === ""){
        $errorEquipo ="Error: Equipo invalido";
    } else {
        $equipo = $_POST["equipo"];
    }

    if(!isset($_POST['altura']) || $_POST['altura'] == ""){
        $errorAltura ="Error: Altura invalida";
    } else {
        $altura = $_POST["altura"];
    }

    if(!isset($_POST['posicion']) || $_POST['posicion'] === ""){
        $errorPosicion ="Error: Posicion invalida";
    } else {
        $posicion = $_POST["posicion"];
    }

    if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["type"] != "image/jpeg") {
        $errorImagen = "Error: Debes seleccionar una imagen JPEG válida.";
    } else {
        
        $nombreImagen = 'img/' . $_FILES["imagen"]["name"];
        $imagen2 = base64_encode(file_get_contents($_FILES["imagen"]["tmp_name"]));
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $nombreImagen)) {
        } else {
            $errorImagen = "Error al mover el archivo al directorio de destino.";
        }
    }

    if (empty($errorNombre) && empty($errorEquipo) && empty($errorAltura) && empty($errorPosicion) && empty($errorImagen)) {

        try {
            include_once 'claseConectarBDD.php';
            $BD = new ConectarBD();
            $conn = $BD->getConexion();

            $sql = "INSERT INTO jugadores (nombre, equipo, altura, posicion, imagen, imagen2) VALUES (:nombre, :equipo, :altura, :posicion, :imagen, :imagen2)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':equipo', $equipo);
            $stmt->bindParam(':altura', $altura);
            $stmt->bindParam(':posicion', $posicion);
            $stmt->bindParam(':imagen', $nombreImagen);
            $stmt->bindParam(':imagen2', $imagen2, PDO::PARAM_LOB);
            $stmt->execute();

        } catch (PDOException $ex) {
            print "¡Error!: " . $ex->getMessage() . "<br/>";
            echo "Error en la base de datos";
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insercion de datos</title>
    <style>
    body {
        background-color: #f5f5f5; /* Fondo gris claro */
        font-family: Arial, sans-serif;
        text-align: center;
        color: #333; /* Texto gris oscuro */
    }

    #cerrarSesion {
        background-color: #f00; 
        color: #FFF;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    a {
        text-decoration: none;
        color: #005df4; /* Azul */
        padding: 10px;
        border-radius: 5px;
        background-color: #ffb12c; /* Amarillo claro */
    }

    form {
        background-color: #fff; /* Fondo blanco */
        width: 500px;
        margin: 0 auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 10px;
        color: #333; /* Texto gris oscuro */
    }

    input[type="text"],
    input[type="number"],
    input[type="file"] {
        width: 90%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #bbb; /* Borde gris claro */
        border-radius: 5px;
        background-color: #f5f5f5; /* Fondo gris claro */
        color: #333; /* Texto gris oscuro */
    }

    input[type="submit"] {
        background-color: #ff9900; /* Naranja claro */
        color: #fff; /* Texto blanco */
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #ee8b00; /* Naranja oscuro */
    }

    .error {
        color: #ff0000; /* Rojo */
    }
</style>
</head>
<body>
    <a id="cerrarSesion" href="cerrarSesion.php">Cerrar Sesión</a>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Nombre: <input type="text" name="nombre"></label><?php echo '<p class="error">'.  $errorNombre .'</p>'?> <br/>
        <label>Equipo: <input type="text" name="equipo"></label><?php echo '<p class="error">'. $errorEquipo .'</p>'?> <br/>
        <label>Altura: <input type="number" name="altura"></label><?php echo '<p class="error">'. $errorAltura .'</p>'?> <br/>
        <label>Posicion: <input type="text" name="posicion"></label><?php echo '<p class="error">'. $errorPosicion .'</p>'?> <br/>
        <label>Imagen: <input type="file" name="imagen"></label><?php echo '<p class="error">'. $errorImagen .'</p>'?> <br/>
        <input type="submit" name="Enviar" value="Entrar"><br />
    </form>
</body>
</html>