<?php

require_once "../loader.php";

if (isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    StorageRepository::load();
    $users = StorageRepository::getUsers();
    $selectedUsers = array_filter($users, function($user) use ($email, $password) {
        $isSameEmail = (strcasecmp($email, $user->getEmail()) == 0);
        $isSamePassword = password_verify($password, $user->getPassword());

        return $isSameEmail && $isSamePassword;
    });

    $response = [];

    // TODO: think on actions, which should be done here
    // TODO: think about error logging here
    if (count($selectedUsers) > 1) {
        $response['message'] = 'Вибачте, виникла помилка при обробці вашого запиту. Спробуйте пізніше';
        $response['action'] = 'none';
        // log error
    } else if (count($selectedUsers) == 1) {
        $selectedUser = $selectedUsers[0];
        session_start();
        $_SESSION['user'] = $selectedUser;

        $response['message'] = 'Ви успішно увійшли в ваш обліковий запис, ' . $selectedUser->getFullName();
        $response['action'] = 'reload';
    } else {
        $response['message'] = 'Користувача з вказаними даними не було знайдено';
        $response['action'] = 'none'; // think on action here too
    }

    $jsonResponse = json_encode($response);
    echo $jsonResponse;
} else {
    header('Location: http://college-diary.edu/pages/news.php');
}