<?php
require_once '../conexion.php';  // Ajusta la ruta si es necesario

$dbConnection = new DatabaseConnection();
$database = $dbConnection->connect();

if ($database instanceof MongoDB\Database) {
    $collection = $database->clientes;  // Cambiado a la colección de clientes
} else {
    die("Error al conectar a la base de datos: " . $database);
}

$id = $_GET['id'];
$cliente = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

if (!$cliente) {
    die("Cliente no encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombreCliente'];
    $email = $_POST['emailCliente'];
    $telefono = $_POST['telefonoCliente'];
    $direccion = $_POST['direccionCliente'];

    $resultado = $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['$set' => [
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'direccion' => $direccion
        ]]
    );

    if ($resultado->getModifiedCount() === 1) {
        header('Location: clientes.php');  // Redirige a la página de listado
        exit;
    } else {
        echo "Error al editar el cliente.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
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
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 2em;
        }
        form {
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 300px;
            margin: auto;
        }
        label {
            display: block;
            margin: 10px 0 5px;  /* Espaciado mejorado */
            font-size: 1em;
            text-align: left;  /* Alinear a la izquierda para mayor claridad */
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 4px;
            background-color: #222;
            color: #fff;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus {
            border-color: #fff;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #444;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Cliente</h1>
        <form action="editar.php?id=<?php echo $cliente['_id']; ?>" method="POST">
            <input type="hidden" name="idCliente" value="<?php echo $cliente['_id']; ?>">
            <label for="nombreCliente">Nombre del cliente:</label>
            <input type="text" name="nombreCliente" id="nombreCliente" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
            <label for="emailCliente">Email:</label>
            <input type="email" name="emailCliente" id="emailCliente" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
            <label for="telefonoCliente">Teléfono:</label>
            <input type="text" name="telefonoCliente" id="telefonoCliente" value="<?php echo htmlspecialchars($cliente['telefono']); ?>" required>
            <label for="direccionCliente">Dirección:</label>
            <textarea name="direccionCliente" id="direccionCliente" required><?php echo htmlspecialchars($cliente['direccion']); ?></textarea>
            <button type="submit">Guardar cambios</button>
        </form>
    </div>
</body>
</html>

