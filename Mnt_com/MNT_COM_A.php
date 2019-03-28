<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$COM_ID = $COM_NOM = $COM_TIPO = $COM_DIR = $COM_COM = $COM_ESTADO = "";
$COM_ID_err = $COM_NOM_err = $COM_TIPO_err = $COM_DIR_err = $COM_COM_err= $COM_ESTADO_err =  "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate COM_ID
    $input_COM_ID = trim($_POST["COM_ID"]);
    if(empty($input_COM_ID)){
        $COM_ID_err = "Porfavor ingrese el id del Complejo Habitacional.";
    } else{
        $COM_ID = $input_COM_ID;
    }
    
    // Validate COM_NOM
    $input_COM_NOM = trim($_POST["COM_NOM"]);
    if(empty($input_COM_NOM)){
        $COM_NOM_err = "Porfavor ingrese el nombre del complejo habitacional.";
    }else{
        $COM_NOM = $input_COM_NOM;
    }
    
    // Validate COM_TIPO
    $input_COM_TIPO = trim($_POST["COM_TIPO"]);
    if(empty($input_COM_TIPO)){
        $COM_TIPO_err = "Ingrese el tipo de Complejo Habitacional.";     
    } elseif(!filter_var($input_COM_TIPO, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $COM_TIPO_err = "Ingrese un tipo de Complejo Habitacional.";
    } else{
        $COM_TIPO = $input_COM_TIPO;
    }
	// Validate COM_DIR
    $input_COM_DIR = trim($_POST["COM_DIR"]);
     if(empty($input_COM_DIR)){
        $COM_DIR_err = "Ingrese una Direccion.";     
    } elseif(!filter_var($input_COM_DIR, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9,\s]+$/")))){
        $COM_DIR_err = "Ingrese una Dirección Valida.";
    } else{
        $COM_DIR = $input_COM_DIR;
    }
    // Validate COM_COM
    $input_COM_COM = trim($_POST["COM_COM"]);
    if(empty($input_COM_COM)){
        $COM_COM_err = "Porfavor ingrese el nombre del Complejo habitacional.";
    } else{
        $COM_COM = $input_COM_COM;
    }
    // Check input errors before inserting in database
    if(empty($COM_ID_err) && empty($COM_NOM_err) && empty($COM_TIPO_err) && empty($COM_DIR_err) && empty($COM_COM_err) ){
        // Prepare an insert statement
        $sql = "UPDATE complejo_habitacional SET COM_NOM=?,COM_TIPO=?,COM_DIR=?,COM_COM=? WHERE COM_ID=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_COM_NOM, $param_COM_TIPO, $param_COM_DIR, $param_COM_COM, $param_COM_ID);
            
            // Set parameters
            $param_COM_NOM = $COM_NOM;
            $param_COM_TIPO = $COM_TIPO;
            $param_COM_DIR = $COM_DIR;
			$param_COM_COM = $COM_COM;
            $param_COM_ID = $COM_ID;
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
    }
    
    // Close connection
    mysqli_close($link);
}else{
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
					$COM_NOM = $row["COM_NOM"];
					$COM_TIPO = $row["COM_TIPO"];
					$COM_DIR = $row["COM_DIR"];
					$COM_COM = $row["COM_COM"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
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
    <title>Actualizar Complejo Habitacional</title>
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
                        <h2>Actualizar Complejo Habitacional</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para actualizar un complejo habitacional en la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($COM_ID_err)) ? 'has-error' : ''; ?>">
                            <label>ID de Complejo Habitacional (Rut)</label>
                            <input type="text" name="COM_ID" class="form-control" value="<?php echo $COM_ID; ?>">
                            <span class="help-block"><?php echo $COM_ID_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($COM_NOM_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre de Complejo Habitacional</label>
                            <input type="text" name="COM_NOM" class="form-control" value="<?php echo $COM_NOM; ?>">
                            <span class="help-block"><?php echo $COM_NOM_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($COM_TIPO_err)) ? 'has-error' : ''; ?>">
                            <label>Tipo de Complejo Habitacional</label>
                            <input type="text" name="COM_TIPO" class="form-control" value="<?php echo $COM_TIPO; ?>">
                            <span class="help-block"><?php echo $COM_TIPO_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($COM_DIR_err)) ? 'has-error' : ''; ?>">
                            <label>Dirección de Complejo Habitacional</label>
                            <input type="text" name="COM_DIR" class="form-control" value="<?php echo $COM_DIR; ?>">
                            <span class="help-block"><?php echo $COM_DIR_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($COM_COM_err)) ? 'has-error' : ''; ?>">
                            <label>Comuna</label>
                            <input type="text" name="COM_COM" class="form-control" value="<?php echo $COM_COM; ?>">
                            <span class="help-block"><?php echo $COM_COM_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="MNT_COM.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>