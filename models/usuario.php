
<?php

require_once __DIR__ . '/../config/database.php';

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = conectarDB();
        $this->asegurarTabla();
    }

    private function asegurarTabla(): void
    {
        $this->db->exec(
            "CREATE TABLE IF NOT EXISTS usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(100) NOT NULL,
                correo VARCHAR(150) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )"
        );
    }

    public function registrar(string $nombre, string $correo, string $password): bool
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (nombre, correo, password)
             VALUES (:nombre, :correo, :password)"
        );

        return $stmt->execute([
            'nombre' => $nombre,
            'correo' => $correo,
            'password' => $passwordHash
        ]);
    }

    // Buscar usuario por correo electrónico
    public function buscarPorCorreo(string $correo): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, nombre, correo, password
             FROM usuarios
             WHERE correo = :correo
             LIMIT 1"
        );

        $stmt->execute(['correo' => $correo]);

        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    public function verificarCredenciales(string $correo, string $password): ?array
    {
        $usuario = $this->buscarPorCorreo($correo);

        if (!$usuario) {
            return null;
        }

        if (!password_verify($password, $usuario['password'])) {
            return null;
        }

        unset($usuario['password']);

        return $usuario;
    }
}
