<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

if (isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = UserRepository::getUserByEmail($email);

    $response = [];

    if (isset($user) && password_verify($password, $user->getPassword())) {
        $_SESSION['user'] = $user;
        $response['message'] = 'Ви успішно увійшли в ваш обліковий запис, ' .
            $user->getFullName();
        $response['action'] = 'reload';
        $logger->info(
            "User {$user->getEmail()} has logged in"
        );
    } else {
        $response['message'] = 'Користувача з вказаними даними не було знайдено';
        $response['action'] = 'none';
        $logger->info(
            "User tried to log in with email: {$email}"
        );
    }
    
    echo json_encode($response);
} else {
    echo json_encode([
        'message' => 'Не вказані наступні дані: email, password',
        'action' => 'none'
    ]);
}