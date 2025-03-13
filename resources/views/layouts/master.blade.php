<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            overflow: hidden;
            /* max-height: 800px; */
        }
        .sidebar {
            height: 100vh;
            background: #1a252f;
            color: white;
            padding: 20px;
            transition: all 0.3s;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        }
        .sidebar input {
            background: #34495e;
            color: white;
            border: none;
        }
        .sidebar input::placeholder {
            color: #bbb;
        }
        .menu li {
            padding: 12px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.2s;
        }
        .menu li a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px;
        }
        .menu li:hover {
            background: #2c3e50;
            transform: translateX(5px);
        }
        
        .content {
    height: 85vh; 
    overflow-y: auto; 
    padding-bottom: 20px; 
}
        .footer {
            text-align: center;
            padding: 10px;
            background: #34495e;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* LOADER STYLES */
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: black;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .scene {
            
            position: absolute;
            top: 80px;
            right: 80px;
            width: 50px;
            height: 50px;
            /* position: relative; */
            transform-style: preserve-3d;
            animation: rotate 3s infinite linear;
        }

        .face {
            position: absolute;
            width: 50px;
            height: 50px;
        }

        .front  { background: linear-gradient(to bottom right, #b827fc, #2c90fc); transform: translateZ(25px); }
        .back   { background: linear-gradient(to bottom right, #b8fd33, #fec837); transform: rotateY(180deg) translateZ(25px); }
        .left   { background: linear-gradient(to bottom right, #fd1892, #2c90fc); transform: rotateY(-90deg) translateZ(25px); }
        .right  { background: linear-gradient(to bottom right, #fec837, #b827fc); transform: rotateY(90deg) translateZ(25px); }
        .top    { background: linear-gradient(to bottom right, #2c90fc, #b8fd33); transform: rotateX(90deg) translateZ(25px); }
        .bottom { background: linear-gradient(to bottom right, #fd1892, #fec837); transform: rotateX(-90deg) translateZ(25px); }

        @keyframes rotate {
            from { transform: rotateX(0deg) rotateY(0deg); }
            to   { transform: rotateX(360deg) rotateY(360deg); }
        }

        /* Hide loader after animation */
        .hidden {
            opacity: 0;
            visibility: hidden;
            transition: opacity 1s ease-out, visibility 0s 1s;
        }
    </style>
</head>
<body>

    <!-- LOADER -->
    <div class="loader-container" id="loader">
        <div class="scene">
            <div class="face front"></div>
            <div class="face back"></div>
            <div class="face left"></div>
            <div class="face right"></div>
            <div class="face top"></div>
            <div class="face bottom"></div>
        </div>
    </div>

    <!-- Navbar -->
    @include('partials.header')

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('partials.sidebar')

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content card shadow-lg bg-white rounded" style="box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .25) !important;border-color: rgb(0 0 0 / 35%);padding-left: 10px !important; padding-top: 0px;">
                <div class="card-body">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')
    <script>
        document.getElementById('search').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let items = document.querySelectorAll('.menu li');
            items.forEach(item => {
                item.style.display = item.innerText.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    </script>
    <script>
        // Hide loader after 3 seconds
        window.onload = function() {
            setTimeout(() => {
                document.getElementById('loader').classList.add('hidden');
            }, 2000);
        };
    </script>

    @yield('scripts')

</body>
</html>
