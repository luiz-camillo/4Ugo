<?php
declare(strict_types=1);

namespace LuizCamillo\FourUgo\Controllers;

use LuizCamillo\FourUgo\Models\UserModel;
use PDOException;

class UserController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * @param array $data Deve conter first_name, last_name, nickname, email, password
     * @return bool|string  Retorna true em sucesso, false se faltar dado, ou mensagem de erro
     */
    public function register(array $data): bool|string
    {
        // valida campos obrigatórios
        if (
            empty($data['first_name']) ||
            empty($data['last_name']) ||
            empty($data['nickname']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {
            return false;
        }

        // tenta cadastrar e captura duplicidade de email
        try {
            return $this->userModel->register(
                trim($data['first_name']),
                trim($data['last_name']),
                trim($data['nickname']),
                trim($data['email']),
                trim($data['password'])
            );
        } catch (PDOException $e) {
            // 23000 = violation of UNIQUE constraint no SQLite
            if ($e->getCode() === '23000') {
                return 'Este email já está cadastrado e não é possível cadastrar duas vezes o mesmo email.';
            }
            throw $e;
        }
    }
}
