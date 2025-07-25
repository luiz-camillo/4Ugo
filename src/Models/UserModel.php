<?php
declare(strict_types=1);

namespace LuizCamillo\FourUgo\Models;

use PDO;
use PDOException;
use RuntimeException;

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $projectRoot = dirname(__DIR__, 2);
        $dbPath      = $projectRoot . '/database/4ugo.sqlite';

        if (! file_exists($dbPath)) {
            throw new RuntimeException("Banco de dados não encontrado em: {$dbPath}");
        }

        $this->db = new PDO('sqlite:' . $dbPath);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function register(
        string $firstName,
        string $lastName,
        string $nickname,
        string $email,
        string $password
    ): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "
            INSERT INTO users (first_name, last_name, nickname, email, password)
            VALUES (:first_name, :last_name, :nickname, :email, :password)
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':first_name' => $firstName,
            ':last_name'  => $lastName,
            ':nickname'   => $nickname,
            ':email'      => $email,
            ':password'   => $hashedPassword,
        ]);
    }

    /**
     * Busca um usuário pelo e-mail.
     *
     * @param string $email
     * @return array|null  Dados do usuário ou null se não existir
     */
    public function findByEmail(string $email): ?array
    {
        $sql  = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Busca um usuário pelo ID.
     *
     * @param int $id
     * @return array|null  Dados do usuário ou null se não existir
     */
    public function findById(int $id): ?array
    {
        $sql  = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
}
