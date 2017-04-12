<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" type="image/png" href="favicon.png?v=<?php echo md5_file('favicon.png') ?>" />
        <meta charset="UTF-8">
        <title>Monitoreo de servidor</title>
        <meta name="viewport" content="width=device-width, initial-scale=1,height=device-height, maximum-scale=1">
    </head>
    <body>
    <link rel="stylesheet" href="View/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="View/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="View/chartist/chartist.min.css">
    <style>
        body{
            width:100vw;
            height:100vh;
            overflow-x:hidden;
            text-align: center;
        }
        div {
            text-align: center;
        }
        th, td {
            text-align: center;
        }
        .tablaPromedios {
            display: inline-block;
            min-width: 170px;
        }
        .grafica {
            display: inline-block;
            min-width: 630px;
            min-height: 300px;
            background-color: #e1e1e1;
            width: 50vw;
            height: 50vh;
        }
        .ct-series-a .ct-line,.ct-series-a .ct-point {
          stroke: green;
        }
        @media screen and (max-device-width: 768px) {
            .grafica {
                min-width: 100vw;
                min-height: 35vh;
                height: 35vh;
                width: 100vw;
                position: relative;
                left: -18vw;
            }
        }

    </style>
    <?php
        include_once 'Model/raspberry.php';
        $valores = obtenerValores(null, null, null);
        $promedios = obtenerPromedios(null, null, null);
    ?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1>Monitor Raspberry</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-10 col-sm-12 col-md-12 col-lg-12">
            <h1>Formulario</h1>
        </div>
        <div class="col-xs-1"></div>
    </div>
    <div class="row">
        <div class="col-xs-2 col-sm-1 col-md-1 col-lg-2"></div>
        <div class="col-xs-8 col-sm-10 col-md-10 col-lg-8">
            <h1>Promedios y grafica</h1>
            <div class="tablaPromedios">
                <table class="table table-striped table-bordered table-hover">
                    <tr><th></th><th>CPU</th><th>GPU</th></tr>
                    <tr><th>Promedio</th><td><?php echo round($promedios[0]['avgCPU'],2); ?> &deg;C</td><td><?php echo round($promedios[0]['avgGPU'],2); ?> &deg;C</td></tr>
                    <tr><th>Maximos</th><td><?php echo $promedios[0]['maxCPU']; ?> &deg;C</td><td><?php echo $promedios[0]['maxGPU']; ?> &deg;C</td></tr>
                    <tr><th>Minimos</th><td><?php echo $promedios[0]['minCPU']; ?> &deg;C</td><td><?php echo $promedios[0]['minGPU']; ?> &deg;C</td></tr>
                </table>
            </div>
            <div class="grafica ct-chart ct-perfect-fourth">

            </div>
        </div>
        <div class="col-xs-2 col-sm-1 col-md-1 col-lg-2"></div>
    </div>
    <div class="row">
        <div class="col-xs-2 col-sm-2 col-md-3 col-lg-4"></div>
        <div class="col-xs-8 col-sm-8 col-md-6 col-lg-4">
            <h1>Tabla Historica</h1>
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <th>fecha</th><th>CPU</th><th>GPU</th>
                </tr>
                <?php
                    if(isset($valores) && count($valores)>0){
                        foreach ($valores as $value) {
                            $date=date_create($value['fecha']);
                            $cpu = intval($value['CPU']);
                            $gpu = intval($value['GPU']);
                            if($cpu<35 || $gpu<35){
                                echo '<tr class="info">';
                            }elseif ($cpu<40 || $gpu<40) {
                                echo '<tr class="success">';
                            }elseif (($cpu>55 || $gpu>55) && ($cpu<65 || $gpu<65)) {
                                echo '<tr class="warning">';
                            }elseif ($cpu>=65 || $gpu>=65) {
                                echo '<tr class="danger">';
                            }else{
                                echo '<tr>';
                            }

                            echo '<td class="campoFecha">'.date_format($date,"d/m/Y H:i:s").'</td><td class="valorCPU">'.$value['CPU'].' &deg;C</td><td class="valorGPU">'.$value['GPU'].' &deg;C</td></tr>';
                        }
                    }

                ?>
            </table>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-3 col-lg-4"></div>
    </div>
    <script type="text/javascript" src="View/js/jquery-3.2.0.min.js"></script>
    <script type="text/javascript" src="View/bootstrap/js/bootstrap.min.js"></script>
    <script src="View/chartist/chartist.min.js"></script>
    <script type="text/javascript">
        var fechas = [];
        var cpuTemp = [];
        var gpuTemp = [];
        $('.campoFecha').each(function(){
            fechas.push($(this).html().trim().substring($(this).html().trim().indexOf(' '), $(this).html().trim().lastIndexOf(':')));
        });

        $('.valorCPU').each(function(){
            cpuTemp.push($(this).html().trim().substring(0,5));
        });
        $('.valorGPU').each(function(){
            gpuTemp.push($(this).html().trim().substring(0,5));
        });

        //console.log(fechas,cpuTemp,gpuTemp);

        var data = {
          // A labels array that can contain any sort of values
          labels: fechas.reverse(),
          // Our series array that contains series objects or in this case series data arrays
          series: [
            cpuTemp.reverse(),
            gpuTemp.reverse()
          ]
        };

        // Create a new line chart object where as first parameter we pass in a selector
        // that is resolving to our chart container element. The Second parameter
        // is the actual data object.
        new Chartist.Line('.ct-chart', data);
    </script>
    </body>
</html>