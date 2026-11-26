<?php
session_start();

$accion = $_GET['accion'] ?? 'index';

if (in_array($accion, ['login', 'registro', 'registrar', 'logout'], true)) {
    require_once __DIR__ . '/controllers/AuthController.php';
    $auth = new AuthController();

    if ($accion === 'login' && $_SERVER['REQUEST_METHOD'] !== 'POST' && !empty($_SESSION['usuario'])) {
        header('Location: index.php');
        exit;
    }

    match ($accion) {
        'registro'  => $auth->mostrarRegistro(),
        'registrar' => $auth->registrar(),
        'logout'    => $auth->logout(),
        'login'     => $_SERVER['REQUEST_METHOD'] === 'POST' ? $auth->login() : $auth->mostrarLogin(),
    };
    exit;
}

if (empty($_SESSION['usuario'])) {
    header('Location: index.php?accion=login');
    exit;
}

require_once __DIR__ . '/controllers/TareaController.php';

$controlador = new TareaController();

match ($accion) {
    'crear'    => $controlador->crear(),
    'estado'   => $controlador->cambiarEstado(),
    'eliminar' => $controlador->eliminar(),
    default    => $controlador->index(),
};
