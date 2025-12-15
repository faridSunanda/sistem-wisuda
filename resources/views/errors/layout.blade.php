<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code', 'Error') - @yield('title', 'Something went wrong')</title>
    <link href="https://fonts.googleapis.com/css?family=Eczar:800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600" rel="stylesheet">
    <style>
        body {
            font-family: "Poppins";
            height: 100vh;
            background: #435EBE;
            padding: 1em;
            overflow: hidden;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .background-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            user-select: none;
        }

        .background-wrapper h1 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-family: "Eczar";
            font-size: 60vmax;
            color: #2e4491;
            letter-spacing: 0.025em;
            margin: 0;
            transition: 750ms ease-in-out;
        }

        a {
            border: 2px solid #fff;
            padding: 0.5em 0.8em;
            position: fixed;
            z-index: 10;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: 150ms;
            top: 20px;
            left: 20px;
            border-radius: 8px;
            font-weight: 600;
        }

        a svg > polyline {
            transition: 150ms;
            stroke: #fff;
        }

        a:hover {
            color: #435EBE;
            background: #fff;
            border-color: #fff;
        }

        a:hover svg > polyline {
            stroke: #435EBE;
        }

        a:hover + .background-wrapper > h1 {
            color: #3b53a8;
        }

        p {
            color: #fff;
            font-size: calc(1em + 3vmin);
            position: fixed;
            bottom: 1rem;
            right: 1.5rem;
            margin: 0;
            text-align: right;
            text-shadow: none;
        }

        @media screen and (min-width: 340px) {
            p { width: 70%; }
        }
        @media screen and (min-width: 560px) {
            p { width: 50%; }
        }
        @media screen and (min-width: 940px) {
            p { width: 30%; }
        }
        @media screen and (min-width: 1300px) {
            p { width: 25%; }
        }
    </style>
</head>
<body>

    <a href="{{ url('/') }}">
        <svg height="0.8em" width="0.8em" viewBox="0 0 2 1" preserveAspectRatio="none">
            <polyline
                fill="none" 
                stroke="#777777" 
                stroke-width="0.1" 
                points="0.9,0.1 0.1,0.5 0.9,0.9" 
            />
        </svg> Kembali ke Beranda
    </a>

    <div class="background-wrapper">
        <h1 id="visual">@yield('code', 'Error')</h1>
    </div>

    <p>@yield('message', 'An error occurred.')</p>

    <script>
        const visual = document.getElementById("visual")
        const events = ['resize', 'load']

        events.forEach(function(e){
            window.addEventListener(e, function(){
                const width = window.innerWidth
                const height = window.innerHeight
                const ratio = 45 / (width / height)
                visual.style.transform = "translate(-50%, -50%) rotate(-" + ratio + "deg)"
            });
        });
    </script>
</body>
</html>
