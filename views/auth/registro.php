<?php
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta | GESTIUM</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body class="auth-body">
    <main class="auth-container">
        <div class="auth-logo">GESTIUM<span>.</span></div>
        <p class="auth-subtitle">GESTIÓN INTELIGENTE DE EQUIPOS</p>

        <section class="auth-card">
            <h1>Crear cuenta</h1>
            <p class="auth-description">Comienza a gestionar tu trabajo de forma inteligente.</p>

            <?php if ($error): ?>
                <div class="auth-error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="index.php?accion=registrar" method="POST">

                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre"
                       placeholder="Tu nombre" maxlength="100" required>

                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo"
                       placeholder="correo@ejemplo.com" maxlength="150" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password"
                       placeholder="Mínimo 8 caracteres" minlength="8" required>

                <label for="confirmar_password">Confirmar contraseña</label>
                <input type="password" id="confirmar_password"
                       name="confirmar_password"
                       placeholder="Repite tu contraseña" required>

                <button type="submit" class="auth-button">
                    Crear mi cuenta
                </button>
            </form>

            <p class="auth-footer">
                ¿Ya tienes una cuenta?
                <a href="index.php?accion=login">Iniciar sesión</a>
            </p>
        </section>

        <p class="auth-copyright">© 2026 GESTIUM. Todos los derechos reservados.</p>
    </main>
</body>
</html>
