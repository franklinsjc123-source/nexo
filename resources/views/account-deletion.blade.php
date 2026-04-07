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
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
            --danger: #ef4444;
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

        .deletion-form {
            background: #fff5f5;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #fee2e2;
            margin-bottom: 2.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .form-control {
            border-radius: 0.75rem;
            padding: 0.8rem 1.2rem;
            border: 2px solid #e2e8f0;
            font-size: 1.1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn-delete {
            background: var(--danger);
            color: white;
            font-weight: 700;
            border-radius: 0.75rem;
            padding: 0.8rem 2rem;
            border: none;
            width: 100%;
            transition: all 0.2s ease;
            margin-top: 1.5rem;
        }

        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
            color: white;
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

        .data-info {
            background: #fcfcfc;
            border-radius: 1rem;
            padding: 2rem;
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
            <p>Account Deletion Request</p>
        </div>
    </section>

    <div class="main-container">
        <div class="card shadow-lg">
            <h2 class="section-title">Delete your account</h2>
            <p class="text-muted mb-4">Please enter your registered mobile number below to delete your account and all associated data.</p>
            
            <form action="{{ route('post-account-deletion') }}" method="POST" class="deletion-form" id="deleteForm">
                @csrf
                <div class="mb-3">
                    <label for="mobile" class="form-label">Phone Number (10 Digits)</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="E.g. 9876543210" required maxlength="10">
                    @error('mobile')
                        <div class="text-danger mt-2 small">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="button" class="btn btn-delete" onclick="confirmDelete()">Permanently Delete Account</button>
            </form>

            <div class="data-info">
                <h3 class="data-info-title">Important Information</h3>
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
                    <p>After deletion, your data <strong>cannot be recovered</strong>.</p>
                </div>
                <div class="data-item">
                    <span class="data-item-icon">✓</span>
                    <p>Deletion requests are processed instantly and data removal takes <strong>3-5 working days</strong>.</p>
                </div>
            </div>
        </div>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} Nexocart. Rajapalayam, Tamil Nadu, India.</p>
            <a href="{{ route('privacy-policy') }}" class="text-decoration-none text-muted mx-2">Privacy Policy</a>
            <a href="/" class="text-decoration-none text-muted mx-2">Login</a>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete() {
            const mobileInput = document.getElementById('mobile').value;
            if (!mobileInput || mobileInput.length !== 10 || isNaN(mobileInput)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Input',
                    text: 'Please enter a valid 10-digit mobile number.',
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Your account will be permanently deleted and this action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>

</body>
</html>
