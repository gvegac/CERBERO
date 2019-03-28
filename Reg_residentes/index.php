<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <title>Registro de residentes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 1000px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 0px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-15">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Registro de Residentes</h2>
                        <a href="create.php" class="btn btn-success pull-right">Agregar Residente</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
					mysqli_query($link,"SET NAMES 'utf8'");					
                    // Attempt select query execution
                    $sql = "SELECT * FROM residente";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Rut de residente</th>";
                                        echo "<th>Nombre</th>";
                                        echo "<th>Apellido paterno</th>";
										echo "<th>Vivienda</th>";
										echo "<th>Acción</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['RES_ID'] . "</td>";
                                        echo "<td>" . $row['RES_NOM'] . "</td>";
                                        echo "<td>" . $row['RES_APE1'] . "</td>";
										echo "<td>" . $row['RES_VIV'] . "</td>";
                                        echo "<td>";
											echo "<a href='read.php?RES_ID=". $row['RES_ID'] ."' title='Detalles' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='update.php?RES_ID=". $row['RES_ID'] ."' title='Modificar' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='delete.php?RES_ID=". $row['RES_ID'] ."' title='Borrar' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
					<a class="btn btn-primary" href="../../Cerbero/Interfaz/Conserje.html" role="button">Volver al inicio</a>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>