<?php
require_once "config.php";
$VIV_COD_err= "";
// Processing form data when form is submitted
if(isset($_POST["VIV_COD"]) && !empty(trim($_POST["VIV_COD"]))){
	// Include config file
	$sql = "UPDATE vivienda SET VIV_ESTADO=? WHERE VIV_COD=?";
     // Prepare an insert statement
    
    if($stmt = mysqli_prepare($link, $sql)){
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "is", $param_VIV_ESTADO, $param_VIV_COD);
		// Set parameters
		
		$param_VIV_ESTADO = trim($_POST["VIV_ESTADO"]);
		$param_VIV_COD = trim($_POST["VIV_COD"]);

		
		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			// Records created successfully. Redirect to landing page
			header("location: return.php");
			exit();
		} else{
			echo "Something went wrong. Please try again later.";
		}
    }
         
        // Close statement
        mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
}
else{
    // Check existence of id parameter before processing further
    if(isset($_GET["VIV_COD"]) && !empty(trim($_GET["VIV_COD"]))){
        // Get URL parameter
        $VIV_COD =  trim($_GET["VIV_COD"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM vivienda WHERE VIV_COD = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_VIV_COD);
            
            // Set parameters
            $param_VIV_COD = $VIV_COD;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $VIV_COD = $row["VIV_COD"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Error";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Desactivar/Activar Vivienda</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 30px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Desactivar/Activar Vivienda</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para Desactivar/Activar una vivienda de la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($VIV_COD_err)) ? 'has-error' : ''; ?>">
                            <label>Código de vivienda</label>
                            <input type="text" name="VIV_COD" class="form-control" value="<?php echo $VIV_COD; ?>">
                            <span class="help-block"><?php echo $VIV_COD_err;?></span>
                        </div>
                        <div class="form-group">
							<label for="VIV_ESTADO">Estado</label>
							<select class="form-control" name="VIV_ESTADO">
								<option value="1">Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="MNT_VIV.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>