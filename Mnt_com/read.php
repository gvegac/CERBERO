<?php
// Check existence of id parameter before processing further
if(isset($_GET["COM_ID"]) && !empty(trim($_GET["COM_ID"]))){
    // Include config file
    require_once "config.php";
    mysqli_query($link,"SET NAMES 'utf8'");
    // Prepare a select statement
    $sql = "SELECT * FROM complejo_habitacional WHERE COM_ID = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_COM_ID);
        
        // Set parameters
        $param_COM_ID = trim($_GET["COM_ID"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $COM_ID = $row["COM_ID"];
                $COM_NOM = $row["COM_NOM"];
                $COM_TIPO = $row["COM_TIPO"];
				$COM_DIR = $row["COM_DIR"];
				$COM_COM = $row["COM_COM"];
				$COM_ESTADO = $row["COM_ESTADO"];
				
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
                        <p class="form-control-static"><?php echo $row["COM_ID"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <p class="form-control-static"><?php echo $row["COM_NOM"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Tipo de complejo</label>
                        <p class="form-control-static"><?php echo $row["COM_TIPO"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Dirección</label>
                        <p class="form-control-static"><?php echo $row["COM_DIR"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Comuna</label>
                        <p class="form-control-static"><?php echo $row["COM_COM"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Estado de Complejo habitacional</label>
                        <p class="form-control-static"><?php if($row["COM_ESTADO"]=='1'){
																echo "Activo";}
															 else{
																echo "Inactivo";}  ?></p>
                    </div>
                    <p><a href="MNT_COM.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>