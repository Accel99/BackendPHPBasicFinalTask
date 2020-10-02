<?php

require 'start.php';


use Anpi\DBHandler;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;


switch ($_POST['type']) {
    case 'diagram':
        echo json_encode(DBHandler::getData($_POST['btn_type'], $_POST['stat_type'], $_POST['country']));
        break;

    case 'feedback':
        $validator = Validation::createValidator();
        $violationsName = $validator->validate(
            $_POST['name'],
            [
                new NotBlank(['message' => 'Заполните поле "Имя"', 'normalizer' => 'trim']),
                new Length(['min' => 2, 'minMessage' => 'Длина имени должна быть не менее 2-x символов'])
            ]
        );
        $violationsEmail = $validator->validate(
            $_POST['email'],
            [
                new NotBlank(['message' => 'Заполните поле "Email"', 'normalizer' => 'trim']),
                new Email(['message' => 'Адрес электронной почты не верен'])
            ]
        );;
        $violationsMessage = $validator->validate(
            $_POST['message'],
            [
                new NotBlank(['message' => 'Заполните поле "Сообщение"', 'normalizer' => 'trim']),
                new Length(['min' => 2, 'minMessage' => 'Длина сообщения должна быть не менее 2-x символов'])
            ]
        );

        $result = [];

        if (count($violationsName) !== 0) {
            foreach ($violationsName as $violation) {
                $result['message'][] = $violation->getMessage();
            }
        }
        if (count($violationsEmail) !== 0) {
            foreach ($violationsEmail as $violation) {
                $result['message'][] = $violation->getMessage();
            }
        }
        if (count($violationsMessage) !== 0) {
            foreach ($violationsMessage as $violation) {
                $result['message'][] = $violation->getMessage();
            }
        }

        if (!empty($result)) {
            $result['success'] = false;
        } else {
            $result['message'][] = 'Успешно';
            $result['success'] = true;

            $message = wordwrap('Имя: ' . $_POST['name'] . "\r\n" . $_POST['message'], 70, "\r\n");
            $headers = [
                'From' => $_POST['email'],
                'Reply-To' => $_POST['email']
            ];

            mail(EMAIL, 'Feedback', $message, $headers);
        }

        $result = json_encode($result);

        echo $result;
        break;
}