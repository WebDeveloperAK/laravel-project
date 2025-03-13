<!DOCTYPE html>
<html>
<head>
    <title>Email from Laravel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
        }
        .email-container {
            max-width: 600px;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
        }
        .email-header {
            background: #007bff;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .email-content {
            padding: 20px;
            font-size: 16px;
        }
        .email-footer {
            padding: 15px;
            font-size: 14px;
            color: #ccc;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <canvas id="starCanvas"></canvas>

    <div class="email-container">
        <div class="email-header">
            <h2>Welcome to Laravel Mail</h2>
        </div>
        <div class="email-content">
            <p>{{ $details['message'] }}</p>
            <a href="https://yourwebsite.com" class="button">Visit Website</a>
        </div>
        <div class="email-footer">
            <p>Best Regards,</p>
            <p><strong>Your Laravel App</strong></p>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('starCanvas');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let stars = [];

        function createStars(count) {
            for (let i = 0; i < count; i++) {
                stars.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    radius: Math.random() * 2 + 1,
                    speed: Math.random() * 0.5 + 0.2
                });
            }
        }

        function drawStars() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = 'white';
            stars.forEach(star => {
                ctx.beginPath();
                ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
                ctx.fill();
            });
        }

        function updateStars() {
            stars.forEach(star => {
                star.y += star.speed;
                if (star.y > canvas.height) {
                    star.y = 0;
                    star.x = Math.random() * canvas.width;
                }
            });
        }

        function animateStars() {
            drawStars();
            updateStars();
            requestAnimationFrame(animateStars);
        }

        createStars(150);
        animateStars();

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            stars = [];
            createStars(150);
        });
    </script>

</body>
</html>
