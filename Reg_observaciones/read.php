<?php
// Check existence of id parameter before processing further
if(isset($_GET["OBS_COD"]) && !empty(trim($_GET["OBS_COD"]))){
    // Include config file
    require_once "config.php";
    mysqli_query($link,"SET NAMES 'utf8'");
    // Prepare a select statement
    $sql = "SELECT * FROM observacion WHERE OBS_COD = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_OBS_COD);
        
        // Set parameters
        $param_OBS_COD = trim($_GET["OBS_COD"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $OBS_COD = $row["OBS_COD"];
				$OBS_FEC_HOR = $row["OBS_FEC_HOR"];
				$OBS_EMP = $row["OBS_EMP"];
				$OBS_VIV = $row["OBS_VIV"];
				$OBS_TIP = $row["OBS_TIP"];
				$OBS_DES = $row["OBS_DES"];
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
                        <h1>Detalles de observación</h1>
                    </div>
                    <div class="form-group">
                        <label>Fecha y hora del suceso</label>
                        <p class="form-control-static"><?php echo $row["OBS_FEC_HOR"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Conserje de turno</label>
                        <p class="form-control-static"><?php echo $row["OBS_EMP"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Vivienda implicada</label>
                        <p class="form-control-static"><?php echo $row["OBS_VIV"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Tipo</label>
                        <p class="form-control-static"><?php if($row["OBS_TIP" ]== 1){
																echo "Observación negativa";}
															 else{
																echo "Observación neutra";} ?></p>
                    </div>
					<div class="form-group">
                        <label>Observación</label>
                        <p class="form-control-static"><?php echo $row["OBS_DES"]; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>