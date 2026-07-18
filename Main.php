<?php
session_start();
include 'connect.php';
if(isset($_SESSION['Sid']))
{
    header('Location: Login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoShop - Sustainable Shopping</title>
    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --forest: #1a3c2a;
            --forest-light: #2d5a3f;
            --sage: #7c9a6e;
            --sage-light: #a8c49a;
            --cream: #faf7f2;
            --cream-dark: #f0ebe3;
            --terracotta: #c4724e;
            --terracotta-light: #e08a62;
            --sand: #d4c5a9;
            --moss: #4a6741;
            --bark: #3e2c1c;
            --gold: #c9a84c;
            --glass-bg: rgba(255,255,255,0.65);
            --glass-border: rgba(255,255,255,0.3);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cream);
            color: var(--forest);
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(26, 60, 42, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 16px 0;
            border-bottom: 1px solid rgba(124, 154, 110, 0.2);
            transition: all 0.4s ease;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar.scrolled {
            padding: 10px 0;
            box-shadow: 0 4px 30px rgba(0,0,0,0.15);
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 1.8rem;
            color: #fff !important;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-brand .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--sage), var(--terracotta));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nav-link {
            color: rgba(255,255,255,0.75) !important;
            font-weight: 500;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
            padding: 8px 18px !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.08);
        }
        .dropdown-menu {
            background: rgba(26, 60, 42, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(124, 154, 110, 0.2);
            border-radius: 12px;
            padding: 8px;
            margin-top: 8px;
        }
        .dropdown-item {
            color: rgba(255,255,255,0.75);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .dropdown-item:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        /* ===== HERO ===== */
        .hero-section {
            position: relative;
            min-height: 92vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(160deg, var(--forest) 0%, var(--forest-light) 40%, var(--moss) 100%);
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(196, 114, 78, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(124, 154, 110, 0.25) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.05); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* Floating leaf particles */
        .leaf {
            position: absolute;
            width: 12px;
            height: 12px;
            background: rgba(168, 196, 154, 0.3);
            border-radius: 0 50% 50% 50%;
            animation: leafFall linear infinite;
            pointer-events: none;
        }
        @keyframes leafFall {
            0% { transform: translateY(-100px) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(calc(100vh + 100px)) rotate(720deg); opacity: 0; }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #fff;
            padding: 40px 20px;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 28px;
            backdrop-filter: blur(10px);
        }
        .hero-badge .dot {
            width: 8px;
            height: 8px;
            background: var(--sage-light);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: clamp(3rem, 8vw, 6rem);
            line-height: 1;
            margin-bottom: 24px;
            letter-spacing: -2px;
        }
        .hero-title .highlight {
            background: linear-gradient(135deg, var(--sage-light), var(--terracotta-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.25rem);
            font-weight: 300;
            color: rgba(255,255,255,0.7);
            max-width: 540px;
            margin: 0 auto 40px;
            line-height: 1.7;
        }
        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-hero {
            padding: 16px 36px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-hero-primary {
            background: var(--terracotta);
            color: #fff;
            border: 2px solid var(--terracotta);
            box-shadow: 0 4px 20px rgba(196, 114, 78, 0.4);
        }
        .btn-hero-primary:hover {
            background: var(--terracotta-light);
            border-color: var(--terracotta-light);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(196, 114, 78, 0.5);
        }
        .btn-hero-outline {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255,255,255,0.25);
        }
        .btn-hero-outline:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.5);
            color: #fff;
            transform: translateY(-3px);
        }

        .hero-stats {
            display: flex;
            gap: 48px;
            justify-content: center;
            margin-top: 64px;
            padding-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .hero-stat {
            text-align: center;
        }
        .hero-stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
        }
        .hero-stat-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 4px;
        }

        /* ===== SECTION SHARED ===== */
        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--terracotta);
            margin-bottom: 12px;
        }
        .section-tag::before {
            content: '';
            width: 30px;
            height: 2px;
            background: var(--terracotta);
            border-radius: 2px;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: clamp(2rem, 4vw, 3rem);
            letter-spacing: -1px;
            margin-bottom: 16px;
        }
        .section-desc {
            color: var(--sage);
            font-size: 1.05rem;
            font-weight: 300;
            max-width: 520px;
            margin: 0 auto 48px;
            line-height: 1.7;
        }

        /* ===== SHOP SECTION ===== */
        #shop {
            background: var(--cream);
            position: relative;
            padding: 100px 0;
        }
        #shop::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(to bottom, rgba(26,60,42,0.06), transparent);
            pointer-events: none;
        }

        .product-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(26, 60, 42, 0.12);
        }
        .product-card-img {
            position: relative;
            overflow: hidden;
            height: 260px;
            background: var(--cream-dark);
        }
        .product-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .product-card:hover .product-card-img img {
            transform: scale(1.08);
        }
        .product-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            background: var(--terracotta);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 50px;
        }
        .product-card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .product-card-title {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 8px;
            color: var(--forest);
        }
        .product-card-desc {
            font-size: 0.88rem;
            color: var(--sage);
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .product-price {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--forest);
        }
        .product-price .currency {
            font-size: 0.9rem;
            font-weight: 400;
            color: var(--sage);
        }

        .btn-eco {
            padding: 10px 22px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.82rem;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            text-decoration: none !important;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-eco-primary {
            background: var(--forest);
            color: #fff;
        }
        .btn-eco-primary:hover {
            background: var(--forest-light);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 60, 42, 0.3);
        }
        .btn-eco-outline {
            background: transparent;
            color: var(--forest);
            border: 1.5px solid rgba(26, 60, 42, 0.2);
        }
        .btn-eco-outline:hover {
            background: var(--forest);
            color: #fff;
            border-color: var(--forest);
            transform: translateY(-2px);
        }
        .btn-eco-terracotta {
            background: var(--terracotta);
            color: #fff;
        }
        .btn-eco-terracotta:hover {
            background: var(--terracotta-light);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(196, 114, 78, 0.35);
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 500;
            padding: 4px 12px;
            border-radius: 50px;
            margin-top: 12px;
        }
        .stock-badge.in-stock {
            background: rgba(74, 103, 65, 0.1);
            color: var(--moss);
        }
        .stock-badge.out-of-stock {
            background: rgba(196, 114, 78, 0.1);
            color: var(--terracotta);
        }
        .stock-badge .stock-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .stock-badge.in-stock .stock-dot { background: var(--moss); }
        .stock-badge.out-of-stock .stock-dot { background: var(--terracotta); }

        /* ===== CUSTOMER SECTION ===== */
        #customer {
            background: linear-gradient(180deg, var(--cream-dark) 0%, var(--cream) 100%);
            padding: 100px 0;
            position: relative;
        }
        #customer::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(124, 154, 110, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .auth-card {
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            height: 100%;
            transition: all 0.4s ease;
        }
        .auth-card:hover {
            box-shadow: 0 16px 50px rgba(0,0,0,0.08);
        }
        .auth-card-header {
            padding: 28px 32px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .auth-card-header.register {
            background: linear-gradient(135deg, var(--forest), var(--forest-light));
            color: #fff;
        }
        .auth-card-header.login {
            background: linear-gradient(135deg, var(--terracotta), var(--terracotta-light));
            color: #fff;
        }
        .auth-card-header h5 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.4rem;
            margin: 0;
        }
        .auth-card-header p {
            font-size: 0.85rem;
            opacity: 0.8;
            margin: 4px 0 0;
        }
        .auth-card-body {
            padding: 32px;
        }

        .form-group label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--forest);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1.5px solid rgba(0,0,0,0.08);
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--cream);
        }
        .form-control:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 4px rgba(124, 154, 110, 0.12);
            background: #fff;
        }
        .form-control::placeholder {
            color: rgba(0,0,0,0.25);
        }

        .input-group .form-control {
            border-radius: 12px 0 0 12px;
        }
        .input-group-append .input-group-text {
            border-radius: 0 12px 12px 0;
            border: 1.5px solid rgba(0,0,0,0.08);
            border-left: none;
            background: var(--cream);
            color: var(--sage);
        }

        .form-check-label {
            font-size: 0.88rem;
            color: var(--sage);
        }
        .form-check-input:checked ~ .form-check-label {
            color: var(--forest);
        }

        .btn-auth {
            width: 100%;
            padding: 16px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            transition: all 0.4s ease;
            border: none;
            cursor: pointer;
            margin-top: 8px;
        }
        .btn-auth-register {
            background: linear-gradient(135deg, var(--forest), var(--forest-light));
            color: #fff;
        }
        .btn-auth-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 60, 42, 0.35);
            color: #fff;
        }
        .btn-auth-login {
            background: linear-gradient(135deg, var(--terracotta), var(--terracotta-light));
            color: #fff;
        }
        .btn-auth-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(196, 114, 78, 0.4);
            color: #fff;
        }

        .welcome-banner {
            background: linear-gradient(135deg, var(--forest), var(--moss));
            border-radius: 16px;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 32px;
            color: #fff;
        }
        .welcome-banner .banner-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.12);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .welcome-banner h6 {
            font-weight: 600;
            margin: 0 0 4px;
            font-size: 1rem;
        }
        .welcome-banner p {
            font-size: 0.82rem;
            opacity: 0.8;
            margin: 0;
        }

        /* ===== MODAL RESTYLE ===== */
        .modal-content {
            border: none;
            border-radius: 24px;
            overflow: hidden;
        }
        .modal-header {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 24px 28px;
        }
        .modal-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.3rem;
        }
        .modal-body {
            padding: 28px;
        }
        .modal-footer {
            border-top: 1px solid rgba(0,0,0,0.05);
            padding: 20px 28px;
        }
        .modal-body img {
            border-radius: 16px;
        }
        .modal-body h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .modal-body p {
            color: var(--sage);
            line-height: 1.7;
        }
        .modal-body p strong {
            color: var(--forest);
        }

        /* ===== FOOTER ===== */
        .site-footer {
            background: var(--forest);
            color: rgba(255,255,255,0.6);
            padding: 60px 0 30px;
            position: relative;
            overflow: hidden;
        }
        .site-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--sage), transparent);
        }
        .footer-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 1.5rem;
            color: #fff;
            margin-bottom: 12px;
        }
        .footer-tagline {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.4);
            margin-bottom: 24px;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-links li {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: rgba(255,255,255,0.5);
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s;
        }
        .footer-links a:hover {
            color: #fff;
            padding-left: 4px;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.08);
            padding-top: 24px;
            margin-top: 40px;
            text-align: center;
            font-size: 0.82rem;
            color: rgba(255,255,255,0.35);
        }
        .footer-bottom a {
            color: var(--sage-light);
            text-decoration: none;
        }
        .footer-bottom a:hover {
            color: #fff;
        }
        .footer-social {
            display: flex;
            gap: 12px;
        }
        .footer-social a {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: all 0.3s;
        }
        .footer-social a:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
            transform: translateY(-3px);
        }

        /* ===== SCROLL REVEAL ===== */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero-stats {
                gap: 24px;
            }
            .hero-stat-num {
                font-size: 1.6rem;
            }
            .auth-card-body {
                padding: 24px;
            }
        }

        /* ===== SMOOTH SCROLL ===== */
        html {
            scroll-behavior: smooth;
        }

        /* ===== TOAST ===== */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
        }
        .eco-toast {
            background: var(--forest);
            color: #fff;
            padding: 16px 24px;
            border-radius: 14px;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideInRight 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
            margin-bottom: 10px;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Password toggle */
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--sage);
            cursor: pointer;
            padding: 4px;
        }
        .password-toggle:hover {
            color: var(--forest);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <span class="brand-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8c.7-1 1-2.2 1-3.5C18 2.5 16.5 1 14.5 1c-1 0-1.8.4-2.5 1C11.3 1.4 10.5 1 9.5 1 7.5 1 6 2.5 6 4.5 6 5.8 6.3 7 7 8"/><path d="M12 2v6"/><path d="M7 8c-2.8 2-5 5.5-5 9 0 4.4 3.6 8 8 8h4c4.4 0 8-3.6 8-8 0-3.5-2.2-7-5-9"/></svg>
                </span>
                EcoShop
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#shop">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="#customer">Customer</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">More</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#about">About Us</a>
                            <a class="dropdown-item" href="#contact">Contact</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Home Section (Hero) -->
    <section id="home" class="hero-section">
        <!-- Floating leaves -->
        <div class="leaf" style="left:5%; animation-duration:12s; animation-delay:0s; width:14px; height:14px;"></div>
        <div class="leaf" style="left:15%; animation-duration:16s; animation-delay:2s;"></div>
        <div class="leaf" style="left:28%; animation-duration:14s; animation-delay:4s; width:10px; height:10px;"></div>
        <div class="leaf" style="left:42%; animation-duration:18s; animation-delay:1s;"></div>
        <div class="leaf" style="left:55%; animation-duration:13s; animation-delay:3s; width:16px; height:16px;"></div>
        <div class="leaf" style="left:68%; animation-duration:15s; animation-delay:5s;"></div>
        <div class="leaf" style="left:80%; animation-duration:17s; animation-delay:0.5s; width:10px; height:10px;"></div>
        <div class="leaf" style="left:92%; animation-duration:14s; animation-delay:2.5s;"></div>

        <div class="hero-content">
            <div class="hero-badge">
                <span class="dot"></span>
                Sustainable & Eco-Friendly
            </div>
            <h1 class="hero-title">
                Welcome to<br>
                <span class="highlight">EcoShop</span>
            </h1>
            <p class="hero-subtitle">
                Sustainable products for a greener future. Shop eco-friendly items and join our community of conscious consumers.
            </p>
            <div class="hero-actions">
                <a href="#shop" class="btn-hero btn-hero-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Explore Shop
                </a>
                <a href="#customer" class="btn-hero btn-hero-outline">
                    Join Community
                </a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-num">2K+</div>
                    <div class="hero-stat-label">Products</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">15K+</div>
                    <div class="hero-stat-label">Customers</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">98%</div>
                    <div class="hero-stat-label">Satisfaction</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop Section -->
    <section id="shop" class="py-5">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <div class="section-tag">Our Collection</div>
                <h2 class="section-title">Shop Our Products</h2>
                <p class="section-desc">Curated sustainable goods designed with the planet in mind. Every purchase makes a difference.</p>
            </div>

            <?php

              $qry3="SELECT * FROM `products`;";
              $result3=mysqli_query($connect, $qry3);
              $data3=mysqli_fetch_assoc($result3);


             ?>
            <!-- Product Grid -->
    <div class="row">
    <?php
    $qry3 = "SELECT * FROM `products`;";
    $result3 = mysqli_query($connect, $qry3);
    mysqli_data_seek($result3, 0);
    $counter = 0;
    while ($product = mysqli_fetch_assoc($result3)) {
        if ($counter % 3 == 0 && $counter > 0) {
            echo '</div><div class="row">';
        }
        $modalId = "productModal" . $product['Pid'];
    ?>
        <div class="col-lg-4 col-md-6 mb-5 reveal" style="transition-delay: <?php echo ($counter % 3) * 0.15; ?>s;">
            <div class="product-card">
                <div class="product-card-img">
                    <img src="<?php echo "images/" . $product['image']; ?>" alt="<?php echo $product['product_title']; ?>">
                    <span class="product-badge">New</span>
                </div>
                <div class="product-card-body">
                    <h5 class="product-card-title"><?php echo $product['product_title']; ?></h5>
                    <p class="product-card-desc"><?php echo $product['Description']; ?></p>
                    <div class="product-card-footer">
                        <span class="product-price"><span class="currency">$</span><?php echo $product['price']; ?></span>
                        <div>
                            <button class="btn-eco btn-eco-outline mr-1" data-toggle="modal" data-target="#<?php echo $modalId; ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                View
                            </button>
                            <?php if (isset($_SESSION['Sid'])): ?>
                                <form action="add_to_cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="product" value="<?php echo $product['product_title']; ?>">
                                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                    <button type="submit" class="btn-eco btn-eco-terracotta">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                        Add
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="p2.php#customer" class="btn-eco btn-eco-terracotta">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    Add
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="stock-badge <?php echo $product['stock'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                        <span class="stock-dot"></span>
                        <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Modal -->
        <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modalId; ?>Label" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $modalId; ?>Label"><?php echo $product['product_title']; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="<?php echo "images/" . $product['image']; ?>" class="img-fluid" alt="<?php echo $product['product_title']; ?>" style="border-radius:16px;">
                            </div>
                            <div class="col-md-6" style="padding-left:24px;">
                                <h4><?php echo $product['product_title']; ?></h4>
                                <p><strong>Price:</strong> $<?php echo $product['price']; ?></p>
                                <p><strong>Description:</strong> <?php echo $product['Description']; ?></p>
                                <p><strong>Stock:</strong> <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-eco btn-eco-outline" data-dismiss="modal">Close</button>
                        <?php if (isset($_SESSION['Sid'])): ?>
                            <form action="add_to_cart.php" method="post" style="display: inline;">
                                <input type="hidden" name="product" value="<?php echo $product['product_title']; ?>">
                                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                <button type="submit" class="btn-eco btn-eco-terracotta">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <a href="p2.php#customer" class="btn-eco btn-eco-terracotta">Add to Cart</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
        $counter++;
    }
    ?>
</div>

    <!-- Customer Section -->
    <section id="customer" class="py-5">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <div class="section-tag">Your Account</div>
                <h2 class="section-title">Customer Portal</h2>
                <p class="section-desc">Join our growing community or sign in to access your sustainable shopping experience.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6 mb-4 reveal" style="transition-delay: 0.1s;">
                    <div class="auth-card">
                        <div class="auth-card-header register">
                            <h5>Create Account</h5>
                            <p>Join the eco-friendly community</p>
                        </div>
                        <div class="auth-card-body">
                            <form method="post">
                                <div class="form-group">
                                    <label for="regName">Full Name</label>
                                    <input type="text" class="form-control" id="regName" name="Full_Name" placeholder="Your full name" required>
                                </div>
                                <div class="form-group">
                                    <label for="regEmail">Email</label>
                                    <input type="email" class="form-control" id="regEmail" name="Email" placeholder="you@example.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="regPassword">Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" placeholder="Create a strong password" id="regPassword" name="Password" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('regPassword', this)">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <button type="submit" class="btn-auth btn-auth-register" name="Register">Create Account</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 mb-4 reveal" style="transition-delay: 0.25s;">
                    <div class="auth-card">
                        <div class="auth-card-header login">
                            <h5>Welcome Back</h5>
                            <p>Sign in to your account</p>
                        </div>
                        <div class="auth-card-body">
                            <form method="post">
                                <div class="form-group">
                                    <label for="loginEmail">Email</label>
                                    <input type="email" class="form-control" id="loginEmail" name="Email" placeholder="you@example.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="loginPassword">Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" id="loginPassword" name="Password" placeholder="Enter your password" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('loginPassword', this)">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember Me</label>
                                </div>
                                <button type="submit" class="btn-auth btn-auth-login" name="Login">Sign In</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Welcome Banner -->
            <div class="row justify-content-center reveal">
                <div class="col-lg-10">
                    <div class="welcome-banner">
                        <div class="banner-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <div>
                            <h6>Welcome to EcoShop!</h6>
                            <p>Your account is active. Start shopping sustainably and make a positive impact on the planet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5" style="background: #fff;">
        <div class="container">
            <div class="row align-items-center reveal">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="section-tag">Our Story</div>
                    <h2 class="section-title">About Us</h2>
                    <p style="color: var(--sage); line-height: 1.8; font-size: 1.05rem;">
                        At EcoShop, we believe that sustainable living should be accessible to everyone. We carefully curate products that are kind to the earth without compromising on quality or style. Every item in our store tells a story of responsible craftsmanship and environmental care.
                    </p>
                    <div class="d-flex gap-4 mt-4">
                        <div style="text-align:center;">
                            <div style="font-family:'Playfair Display',serif; font-size:2rem; font-weight:700; color:var(--forest);">5+</div>
                            <div style="font-size:0.8rem; color:var(--sage); text-transform:uppercase; letter-spacing:1px;">Years</div>
                        </div>
                        <div style="text-align:center;">
                            <div style="font-family:'Playfair Display',serif; font-size:2rem; font-weight:700; color:var(--forest);">100%</div>
                            <div style="font-size:0.8rem; color:var(--sage); text-transform:uppercase; letter-spacing:1px;">Eco</div>
                        </div>
                        <div style="text-align:center;">
                            <div style="font-family:'Playfair Display',serif; font-size:2rem; font-weight:700; color:var(--forest);">50+</div>
                            <div style="font-size:0.8rem; color:var(--sage); text-transform:uppercase; letter-spacing:1px;">Partners</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div style="background: linear-gradient(135deg, var(--cream-dark), var(--sage-light)); border-radius:24px; height:360px; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden;">
                        <div style="position:absolute; width:200px; height:200px; background:rgba(255,255,255,0.3); border-radius:50%; top:-40px; right:-40px;"></div>
                        <div style="position:absolute; width:120px; height:120px; background:rgba(196,114,78,0.15); border-radius:50%; bottom:20px; left:20px;"></div>
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.4;"><path d="M17 8c.7-1 1-2.2 1-3.5C18 2.5 16.5 1 14.5 1c-1 0-1.8.4-2.5 1C11.3 1.4 10.5 1 9.5 1 7.5 1 6 2.5 6 4.5 6 5.8 6.3 7 7 8"/><path d="M12 2v6"/><path d="M7 8c-2.8 2-5 5.5-5 9 0 4.4 3.6 8 8 8h4c4.4 0 8-3.6 8-8 0-3.5-2.2-7-5-9"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5" style="background: var(--cream);">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <div class="section-tag">Get In Touch</div>
                <h2 class="section-title">Contact Us</h2>
                <p class="section-desc">Have questions? We'd love to hear from you. Reach out and we'll get back to you shortly.</p>
            </div>
            <div class="row justify-content-center reveal">
                <div class="col-lg-8">
                    <div style="background:#fff; border-radius:24px; padding:40px; border:1px solid rgba(0,0,0,0.05); box-shadow:0 10px 40px rgba(0,0,0,0.04);">
                        <div class="row">
                            <div class="col-md-4 mb-4 mb-md-0">
                                <div style="display:flex; flex-direction:column; gap:24px;">
                                    <div>
                                        <div style="width:40px; height:40px; background:rgba(124,154,110,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:8px;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sage)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                        </div>
                                        <div style="font-size:0.8rem; font-weight:600; color:var(--forest); text-transform:uppercase; letter-spacing:0.5px;">Email</div>
                                        <div style="font-size:0.9rem; color:var(--sage); margin-top:4px;">hello@ecoshop.com</div>
                                    </div>
                                    <div>
                                        <div style="width:40px; height:40px; background:rgba(196,114,78,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:8px;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        </div>
                                        <div style="font-size:0.8rem; font-weight:600; color:var(--forest); text-transform:uppercase; letter-spacing:0.5px;">Location</div>
                                        <div style="font-size:0.9rem; color:var(--sage); margin-top:4px;">Green City, Earth</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Your Name">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Your Email">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" rows="4" placeholder="Your Message" style="border-radius:12px; resize:none;"></textarea>
                                </div>
                                <button class="btn-eco btn-eco-primary" style="width:100%; padding:14px; border-radius:12px; font-size:0.95rem;">Send Message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="footer-brand">EcoShop</div>
                    <p class="footer-tagline">Sustainable products for a greener future.<br>Every purchase makes a difference.</p>
                    <div class="footer-social">
                        <a href="#">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="#">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="#">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h6 style="color:#fff; font-size:0.85rem; font-weight:600; letter-spacing:1px; text-transform:uppercase; margin-bottom:20px;">Shop</h6>
                    <ul class="footer-links">
                        <li><a href="#shop">All Products</a></li>
                        <li><a href="#shop">New Arrivals</a></li>
                        <li><a href="#shop">Best Sellers</a></li>
                        <li><a href="#shop">Sale</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h6 style="color:#fff; font-size:0.85rem; font-weight:600; letter-spacing:1px; text-transform:uppercase; margin-bottom:20px;">Company</h6>
                    <ul class="footer-links">
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h6 style="color:#fff; font-size:0.85rem; font-weight:600; letter-spacing:1px; text-transform:uppercase; margin-bottom:20px;">Stay Updated</h6>
                    <p style="font-size:0.88rem; color:rgba(255,255,255,0.4); margin-bottom:16px;">Get the latest on new products and exclusive deals.</p>
                    <div style="display:flex; gap:8px;">
                        <input type="email" class="form-control" placeholder="Your email" style="border-radius:12px; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); color:#fff; font-size:0.9rem; padding:12px 16px;">
                        <button class="btn-eco btn-eco-terracotta" style="white-space:nowrap; border-radius:12px; padding:12px 20px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; 2024 EcoShop. All rights reserved. Crafted with care for the planet. | <a href="#contact">Contact Us</a>
            </div>
        </div>
    </footer>

    <!-- Toast container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Bootstrap 4 JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Scroll reveal
        function revealOnScroll() {
            const reveals = document.querySelectorAll('.reveal');
            reveals.forEach(el => {
                const windowHeight = window.innerHeight;
                const elementTop = el.getBoundingClientRect().top;
                const revealPoint = 100;
                if (elementTop < windowHeight - revealPoint) {
                    el.classList.add('active');
                }
            });
        }
        window.addEventListener('scroll', revealOnScroll);
        window.addEventListener('load', revealOnScroll);

        // Password toggle
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
            } else {
                input.type = 'password';
                btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
            }
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                // Close mobile menu
                const navCollapse = document.querySelector('.navbar-collapse');
                if (navCollapse.classList.contains('show')) {
                    $('.navbar-collapse').collapse('hide');
                }
            });
        });
    </script>

