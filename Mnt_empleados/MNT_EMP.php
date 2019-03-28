<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mantenedor</title>
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
            margin-right: 30px;
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
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Datos de Empleado</h2>
                        <a href="MNT_EMP_I.php" class="btn btn-warning pull-right">Agregar Empleado</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    mysqli_query($link,"SET NAMES 'utf8'");
                    // Attempt select query execution
                    $sql = "SELECT * FROM empleado";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Rut Empleado</th>";
                                        echo "<th>Nombre de Empleado</th>";
                                        echo "<th>Apellido Paterno de Empleado</th>";
                                        echo "<th>Telefono de Empleado</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['EMP_ID'] . "</td>";
                                        echo "<td>" . $row['EMP_NOM'] . "</td>";
                                        echo "<td>" . $row['EMP_APE1'] . "</td>";
                                        echo "<td>" . $row['EMP_TEL'] . "</td>";
                                        echo "<td>";
											echo "<a href='read.php?EMP_ID=". $row['EMP_ID'] ."' title='Detalles' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='MNT_EMP_A.php?EMP_ID=". $row['EMP_ID'] ."' title='Modificar' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='MNT_EMP_E.php?EMP_ID=". $row['EMP_ID'] ."' title='Desactivar/Activar' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
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
					<a class="btn btn-warning" href="../../Cerbero/Interfaz/Admin.html" role="button">Volver al inicio</a>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>