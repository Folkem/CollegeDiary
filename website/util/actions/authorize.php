<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/logging.php";

if (isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $users = UserRepository::getUsers();
    $selectedUsers = array_filter($users, function($user) use ($email, $password) {
        $isSameEmail = (strcasecmp($email, $user->getEmail()) == 0);
        $isSamePassword = password_verify($password, $user->getPassword());

        return $isSameEmail && $isSamePassword;
    });
    $selectedUsers = array_values($selectedUsers);

    $response = [];

    if (count($selectedUsers) > 1) {
        $repeatedId = $selectedUsers[0]->getId();
        $response['message'] = 'Вибачте, виникла помилка при обробці вашого запиту. 
        Спробуйте пізніше';
        $response['action'] = 'none';
        $logger->error("Було знайдено {userCount} користувачів з id = {id}",
            ["userCount" => count($selectedUsers), "id" => $repeatedId]);
    } else if (count($selectedUsers) == 1) {
        $selectedUser = $selectedUsers[0];
        session_start();
        $_SESSION['user'] = $selectedUser;

        $response['message'] = 'Ви успішно увійшли в ваш обліковий запис, ' .
            $selectedUser->getFullName();
        $response['action'] = 'reload';
    } else {
        $response['message'] = 'Користувача з вказаними даними не було знайдено';
        $response['action'] = 'none';
    }

    $jsonResponse = json_encode($response);
    echo $jsonResponse;
} else {
    header('Location: /');
}