<?php
include_once('conexion.php');

$clientesxpag = 5;
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$error = '';

// POST: añadir cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ['clave', 'nombre', 'apellido', 'correo', 'telefono', 'sexo'];
    $valid = true;
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $valid = false;
            break;
        }
    }
    if ($valid) {
        try {
            $stmt = $pdo->prepare("INSERT INTO clientes (clave, nombre, apellido, correo, telefono, sexo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['clave'],
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['correo'],
                $_POST['telefono'],
                $_POST['sexo']
            ]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = "Error al añadir cliente.";
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}

// Paginación
$total = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
$paginas = max(1, ceil($total / $clientesxpag));
$pagina = min($pagina, $paginas);
$offset = ($pagina - 1) * $clientesxpag;

// Listado
$stmt = $pdo->prepare("SELECT * FROM clientes LIMIT ? OFFSET ?");
$stmt->execute([$clientesxpag, $offset]);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?5.0">
    <title>Práctica Inicial</title>
</head>

<body class="container my-5">
    <div class="container my-5">
        <div class="row">
            <div class="col">
                <h1>Práctica Inicial</h1>
                <p>Aplicación web para la gestión de clientes. Permite visualizar, buscar, añadir registros y exportar datos en formato XML, con paginación integrada y una interfaz limpia usando Bootstrap.</p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="d-grid gap-2 col-4 mx-auto mt-3">
            <button id="myBoton2" class="btn btn-light btn-outline-dark" type="button" onclick="ocultarTabla()">Ocultar tabla</button>
            <button id="myBoton" class="btn btn-light btn-outline-dark" type="button" onclick="mostrarTabla()" style="display: none;">Mostrar tabla</button>

            <a href="archivo/clientes.xml" download="clientes.xml" class="btn btn-light btn-outline-dark">Descargar XML</a>

            <button type="button" class="btn btn-light btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addClienteModal">Añadir cliente</button>

            <!-- Modal -->
            <div class="modal fade" id="addClienteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Añadir cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label>Clave</label>
                                <input type="text" class="form-control" placeholder="Clave" name="clave" required>
                                <label>Nombre</label>
                                <input type="text" class="form-control" placeholder="Nombre" name="nombre" required>
                                <label>Apellido</label>
                                <input type="text" class="form-control" placeholder="Apellido" name="apellido" required>
                                <label>Correo</label>
                                <input type="email" class="form-control" placeholder="correo@ejemplo.com" name="correo" required>
                                <label>Teléfono</label>
                                <input type="text" class="form-control" placeholder="Teléfono" name="telefono" required>
                                <label>Sexo</label>
                                <select class="form-control" name="sexo" required>
                                    <option value="">Seleccionar</option>
                                    <option value="H">H</option>
                                    <option value="M">M</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-light btn-outline-dark">Aceptar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <form style="text-align:right;" method="get" action="search.php">
            <label>
                <input type="text" name="keywords" autocomplete="off" placeholder="Buscar">
            </label>
            <input type="submit" value="Buscar"><br>
        </form>

        <div class="row">
            <table id="myTable" class="table table-bordered border-dark mt-5">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Sexo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['id']) ?></td>
                            <td><?= htmlspecialchars($c['clave']) ?></td>
                            <td><?= htmlspecialchars($c['nombre']) ?></td>
                            <td><?= htmlspecialchars($c['apellido']) ?></td>
                            <td><?= htmlspecialchars($c['correo']) ?></td>
                            <td><?= htmlspecialchars($c['telefono']) ?></td>
                            <td><?= htmlspecialchars($c['sexo']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($paginas > 1): ?>
            <nav aria-label="..." id="myPagination">
                <ul class="pagination">
                    <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $pagina - 1 ?>">Anterior</a>
                    </li>
                    <?php for ($i = 1; $i <= $paginas; $i++): ?>
                        <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $pagina >= $paginas ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $pagina + 1 ?>">Siguiente</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
