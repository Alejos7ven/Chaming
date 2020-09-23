<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header('location:login.php');
    }else{
        $ult_act=$_SESSION["ult_act"];
        $h_actual=date("Y-n-j H:i:s");
        $tiempo_transcurrido=(strtotime($h_actual)-strtotime($ult_act));
        if ($tiempo_transcurrido>=1800) {
            # code...
            header("location:controller/controller user.php?logout=true");
        }
    }
?>