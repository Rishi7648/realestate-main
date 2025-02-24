<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Real Estate Nepal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .contact-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .contact-header h1 {
            color: #2c3e50;
        }

        .contact-info {
            margin: 20px 0;
            line-height: 1.8;
        }

        .contact-info h3 {
            margin: 10px 0;
            color: #34495e;
        }

        .contact-info a {
            text-decoration: none;
            color: #3498db;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        .contact-card {
            display: flex;
            align-items: center;
            padding: 15px;
            margin: 10px 0;
            background: #f8f9fa;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .contact-icon {
            font-size: 30px;
            margin-right: 15px;
            color: #2ecc71;
        }

        .map-container {
            margin: 30px 0;
            text-align: center;
        }

        iframe {
            width: 100%;
            height: 300px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .footer {
            text-align: center;
            padding: 10px;
            background: #2c3e50;
            color: #ecf0f1;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <div class="contact-header">
            <h1>Contact Us</h1>
            <p>We'd love to hear from you! Reach out to us via the details below:</p>
        </div>
        <div class="contact-info">
            <div class="contact-card">
                <span class="contact-icon">&#127968;</span>
                <h3>Company: Real Estate Nepal</h3>
            </div>
            <div class="contact-card">
                <span class="contact-icon">&#127970;</span>
                <h3>Location: Thamel, Kathmandu</h3>
            </div>
            <div class="contact-card">
                <span class="contact-icon">&#128222;</span>
                <h3>Contact: <a href="tel:+9899100101">9899100101</a></h3>
            </div>
            <div class="contact-card">
                <span class="contact-icon">&#128231;</span>
                <h3>Email: <a href="mailto:contact@realestate.com">contact@realestate.com</a></h3>
            </div>
        </div>
        <div class="map-container">
            <h2>Our Location</h2>
            <!--<iframe> tag is used to embed a Google Map using URL but not google map API.  -->
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3533.0204361616183!2d85.31232921543756!3d27.714929932279202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb18fd3f94d073%3A0x5e5e07e1b2d7b115!2sThamel%2C%20Kathmandu%2044600%2C%20Nepal!5e0!3m2!1sen!2snp!4v1694138885534!5m2!1sen!2snp" 
                allowfullscreen 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
    <div class="footer">
        &copy; 2025 Real Estate Nepal. All rights reserved.
    </div>
</body>
</html>
