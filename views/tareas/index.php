<?php
$lista = $tareas ?? [];
$total = count($lista);
$completadas = 0;
foreach ($lista as $tarea) {
    if (($tarea['estado'] ?? '') === 'completada') {
        $completadas++;
    }
}
$pendientes = $total - $completadas;
$porcentaje = $total > 0 ? (int) round(($completadas / $total) * 100) : 0;
$nombre = $_SESSION['usuario']['nombre'] ?? 'Usuario';
$inicial = mb_strtoupper(mb_substr($nombre, 0, 1, 'UTF-8'), 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas | GESTIUM</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body class="dashboard-body">
    <aside class="sidebar">
        <div class="sidebar-brand">GESTIUM<span>.</span><small>TAREAS</small></div>
        <div class="sidebar-user">
            <div class="user-avatar"><?= htmlspecialchars($inicial) ?></div>
            <div class="user-details">
                <strong><?= htmlspecialchars($nombre) ?></strong>
                <small>Sesión activa</small>
            </div>
        </div>
        <form class="logout-form" action="index.php?accion=logout" method="POST">
            <button type="submit" class="logout-button">Cerrar sesión</button>
        </form>
    </aside>

    <main class="dashboard-main">
        <header class="dashboard-topbar">
            <div>
                <p class="topbar-label">GESTOR</p>
                <h1>Tareas</h1>
            </div>
            <p class="topbar-date"><?= date('d/m/Y') ?></p>
        </header>

        <section class="stats-grid">
            <article class="stat-card">
                <span class="stat-label">TOTAL</span>
                <strong class="stat-number"><?= $total ?></strong>
                <span class="stat-description">Tareas registradas</span>
            </article>
            <article class="stat-card">
                <span class="stat-label">PENDIENTES</span>
                <strong class="stat-number pending-number"><?= $pendientes ?></strong>
                <span class="stat-description">Aún sin completar</span>
            </article>
            <article class="stat-card">
                <span class="stat-label">COMPLETADAS</span>
                <strong class="stat-number completed-number"><?= $completadas ?></strong>
                <span class="stat-description"><?= $porcentaje ?>% del total</span>
            </article>
        </section>

        <section class="progress-card">
            <div class="progress-heading">
                <h3>Avance</h3>
                <strong><?= $porcentaje ?>%</strong>
            </div>
            <div class="progress-track">
                <div class="progress-fill" style="width: <?= $porcentaje ?>%"></div>
            </div>
        </section>

        <?php if (!empty($error)): ?>
            <p class="aviso"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form class="task-create-form" action="index.php?accion=crear" method="POST">
            <input type="text" name="titulo" placeholder="Título" maxlength="150" required>
            <input type="text" name="descripcion" placeholder="Descripción">
            <button type="submit">Agregar</button>
        </form>

        <?php if ($total === 0): ?>
            <div class="vacio">
                <h3>Todavía no tienes tareas</h3>
                <p>Agrega la primera arriba.</p>
            </div>
        <?php else: ?>
            <div class="filtros">
                <div class="filtro-grupo">
                    <span class="filtro-label">ESTADO</span>
                    <div class="task-filters" data-filtro="estado">
                        <button type="button" class="filter-button active" data-valor="todas">Todas</button>
                        <button type="button" class="filter-button" data-valor="pendiente">Pendientes</button>
                        <button type="button" class="filter-button" data-valor="completada">Completadas</button>
                    </div>
                </div>
                <div class="filtro-grupo">
                    <span class="filtro-label">FECHA</span>
                    <div class="task-filters" data-filtro="fecha">
                        <button type="button" class="filter-button active" data-valor="todas">Todas</button>
                        <button type="button" class="filter-button" data-valor="hoy">Hoy</button>
                        <button type="button" class="filter-button" data-valor="semana">Esta semana</button>
                        <button type="button" class="filter-button" data-valor="mes">Este mes</button>
                    </div>
                </div>
            </div>

            <p class="vacio filtro-vacio" hidden>Ninguna tarea coincide con ese filtro.</p>

            <ul class="task-list">
                <?php foreach ($lista as $tarea): ?>
                    <?php $estado = htmlspecialchars((string) $tarea['estado']); ?>
                    <li class="<?= $estado ?>" data-estado="<?= $estado ?>" data-fecha="<?= htmlspecialchars(date('Y-m-d', strtotime($tarea['creado_en']))) ?>">
                        <div class="tarea-info">
                            <span class="tarea-titulo"><?= htmlspecialchars($tarea['titulo']) ?></span>
                            <span class="tarea-descripcion"><?= htmlspecialchars((string) ($tarea['descripcion'] ?? '')) ?></span>
                            <span class="badge <?= $estado ?>"><?= $estado ?></span>
                            <time class="tarea-fecha" datetime="<?= htmlspecialchars($tarea['creado_en']) ?>">
                                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($tarea['creado_en']))) ?>
                            </time>
                        </div>
                        <div class="tarea-acciones">
                            <form action="index.php?accion=estado" method="POST">
                                <input type="hidden" name="id" value="<?= (int) $tarea['id'] ?>">
                                <button type="submit" class="action-button">Cambiar estado</button>
                            </form>
                            <form action="index.php?accion=eliminar" method="POST" onsubmit="return confirm('¿Eliminar esta tarea de forma permanente?');">
                                <input type="hidden" name="id" value="<?= (int) $tarea['id'] ?>">
                                <button type="submit" class="delete-button">Eliminar</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>
    <script src="public/js/filtros.js"></script>
</body>
</html>
