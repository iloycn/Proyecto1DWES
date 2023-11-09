<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesion</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
    }

    form {
        width: 300px;
        margin: 30px auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"],
    input[type="password"] {
        width: 90%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid ; /* Amarillo claro */
        border-radius: 5px;
    }

    input[type="submit"] {
        background-color: #ff9900; /* Naranja claro */
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        opacity: 0.7;
        /*background-color: #ff9900; /* Naranja claro */
    }

    .error {
        color: #f00; /* Naranja claro */
    }
</style>
    
</head>

<body>
    <?php
    $errorUsuario = "";
    $errorClave = "";
    $errorDatos = "";

    if (!empty($_POST)) {

        if (!empty($_POST['usuario']) && !empty($_POST['clave'])) {
            $userintrod = $_POST['usuario'];
            $claveintrod = $_POST['clave'];

            try {
                //busqueda en la base de datos del usuario y la clave
                include_once 'claseConectarBDD.php';
                $BD = new ConectarBD();
                $conn = $BD->getConexion();

                $stmt = $conn->prepare('SELECT * FROM usuarios');
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);

                $encontrado = false;
                while ($users = $stmt->fetch()) {
                    if ($users['usuario'] == $userintrod && $users['clave'] == $claveintrod) {
                        $encontrado = true;
                        //guardas los permisos para mas tarde
                        $permisos = $users['rol'];
                    }
                }
                
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
                die();
            }

            //gestion de los datos encontrados o no encontrados
            if ($encontrado) {
                iniciarSesion($userintrod, $claveintrod,$permisos);
                header("location: principal.php");
                exit;
            } else {
                $errorDatos = "Usuario o clave incorrectos";
            }

        } else {
            //manejo de campos vacios
            if (!isset($_POST['usuario']) || $_POST['usuario'] === "") {
                $errorUsuario = "Debe rellenar el campo del nombre ";
            }
            if (!isset($_POST['clave']) || $_POST['clave'] === "") {
                $errorClave = "Debe rellenar el campo de la contraseña ";
            }
        }
    }

    function iniciarSesion($usuario, $clave,$permisos)
    {
        //se inicia sesion con el usuario y la contraseña correctos
        session_start();
        $_SESSION['nombre'] = $usuario;
        $_SESSION['clave'] = $clave;
        $_SESSION['permisos'] = $permisos;
    }

    ?>

    <form action="" method="post">
        <?php /* para que ponga el nombre  value="<?php if (isset($POST['nombre'])) echo $POST['nombre']; else echo ''; ?>" */ ?>
        <label>Nombre<input type="text" name="usuario"></label> <?php echo '<p class="error">'. $errorUsuario .'</p>' ?> <br />
        <label>Contraseña<input type="password" name="clave"></label> <?php echo '<p class="error"> '. $errorClave .'</p>'?><br />
        <input type="submit" name="Enviar" value="Entrar"><br />
        <?php echo'<p class="error">'. $errorDatos .'</p>'?>
    </form>


</body>

</html>