<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Non Trovata</title>
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
</head>

<body>

    <div class="container">

        <h1>404</h1>
        <p>Pagina Non Trovata</p>

        <a class="btn my_btn" href="{{ url('/') }}">Torna alla HomePage</a>

    </div>
</body>
</html>

<style>
    .my_btn {
        border: 1px solid #3f8d8e;
        background-color: white;

        font-weight: bold;
        color: #3f8d8e;

        padding: 12px;
        border-radius: 18px;


        &:hover {
            border: 1px solid white;
            background-color: #3f8d8e;
            color: white;
        }
    }

    .container {
        text-align: center;
    }

    body {
        background-color: white;
        color: black;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: 'Arial', sans-serif;
    }

    h1 {
        font-size: 72px;
        margin-bottom: 24px;
    }

    p {
        font-size: 24px;
        margin-bottom: 24px;
    }

    a {
        color: #3f8d8e;
        text-decoration: none;
        font-size: 18px;
    }
</style>