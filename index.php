<?php
// Include the database connection file
include 'includes/db_connect.php';

// Test the database connection
if ($conn) {
    // Connection is successful
} else {
    echo "<p>Connection failed: " . $conn->connect_error . "</p>";
}

// Include the header file
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Connect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #e74a3b;
            --text-color: #5a5c69;
            --light-gray: #f8f9fc;
            --dark-gray: #5a5c69;
            --success-color: #1cc88a;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        /* Header */
        .large-header {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background-size: cover;
            background-position: center center;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #large-header {
            background-image: url('https://www.marcoguglie.it/Codepen/AnimatedHeaderBg/demo-1/img/demo-1-bg.jpg');
            position: relative;
        }
        
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .header-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 0 20px;
            max-width: 800px;
        }

        .main-title {
            margin: 0;
            padding: 0;
            color: #f9f1e9;
            text-align: center;
            font-size: 4.5rem;
            letter-spacing: 0.05em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        .main-title .thin {
            font-weight: 200;
        }
        
        .subtitle {
            color: #fff;
            font-size: 1.5rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        
        .cta-button {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .primary-button {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4);
        }
        
        .primary-button:hover {
            background: #2e59d9;
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(78, 115, 223, 0.5);
        }
        
        .secondary-button {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .secondary-button:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }
        
        /* Features Section */
        .features-section {
            padding: 80px 20px;
            background: #fff;
            text-align: center;
        }
        
        .section-title {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 50px;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }
        
        .features-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-color);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }
        
        .feature-description {
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Responsive Design */
        @media only screen and (max-width: 768px) {
            .main-title {
                font-size: 3rem;
            }
            
            .subtitle {
                font-size: 1.2rem;
            }
            
            .cta-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div id="large-header" class="large-header">
        <canvas id="demo-canvas"></canvas>
        <div class="header-content">
            <h1 class="main-title">College <span class="thin">Connect</span></h1>
            <p class="subtitle">Bridging the gap between students, representatives, and administration</p>
            
            <div class="cta-buttons">
                <a href="registration_page.php" class="cta-button primary-button">
                    <i class="fas fa-user-plus"></i> Join Now
                </a>
                <a href="about.php" class="cta-button secondary-button">
                    <i class="fas fa-info-circle"></i> Learn More
                </a>
            </div>
        </div>
    </div>
    
    <section class="features-section">
        <h2 class="section-title">Our Features</h2>
        
        <div class="features-container">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <h3 class="feature-title">Announcements</h3>
                <p class="feature-description">
                    Stay updated with all official and unofficial notices from your college administration and class representatives.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="feature-title">Lost & Found</h3>
                <p class="feature-description">
                    Report lost items or find items that others have lost through our efficient lost and found system.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="feature-title">Role-Based Access</h3>
                <p class="feature-description">
                    Different access levels for students, class representatives, and administrators for secure information sharing.
                </p>
            </div>
        </div>
    </section>

<!-- Include footer -->
<?php include('includes/footer.php'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
<script>
    // Your existing JavaScript code for the animated header
    (function() {
        var width, height, largeHeader, canvas, ctx, points, target, animateHeader = true;

        // Main
        initHeader();
        initAnimation();
        addListeners();

        function initHeader() {
            width = window.innerWidth;
            height = window.innerHeight;
            target = {x: width/2, y: height/2};

            largeHeader = document.getElementById('large-header');
            largeHeader.style.height = height+'px';

            canvas = document.getElementById('demo-canvas');
            canvas.width = width;
            canvas.height = height;
            ctx = canvas.getContext('2d');

            // create points
            points = [];
            for(var x = 0; x < width; x = x + width/20) {
                for(var y = 0; y < height; y = y + height/20) {
                    var px = x + Math.random()*width/20;
                    var py = y + Math.random()*height/20;
                    var p = {x: px, originX: px, y: py, originY: py };
                    points.push(p);
                }
            }

            // for each point find the 5 closest points
            for(var i = 0; i < points.length; i++) {
                var closest = [];
                var p1 = points[i];
                for(var j = 0; j < points.length; j++) {
                    var p2 = points[j]
                    if(!(p1 == p2)) {
                        var placed = false;
                        for(var k = 0; k < 5; k++) {
                            if(!placed) {
                                if(closest[k] == undefined) {
                                    closest[k] = p2;
                                    placed = true;
                                }
                            }
                        }

                        for(var k = 0; k < 5; k++) {
                            if(!placed) {
                                if(getDistance(p1, p2) < getDistance(p1, closest[k])) {
                                    closest[k] = p2;
                                    placed = true;
                                }
                            }
                        }
                    }
                }
                p1.closest = closest;
            }

            // assign a circle to each point
            for(var i in points) {
                var c = new Circle(points[i], 2+Math.random()*2, 'rgba(255,255,255,0.3)');
                points[i].circle = c;
            }
        }

        // Event handling
        function addListeners() {
            if(!('ontouchstart' in window)) {
                window.addEventListener('mousemove', mouseMove);
            }
            window.addEventListener('scroll', scrollCheck);
            window.addEventListener('resize', resize);
        }

        function mouseMove(e) {
            var posx = posy = 0;
            if (e.pageX || e.pageY) {
                posx = e.pageX;
                posy = e.pageY;
            }
            else if (e.clientX || e.clientY)    {
                posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
            }
            target.x = posx;
            target.y = posy;
        }

        function scrollCheck() {
            if(document.body.scrollTop > height) animateHeader = false;
            else animateHeader = true;
        }

        function resize() {
            width = window.innerWidth;
            height = window.innerHeight;
            largeHeader.style.height = height+'px';
            canvas.width = width;
            canvas.height = height;
        }

        // animation
        function initAnimation() {
            animate();
            for(var i in points) {
                shiftPoint(points[i]);
            }
        }

        function animate() {
            if(animateHeader) {
                ctx.clearRect(0,0,width,height);
                for(var i in points) {
                    // detect points in range
                    if(Math.abs(getDistance(target, points[i])) < 4000) {
                        points[i].active = 0.3;
                        points[i].circle.active = 0.6;
                    } else if(Math.abs(getDistance(target, points[i])) < 20000) {
                        points[i].active = 0.1;
                        points[i].circle.active = 0.3;
                    } else if(Math.abs(getDistance(target, points[i])) < 40000) {
                        points[i].active = 0.02;
                        points[i].circle.active = 0.1;
                    } else {
                        points[i].active = 0;
                        points[i].circle.active = 0;
                    }

                    drawLines(points[i]);
                    points[i].circle.draw();
                }
            }
            requestAnimationFrame(animate);
        }

        function shiftPoint(p) {
            gsap.to(p, {
                duration: 1 + 1 * Math.random(),
                x: p.originX - 50 + Math.random() * 100,
                y: p.originY - 50 + Math.random() * 100,
                ease: "circ.inOut",
                onComplete: function() {
                    shiftPoint(p);
                }
            });
        }

        // Canvas manipulation
        function drawLines(p) {
            if(!p.active) return;
            for(var i in p.closest) {
                ctx.beginPath();
                ctx.moveTo(p.x, p.y);
                ctx.lineTo(p.closest[i].x, p.closest[i].y);
                ctx.strokeStyle = 'rgba(156,217,249,'+ p.active+')';
                ctx.stroke();
            }
        }

        function Circle(pos,rad,color) {
            var _this = this;

            // constructor
            (function() {
                _this.pos = pos || null;
                _this.radius = rad || null;
                _this.color = color || null;
            })();

            this.draw = function() {
                if(!_this.active) return;
                ctx.beginPath();
                ctx.arc(_this.pos.x, _this.pos.y, _this.radius, 0, 2 * Math.PI, false);
                ctx.fillStyle = 'rgba(156,217,249,'+ _this.active+')';
                ctx.fill();
            };
        }

        // Util
        function getDistance(p1, p2) {
            return Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2);
        }

    })();
</script>
</body>
</html>
