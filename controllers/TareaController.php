<?php
//aqui se trae la clase modelo para pedir datos

require_once __DIR__ . '/../models/Tarea.php';

class TareaController
{
    private const TITULO_MAX = 150;

    //controlador pide datos al modelo sin hablar directamente con DB
    private Tarea $modelo;

    public function __construct()
    {
        $this->modelo = new Tarea();
    }

    //muestra la lista de tareas
    public function index(): void
    {
        $tareas = $this->modelo->obtenerTareas();
        $error = $this->mensajeError($_GET['error'] ?? null);
        //pasamos la variable $tareas a la vista para muestre
        require __DIR__ . '/../views/tareas/index.php';
    }

    //recibe el formulario de "nueva tarea" y guarda
    public function crear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($titulo === '') {
            header('Location: index.php?error=titulo_vacio');
            exit;
        }

        if (mb_strlen($titulo, 'UTF-8') > self::TITULO_MAX) {
            header('Location: index.php?error=titulo_largo');
            exit;
        }

        $this->modelo->crearTarea($titulo, $descripcion);

        // Después de guardar lo devuelve a la pantalla principal
        header('Location: index.php');
        exit;
    }

    // Cambia el estado de una tarea entre pendiente y completada
    public function cambiarEstado(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->modelo->cambiarEstado((int) $_POST['id']);
        }

        header('Location: index.php');
        exit;
    }

    // Elimina una tarea
    public function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->modelo->eliminarTarea((int) $_POST['id']);
        }

        header('Location: index.php');
        exit;
    }

    private function mensajeError(?string $codigo): ?string
    {
        return match ($codigo) {
            'titulo_vacio' => 'El título es obligatorio.',
            'titulo_largo' => 'El título no puede superar 150 caracteres.',
            default => null,
        };
    }
}
