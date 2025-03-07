<?php include('includes/header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About College Connect</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Internal CSS for About Section */
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
            background-color: #f8f9fc;
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .about-section {
            background: linear-gradient(135deg, #fff 0%, #f8f9fc 100%);
            color: var(--text-color);
            padding: 80px 30px;
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin: 40px auto;
            max-width: 1100px;
            position: relative;
            overflow: hidden;
        }
        
        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .about-section .container {
            max-width: 900px;
            margin: auto;
            position: relative;
            z-index: 2;
        }

        .about-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
            font-weight: 700;
        }

        .about-section h2 span {
            color: var(--primary-color);
            font-weight: bold;
            position: relative;
        }
        
        .about-section h2 span::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }

        .about-section p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 25px;
            color: #555;
        }

        .about-section h3 {
            margin-top: 30px;
            margin-bottom: 15px;
            color: #333;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .about-section h3 i {
            color: var(--primary-color);
            margin-right: 10px;
            font-size: 1.3rem;
        }

        .about-section ul {
            list-style: none;
            padding: 0;
            margin-bottom: 25px;
        }

        .about-section ul li {
            font-size: 1.05rem;
            margin: 12px 0;
            padding-left: 35px;
            position: relative;
            transition: all 0.3s ease;
        }

        .about-section ul li::before {
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            content: "\f00c";
            position: absolute;
            left: 0;
            color: var(--success-color);
            font-size: 1.1rem;
        }

        .about-section ul li:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        /* CTA Button Styles */
        .cta-container {
            margin-top: 40px;
            text-align: center;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 35px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(135deg, #224abe 0%, var(--primary-color) 100%);
            transition: all 0.5s ease;
            z-index: -1;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(78, 115, 223, 0.5);
        }
        
        .cta-button:hover::before {
            width: 100%;
        }
        
        .highlight {
            background: linear-gradient(120deg, rgba(78, 115, 223, 0.2) 0%, rgba(78, 115, 223, 0.2) 100%);
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: 600;
        }
        
        .section-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(78, 115, 223, 0.3), transparent);
            margin: 40px 0;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }
        
        .feature-item {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: all 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }
        
        .feature-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .about-section {
                padding: 50px 20px;
                margin: 20px;
            }

            .about-section h2 {
                font-size: 2rem;
            }

            .about-section p, .about-section ul li {
                font-size: 1rem;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <section class="about-section">
        <div class="container">
            <h2>Welcome to <span>College Connect</span></h2>
            <p>
                <b>College Connect</b> is your comprehensive platform designed to enhance campus life by bridging the communication gap between students, class representatives, and college administration. Our mission is to create a seamless, organized, and engaging college experience for everyone.
            </p>

            <div class="feature-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="feature-title">Stay Updated</div>
                    <p>Access all official and unofficial notices instantly in one place.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="feature-title">Easy Management</div>
                    <p>Class Representatives can post updates and manage information effortlessly.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="feature-title">Lost & Found</div>
                    <p>Report or find lost items quickly through our dedicated system.</p>
                </div>
            </div>

            <div class="section-divider"></div>

            <h3><i class="fas fa-star"></i> Why Choose College Connect?</h3>
            <ul>
                <li>Instant access to important campus announcements and updates</li>
                <li>Streamlined communication between students and administration</li>
                <li>Efficient lost and found reporting system</li>
                <li>Role-based access for secure and relevant information sharing</li>
                <li>Modern, intuitive interface designed for the best user experience</li>
            </ul>

            <h3><i class="fas fa-globe"></i> Our Mission</h3>
            <p>
                We believe in fostering a connected and informed student community. Our goal is to eliminate 
                communication gaps and provide a platform that enhances productivity and collaboration. Through College Connect, 
                we aim to make campus life more organized, efficient, and enjoyable for everyone involved.
            </p>

            <h3><i class="fas fa-users"></i> Who Can Use College Connect?</h3>
            <ul>
                <li><span class="highlight">Students</span> - Access notices & report lost items</li>
                <li><span class="highlight">Class Representatives (CRs)</span> - Post unofficial notices and manage lost & found items</li>
                <li><span class="highlight">Administrators</span> - Oversee all activities and manage official notices</li>
            </ul>

            <div class="section-divider"></div>

            <h3><i class="fas fa-rocket"></i> Get Started Today!</h3>
            <p>
                Join <b>College Connect</b> now and experience seamless campus communication like never before! Let's make college life more connected, organized, and enjoyable for everyone.
            </p>

            <!-- Call-to-Action Button -->
            <div class="cta-container">
                <a href="registration_page.php" class="cta-button">
                    <i class="fas fa-user-plus"></i> Join College Connect Today!
                </a>
            </div>
        </div>
    </section>
</body>
</html>
<?php include('includes/footer.php'); ?>
