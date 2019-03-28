<?php
require_once "config.php";
// Check existence of id parameter before processing further
if(isset($_GET["VIV_COD"]) && !empty(trim($_GET["VIV_COD"]))){
    // Include config file
    mysqli_query($link,"SET NAMES 'utf8'");
    // Prepare a select statement
    $sql = "SELECT * FROM vivienda WHERE VIV_COD = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_VIV_COD);
        
        // Set parameters
        $param_VIV_COD = trim($_GET["VIV_COD"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $VIV_COD = $row["VIV_COD"];
				$VIV_COM = $row["VIV_COM"];
				$VIV_EST = $row["VIV_EST"];
				$VIV_BOD = $row["VIV_BOD"];
				
            } 
			else{
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
    $sq="SELECT COM_NOM FROM complejo_habitacional WHERE COM_ID='$VIV_COM'";
	$rresult=mysqli_query($link, $sq);
	$low= mysqli_fetch_array($rresult);
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
                        <label>Código de Vivienda</label>
                        <p class="form-control-static"><?php echo $row["VIV_COD"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Complejo habitacional</label>
                        <p class="form-control-static"><?php echo $low["COM_NOM"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Bodega </label>
                        <p class="form-control-static"><?php echo $row["VIV_BOD"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Estacionamiento </label>
                        <p class="form-control-static"><?php echo $row["VIV_EST"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Estado de vivienda</label>
                        <p class="form-control-static"><?php if($row["VIV_ESTADO"]=='1'){
																echo "Activo";}
															 else{
																echo "Inactivo";}  ?></p>
                    </div>
                    <p><a href="MNT_VIV.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>