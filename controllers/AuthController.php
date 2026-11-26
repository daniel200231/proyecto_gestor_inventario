
<?php

require_once __DIR__ . '/../models/Usuario.php';

class AuthController
{
    private Usuario $modelo;

    public function __construct()
    {
        $this->modelo = new Usuario();
    }

    public function mostrarRegistro(): void
    {
        require __DIR__ . '/../views/auth/registro.php';
    }

    // Proceso de registro
    public function registrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?accion=registro');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmar = $_POST['confirmar_password'] ?? '';

        if ($nombre === '' || $correo === '' || $password === '') {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: index.php?accion=registro');
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'El correo electrónico no es válido.';
            header('Location: index.php?accion=registro');
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'La contraseña debe tener mínimo 8 caracteres.';
            header('Location: index.php?accion=registro');
            exit;
        }

        if ($password !== $confirmar) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: index.php?accion=registro');
            exit;
        }

        if ($this->modelo->buscarPorCorreo($correo)) {
            $_SESSION['error'] = 'Este correo ya está registrado.';
            header('Location: index.php?accion=registro');
            exit;
        }

        try {
            $this->modelo->registrar($nombre, $correo, $password);

            $_SESSION['exito'] = 'Registro exitoso. Ahora puedes iniciar sesión.';
            header('Location: index.php?accion=login');
            exit;

        } catch (PDOException $e) {
            $_SESSION['error'] = 'No fue posible completar el registro.';
            header('Location: index.php?accion=registro');
            exit;
        }
    }

    //formulario de login
    public function mostrarLogin(): void
    {
        require __DIR__ . '/../views/auth/login.php';
    }

        public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?accion=login');
            exit;
        }

        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';

        $usuario = $this->modelo->verificarCredenciales($correo, $password);

        if (!$usuario) {
            $_SESSION['error'] = 'Correo o contraseña incorrectos.';
            header('Location: index.php?accion=login');
            exit;
        }

        session_regenerate_id(true);

        $_SESSION['usuario'] = $usuario;

        header('Location: index.php');
        exit;
    }

    // Cerrar sesión
    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: index.php?accion=login');
        exit;
    }
}
