<?php
// Check existence of id parameter before processing further
if(isset($_GET["MAS_COD"]) && !empty(trim($_GET["MAS_COD"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM mascota WHERE MAS_COD = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_MAS_COD);
        
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
                        <label>Codigo</label>
                        <p class="form-control-static"><?php echo $row["MAS_COD"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <p class="form-control-static"><?php echo $row["MAS_NOM"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Especie</label>
                        <p class="form-control-static"><?php echo $row["MAS_ESP"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Raza</label>
                        <p class="form-control-static"><?php echo $row["MAS_RAZA"]; ?></p>
                    </div>
					<div class="form-group">
                        <label>Rut responsable</label>
                        <p class="form-control-static"><?php echo $row["MAS_RES"]; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>