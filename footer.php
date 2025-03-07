</main>
<!-- Site footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="footer-about">
                    <h6>About College Connect</h6>
                    <p class="text-justify">
                        College Connect is a comprehensive platform designed to enhance campus life by bridging the communication gap between students, class representatives, and college administration.
                    </p>
                    <div class="footer-logo">
                        <i class="fas fa-graduation-cap"></i> College Connect
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2 mb-4 mb-md-0">
                <h6>Features</h6>
                <ul class="footer-links">
                    <li><a href="add_notice.php"><i class="fas fa-bullhorn"></i> Notices</a></li>
                    <li><a href="lost_found.php"><i class="fas fa-search"></i> Lost & Found</a></li>
                    <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                </ul>
            </div>

            <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
                <h6>Quick Links</h6>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="login_page.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="registration_page.php"><i class="fas fa-user-plus"></i> Register</a></li>
                </ul>
            </div>

            <div class="col-md-12 col-lg-3">
                <h6>Connect With Us</h6>
                <div class="contact-info">
                    <p><i class="fas fa-map-marker-alt"></i> MVGR Collage og Engineering</p>
                    <p><i class="fas fa-envelope"></i> mvgr@collegeconnect.edu</p>
                    <p><i class="fas fa-phone"></i>+91 9876543219</p>
                </div>
            </div>
        </div>
        <hr>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12">
                <p class="copyright-text">
                    &copy; <?php echo date('Y'); ?> College Connect. All rights reserved.
                </p>
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <ul class="social-icons">
                    <li><a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a class="twitter" href="#"><i class="fab fa-twitter"></i></a></li>
                    <li><a class="instagram" href="#"><i class="fab fa-instagram"></i></a></li>
                    <li><a class="linkedin" href="#"><i class="fab fa-linkedin-in"></i></a></li>   
                </ul>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Footer Styles */
    .site-footer {
        background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
        padding: 60px 0 30px;
        font-size: 15px;
        line-height: 24px;
        color: #bdc3c7;
        margin-top: 50px;
    }
    
    .site-footer hr {
        border-top-color: #3a4750;
        opacity: 0.5;
        margin: 30px 0;
    }
    
    .site-footer h6 {
        color: #fff;
        font-size: 18px;
        text-transform: uppercase;
        margin-top: 5px;
        margin-bottom: 20px;
        letter-spacing: 1px;
        font-weight: 700;
        position: relative;
        padding-bottom: 10px;
    }
    
    .site-footer h6::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: var(--primary-color);
        border-radius: 3px;
    }
    
    .site-footer a {
        color: #bdc3c7;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .site-footer a:hover {
        color: #fff;
        padding-left: 5px;
    }
    
    .footer-links {
        padding-left: 0;
        list-style: none;
    }
    
    .footer-links li {
        display: block;
        margin-bottom: 12px;
    }
    
    .footer-links a {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .footer-links a i {
        color: var(--primary-color);
        font-size: 0.9rem;
    }
    
    .site-footer .social-icons {
        text-align: right;
    }
    
    .site-footer .social-icons a {
        width: 40px;
        height: 40px;
        line-height: 40px;
        margin-left: 6px;
        margin-right: 0;
        border-radius: 100%;
        background-color: #33353d;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        transition: all 0.3s ease;
    }
    
    .site-footer .social-icons a:hover {
        transform: translateY(-3px);
    }
    
    .copyright-text {
        margin: 0;
    }
    
    .social-icons {
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .social-icons li {
        display: inline-block;
    }
    
    .social-icons a {
        background-color: #33353d;
        color: #fff;
        font-size: 16px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        text-align: center;
        border-radius: 100%;
        transition: all 0.3s ease;
    }
    
    .social-icons a:hover {
        color: #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .social-icons a.facebook:hover {
        background-color: #3b5998;
    }
    
    .social-icons a.twitter:hover {
        background-color: #00aced;
    }
    
    .social-icons a.instagram:hover {
        background-color: #e1306c;
    }
    
    .social-icons a.linkedin:hover {
        background-color: #007bb6;
    }
    
    .footer-about {
        margin-bottom: 20px;
    }
    
    .footer-logo {
        margin-top: 20px;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .footer-logo i {
        font-size: 1.8rem;
        color: var(--primary-color);
    }
    
    .contact-info {
        margin-top: 15px;
    }
    
    .contact-info p {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .contact-info i {
        color: var(--primary-color);
        font-size: 1.1rem;
        width: 20px;
    }
    
    @media (max-width: 991px) {
        .site-footer [class^=col-] {
            margin-bottom: 30px;
        }
    }
    
    @media (max-width: 767px) {
        .site-footer {
            padding-bottom: 0;
        }
        
        .site-footer .copyright-text,
        .site-footer .social-icons {
            text-align: center;
        }
        
        .social-icons {
            justify-content: center;
            margin-top: 20px;
        }
    }
</style>
</body>
</html>

