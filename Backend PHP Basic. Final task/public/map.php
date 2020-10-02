<?php

require '../script/start.php';


use Anpi\DBHandler;


$countries = DBHandler::getDataWithCoord();
$plots = [];
foreach ($countries as $key => $value) {
    $plots[str_replace(" ", "", $value['country_en'])] = [
        'latitude' => $value['latitude'],
        'longitude' => $value['longitude'],
        'value' => $value['total_confirmed'],
        'tooltip' => [
                'content' => sprintf("%s<br>Всего зараженных: %d<br>Новые зараженные: %d<br>Всего смертей: %d<br>Новые умершие: %d<br>Всего выздоровевших: %d<br>Новые выздоровевшие: %d",
                    $value['country_ru'],
                    $value['total_confirmed'],
                    $value['new_confirmed'],
                    $value['total_death'],
                    $value['new_death'],
                    $value['total_recovered'],
                    $value['new_recovered']
                )
        ]
    ];
}

$plots = json_encode($plots);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Map</title>
    <style type="text/css">
        .mapael .mapTooltip {
            position: absolute;
            background-color: #fff;
            opacity: 1;
            filter: alpha(opacity=70);
            border-radius: 10px;
            padding: 10px;
            z-index: 1000;
            max-width: 200px;
            display: none;
            color: #343434;
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
</p>

<h1>Карта распространения COVID-19</h1>

<div class="container">
    <div class="map">Альтернативное содержимое</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mapael/2.2.0/js/jquery.mapael.min.js"></script>

<script src="../js/world_countries.js"></script>
<script>
    $(".container").mapael({
        map: {
            name: "world_countries",
            zoom: {
                enabled: true,
                maxLevel: 15
            }
        },
        legend: {
            plot: {
                mode: "horizontal",
                title: "Cities population",
                labelAttrs: {
                    "font-size": 12
                },
                marginLeft: 5,
                marginLeftLabel: 5,
                slices: [
                    {
                        max: 1000,
                        attrs: {
                            fill: "#ff0000"
                        },
                        attrsHover: {
                            transform: "s1.5",
                            "stroke-width": 1
                        },
                        size: 2
                    },
                    {
                        min: 1000,
                        max: 10000,
                        attrs: {
                            fill: "#ff0000"
                        },
                        attrsHover: {
                            transform: "s1.5",
                            "stroke-width": 1
                        },
                        size: 4
                    },
                    {
                        min: 10000,
                        max: 50000,
                        attrs: {
                            fill: "#ff0000"
                        },
                        attrsHover: {
                            transform: "s1.5",
                            "stroke-width": 1
                        },
                        size: 6
                    },
                    {
                        min: 50000,
                        max: 300000,
                        attrs: {
                            fill: "#ff0000"
                        },
                        attrsHover: {
                            transform: "s1.5",
                            "stroke-width": 1
                        },
                        size: 8
                    },
                    {
                        min: 300000,
                        max: 700000,
                        attrs: {
                            fill: "#ff0000"
                        },
                        attrsHover: {
                            transform: "s1.5",
                            "stroke-width": 1
                        },
                        size: 10
                    },
                    {
                        min: 700000,
                        max: 1200000,
                        attrs: {
                            fill: "#ff0000"
                        },
                        attrsHover: {
                            transform: "s1.5",
                            "stroke-width": 1
                        },
                        size: 12
                    },
                    {
                        min: 1200000,
                        attrs: {
                            fill: "#ff0000"
                        },
                        attrsHover: {
                            transform: "s1.5",
                            "stroke-width": 1
                        },
                        size: 14
                    }
                ]
            }
        },
        plots: <?=$plots?>
    });
</script>

</body>

</html>