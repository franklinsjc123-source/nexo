<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Nexocart – Account Deletion Request</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            --secondary: #6366f1;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-page: #f8fafc;
            --white: #ffffff;
            --accent: #10b981;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .hero-section {
            background: var(--primary-gradient);
            padding: 5rem 1rem 8rem;
            text-align: center;
            color: white;
            position: relative;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4rem;
            background: var(--bg-page);
            clip-path: polygon(0 100%, 100% 100%, 100% 0);
        }

        .app-logo {
            max-width: 120px;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .hero-section h1 {
            font-weight: 700;
            font-size: 2.75rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .hero-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .main-container {
            max-width: 800px;
            margin: -4rem auto 4rem;
            padding: 0 1rem;
            position: relative;
            z-index: 10;
        }

        .card {
            background: var(--white);
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            overflow: hidden;
            padding: 3rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: var(--text-dark);
            display: flex;
            align-items: center;
        }

        .section-title::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 24px;
            background: var(--primary);
            border-radius: 2px;
            margin-right: 12px;
        }

        .step-list {
            list-style: none;
            padding: 0;
            margin-bottom: 2.5rem;
        }

        .step-item {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            margin-bottom: 1rem;
            padding: 1.25rem;
            border-radius: 1rem;
            transition: transform 0.2s ease, background 0.2s ease;
            text-decoration: none;
            color: var(--text-dark);
        }

        .step-item:hover {
            transform: translateX(10px);
            background: #eef2ff;
            color: var(--primary);
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 700;
            margin-right: 1.25rem;
            flex-shrink: 0;
        }

        .divider {
            text-align: center;
            margin: 3rem 0;
            position: relative;
        }

        .divider span {
            background: var(--white);
            padding: 0 20px;
            position: relative;
            z-index: 1;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 2px;
        }

        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }

        .contact-card {
            background: #fdf2f2;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            border: 1px solid #fee2e2;
        }

        .contact-card h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #b91c1c;
        }

        .contact-email {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            display: block;
            margin: 1rem 0;
        }

        .data-info {
            background: #fcfcfc;
            border-radius: 1rem;
            padding: 2rem;
            margin-top: 3.5rem;
            border: 1px dashed #e2e8f0;
        }

        .data-info-title {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
            font-size: 1.1rem;
        }

        .data-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .data-item-icon {
            color: var(--accent);
            margin-right: 12px;
            font-weight: bold;
        }

        .data-item p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .footer {
            text-align: center;
            padding: 2rem 0 4rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        @media (max-width: 600px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            .card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <section class="hero-section">
        <div class="container">
            <img src="{{ asset('backend_assets/images/logo.jpg') }}" alt="Nexocart Logo" class="app-logo">
            <h1>Nexocart</h1>
            <p>Account Deletion Request & Data Privacy Information</p>
        </div>
    </section>

    <div class="main-container">
        <div class="card shadow-lg">
            <h2 class="section-title">How to delete your account</h2>
            <div class="step-list">
                <div class="step-item">
                    <span class="step-number">1</span>
                    <span>Open the Nexocart app on your device</span>
                </div>
                <div class="step-item">
                    <span class="step-number">2</span>
                    <span>Go to Profile / Settings menu</span>
                </div>
                <div class="step-item">
                    <span class="step-number">3</span>
                    <span>Tap on "Delete Account" option</span>
                </div>
                <div class="step-item">
                    <span class="step-number">4</span>
                    <span>Confirm your request to proceed</span>
                </div>
            </div>

            <div class="divider">
                <span>OR</span>
            </div>

            <div class="contact-card">
                <h3>Request via Email</h3>
                <p class="text-muted small">If you cannot access the app, you can send us a request directly.</p>
                <a href="mailto:support@nexocart.com" class="contact-email">support@nexocart.com</a>
                <p class="mb-0 text-muted small">Please include your registered mobile number or email address in your request.</p>
            </div>

            <div class="data-info">
                <h3 class="data-info-title">Data Deletion Details</h3>
                <div class="data-item">
                    <span class="data-item-icon">✓</span>
                    <p>Your account information (name, phone number, email) will be <strong>permanently deleted</strong>.</p>
                </div>
                <div class="data-item">
                    <span class="data-item-icon">✓</span>
                    <p>Order history may be retained for legal and accounting purposes for a limited time as required by law.</p>
                </div>
                <div class="data-item">
                    <span class="data-item-icon">✓</span>
                    <p>After deletion, your data <strong>cannot be recovered</strong>. If you wish to use our services again, you will need to create a new account.</p>
                </div>
                <div class="data-item">
                    <span class="data-item-icon">✓</span>
                    <p>Deletion requests are typically processed within <strong>3-5 working days</strong>.</p>
                </div>
            </div>
        </div>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} Nexocart. Rajapalayam, Tamil Nadu, India.</p>
            <a href="{{ route('privacy-policy') }}" class="text-decoration-none text-muted mx-2">Privacy Policy</a>
            <a href="/" class="text-decoration-none text-muted mx-2">Login</a>
        </footer>
    </div>

</body>
</html>
