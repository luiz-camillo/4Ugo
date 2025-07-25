<?php
declare(strict_types=1);

namespace LuizCamillo\FourUgo\Controllers;

use LuizCamillo\FourUgo\Models\UserModel;

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * @param array $data Deve conter: email, password
     * @return bool|'invalid'|false  
     *   - true se sucesso,  
     *   - 'invalid' se usuário não existe ou senha errada,  
     *   - false se faltar dado  
     */
    public function login(array $data): bool|string
    {
        if (empty($data['email']) || empty($data['password'])) {
            return false;
        }

        $user = $this->userModel->findByEmail(trim($data['email']));
        if (! $user) {
            return 'invalid';
        }

        if (! password_verify($data['password'], $user['password'])) {
            return 'invalid';
        }

        // grava o ID do usuário na sessão
        $_SESSION['user_id'] = (int)$user['id'];
        return true;
    }
}
