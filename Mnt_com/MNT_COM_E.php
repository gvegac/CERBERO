<?php
require_once "config.php";
$COM_ID_err= "";
// Processing form data when form is submitted
if(isset($_POST["COM_ID"]) && !empty(trim($_POST["COM_ID"]))){
	// Include config file
	$sql = "UPDATE complejo_habitacional SET COM_ESTADO=? WHERE COM_ID=?";
     // Prepare an insert statement
    
    if($stmt = mysqli_prepare($link, $sql)){
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "is", $param_COM_ESTADO, $param_COM_ID);
		// Set parameters
		
		$param_COM_ESTADO = trim($_POST["COM_ESTADO"]);
		$param_COM_ID = trim($_POST["COM_ID"]);

		
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
    if(isset($_GET["COM_ID"]) && !empty(trim($_GET["COM_ID"]))){
        // Get URL parameter
        $COM_ID =  trim($_GET["COM_ID"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM complejo_habitacional WHERE COM_ID = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_COM_ID);
            
            // Set parameters
            $param_COM_ID = $COM_ID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $COM_ID = $row["COM_ID"];
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
    <title>Desactivar/Activar Complejo habitacional</title>
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
                        <h2>Desactivar/activar Complejo habitacional</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para Desactivar/Activar un Complejo habitacional.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group <?php echo (!empty($COM_ID_err)) ? 'has-error' : ''; ?>">
                            <label>Rut del Complejo Habitacional</label>
                            <input type="text" name="COM_ID" class="form-control" value="<?php echo $COM_ID; ?>">
                            <span class="help-block"><?php echo $COM_ID_err;?></span>
                        </div>
                        <div class="form-group">
							<label for="COM_ESTADO">Estado</label>
							<select class="form-control" name="COM_ESTADO">
								<option value="1">Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
                        <input type="submit" class="btn btn-primary" value="Ingresar">
                        <a href="MNT_COM.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>