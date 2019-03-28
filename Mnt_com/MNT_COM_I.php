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
    } elseif(!filter_var($input_COM_ID, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9-0-9a-zA-Z]+$/")))){
        $COM_ID_err = "Porfavor ingresar un ID valido.";
    } else{
        $COM_ID = $input_COM_ID;
    }
    
    // Validate COM_NOM
    $input_COM_NOM = trim($_POST["COM_NOM"]);
    if(empty($input_COM_NOM)){
        $COM_NOM_err = "Porfavor ingrese el nombre del Complejo habitacional.";
    } elseif(!filter_var($input_COM_NOM, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $COM_NOM_err = "Porfavor ingresar un nombre valido.";
    } else{
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
    }else{
        $COM_DIR = $input_COM_DIR;
    }
	// Validate COM_COM
    $input_COM_COM = trim($_POST["COM_COM"]);
    if(empty($input_COM_COM)){
        $COM_COM_err = "Porfavor ingrese la comuna del Complejo habitacional.";
    }else{
        $COM_COM = $input_COM_COM;
    }
	// Validate COM_ESTADO
    $COM_ESTADO = trim($_POST["COM_ESTADO"]);
    
    // Check input errors before inserting in database
    if(empty($COM_ID_err) && empty($COM_NOM_err) && empty($COM_TIPO_err) && empty($COM_DIR_err) ){
        // Prepare an insert statement
        $sql = "INSERT INTO complejo_habitacional (COM_ID, COM_NOM, COM_TIPO, COM_DIR, COM_COM, COM_ESTADO) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_COM_ID, $param_COM_NOM, $param_COM_TIPO, $param_COM_DIR, $param_COM_COM, $param_COM_ESTADO);
            
            // Set parameters
            $param_COM_ID = $COM_ID;
            $param_COM_NOM = $COM_NOM;
            $param_COM_TIPO = $COM_TIPO;
			$param_COM_DIR = $COM_DIR;
			$param_COM_COM =$COM_COM;
			$param_COM_ESTADO = $COM_ESTADO;   
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ingresar Complejo Habitacional</title>
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
                        <h2>Ingrese Complejo Habitacional</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para agregar un complejo habitacional a la base de datos.</p>
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