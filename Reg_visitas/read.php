<?php
// Check existence of id parameter before processing further
if(isset($_GET["VIS_COD"]) && !empty(trim($_GET["VIS_COD"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM visita WHERE VIS_COD = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_VIS_COD);
        
        // Set parameters
        $param_VIS_COD = trim($_GET["VIS_COD"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $VIS_COD = $row["VIS_COD"];
				$VIS_FEC_HOR = $row["VIS_FEC_HOR"];
				$VIS_RUT = $row["VIS_RUT"];
				$VIS_NOM = $row["VIS_NOM"];
				$VIS_APE1 = $row["VIS_APE1"];
				$VIS_APE2 = $row["VIS_APE2"];
				$VIS_VIV = $row["VIS_VIV"];
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
                        <h1>Detalles de visita</h1>
                    </div>
                    <div class="form-group">
                        <label>Fecha y hora de la visita</label>
                        <p class="form-control-static"><?php echo $row["VIS_FEC_HOR"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Rut</label>
                        <p class="form-control-static"><?php echo $row["VIS_RUT"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Nombre</label>
                        <p class="form-control-static"><?php echo $row["VIS_NOM"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Apellido paterno</label>
                        <p class="form-control-static"><?php echo $row["VIS_APE1"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Apellido materno</label>
                        <p class="form-control-static"><?php echo $row["VIS_APE2"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Vivienda visitada </label>
                        <p class="form-control-static"><?php echo $row["VIS_VIV"]; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>