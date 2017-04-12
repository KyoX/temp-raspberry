<?php
    function insertarValores($cpu, $gpu,$fecha){
        if(strcmp($_SERVER['HTTP_HOST'],'server-monitor-theky0x.c9users.io')==0){
            $servername = getenv('IP');
            $username = getenv('C9_USER');
            $password = "";
            $database = "id1257286_monitor";
        }else{
            $servername = "localhost";
            $username = "id1299347_njserranor";
            $password = "zzab262f";
            $database = "id1299347_monitor";
        }

        $dbport = 3306;
        $con = new mysqli($servername, $username, $password, $database, $dbport);
        /* verificar la conexión */
        if (mysqli_connect_errno()) {
            printf("Conexión fallida: %s\n", mysqli_connect_error());
            exit();
        }
        $insertSQL = "INSERT INTO raspberrypi(fecha,CPU, GPU) VALUES ('$fecha',$cpu, $gpu)";
        //echo $insertSQL."\n";
        mysqli_query($con,$insertSQL);
        mysqli_commit($con);
        mysqli_close($con);
    }

    function obtenerValores($fechaInicio, $fechaFin, $cant){
        if(strcmp($_SERVER['HTTP_HOST'],'server-monitor-theky0x.c9users.io')==0){
            $servername = getenv('IP');
            $username = getenv('C9_USER');
            $password = "";
            $database = "id1257286_monitor";
        }else{
            $servername = "localhost";
            $username = "id1299347_njserranor";
            $password = "zzab262f";
            $database = "id1299347_monitor";
        }
        $dbport = 3306;
        $conn = new mysqli($servername, $username, $password, $database, $dbport);
        if ($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }

        $consulta = "SELECT fecha, CPU, GPU FROM raspberrypi WHERE 1";
        if(isset($fechaInicio) && $fechaInicio !== NULL){
            $consulta = $consulta." AND fecha BETWEEN '$fechaFin' AND '$fechaInicio'"; //BETWEEN '2010-01-30 14:15:55' AND '2010-09-29 10:15:55'
        }

        $consulta = $consulta." ORDER BY fecha DESC";

        //echo $consulta;

        if(isset($cant) && $cant !== NULL){
            $consulta = $consulta." LIMIT $cant";
        }


        $result = $conn->query($consulta);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }
        }
        $conn->close();

        if (isset($resultados)) return $resultados;

    }

    function obtenerPromedios($fechaInicio, $fechaFin, $cant){
        if(strcmp($_SERVER['HTTP_HOST'],'server-monitor-theky0x.c9users.io')==0){
            $servername = getenv('IP');
            $username = getenv('C9_USER');
            $password = "";
            $database = "id1257286_monitor";
        }else{
            $servername = "localhost";
            $username = "id1299347_njserranor";
            $password = "zzab262f";
            $database = "id1299347_monitor";
        }
        $dbport = 3306;
        $conn = new mysqli($servername, $username, $password, $database, $dbport);
        if (mysqli_connect_errno()) {
            printf("Conexión fallida: %s\n", mysqli_connect_error());
            exit();
        }

        $consulta = "SELECT avg(CPU) as avgCPU, avg(GPU) as avgGPU,max(CPU) as maxCPU, max(GPU) as maxGPU,min(CPU) as minCPU, min(GPU) as minGPU  FROM raspberrypi WHERE 1";
        if(isset($fechaInicio) && $fechaInicio !== NULL){
            $consulta = $consulta." AND fecha BETWEEN '$fechaFin' AND '$fechaInicio'"; //BETWEEN '2010-01-30 14:15:55' AND '2010-09-29 10:15:55'
        }

        $consulta = $consulta." ORDER BY fecha DESC";

        if(isset($cant) && $cant !== NULL){
            $consulta = $consulta."LIMIT $cant";
        }


        $result = $conn->query($consulta);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }
        }
        $conn->close();

        if (isset($resultados)) return $resultados;
    }

?>
