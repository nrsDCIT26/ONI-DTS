<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('settings.system_title')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: rgba(0, 0, 0, 0.3);
            color: white;
            font-family: 'Arial', sans-serif;
            font-weight: 500;
            height: 100vh;
            margin: 0;
        }

        body::after {
            content: "";
            background-image: url('../asset_img/oni.jpg'); 
            opacity: 0.7; 
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            position: fixed;
            z-index: -1;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
            flex-direction: column; 
            text-align: center;
        }

        .position-ref {
            position: relative;
        }

        .top-left {
            position: absolute;
            left: 10px;
            top: 18px;
            color: white; 
        }
        

        .content {
            margin: auto; 
        }

        .title {
            font-size: 4rem; 
            font-weight: bold; 
            margin-bottom: 0.5rem; 
        }

        .subtext {
            font-size: 1.5rem; 
            font-style: italic; 
        }

        .links > a {
            color: #ffffff;
            padding: 0.5rem 1rem;
            font-size: 1rem; 
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            border: 1px solid #ffffff;
            border-radius: 10px;
            transition: background-color 0.3s, color 0.3s; 
        }

        .links > a:hover {
            background-color: #ffffff;
            color: #000000;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
        <div class="top-left links">
            <img src="../asset_img/logo-imus.png" alt="City Government of Imus" width="125px" height="50px">
            <img src="../asset_img/logo-oni.png" alt="Ospital ng Imus" width="50px" height="50px">
        </div>

    <div class="content">
        <div class="title">
            Ospital ng Imus
        </div>

        <div class="subtext">
            Document Tracking System
        </div>
        <br><br>

        @if (Route::has('login'))
        <div class="links">
            @auth
                <a href="{{ route('admin.dashboard') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
        @endif

        <div class="links">
            <!-- Additional links here if needed -->
        </div>
    </div>
</div>
</body>
</html>
