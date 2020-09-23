<?php
    include("config.php");
    try { 
        
        $conexion=new PDO($ser_bd, $usr_bd, $psw_bd);//conectando con PDO
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        include_once('../model/classes.php');
        $chat = new Chat($conexion);
        if($_POST['action']=="show"){ 
            $_SESSION['ult_act']=date("Y-m-d H:i:s");
            echo $chat->getMessages($_POST['my_id'],$_POST['receiver_id']);
        }
        if($_POST['action']=="getUsers"){ 
            $_SESSION['ult_act']=date("Y-m-d H:i:s");
            echo $chat->getAllUsers($_POST['current_session']);
        } 
        if($_POST['action']=="sendMessage"){ 
            $_SESSION['ult_act']=date("Y-m-d H:i:s");
            echo $chat->sendMessage($_POST["current_session"],$_POST["chatid"],$_POST["message"]);
        } 
        if($_POST['action']=="updateUnread"){ 
            $_SESSION['ult_act']=date("Y-m-d H:i:s");
            $texto=$chat->getUnreadMessages($_POST["current_chat"],$_POST["current_session"]);
            $array=array(
                "unreaded"=>$texto
            );
            echo json_encode($array);
        } 
    }catch (Exception $e) {
        //throw $th;
        die("Error de conexion con la BBDD");
}
?>