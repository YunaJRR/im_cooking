<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Bienvenido al Dashboard</h1>

    <?php $session = session(); ?>
    <?php if ($session->get('isLoggedIn')): ?>
        <div class="alert alert-success text-center">
            <h2>Hola, <?= esc($session->get('name')) ?>!</h2>
            <p>Tu correo: <?= esc($session->get('email')) ?></p>
            <p>Fecha de registro: <?= esc($session->get('created_at')) ?></p>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="<?= base_url('logout') ?>" class="btn btn-danger">Cerrar sesión</a>
    </div>
</div>
</body>
</html>