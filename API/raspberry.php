<?php
    include_once '../Model/raspberry.php';
    $fecha = $_GET['fecha'].' '.$_GET['hora'];
    $gpu = $_GET['GPU'];
    $gpu= str_replace('temp=','',$gpu);
    $gpu= str_replace("'C","",$gpu);
    $gpu= str_replace("'F","",$gpu);

    insertarValores($_GET['CPU'],$gpu,$fecha);

?>