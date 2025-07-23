<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

session_start();

  $page = $_GET['page'] ?? 'home';
  
switch ($page) {
    case 'home':
        echo "<h1>Bem-vindo ao 4Ugo</h1>";
        echo "<p><a href='?page=login'>Área do Cliente</a> | <a href='?page=vendor'>Área do Vendedor</a></p>";
        break;

    case 'login':
        echo "<h1>Login do Cliente</h1>";
        break;

    case 'register':
        echo "<h1>Cadastro de Comprador</h1>";
        break;

    case 'vendor':
        echo "<h1>Área do Vendedor</h1>";
        break;

    default:
        echo "<h1>Página não encontrada</h1>";
}