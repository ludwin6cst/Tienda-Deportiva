<?php
require_once '../conexion.php';  // Ajusta la ruta si es necesario

$dbConnection = new DatabaseConnection();
$database = $dbConnection->connect();

if ($database instanceof MongoDB\Database) {
    $collection = $database->categoria;  // Cambia el nombre de la colección
} else {
    die("Error al conectar a la base de datos: " . $database);
}

$id = $_GET['id'];
$categoria = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

if (!$categoria) {
    die("Categoría no encontrada.");
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombreCategoria']);
    $descripcion = trim($_POST['descripcionCategoria']);

    // Validar campos
    if (empty($nombre)) {
        $errorMessage = 'El nombre de la categoría no puede estar vacío.';
    } elseif (strlen($nombre) > 100) {
        $errorMessage = 'El nombre de la categoría no puede exceder los 100 caracteres.';
    } elseif (empty($descripcion)) {
        $errorMessage = 'La descripción no puede estar vacía.';
    } elseif (strlen($descripcion) > 255) {
        $errorMessage = 'La descripción no puede exceder los 255 caracteres.';
    } else {
        $resultado = $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => [
                'nombre' => $nombre,
                'descripcion' => $descripcion
            ]]
        );

        if ($resultado->getModifiedCount() === 1) {
            header('Location: categoria.php');  // Redirige a la lista de categorías
            exit;
        } else {
            $errorMessage = "Error al editar la categoría.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #222;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 300px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 2em;
        }
        .alert {
            margin-bottom: 15px;
        }
        .form-control {
            background-color: #222;
            color: #fff;
            border: 1px solid #444;
        }
        .form-control:focus {
            border-color: #fff;
            outline: none;
        }
        .btn-primary {
            background-color: #444;
            border: none;
        }
        .btn-primary:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Categoría</h1>

        <?php if ($errorMessage): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nombreCategoria">Nombre de la Categoría</label>
                <input type="text" class="form-control" id="nombreCategoria" name="nombreCategoria" value="<?php echo htmlspecialchars($categoria['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcionCategoria">Descripción</label>
                <textarea class="form-control" id="descripcionCategoria" name="descripcionCategoria" rows="3" required><?php echo htmlspecialchars($categoria['descripcion']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
