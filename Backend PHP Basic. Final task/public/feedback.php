<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Форма обратной связи</title>
    <style>
        div {
            position: relative;
            width: 20vw;
        }

        textarea {
            resize: none;
            width: 90%;
        }

        input {
            width: 90%;
        }

        a {
            font-size: large;
            padding-right: 10px;
        }
    </style>
</head>

<body>

<p>
    <a href="index.php">Главная</a>
    <a href="diagram.php">Диаграммы</a>
    <a href="map.php">Карта</a>
</p>

<h1>Форма обратной связи</h1>

<div>
    <p>
        <b>Ваше имя: *</b><br>
        <input type="text" id="name" maxlength="255" placeholder="Имя" required>
    </p>

    <p>
        <b>Email адрес: *</b><br>
        <input type="text" id="email" maxlength="255" placeholder="Email" required>
    </p>

    <p>
        <b>Текст сообщения: *</b><br>
        <textarea id="message" cols="40" rows="10" maxlength="1000" placeholder="Сообщение" required></textarea>
    </p>

    <p>
        <button id="btn_send">Отправить</button>
        <div id="result"></div>
    </p>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(function () {

        $('#btn_send').click(function () {

            $.post(
                '../script/ajax.php',
                {
                    type: 'feedback',
                    name: $('#name').val(),
                    email: $('#email').val(),
                    message: $('#message').val()
                },
                function (data) {
                    data = JSON.parse(data);
                    $('#result').html(data['message'].join('<br>'));
                }
            );
        });

    })
</script>

</body>

</html>