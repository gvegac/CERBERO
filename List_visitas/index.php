﻿<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <title>Listado de Visitas</title>
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
                        <h2 class="pull-left">Listado de Visitas</h2>
                        <form align="right" name="search_form" method="POST" action="">
							Vivienda: <input type="text" name="search_box"/>
							<input type="submit" name="search" value="Filtrar">
						</form>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php"; 
                    // Attempt select query execution
					//$search_term = mysqli_real_escape_string($_POST['search_box']);
					
					$verifier = 'false';	
					if (isset($_POST['search'])) {
						$verifier = 'true';
					}
					
					
					
					if($verifier == 'false'){
						$sql = "SELECT * FROM visita";
					}
					else if($verifier == 'true'){
						$name = $_POST['search_box'];
						$sql = "SELECT * FROM visita WHERE VIS_VIV = ".$name;	
					}
					if($result = mysqli_query($link, $sql)){
						if(mysqli_num_rows($result) > 0){
							echo "<table class='table table-bordered table-striped'>";
								echo "<thead>";
									echo "<tr>";
										echo "<th>Vivienda</th>";
										echo "<th>Nombre de la visita</th>";
										echo "<th>Apellido paterno</th>";
										echo "<th>Apellido materno</th>";
										echo "<th>Fecha y hora</th>";
										echo "<th>Acción</th>";
									echo "</tr>";
								echo "</thead>";
								echo "<tbody>";
								while($row = mysqli_fetch_array($result)){
									echo "<tr>";
										echo "<td>" . $row['VIS_VIV'] . "</td>";
										echo "<td>" . $row['VIS_NOM'] . "</td>";
										echo "<td>" . $row['VIS_APE1'] . "</td>";
										echo "<td>" . $row['VIS_APE2'] . "</td>";
										echo "<td>" . $row['VIS_FEC_HOR'] . "</td>";
										echo "<td>";
											echo "<a href='read.php?VIS_COD=". $row['VIS_COD'] ."' title='Detalles' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
										echo "</td>";
									echo "</tr>";
								}
								echo "</tbody>";                            
							echo "</table>";
							// Free result set
							mysqli_free_result($result);
						} else{
							echo "<p class='lead'><em>No se encontraron registros.</em></p>";
						}
					} else{
						echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($link);
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