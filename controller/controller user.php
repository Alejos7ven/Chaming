<?php
    
    include("config.php");
    try { 
        
        $conexion=new PDO($ser_bd, $usr_bd, $psw_bd);//conectando con PDO
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        
        
        $loginError = '';
        if (isset($_POST["login"])) {
                include_once('../model/classes.php');
                $chat = new Chat($conexion);
                $user = $chat->validateUser($_POST['username'], $_POST['psw']);	
                if($user!=0) { 
                    session_start();
                    $_SESSION["id"]=$chat->getCurrentID($_POST['username'], $_POST['psw']);
                    
                    $current_user=$chat->getUserDetails($_SESSION["id"]);
                    $_SESSION['user'] =$current_user[0]["username"];
                    $_SESSION['psw'] = $current_user[0]["psw"];
                    $_SESSION["avatar"]=$current_user[0]["avatar"];
                    $_SESSION["last"]=$current_user[0]["last"];
                    $_SESSION['ult_act']=date('Y-n-j H:i:s');
                    $_SESSION["status"]=$chat->updateStatusConnection($_SESSION["id"],"on");
                    header("Location:../index.php");
                } else {
                    $loginError = "Usuario y Contraseña invalida";
                    echo "<center>$loginError</center>";
                    header("refresh:2; ../login.php");
                }
        }
        if (!empty($_GET["logout"])) {
            include_once('../model/classes.php');
            $chat = new Chat($conexion);
            session_start();
            $chat->updateLastSeen($_SESSION["id"]);
            $chat->updateStatusConnection($_SESSION["id"],"off");
            echo "Hasta luego...";
            header("refresh:3; ../login.php");
            session_destroy();
        }
        
        
} catch (Exception $e) {
        //throw $th;
        die("Error de conexion con la BBDD");
}

?>