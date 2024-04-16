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
            margin: 0;
        }

        body::after {
            content: "";
            background-image: url('../asset_img/oni.jpg'); 
            opacity: 0.5; 
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
        .top-right {
            position: relative;
            left: 30%;
            top: 18px;
            color: white; 
        }
        .content {
            margin: auto; 
        }

        .title {
            font-size: 4vw; 
            font-weight: bold; 
            margin-bottom: 0.5rem; 
            position: relative;
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
        .typewriter h1 {
            font-size: 2vw; 
            color: #fff;
            font-family: monospace;
            overflow: hidden; /* Ensures the content is not revealed until the animation */
            border-right: .15em solid orange; /* The typwriter cursor */
            white-space: nowrap; /* Keeps the content on a single line */
            margin: 0 auto; /* Gives that scrolling effect as the typing happens */
            letter-spacing: .15em; /* Adjust as needed */
            animation: 
                typing 3.5s steps(30, end),
                blink-caret .5s step-end infinite;
            }

            /* The typing effect */
            @keyframes typing {
            from { width: 0 }
            to { width: 100% }
            }

            /* The typewriter cursor effect */
            @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: orange }
            }
        .logos {
            display: inline-block;
            margin-left: auto;
            margin-right: auto;
            white-space: nowrap; 
            width: 15%;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
        <div class="top-right links">
            <img  class="logos" src="../asset_img/logo-oni.png" alt="Ospital ng Imus">
            <img  class="logos" src="../asset_img/logo-imus.png" alt="City Government of Imus">
        </div>

    <div class="content">
        <div class="title">
            Ospital ng Imus
        </div>

        <div class="typewriter">
            <h1>Document Tracking System</h1>
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
