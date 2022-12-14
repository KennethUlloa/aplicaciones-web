<?php 
    //Verifico que los datos tengan las claves que necesito
    //Inicialización de la sesión
    session_start();
    $nombreUsuario = "";
    $claveUsuario = "";
    $recordar = false;
    $isLogIn = false;

    if(isset($_POST["nombre_usuario"], $_POST["clave_usuario"])){ //Si se envió la peitición por POST (se intenta crear una nueva sesión)
        $nombreUsuario = $_POST["nombre_usuario"];
        $claveUsuario = $_POST["clave_usuario"];
        $isLogIn = true;

        $_SESSION["sesionNombre"] = $nombreUsuario;
        $_SESSION["sessionClave"] = $claveUsuario;

        //Creación de cookies
        if(isset($_POST["recordar_sesion"] )){ //Se seleccionó la opción de recordar
            $recordar = ($_POST["recordar_sesion"] == "on")? true : false; 
            
        }
        
        if($recordar){ //Si seleccionó la opción recordar se crean las cookies
            setcookie("cookieNombre", $nombreUsuario);
            setcookie("cookieClave", $claveUsuario);
            setcookie("cookieRecordar", "on");
        }else { //caso contrario destruyo las cookies guardadas
            setcookie("cookieNombre", "");
            setcookie("cookieClave", "");
            setcookie("cookieRecordar", "");
            setcookie("cookieIdioma", "");
        }
    }

    //Verificación de existencia de la sesión si viene por GET
    if(!isset($_SESSION["sesionNombre"]) && !isset($_SESSION["sessionClave"])){ //Si no hay sesión
        header('Location: index.php'); //Chao 
        exit; 
    }

    $lang = "es"; //Idioma por defecto
    /*
    Si es el login y no necesito recordar información, por ende
    obvio todas las condiciones futuras
    
    */

    $CookieRecordarSet = isset($_COOKIE["cookieRecordar"]);
    $CookieIdiomaSet = isset($_COOKIE["cookieIdioma"]);
    $LangParameterSet = isset($_GET["lang"]);

    if($isLogIn && !$recordar){ //Primer log in sin recordar (se evita el uso de la cookie)

        $lang = "es";

    }else if($LangParameterSet){ //Me mandaron un parámetro de idioma

        $lang = ($_GET["lang"] == "en")? "en" : "es"; //Actualizo el parámetro actual

        if($CookieRecordarSet){ //Si se encuentra con la cookie para recordar datos

            if($_COOKIE["cookieRecordar"] == "on"){ //Verifica que su contenido sea on
                setcookie("cookieIdioma", $lang); //Se guarda en la cookie la selección actual
            }  

        }else {
            setcookie("cookieIdioma", ""); //Se elimina la cookie de idioma
        }
    }else if($CookieRecordarSet) { //Si la cookie para recordar datos existe

        if($CookieIdiomaSet) {

            $lang = ($_COOKIE["cookieIdioma"] == "en")? "en" : "es"; 

        }
    } 
    //Si el usuario no seleccionó la preferencia de idioma y no existen cookies, se toma por defecto el español;
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

            echo "<h2>" . $selected_title . "</h2>";

            $fp = fopen($selected_file, "r"); //Se abre el recurso de archivo
            
            while(!feof($fp)){ //Hasta no encontrar el fin de archivo
                $curr_line = fgets($fp); //Obtenemos la línea correspondiente
                echo $curr_line . "<br>"; //Imprimo la línea
            }
            //Cerrar el archivo
            fclose($fp);
        ?>
    </body>
</html>