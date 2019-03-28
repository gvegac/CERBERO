<?php
require_once "config.php";
$EMP_ID_err= "";
// Processing form data when form is submitted
if(isset($_POST["EMP_ID"]) && !empty(trim($_POST["EMP_ID"]))){
	// Include config file
	$sql = "UPDATE empleado SET EMP_ESTADO=? WHERE EMP_ID=?";
     // Prepare an insert statement
    
    if($stmt = mysqli_prepare($link, $sql)){
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "is", $param_EMP_ESTADO, $param_EMP_ID);
		// Set parameters
		
		$param_EMP_ESTADO = trim($_POST["EMP_ESTADO"]);
		$param_EMP_ID = trim($_POST["EMP_ID"]);

		
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
    if(isset($_GET["EMP_ID"]) && !empty(trim($_GET["EMP_ID"]))){
        // Get URL parameter
        $EMP_ID =  trim($_GET["EMP_ID"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM empleado WHERE EMP_ID = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_EMP_ID);
            
            // Set parameters
            $param_EMP_ID = $EMP_ID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $EMP_ID = $row["EMP_ID"];
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
    <title>Desactivar/Activar Empleado</title>
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
                        <h2>Desactivar/activar Empleado</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para Desactivar/Activar a un empleado.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group <?php echo (!empty($EMP_ID_err)) ? 'has-error' : ''; ?>">
                            <label>Rut de Empleado</label>
                            <input type="text" name="EMP_ID" class="form-control" value="<?php echo $EMP_ID; ?>">
                            <span class="help-block"><?php echo $EMP_ID_err;?></span>
                        </div>
                        <div class="form-group">
							<label for="EMP_ESTADO">Estado</label>
							<select class="form-control" name="EMP_ESTADO">
								<option value="1">Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
                        <input type="submit" class="btn btn-primary" value="Ingresar">
                        <a href="MNT_EMP.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>