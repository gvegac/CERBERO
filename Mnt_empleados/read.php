<?php
// Check existence of id parameter before processing further
if(isset($_GET["EMP_ID"]) && !empty(trim($_GET["EMP_ID"]))){
    // Include config file
    require_once "config.php";
    mysqli_query($link,"SET NAMES 'utf8'");
    // Prepare a select statement
    $sql = "SELECT * FROM empleado WHERE EMP_ID = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_EMP_ID);
        
        // Set parameters
        $param_EMP_ID = trim($_GET["EMP_ID"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $EMP_ID = $row["EMP_ID"];
                $EMP_NOM = $row["EMP_NOM"];
                $EMP_APE1 = $row["EMP_APE1"];
				$EMP_APE2 = $row["EMP_APE2"];
				$EMP_TEL = $row["EMP_TEL"];
				$EMP_PASS = $row["EMP_PASS"];
				$EMP_TIP = $row["EMP_TIP"];
				$EMP_ESTADO = $row["EMP_ESTADO"];
				
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
                        <label>Rut del empleado</label>
                        <p class="form-control-static"><?php echo $row["EMP_ID"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <p class="form-control-static"><?php echo $row["EMP_NOM"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Apellido Paterno</label>
                        <p class="form-control-static"><?php echo $row["EMP_APE1"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Apellido Materno</label>
                        <p class="form-control-static"><?php echo $row["EMP_APE2"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Teléfono</label>
                        <p class="form-control-static"><?php echo $row["EMP_TEL"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Contraseña asignada</label>
                        <p class="form-control-static"><?php echo $row["EMP_PASS"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Credencial</label>
                        <p class="form-control-static"><?php if($row["EMP_TIP"] == 'C'){
																echo "Conserje";}
															 else{
																echo "Adminitrador";} ?></p>
                    </div>
					<div class="form-group">
                        <label>Estado de empleado</label>
                        <p class="form-control-static"><?php if($row["EMP_ESTADO"]=='1'){
																echo "Activo";}
															 else{
																echo "Inactivo";}  ?></p>
                    </div>
                    <p><a href="MNT_EMP.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>