</body>
</html>

<?php 


if(isset($_POST['Register'])) 
{        
  $fn=htmlspecialchars($_POST['Full_Name']);
  $em=htmlspecialchars($_POST['Email']);
  $pwd=htmlspecialchars($_POST['Password']);
  

  if(!empty($fn) && !empty($em) && !empty($pwd))
  {
  $qry = "INSERT INTO `Customer`(`Full Name`, `Email`, `Password`) VALUES ('$fn','$em','$pwd');";
  $result=mysqli_query($connect, $qry);

  if($result)
  { echo $em;
    echo "<script>alert('registered');</script>";
    header('Location: ' .$_SERVER['PHP_SELF']); 
    exit();
  }
  else{
    echo "fails";
  }

  }// full nam shouldn't be 0 

  else
  {
    echo "Give proper details";
  }

}//if register

if (isset($_POST['Login'])) {
    // code...

    $lem=htmlspecialchars($_POST['Email']);
    $lpwd=htmlspecialchars($_POST['Password']);

    $qry2="SELECT * FROM `Customer`;";
    $result2=mysqli_query($connect, $qry2); // compile query with database info taken from 
    $nouse=0;

    while ($data2=mysqli_fetch_assoc($result2)) {
        // code...
        if(($lem == $data2['Email']) && ($lpwd == $data2['Password'])) // comparing database data with data get from user.
        {
            $id=$data2['ID'];
            $_SESSION['Sid']=$id;$nouse=0;
            header("Location: Login.php");
            exit();
        }
        $nouse++;
    }//while
    if($nouse > 0 )
    {
        echo "<script>alert('Invalid ID and Password');</script>";
    }

} // if login 

?>
