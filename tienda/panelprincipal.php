<?php 
    //Verifico que los datos tengan las claves que necesito
    //Inicialización de la sesión
    session_start();

    $lang = "es"; //Idioma por defecto

    if(isset($_POST["nombre_usuario"], $_POST["clave_usuario"])){ //Si se envió la peitición por POST (se intenta crear una nueva sesión)
        $nombreUsuario = $_POST["nombre_usuario"];
        $claveUsuario = $_POST["clave_usuario"];

        $_SESSION["sesionNombre"] = $nombreUsuario;
        $_SESSION["sesionClave"] = $claveUsuario;

        if(isset($_POST["recordar_sesion"])){ //Debería recordar
            $_SESSION["sesionRecordar"] = true;
            //Envío las cookies creadas 8duración de 24h)
            setcookie("cookieNombre", $nombreUsuario, (time() + 60 * 60 * 24));
            setcookie("cookieClave", $claveUsuario, (time() + 60 * 60 * 24));
            setcookie("cookieRecordar", "on", (time() + 60 * 60 * 24));
        }else{
            /*
            * En caso de que existan cookies (una sesión anterior) y no quiero recordar nada,
            * instruyo al navegador a destruir las cookies después de recibir esta respuesta
            */
            setcookie("cookieNombre", "");
            setcookie("cookieClave", "");
            setcookie("cookieRecordar", "");
        }
    }

    //Verificación de existencia de la sesión si viene por GET
    if(!isset($_SESSION["sesionNombre"]) && !isset($_SESSION["sesionClave"])){ //Si no hay sesión
        header('Location: index.php'); //Chao 
        exit; 
    }

    //Me vino un parametro de query con la selección de idioma
    if(isset($_GET["lang"])){
        $_SESSION["sesionIdioma"] = ($_GET["lang"] == "en")? "en" : "es";
    }

    //Existe un idioma seleccionado en la sesión
    if(isset($_SESSION["sesionIdioma"])){ 
        $lang = $_SESSION["sesionIdioma"]; 
    }else{
        $_SESSION["sesionIdioma"] = $lang; //Idioma por defecto
    }

    //Tengo una cookie y no me vino como query
    if(isset($_COOKIE["cookieIdioma"]) && !isset($_GET["lang"])) { 
        $_SESSION["sesionIdioma"] = ($_COOKIE["cookieIdioma"] == "en")? "en" : "es";
        $lang = $_SESSION["sesionIdioma"]; 
    }

    //Envío la preferencia actual de idioma
    setcookie("cookieIdioma", $_SESSION["sesionIdioma"], (time() + 60 * 60 * 24)); //Envío la cookie del idioma actual
    

    //Archivos en un arreglo para reusabilidad del algoritmo de lectura e impresión
    $files = array("es" => "categorias_es.txt", "en" => "categorias_en.txt"); 
    $titles = array("es" => "Lista de Productos", "en" => "Product List");

    $selected_file = $files[$lang];
    $selected_title = $titles[$lang];

?>
<html>
    <head></head>
    <body>
        <h1>PANEL PRINCIPAL</h1>
        <h3>Bienvenido Usuario: <?php echo $_SESSION["sesionNombre"] ?> </h3>
        <a href="panelprincipal.php?lang=es">ES (Español)</a>|<a href="panelprincipal.php?lang=en">EN (English)</a>
        <br>
        <br>
        <a href="cerrarsesion.php">Cerrar Sesion</a>
        <br>
        <?php 

            echo "<h2>$selected_title</h2>";

            $fp = fopen($selected_file, "r"); //Se abre el recurso de archivo
            
            while(!feof($fp)){ //Hasta no encontrar el fin de archivo
                $curr_line = fgets($fp); //Se obtiene la línea correspondiente
                echo "$curr_line<br>"; //Se imprime la línea
            }
            //Se cierra el archivo
            fclose($fp);
        ?>
    </body>
</html>