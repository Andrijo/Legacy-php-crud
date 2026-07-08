<?php
include_once('conexion.php');

$keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
$resultados = [];

if ($keywords !== '') {
    $like = '%' . $keywords . '%';
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE nombre LIKE ? OR apellido LIKE ? OR correo LIKE ? OR clave LIKE ?");
    $stmt->execute([$like, $like, $like, $like]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?5.0">
    <title>Búsqueda - Práctica Inicial</title>
</head>

<body class="container my-5">
    <div class="container my-5">
        <a href="index.php" class="btn btn-light btn-outline-dark mb-3">&larr; Volver</a>
        <h2>Resultados para: "<?= htmlspecialchars($keywords) ?>"</h2>

        <?php if ($keywords === ''): ?>
            <div class="alert alert-warning mt-3">Ingrese un término de búsqueda.</div>
        <?php elseif (count($resultados) > 0): ?>
            <p class="text-muted"><?= count($resultados) ?> cliente(s) encontrado(s).</p>
            <table class="table table-bordered border-dark mt-3">
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
                    <?php foreach ($resultados as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['id']) ?></td>
                            <td><?= htmlspecialchars($r['clave']) ?></td>
                            <td><?= htmlspecialchars($r['nombre']) ?></td>
                            <td><?= htmlspecialchars($r['apellido']) ?></td>
                            <td><?= htmlspecialchars($r['correo']) ?></td>
                            <td><?= htmlspecialchars($r['telefono']) ?></td>
                            <td><?= htmlspecialchars($r['sexo']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info mt-3">No se encontraron resultados para "<?= htmlspecialchars($keywords) ?>".</div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
