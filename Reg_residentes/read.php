<?php
// Check existence of id parameter before processing further
if(isset($_GET["RES_ID"]) && !empty(trim($_GET["RES_ID"]))){
    // Include config file
    require_once "config.php";
    mysqli_query($link,"SET NAMES 'utf8'");
    // Prepare a select statement
    $sql = "SELECT * FROM residente WHERE RES_ID = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_RES_ID);
        
        // Set parameters
        $param_RES_ID = trim($_GET["RES_ID"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $RES_ID = $row["RES_ID"];
                $RES_NOM = $row["RES_NOM"];
                $RES_APE1 = $row["RES_APE1"];
				$RES_APE2 = $row["RES_APE2"];
				$RES_VIV = $row["RES_VIV"];
				$RES_TEL = $row["RES_TEL"];
				$RES_EMAIL = $row["RES_EMAIL"];
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
                        <label>Rut de residente</label>
                        <p class="form-control-static"><?php echo $row["RES_ID"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <p class="form-control-static"><?php echo $row["RES_NOM"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Apellido Paterno</label>
                        <p class="form-control-static"><?php echo $row["RES_APE1"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Apellido Materno</label>
                        <p class="form-control-static"><?php echo $row["RES_APE2"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Vivienda</label>
                        <p class="form-control-static"><?php echo $row["RES_VIV"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Teléfono</label>
                        <p class="form-control-static"><?php echo $row["RES_TEL"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>E-MAIL</label>
                        <p class="form-control-static"><?php echo $row["RES_EMAIL"]; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>