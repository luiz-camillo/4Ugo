<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
session_start();

$page = $_GET['page'] ?? 'home';

switch ($page) {

    // === HOME (público) ===
    case 'home':
        // Exemplo estático de ingressos (depois puxa do DB)
        $tickets = [
            ['id'=>1,'name'=>'Executivo','price'=>'R$150'],
            ['id'=>2,'name'=>'VIP','price'=>'R$200'],
            ['id'=>3,'name'=>'Standard','price'=>'R$80'],
        ];
        echo "<h1>Ingressos Disponíveis</h1><ul>";
        foreach ($tickets as $t) {
            echo "<li>{$t['name']} — {$t['price']}</li>";
        }
        echo "</ul>";

        echo "<p>
                <a href='?page=login'>Faça Login</a> |
                <a href='?page=register'>Crie sua Conta</a> |
                <a href='?page=vendor'>Área do Vendedor</a>
              </p>";
        break;


    // === LOGIN DO COMPRADOR ===
    case 'login':
        echo "<h1>Login do Cliente</h1>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new \LuizCamillo\FourUgo\Controllers\AuthController();
            $res  = $auth->login($_POST);

            if ($res === true) {
                header('Location: ?page=account');
                exit;
            } elseif ($res === 'invalid') {
                echo "<p style='color:red;'>❌ E-mail ou senha inválidos.</p>";
            } else {
                echo "<p style='color:red;'>❌ Preencha e-mail e senha.</p>";
            }
        }

        echo '
        <form method="POST">
            <label>E-mail: <input type="email"    name="email"    required></label><br><br>
            <label>Senha:  <input type="password" name="password" required minlength="6"></label><br><br>
            <button type="submit">Entrar</button>
        </form>';
        break;


    // === REGISTRO DO COMPRADOR ===
    case 'register':
        echo "<h1>Crie sua Conta</h1>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl = new \LuizCamillo\FourUgo\Controllers\UserController();
            $res  = $ctrl->register($_POST);

            if (is_string($res)) {
                echo "<p style='color:red;'>❌ {$res}</p>";
            } elseif ($res === true) {
                echo "<p style='color:green;'>✅ Conta criada com sucesso! Faça login agora.</p>";
            } else {
                echo "<p style='color:red;'>❌ Preencha todos os campos corretamente.</p>";
            }
        }

        echo '
        <form method="POST">
            <label>Primeiro Nome:      <input type="text"     name="first_name"      required minlength="2"></label><br><br>
            <label>Sobrenome:          <input type="text"     name="last_name"       required minlength="2"></label><br><br>
            <label>Apelido:            <input type="text"     name="nickname"        required minlength="2"></label><br><br>
            <label>E-mail:             <input type="email"    name="email"           required></label><br><br>
            <label>Senha:              <input type="password" name="password"        required minlength="6"></label><br><br>
            <label>Confirmar Senha:    <input type="password" name="confirm_password" required minlength="6"></label><br><br>
            <button type="submit">Cadastrar</button>
        </form>';
        break;


    // === PAINEL DO COMPRADOR ===
    case 'account':
        if (empty($_SESSION['user_id'])) {
            header('Location: ?page=login');
            exit;
        }

        // busca dados do usuário pelo ID
        $model = new \LuizCamillo\FourUgo\Models\UserModel();
        $user  = $model->findById((int) $_SESSION['user_id']);
        $name  = $user['first_name'] ?? 'Cliente';

        echo "<h1>Bem-vindo, {$name}</h1>";
        echo "<p>Esta é sua área de cliente. Em breve aqui você verá seu histórico de compras.</p>";
        echo "<p><a href='?page=logout'>Sair</a></p>";
        break;


    // === LOGOUT DO COMPRADOR ===
    case 'logout':
        session_destroy();
        header('Location: ?page=home');
        exit;


    // === ÁREA DO VENDEDOR ===
    case 'vendor':
        // seu código de vendedor (login + painel) permanece aqui...
        break;


    default:
        echo "<h1>Página não encontrada</h1>";
        break;
}
