<?php
session_start(); // Inicia a sessão
require_once 'includes/Database.php'; // Inclui a classe de banco de dados
require_once 'includes/Cliente.php'; // Inclui a classe Cliente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Validação básica
    if (empty($email) || empty($senha)) {
        header("Location: login.html?erro=campos_vazios");
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();

    // Buscar o cliente pelo email
    $query = "SELECT id, nome, email, senha FROM clientes WHERE email = :email LIMIT 0,1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password_from_db = $row['senha'];

        // Instanciar um objeto Cliente (apenas para usar o método autenticar)
        // Nota: Idealmente, teríamos um método estático ou uma classe de gerenciamento de usuários
        // Para este exemplo, simplificamos.
        // Um cliente fictício é criado para usar a função autenticar,
        // mas em um sistema real, você buscaria o cliente completo do DB.
        $clienteFicticio = new Cliente($row['nome'], $row['email'], 'dummy_password', '00000000000', '00000000000');
        
        // Sobrescrever a senha hash para autenticação
        // Isso é uma simplificação para usar o método existente, o ideal seria buscar o Cliente completo
        // ou ter um método estático de autenticação na classe Cliente ou um gerenciador de usuários.
        $reflection = new ReflectionProperty('Cliente', 'senha');
        $reflection->setAccessible(true);
        $reflection->setValue($clienteFicticio, $hashed_password_from_db);

        if ($clienteFicticio->autenticar($senha)) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nome'];
            header("Location: index.php"); // Redireciona para a página inicial
            exit();
        } else {
            header("Location: login.html?erro=senha_incorreta");
            exit();
        }
    } else {
        header("Location: login.html?erro=email_nao_encontrado");
        exit();
    }
} else {
    // Redireciona se não for um POST
    header("Location: login.html");
    exit();
}
?>