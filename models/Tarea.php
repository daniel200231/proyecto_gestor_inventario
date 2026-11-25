<?php
//trae  la funcion y conecta la DB
require_once __DIR__ . '/../config/database.php';

class Tarea
{
    //SE GUARDA LA CONEXION PDO PARA USARLA EN TODOS LOS METODOS
    private PDO $db;
     public function __construct()
    {
        $this->db = conectarDB();
    }
    //DEVUELVE LAS TAREAS GUARDADAS,DE MAS RECIENTE A MAS ANTIGUA
    public function obtenerTareas(): array
    {
       $stmt = $this->db->query("SELECT * FROM tareas ORDER BY creado_en DESC, id DESC");
       return $stmt->fetchAll();
    }
    //INSTERTA NUEVA TAREA MAS DESCRIPCION LA CUAL DEVUELVE TRUE SI SE GUARDA BIEN 
    public function crearTarea(string $titulo, string $descripcion): bool
    {
        $stmt = $this->db->prepare("INSERT INTO tareas (titulo, descripcion) VALUES (:titulo, :descripcion)");
        return $stmt->execute(['titulo'=> $titulo, 'descripcion'=> $descripcion]);
    }
    //CAMBIA EL ESTADO DE UNA TAREA SI ESTA PENDIENTE O COMPLETADA
    public function cambiarEstado(int $id):void
    {
        $stmt = $this->db->prepare("UPDATE tareas SET estado = IF(estado = 'pendiente', 'completada', 'pendiente') WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    public function eliminarTarea(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM tareas WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}