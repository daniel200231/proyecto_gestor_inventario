<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Tareas</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
    <h1>Gestor de Tareas</h1>
    <p class="subtitulo"><?= count($tareas ?? []) ?> tarea(s) registrada(s)</p>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Formulario que envía los datos al Controlador -->
    <form action="index.php?accion=crear" method="POST">
        <input type="text" name="titulo" placeholder="Título" maxlength="150" required>
        <input type="text" name="descripcion" placeholder="Descripción">
        <button type="submit">Agregar</button>
    </form>

    <!-- Recorremos $tareas, la variable que nos entregó el Controlador -->
    <?php if (empty($tareas)): ?>
        <div class="vacio">Todavía no tienes tareas. Agrega la primera arriba.</div>
    <?php else: ?>
        <ul>
            <?php foreach ($tareas as $tarea): ?>
                <li class="<?= htmlspecialchars($tarea['estado']) ?>">
                    <div class="tarea-info">
                        <!-- htmlspecialchars evita que un título con HTML/JS rompa la página -->
                        <span class="tarea-titulo"><?= htmlspecialchars($tarea['titulo']) ?></span>
                        <span class="tarea-descripcion"><?= htmlspecialchars((string) ($tarea['descripcion'] ?? '')) ?></span>
                        <span class="badge <?= htmlspecialchars($tarea['estado']) ?>"><?= htmlspecialchars($tarea['estado']) ?></span>
                        <time class="tarea-fecha" datetime="<?= htmlspecialchars($tarea['creado_en']) ?>">
                            <?= htmlspecialchars(date('d/m/Y H:i', strtotime($tarea['creado_en']))) ?>
                        </time>
                    </div>

                    <!-- Formularios POST: un enlace no puede cambiar ni borrar datos -->
                    <div class="tarea-acciones">
                        <form action="index.php?accion=estado" method="POST">
                            <input type="hidden" name="id" value="<?= (int) $tarea['id'] ?>">
                            <button type="submit">Cambiar estado</button>
                        </form>
                        <form action="index.php?accion=eliminar" method="POST" onsubmit="return confirm('¿Eliminar esta tarea de forma permanente?');">
                            <input type="hidden" name="id" value="<?= (int) $tarea['id'] ?>">
                            <button type="submit" class="peligro">Eliminar</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
