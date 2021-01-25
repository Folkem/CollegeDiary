<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/configs.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/logging.php";

if (isset($_POST['email'])) {
    $response = [];

    $email = $_POST['email'];
    $foundUser = StorageRepository::getUserByEmail($email);

    if (is_null($foundUser)) {
        $response['message'] = 'Користувача з даною поштою не було знайдено';
    } else {
        $userEmail = $email;
        $subject = 'Заміна паролю';
        $newPassword = random_str(16);
        $messageContent = <<<MESSAGE
<style>
    .main-text {
        font-size: 2rem;
        font-family: 'Arial', serif;
        padding: 1rem;
    }
</style>
<div class="main-text">
    Новий пароль: $newPassword
</div>
MESSAGE;

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $foundUser->setPassword($hashedPassword);
        $userWasUpdated = StorageRepository::updateUser($foundUser);

        if ($userWasUpdated) {
            $transport = new Swift_SmtpTransport(MAIL_SMTP_HOST, MAIL_SMTP_PORT, MAIL_SMTP_ENC);
            $transport->setUsername(MAIL_USER)->setPassword(MAIL_PASS);

            $mailer = new Swift_Mailer($transport);

            $message = (new Swift_Message($subject))
                ->setFrom([MAIL_USER => 'Онлайн-щоденник'])
                ->setTo([$userEmail])
                ->setBody($messageContent);
            $message->setContentType('text/html');

            $acceptedToDeliveryNumber = $mailer->send($message);

            if ($acceptedToDeliveryNumber === 1) {
                $response['message'] = 'Новий пароль було відправлено на вашу пошту';
            } else {
                $response['message'] = 'Виникла помилка на сервері. Спробуйте знову 
                    пізніше або зверніться до адміністратора';
            }
        } else {
            $response['message'] = 'Виникла помилка на сервері. Спробуйте знову 
                пізніше або зверніться до адміністратора';
        }
    }

    $jsonResponse = json_encode($response);
    echo $jsonResponse;
} else {
    header('Location: /');
}

// TODO: move to normal place
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string
{
    if ($length < 1) {
        throw new RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        /** @noinspection PhpUnhandledExceptionInspection */
        $pieces [] = $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}