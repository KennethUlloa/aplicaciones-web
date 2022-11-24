<?php

    $nombreUsuario = "";
    $claveUsuario = "";
    $recordar = "";

    if(isset($_COOKIE["cookieNombre"]) && isset($_COOKIE["cookieClave"]) && isset($_COOKIE["cookieRecordar"])){
        $nombreUsuario = $_COOKIE["cookieNombre"];
        $claveUsuario = $_COOKIE["cookieClave"];
        $recordar = $_COOKIE["cookieRecordar"];
    }
?>
<html>
    <head></head>
    <body>
        <h1>LOGIN</h1>
        <form action="panelprincipal.php" method="POST">
            Usuario: <br>
            <input type="text" name="nombre_usuario" id="" value="<?php echo $nombreUsuario ?>" ><br>
            Clave: <br>
            <input type="password" name="clave_usuario" id="" value="<?php echo $claveUsuario ?>"><br>
            <input type="checkbox" name="recordar_sesion" id="" <?php echo ($recordar == "on")? "checked" : "" ?>> Recordarme <br>
            <input type="submit" value="Enviar">
        </form>
    </body>
</html>