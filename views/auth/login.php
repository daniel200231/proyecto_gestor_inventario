
<?php
$error = $_SESSION['error'] ?? null;
$exito = $_SESSION['exito'] ?? null;

unset($_SESSION['error'], $_SESSION['exito']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | GESTIUM</title>
<link rel="stylesheet" href="/public/css/variables.css">
<link rel="stylesheet" href="/public/css/base.css">
<link rel="stylesheet" href="/public/css/auth.css">
<link rel="stylesheet" href="/public/css/dashboard.css">
<link rel="stylesheet" href="/public/css/responsive.css"></head>

<body class="auth-body">

    <main class="auth-container">

        <div class="auth-logo">GESTIUM<span>.</span></div>
        <p class="auth-subtitle">GESTIÓN INTELIGENTE DE EQUIPOS</p>

        <section class="auth-card">

            <h1>Bienvenido de nuevo</h1>
            <p class="auth-description">
                Ingresa tus credenciales para continuar.
            </p>

            <?php if ($error): ?>
                <div class="auth-error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($exito): ?>
                <div class="auth-success">
                    <?= htmlspecialchars($exito, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="index.php?accion=login" method="POST">

                <label for="correo">Correo electrónico</label>
                <input
                    type="email"
                    id="correo"
                    name="correo"
                    placeholder="correo@ejemplo.com"
                    required
                >

                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Ingresa tu contraseña"
                    required
                >

                <button type="submit" class="auth-button">
                    Iniciar sesión
                </button>

            </form>

            <p class="auth-footer">
                ¿Todavía no tienes una cuenta?
                <a href="index.php?accion=registro">Crear cuenta</a>
            </p>

        </section>

        <p class="auth-copyright">
            © 2026 GESTIUM. Todos los derechos reservados.
        </p>

    </main>

</body>
</html>
