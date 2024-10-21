<?php
session_start();
require 'conexion.php';

$dbConnection = new DatabaseConnection();
$collectionUsuarios = $dbConnection->getCollection("usuarios");

$empleados = $collectionUsuarios->find();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <title>Lista de Empleados - CRUD</title>
    <style>
        body {
            background-color: #f5f5dc; /* Beige claro */
        }
        .container {
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table thead {
            background-color: #6a11cb;
            color: white;
        }
        .btn-primary {
            background-color: #6a11cb;
            border: none;
        }
        .btn-primary:hover {
            background-color: #2575fc;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button"><i class="fas fa-search"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="admin_dashboard.php" class="brand-link">
      <span class="brand-text font-weight-light">SPORTLINE AD</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="list_empleados.php" class="nav-link active">
              <i class="nav-icon fas fa-user"></i>
              <p>Empleado CRUD</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="categoria/categoria.php" class="nav-link">
              <i class="nav-icon fas fa-tags"></i> <!-- Icono de categoría -->
              <p>Categoria CRUD</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="productos/productos.php" class="nav-link">
              <i class="nav-icon fas fa-box"></i> <!-- Icono de productos -->
              <p>Productos CRUD</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="clientes/clientes.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i> <!-- Icono de clientes -->
              <p>Clientes CRUD</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Lista de Empleados</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Lista de Empleados</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Listado de Empleados</h3>
              </div>
              <div class="card-body">
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class='alert alert-info'><?php echo $_SESSION['mensaje']; ?></div>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>

                <div class="mb-3">
                    <input type="text" id="search" class="form-control" placeholder="Buscar empleados..." onkeyup="searchEmployees()">
                </div>

                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Email</th>
                      <th>Puesto</th>
                      <th>Rol</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($empleados as $empleado): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($empleado['nombre']); ?></td>
                      <td><?php echo htmlspecialchars($empleado['email']); ?></td>
                      <td><?php echo htmlspecialchars($empleado['puesto'] ?? 'No especificado'); ?></td>
                      <td><?php echo htmlspecialchars($empleado['rol'] ?? 'No especificado'); ?></td>
                      <td>
                        <?php if (($empleado['rol'] ?? 'No especificado') !== 'administrador'): ?>
                            <a href="edit_empleado.php?id=<?php echo $empleado['_id']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form id="delete-form-<?php echo $empleado['_id']; ?>" method="POST" action="delete_empleado.php?id=<?php echo $empleado['_id']; ?>" style="display:inline;">
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(event, 'delete-form-<?php echo $empleado['_id']; ?>')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <div class="mt-4">
                    <a href="admin_dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Panel de Administración
                    </a>
                    <a href="exportar_empleados_pdf.php" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Exportar a PDF
                    </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      Cualquier cosa que desees
    </div>
    <strong>Copyright &copy; 2024 <a href="#">TuSitioWeb</a>.</strong> Todos los derechos reservados.
  </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script>
    function searchEmployees() {
        const input = document.getElementById('search').value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let found = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(input));
            row.style.display = found ? '' : 'none';
        });
    }

    function confirmDelete(event, formId) {
        event.preventDefault(); 
        const form = document.getElementById(formId);

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará permanentemente el registro. ¿Estás seguro de que deseas continuar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminarlo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registro eliminado exitosamente!',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    form.submit();
                });
            }
        });
    }
</script>

</body>
</html>
