<?php
// Check existence of id parameter before processing further
if(isset($_GET["PRO_RUT"]) && !empty(trim($_GET["PRO_RUT"]))){
    // Include config file
    require_once "config.php";
    mysqli_query($link,"SET NAMES 'utf8'");
    // Prepare a select statement
    $sql = "SELECT * FROM propietario WHERE PRO_RUT = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_PRO_RUT);
        
        // Set parameters
        $param_PRO_RUT = trim($_GET["PRO_RUT"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $PRO_RUT = $row["PRO_RUT"];
                $PRO_NOM = $row["PRO_NOM"];
                $PRO_APE1 = $row["PRO_APE1"];
				$PRO_APE2 = $row["PRO_APE2"];
				$PRO_TEL = $row["PRO_TEL"];
				$PRO_ESTADO = $row["PRO_ESTADO"];
				
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
    
    // Close connection
    mysqli_close($link);
} else{
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
                        <label>Rut del Propietario</label>
                        <p class="form-control-static"><?php echo $row["PRO_RUT"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <p class="form-control-static"><?php echo $row["PRO_NOM"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Apellido Paterno</label>
                        <p class="form-control-static"><?php echo $row["PRO_APE1"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Apellido Materno</label>
                        <p class="form-control-static"><?php echo $row["PRO_APE2"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Teléfono</label>
                        <p class="form-control-static"><?php echo $row["PRO_TEL"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Estado de empleado</label>
                        <p class="form-control-static"><?php if($row["PRO_ESTADO"]=='1'){
																echo "Activo";}
															 else{
																echo "Inactivo";}  ?></p>
                    </div>
                    <p><a href="MNT_PRO.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>