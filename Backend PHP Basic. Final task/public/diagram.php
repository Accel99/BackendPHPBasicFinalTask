<?php

require '../script/start.php';


use Anpi\DBHandler;


$countryArr = DBHandler::getCountries();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Диаграммы статистики COVID-19</title>
    <style>
        div#canvas_div{
            position: relative;
            height:35vh;
            width:70vw
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
    <a href="map.php">Карта</a>
</p>

<h1>Диаграммы статистики COVID-19</h1>

<p>
    <button id="by_countries" class="btn">По странам</button>
    <button id="by_country" class="btn">По стране</button>
    <button id="total" class="btn">Общая статистика</button>
</p>

<p>
    <select id="select_type">
        <option value="total_confirmed">Всего зараженных</option>
        <option value="new_confirmed">Новые зараженные</option>
        <option value="total_death">Всего смертей</option>
        <option value="new_death">Новые умершие</option>
        <option value="total_recovered">Всего выздоровевших</option>
        <option value="new_recovered">Новые выздоровевшие</option>
    </select>

    <select id="select_country" hidden>
        <?php
            foreach ($countryArr as $key => $value) {
                echo sprintf("<option value=\"%s\">%s</option>", $value['country_en'], $value['country_ru']);
            }
        ?>
    </select>
</p>

<div id="canvas_div">
    <canvas id="canvas"></canvas>
</div>





<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

    let ctx = $('#canvas')[0].getContext('2d');
    let canvas;

    function view(btn_type, stat_type, country) {
        $(".btn").prop('disabled', false);
        $("#" + btn_type).prop('disabled', true);

        if (btn_type !== 'by_country') {
            $('#select_country').prop('hidden', true);
        } else {
            $('#select_country').prop('hidden', false);
        }

        $.post(
            '../script/ajax.php',
            {
                type: 'diagram',
                btn_type: btn_type,
                stat_type: stat_type,
                country: country
            },
            function (data) {

                let labelsChart = [];
                let dataChart = [];
                let backgroundColor = [];
                let borderColor = [];
                let type = 'bar';

                data = JSON.parse(data);

                $.each(data, function (key, value) {
                    if (btn_type === 'by_countries') {
                        labelsChart.push(value['country_ru']);
                    } else {
                        labelsChart.push(value['date'].split(' ')[0]);
                        type = 'line'
                    }

                    dataChart.push(value[stat_type]);

                    let r = Math.floor(Math.random() * 205) + 50;
                    let g = Math.floor(Math.random() * 205) + 50;
                    let b = Math.floor(Math.random() * 205) + 50;
                    backgroundColor.push('rgba(' +  r + ', ' + g + ', ' + b + ', 0.6)');
                    borderColor.push('rgba(' +  r + ', ' + g + ', ' + b + ', 1)')
                });

                if (canvas) {
                    canvas.destroy();
                }

                canvas = new Chart(ctx, {
                   type: type,
                   data: {
                       labels: labelsChart,
                       datasets: [{

                           data: dataChart,
                           backgroundColor: backgroundColor,
                           borderColor: borderColor,
                           borderWidth: 1
                       }]
                   },
                   options: {
                       legend: {
                           display: false
                       }
                   }
                });
            }
        );
    }

    let btn_type = 'by_countries';
    let stat_type = 'total_confirmed';
    let country = 'Russian Federation';

    view(btn_type, stat_type, country);

    $(document).ready(function () {

        $("#select_country option[value=\"" + country +"\"]").prop('selected', true);

        $('#by_countries').click(function () {
            btn_type = 'by_countries';
            view(btn_type, stat_type, country);
        });

        $('#by_country').click(function () {
            btn_type = 'by_country';
            view(btn_type, stat_type, country);
        });

        $('#total').click(function () {
            btn_type = 'total';
            view(btn_type, stat_type, country);
        });

        $('#select_type').change(function () {
            stat_type = $('#select_type').val();
            view(btn_type, stat_type, country);
        });

        $('#select_country').change(function () {
            country = $('#select_country').val();
            view(btn_type, stat_type, country);
        });

    })

</script>

</body>
</html>