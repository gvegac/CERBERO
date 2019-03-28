<?php
// Check existence of id parameter before processing further
if(isset($_GET["MAS_COD"]) && !empty(trim($_GET["MAS_COD"]))){
    // Include config file
    require_once "config.php";
    mysqli_query($link,"SET NAMES 'utf8'");
    // Prepare a select statement
    $sql = "SELECT * FROM mascota WHERE MAS_COD = ?";
	
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_MAS_COD);
        
        // Set parameters
        $param_MAS_COD = trim($_GET["MAS_COD"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          
                // Retrieve individual field value
                $MAS_COD = $row["MAS_COD"];
                $MAS_NOM = $row["MAS_NOM"];
                $MAS_ESP = $row["MAS_ESP"];
				$MAS_RAZA = $row["MAS_RAZA"];
				$MAS_RES = $row["MAS_RES"];
				$MAS_VIV = $row["MAS_VIV"];
				$MAS_DES = $row["MAS_DES"];
				
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    // Close statement
    mysqli_stmt_close($stmt);
	// Selcciona raza de la tabla raza
    $sql1="SELECT RAZA_NOM FROM raza WHERE RAZA_ID=".$MAS_RAZA;
	$rresult=mysqli_query($link, $sql1);
	$low= mysqli_fetch_array($rresult);
	// Selecciona Especie de la tabla especie
	$sql2="SELECT ESP_NOM FROM especie WHERE ESP_ID=".$MAS_ESP;
	$rrresult=mysqli_query($link, $sql2);
	$low1= mysqli_fetch_array($rrresult);
	
	$sql3="SELECT RES_NOM FROM residente WHERE RES_ID='$MAS_RES'";
	$r_esult=mysqli_query($link, $sql3);
	$wow= mysqli_fetch_array($r_esult);
    // Close connection
    mysqli_close($link);
}
else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ver registro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Ver Registro</h1>
                    </div>
                    <div class="form-group">
                        <label>Codigo</label>
                        <p class="form-control-static"><?php echo $row["MAS_COD"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <p class="form-control-static"><?php echo $row["MAS_NOM"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Especie</label>
                        <p class="form-control-static"><?php echo $low1["ESP_NOM"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Raza</label>
                        <p class="form-control-static"><?php echo $low["RAZA_NOM"] ?></p>
                    </div>
					<div class="form-group">
                        <label>Rut responsable</label>
                        <p class="form-control-static"><?php echo $row["MAS_RES"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Nombre del responsable</label>
                        <p class="form-control-static"><?php echo $wow["RES_NOM"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Descripción breve de la mascota</label>
                        <p class="form-control-static"><?php echo $row["MAS_DES"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Departamento donde vive la mascota</label>
                        <p class="form-control-static"><?php echo $row["MAS_VIV"]; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Regresar</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>