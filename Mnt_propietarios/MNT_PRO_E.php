<?php
require_once "config.php";
$PRO_RUT_err= "";
// Processing form data when form is submitted
if(isset($_POST["PRO_RUT"]) && !empty(trim($_POST["PRO_RUT"]))){
	// Include config file
	$sql = "UPDATE propietario SET PRO_ESTADO=? WHERE PRO_RUT=?";
     // Prepare an insert statement
    
    if($stmt = mysqli_prepare($link, $sql)){
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "is", $param_PRO_ESTADO, $param_PRO_RUT);
		// Set parameters
		
		$param_PRO_ESTADO = trim($_POST["PRO_ESTADO"]);
		$param_PRO_RUT = trim($_POST["PRO_RUT"]);

		
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
    if(isset($_GET["PRO_RUT"]) && !empty(trim($_GET["PRO_RUT"]))){
        // Get URL parameter
        $PRO_RUT =  trim($_GET["PRO_RUT"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM propietario WHERE PRO_RUT = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_PRO_RUT);
            
            // Set parameters
            $param_PRO_RUT = $PRO_RUT;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $PRO_RUT = $row["PRO_RUT"];
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
    <title>Desactivar/Activar Propietario</title>
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
                        <h2>Desactivar/Activar Propietario</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para Desactivar/Activar un propietario a la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($PRO_RUT_err)) ? 'has-error' : ''; ?>">
                            <label>Rut de Propietario</label>
                            <input type="text" name="PRO_RUT" class="form-control" value="<?php echo $PRO_RUT; ?>">
                            <span class="help-block"><?php echo $PRO_RUT_err;?></span>
                        </div>
                        <div class="form-group">
							<label for="PRO_ESTADO">Estado</label>
							<select class="form-control" name="PRO_ESTADO">
								<option value="1">Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
                        <input type="submit" class="btn btn-primary" value="Ingresar">
                        <a href="MNT_PRO.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>