<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minerva - Educational Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        
        .nav-links a:hover {
            opacity: 0.8;
        }
        
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5rem 2rem;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: transform 0.3s;
            margin: 0 0.5rem;
        }
        
        .btn:hover {
            transform: scale(1.05);
        }
        
        .features {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
        }
        
        .features h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .feature-card h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Minerva</div>
            <div class="nav-links">
                <a href="/">Home</a>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
            </div>
        </nav>
    </header>

    <section class="hero">
        <h1>Welcome to Minerva</h1>
        <p>Your Educational Management Platform</p>
        <a href="/register" class="btn">Get Started</a>
        <a href="/login" class="btn">Sign In</a>
    </section>

    <section class="features">
        <h2>Features</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <h3>📚 Course Management</h3>
                <p>Easily manage and organize your courses with our intuitive interface.</p>
            </div>
            <div class="feature-card">
                <h3>👨‍🎓 Student Tracking</h3>
                <p>Monitor student progress and performance in real-time.</p>
            </div>
            <div class="feature-card">
                <h3>📊 Analytics</h3>
                <p>Get detailed insights with comprehensive analytics and reporting.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 Minerva. All rights reserved.</p>
    </footer>
</body>
</html>