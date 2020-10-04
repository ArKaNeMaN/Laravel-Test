<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Xorn test</title>
    </head>
    <body>
        <h1>Курс <b>{{$Coin->Name}}</b> к <b>{{$Currency->Name}}</b></h1>
        <ul>
            <li><b>Курс:</b> {{$Course->Amount}}</li>
            <li><b>Обновлено:</b> {{$Course->LastUpdate}}</li>
        </ul>
    </body>
</html>