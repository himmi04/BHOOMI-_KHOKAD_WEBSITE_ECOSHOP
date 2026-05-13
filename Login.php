<?php

include 'connect.php';
session_start();
if (!isset($_SESSION['Sid'])) {
    header("Location: Main.php");
    exit();
}

 $id = $_SESSION['Sid'];


// ALL POST PROCESSING AT THE TOP (BEFORE HTML)
// This makes header() redirects ACTUALLY WORK


// --- Update Profile ---
if(isset($_POST['update_profile']))
{
    $ufn = $_POST['full_name'];
    $uem = $_POST['email'];
    $qry4 = "UPDATE `Customer` SET `Full Name`='$ufn',`Email`='$uem' WHERE `ID`='$id';";
    $result4 = mysqli_query($connect, $qry4);

    if ($result4) {
        $_SESSION['flash_msg'] = 'Profile updated successfully!';
        $_SESSION['flash_type'] = 'success';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// --- Update Password ---
if (isset($_POST['update_password'])) {
    $np = $_POST['new_password'];
    $cp = $_POST['confirm_password'];

    // Fetch current password fresh from DB
    $qryCheck = "SELECT `Password` FROM `Customer` WHERE `ID`='$id'";
    $resCheck = mysqli_query($connect, $qryCheck);
    $dataCheck = mysqli_fetch_assoc($resCheck);

    if ($_POST['old_password'] == $dataCheck['Password']) {
        if ($np == $cp) {
            $qry5 = "UPDATE `Customer` SET `Password`='$cp' WHERE `ID`='$id';";
            $result5 = mysqli_query($connect, $qry5);
            if ($result5) {
                $_SESSION['flash_msg'] = 'Password changed successfully!';
                $_SESSION['flash_type'] = 'success';
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            $_SESSION['flash_msg'] = 'New password and confirm password do not match!';
            $_SESSION['flash_type'] = 'error';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['flash_msg'] = 'Please check your old password!';
        $_SESSION['flash_type'] = 'error';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// --- Add to Cart ---

if (isset($_POST['add_to_cart'])) {
    $Pid = $_POST['product_id'];

    $qry6 = "SELECT * FROM `products` WHERE `Pid` = '$Pid';";
    $result6 = mysqli_query($connect, $qry6);
    $data6 = mysqli_fetch_assoc($result6);

    if (!$result6 || mysqli_num_rows($result6) == 0) {
        $_SESSION['flash_msg'] = 'Product not found!';
        $_SESSION['flash_type'] = 'error';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    $product_name = $data6['product_title'];
    $quantity = 1;
    $price = $data6['price'];

    $qry7 = "SELECT * FROM `cart` WHERE `Pid` = '$Pid' AND `customer_id` = '$id';";
    $result7 = mysqli_query($connect, $qry7);

    if (mysqli_num_rows($result7) > 0) {
        // Already in cart → increase quantity
        $data7 = mysqli_fetch_assoc($result7);
        $quantity_of_cart = $data7['quantity'] + 1;
        $price_of_cart = $data7['price'] + $price;
        $qry8 = "UPDATE `cart` SET `quantity`='$quantity_of_cart', `price`='$price_of_cart' WHERE `Pid` = '$Pid' AND `customer_id` = '$id';";
        $result8 = mysqli_query($connect, $qry8);

        if ($result8) {
            $_SESSION['flash_msg'] = 'Increased quantity for ' . $product_name;
            $_SESSION['flash_type'] = 'success';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        // Not in cart → insert new
        $qry9 = "INSERT INTO `cart`(`Pid`, `customer_id`, `product_name`, `quantity`, `price`) VALUES ('$Pid','$id','$product_name','$quantity','$price');";
        $result9 = mysqli_query($connect, $qry9);
        if ($result9) {
            $_SESSION['flash_msg'] = $product_name . ' added to cart!';
            $_SESSION['flash_type'] = 'success';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}


// NOW FETCH DISPLAY DATA

 $qry3 = "SELECT * FROM `Customer` WHERE `ID`= '$id'";
 $result3 = mysqli_query($connect, $qry3);
 $data3 = mysqli_fetch_assoc($result3);
 $Pid = 0;

// Grab flash message then clear it
 $flash_msg = '';
 $flash_type = '';
if (isset($_SESSION['flash_msg'])) {
    $flash_msg = $_SESSION['flash_msg'];
    $flash_type = $_SESSION['flash_type'];
    unset($_SESSION['flash_msg']);
    unset($_SESSION['flash_type']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EcoShop</title>
    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom Styles -->
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
            background: rgba(26, 60, 42, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 14px 0;
            border-bottom: 1px solid rgba(124, 154, 110, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.4s ease;
        }
        .navbar.scrolled {
            padding: 10px 0;
            box-shadow: 0 4px 30px rgba(0,0,0,0.15);
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 1.6rem;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-brand .brand-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, var(--sage), var(--terracotta));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .navbar-text {
            color: rgba(255,255,255,0.7) !important;
            font-size: 0.88rem;
            font-weight: 400;
            margin-right: 16px;
        }
        .navbar-text strong {
            color: var(--sage-light);
            font-weight: 600;
        }
        .nav-link {
            color: rgba(255,255,255,0.6) !important;
            font-weight: 500;
            font-size: 0.88rem;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.08);
        }
        .nav-link.logout-link {
            color: var(--terracotta-light) !important;
        }
        .nav-link.logout-link:hover {
            background: rgba(196, 114, 78, 0.15);
            color: var(--terracotta) !important;
        }

        /* ===== HERO / WELCOME ===== */
        .welcome-hero {
            position: relative;
            background: linear-gradient(160deg, var(--forest) 0%, var(--forest-light) 50%, var(--moss) 100%);
            padding: 80px 0 60px;
            overflow: hidden;
        }
        .welcome-hero::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -15%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(196, 114, 78, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: floatOrb 8s ease-in-out infinite;
        }
        .welcome-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(124, 154, 110, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            animation: floatOrb 10s ease-in-out infinite reverse;
        }
        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(25px, -25px) scale(1.04); }
            66% { transform: translate(-15px, 15px) scale(0.96); }
        }

        .welcome-content {
            position: relative;
            z-index: 2;
            color: #fff;
        }
        .welcome-greeting {
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--sage-light);
            margin-bottom: 12px;
        }
        .welcome-name {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: clamp(2rem, 5vw, 3.2rem);
            line-height: 1.1;
            margin-bottom: 16px;
            letter-spacing: -1px;
        }
        .welcome-name .highlight {
            background: linear-gradient(135deg, var(--sage-light), var(--terracotta-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .welcome-subtitle {
            font-size: 1.05rem;
            font-weight: 300;
            color: rgba(255,255,255,0.6);
            max-width: 480px;
        }

        /* Floating leaf particles */
        .leaf {
            position: absolute;
            width: 10px;
            height: 10px;
            background: rgba(168, 196, 154, 0.2);
            border-radius: 0 50% 50% 50%;
            animation: leafFall linear infinite;
            pointer-events: none;
        }
        @keyframes leafFall {
            0% { transform: translateY(-80px) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(calc(100vh)) rotate(720deg); opacity: 0; }
        }

        /* ===== DASHBOARD CARDS ===== */
        .dash-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
            height: 100%;
        }
        .dash-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(26, 60, 42, 0.1);
        }
        .dash-card-header {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .dash-card-header .header-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .dash-card-header.profile .header-icon {
            background: rgba(26, 60, 42, 0.08);
            color: var(--forest);
        }
        .dash-card-header.cart .header-icon {
            background: rgba(74, 103, 65, 0.1);
            color: var(--moss);
        }
        .dash-card-header.orders .header-icon {
            background: rgba(196, 114, 78, 0.1);
            color: var(--terracotta);
        }
        .dash-card-header h5 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.15rem;
            margin: 0;
        }
        .dash-card-header .card-subtitle {
            font-size: 0.78rem;
            color: var(--sage);
            margin-top: 2px;
        }

        .dash-card-body {
            padding: 24px;
        }
        .dash-card-body .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .dash-card-body .info-row:last-of-type {
            border-bottom: none;
        }
        .info-label {
            font-size: 0.82rem;
            color: var(--sage);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--forest);
        }
        .info-value.price {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
        }

        .btn-eco {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            text-decoration: none !important;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
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
        .btn-eco-outline {
            background: transparent;
            color: var(--forest);
            border: 1.5px solid rgba(26, 60, 42, 0.15);
        }
        .btn-eco-outline:hover {
            background: var(--forest);
            color: #fff;
            border-color: var(--forest);
            transform: translateY(-2px);
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
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            letter-spacing: -1px;
            margin-bottom: 12px;
        }
        .section-desc {
            color: var(--sage);
            font-size: 1rem;
            font-weight: 300;
            max-width: 480px;
            margin: 0 auto 40px;
            line-height: 1.7;
        }

        /* ===== SHOP SECTION ===== */
        #shop {
            background: var(--cream);
            position: relative;
            padding: 80px 0;
        }
        #shop::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(to bottom, rgba(26,60,42,0.04), transparent);
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
            transform: translateY(-8px);
            box-shadow: 0 18px 50px rgba(26, 60, 42, 0.1);
        }
        .product-card-img {
            position: relative;
            overflow: hidden;
            height: 240px;
            background: var(--cream-dark);
        }
        .product-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .product-card:hover .product-card-img img {
            transform: scale(1.06);
        }
        .product-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: var(--terracotta);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 50px;
        }
        .product-card-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .product-card-title {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 6px;
            color: var(--forest);
        }
        .product-card-desc {
            font-size: 0.84rem;
            color: var(--sage);
            line-height: 1.6;
            margin-bottom: 16px;
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
            gap: 10px;
        }
        .product-price {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--forest);
        }
        .product-price .currency {
            font-size: 0.85rem;
            font-weight: 400;
            color: var(--sage);
        }

        .btn-sm-eco {
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.78rem;
            transition: all 0.3s ease;
            text-decoration: none !important;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-sm-eco-outline {
            background: transparent;
            color: var(--forest);
            border: 1.5px solid rgba(26, 60, 42, 0.15);
        }
        .btn-sm-eco-outline:hover {
            background: var(--forest);
            color: #fff;
            border-color: var(--forest);
        }
        .btn-sm-eco-terra {
            background: var(--terracotta);
            color: #fff;
        }
        .btn-sm-eco-terra:hover {
            background: var(--terracotta-light);
            color: #fff;
        }
        .btn-sm-eco-green {
            background: var(--forest);
            color: #fff;
        }
        .btn-sm-eco-green:hover {
            background: var(--forest-light);
            color: #fff;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
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

        /* ===== MODALS ===== */
        .modal-content {
            border: none;
            border-radius: 24px;
            overflow: hidden;
        }
        .modal-header {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 24px 28px;
            background: #fff;
        }
        .modal-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.25rem;
        }
        .modal-body {
            padding: 28px;
        }
        .modal-footer {
            border-top: 1px solid rgba(0,0,0,0.05);
            padding: 20px 28px;
        }
        .modal-body img {
            border-radius: 14px;
        }
        .modal-body h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            margin-bottom: 14px;
        }
        .modal-body p {
            color: var(--sage);
            line-height: 1.7;
            font-size: 0.92rem;
        }
        .modal-body p strong {
            color: var(--forest);
        }

        /* Edit Profile Modal */
        .edit-accordion .card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 12px;
            background: var(--cream);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .edit-accordion .card-header {
            background: transparent;
            border-bottom: none;
            padding: 0;
        }
        .edit-accordion .card-header .btn-link {
            color: var(--forest);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none !important;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            text-align: left;
        }
        .edit-accordion .card-header .btn-link:hover {
            color: var(--terracotta);
        }
        .edit-accordion .card-body {
            padding: 0 20px 20px;
        }

        .form-group label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--forest);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1.5px solid rgba(0,0,0,0.07);
            border-radius: 12px;
            padding: 13px 16px;
            font-size: 0.92rem;
            transition: all 0.3s;
            background: #fff;
        }
        .form-control:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 4px rgba(124, 154, 110, 0.1);
            background: #fff;
        }
        .form-control::placeholder {
            color: rgba(0,0,0,0.22);
        }

        /* ===== FOOTER ===== */
        .site-footer {
            background: var(--forest);
            color: rgba(255,255,255,0.5);
            padding: 40px 0 24px;
            position: relative;
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
            font-size: 1.3rem;
            color: #fff;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 20px;
            margin-top: 20px;
            text-align: center;
            font-size: 0.82rem;
            color: rgba(255,255,255,0.3);
        }
        .footer-bottom a {
            color: var(--sage-light);
            text-decoration: none;
        }
        .footer-bottom a:hover {
            color: #fff;
        }

        /* ===== SCROLL REVEAL ===== */
        .reveal {
            opacity: 0;
            transform: translateY(35px);
            transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* ===== PASSWORD TOGGLE ===== */
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
            font-size: 0.88rem;
            font-weight: 500;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideInRight 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
            margin-bottom: 10px;
        }
        .eco-toast.error {
            background: #7a2e1a;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 20px 0;
        }
        .empty-state-icon {
            width: 56px;
            height: 56px;
            background: rgba(196, 114, 78, 0.08);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            color: var(--terracotta);
        }
        .empty-state p {
            font-size: 0.88rem;
            color: var(--sage);
            margin-bottom: 16px;
        }

        html { scroll-behavior: smooth; }

        @media (max-width: 768px) {
            .welcome-hero { padding: 50px 0 40px; }
            .product-card-img { height: 200px; }
        }
    </style>
</head>
<body>

    <!-- ✅ Flash Message Toast -->
    <?php if ($flash_msg): ?>
    <div id="flashToast" class="eco-toast <?php echo $flash_type === 'error' ? 'error' : ''; ?>" style="position:fixed; top:80px; right:20px; z-index:9999;">
        <?php if ($flash_type === 'success'): ?>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sage-light)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <?php else: ?>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--terracotta-light)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        <?php endif; ?>
        <?php echo $flash_msg; ?>
    </div>
    <script>
        setTimeout(function() {
            const toast = document.getElementById('flashToast');
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.5s';
            setTimeout(function() { toast.remove(); }, 500);
        }, 3000);
    </script>
    <?php endif; ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <span class="brand-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8c.7-1 1-2.2 1-3.5C18 2.5 16.5 1 14.5 1c-1 0-1.8.4-2.5 1C11.3 1.4 10.5 1 9.5 1 7.5 1 6 2.5 6 4.5 6 5.8 6.3 7 7 8"/><path d="M12 2v6"/><path d="M7 8c-2.8 2-5 5.5-5 9 0 4.4 3.6 8 8 8h4c4.4 0 8-3.6 8-8 0-3.5-2.2-7-5-9"/></svg>
                </span>
                EcoShop
            </a>
            <div class="d-flex align-items-center">
                <span class="navbar-text">Welcome, <strong><?php echo $data3['Full Name']; ?></strong></span>
                <a class="nav-link logout-link" href="logout.php">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Welcome Hero -->
    <section class="welcome-hero">
        <div class="leaf" style="left:8%; animation-duration:14s; animation-delay:0s;"></div>
        <div class="leaf" style="left:25%; animation-duration:18s; animation-delay:2s; width:8px; height:8px;"></div>
        <div class="leaf" style="left:50%; animation-duration:16s; animation-delay:4s;"></div>
        <div class="leaf" style="left:72%; animation-duration:12s; animation-delay:1s; width:12px; height:12px;"></div>
        <div class="leaf" style="left:90%; animation-duration:15s; animation-delay:3s;"></div>

        <div class="container">
            <div class="welcome-content">
                <div class="welcome-greeting">Dashboard</div>
                <h1 class="welcome-name">
                    Hello, <span class="highlight"><?php echo $data3['Full Name']; ?></span>
                </h1>
                <p class="welcome-subtitle">Manage your account, view your cart, and browse our eco-friendly products.</p>
            </div>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="py-5" style="margin-top: -30px; position: relative; z-index: 2;">
        <div class="container">
            <div class="row">
                <!-- Profile Overview -->
                <div class="col-md-4 mb-4 reveal">
                    <div class="dash-card">
                        <div class="dash-card-header profile">
                            <div class="header-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <h5>Your Profile</h5>
                                <div class="card-subtitle">Account details</div>
                            </div>
                        </div>
                        <div class="dash-card-body">
                            <div class="info-row">
                                <span class="info-label">Name</span>
                                <span class="info-value"><?php echo htmlspecialchars($data3['Full Name']); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email</span>
                                <span class="info-value"><?php echo htmlspecialchars($data3['Email']); ?></span>
                            </div>
                            <button class="btn-eco btn-eco-primary" data-toggle="modal" data-target="#editProfileModal" style="margin-top: 16px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit Profile
                            </button>
                        </div>
                    </div>
                </div>

                <?php

                    $qry9="SELECT * FROM `cart` WHERE `customer_id`= '$id';";
                    $result9=mysqli_query($connect, $qry9);
                    $item=0;$total=0;

                    if (mysqli_num_rows($result9)>0) {
                        while ($data9=mysqli_fetch_assoc($result9)) {
                            $item=$item+$data9['quantity'];
                            $total=$total+$data9['price'];
                        }
                    }
                    else{
                        $item=0;
                        $total=0.00;
                    }

                 ?>

                <!-- Cart -->
                <div class="col-md-4 mb-4 reveal" style="transition-delay: 0.15s;">
                    <div class="dash-card">
                        <div class="dash-card-header cart">
                            <div class="header-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            </div>
                            <div>
                                <h5>Your Cart</h5>
                                <div class="card-subtitle">Shopping summary</div>
                            </div>
                        </div>
                        <div class="dash-card-body">
                            <div class="info-row">
                                <span class="info-label">Items</span>
                                <span class="info-value" id="cart-count"><?php echo $item; ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Total</span>
                                <span class="info-value price" id="cart-total"><span class="currency">$</span><?php echo $total; ?></span>
                            </div>
                            <a href="cart.php" class="btn-eco btn-eco-terracotta" style="margin-top: 16px;" target="_blank">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                View Cart
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order History -->
                <div class="col-md-4 mb-4 reveal" style="transition-delay: 0.3s;">
                    <div class="dash-card">
                        <div class="dash-card-header orders">
                            <div class="header-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            </div>
                            <div>
                                <h5>Order History</h5>
                                <div class="card-subtitle">Recent purchases</div>
                            </div>
                        </div>
                        <div class="dash-card-body">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                </div>
                                <p>No recent orders yet.<br>Start shopping below!</p>
                            </div>
                            <button class="btn-eco btn-eco-outline" onclick="document.getElementById('shop').scrollIntoView({behavior:'smooth'});">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                Browse Products
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop Section -->
    <section id="shop" class="py-5">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <div class="section-tag">Our Collection</div>
                <h2 class="section-title">Browse Our Products</h2>
                <p class="section-desc">Curated sustainable goods designed with the planet in mind. Add items to your cart with one click.</p>
            </div>
            <div class="row">
                <?php
                $qry3 = "SELECT * FROM `products`;";
                $result3 = mysqli_query($connect, $qry3);
                $counter = 0;
                while ($product = mysqli_fetch_assoc($result3)) {
                    if ($counter % 3 == 0 && $counter > 0) {
                        echo '</div><div class="row">';
                    }
                    $modalId = "productModal" . $product['Pid'];
                ?>
                    <div class="col-lg-4 col-md-6 mb-4 reveal" style="transition-delay: <?php echo ($counter % 3) * 0.12; ?>s;">
                        <div class="product-card">
                            <div class="product-card-img">
                                <img src="<?php echo "images/" . $product['image']; ?>" alt="<?php echo $product['product_title']; ?>">
                                <span class="product-badge">New</span>
                            </div>
                            <div class="product-card-body">
                                <h5 class="product-card-title"><?php echo $product['product_title']; ?></h5>
                                <p class="product-card-desc"><?php echo substr($product['Description'], 0, 60) . '...'; ?></p>
                                <div class="product-card-footer">
                                    <span class="product-price"><span class="currency">$</span><?php echo $product['price']; ?></span>
                                    <div style="display:flex; gap:6px;">
                                        <button class="btn-sm-eco btn-sm-eco-outline" data-toggle="modal" data-target="#<?php echo $modalId; ?>">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            View
                                        </button>

                                        <form method="post" style="display: inline;">
                                            <input type="hidden" name="product_title" value="<?php echo $product['product_title']; ?>">
                                            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['Pid']); ?>">
                                            <button type="submit" class="btn-sm-eco btn-sm-eco-terra" name="add_to_cart">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                                Add
                                            </button>
                                        </form>
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
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="<?php echo $modalId; ?>Label"><?php echo $product['product_title']; ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <img src="<?php echo "images/" . $product['image']; ?>" class="img-fluid mb-3" alt="<?php echo $product['product_title']; ?>" style="border-radius:14px; max-height:220px; width:100%; object-fit:cover;">
                                    <h4><?php echo $product['product_title']; ?></h4>
                                    <p><strong>Price:</strong> $<?php echo $product['price']; ?></p>
                                    <p><strong>Description:</strong> <?php echo $product['Description']; ?></p>
                                    <p><strong>Stock:</strong> <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-sm-eco btn-sm-eco-outline" data-dismiss="modal" style="width:auto; padding:10px 20px;">Close</button>

                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="product_title" value="<?php echo $product['product_title']; ?>">
                                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['Pid']); ?>">
                                        <button type="submit" class="btn-sm-eco btn-sm-eco-terra" name="add_to_cart" style="width:auto; padding:10px 20px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    $counter++;   
                }
                ?>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="footer-brand">EcoShop</div>
                    <p style="font-size:0.85rem; color:rgba(255,255,255,0.35); margin:0;">Sustainable products for a greener future.</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <a href="index.php#contact" style="color:var(--sage-light); text-decoration:none; font-size:0.88rem; transition: color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--sage-light)'">Contact Us</a>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; 2024 EcoShop. All rights reserved. Crafted with care for the planet.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 4 JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Toast container -->
    <div class="toast-container" id="toastContainer"></div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="editAccordion" class="edit-accordion">
                    <div class="card">
                        <div class="card-header" id="profileHeading">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#profileCollapse" aria-expanded="true" aria-controls="profileCollapse">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Edit Profile Details
                                </button>
                            </h5>
                        </div>
                        <div id="profileCollapse" class="collapse show" aria-labelledby="profileHeading" data-parent="#editAccordion">
                            <div class="card-body">
                                <p style="color: var(--sage); font-size:0.85rem; margin-bottom:16px;">Fields are pre-filled with your current data. Update only what you want to change.</p>
                                <form id="profileForm" method="post">
                                    <div class="form-group">
                                        <label for="editName">Full Name</label>
                                        <input type="text" class="form-control" id="editName" name="full_name" value="<?php echo htmlspecialchars($data3['Full Name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editEmail">Email</label>
                                        <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($data3['Email']); ?>" required>
                                    </div>
                                    <button type="submit" name="update_profile" class="btn-eco btn-eco-primary" style="width:auto; padding:12px 32px;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        Update Profile
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="passwordHeading">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#passwordCollapse" aria-expanded="false" aria-controls="passwordCollapse">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Update Password
                                </button>
                            </h5>
                        </div>
                        <div id="passwordCollapse" class="collapse" aria-labelledby="passwordHeading" data-parent="#editAccordion">
                            <div class="card-body">
                                <form id="passwordForm" method="post" onsubmit="return validatePassword()">
                                    <div class="form-group">
                                        <label for="oldPassword">Old Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" class="form-control" id="oldPassword" name="old_password" placeholder="Enter current password" required>
                                            <button type="button" class="password-toggle" onclick="togglePassword('oldPassword', this)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword">New Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" class="form-control" id="newPassword" name="new_password" placeholder="Create new password" required>
                                            <button type="button" class="password-toggle" onclick="togglePassword('newPassword', this)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm New Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm new password" required>
                                            <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword', this)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="submit" name="update_password" class="btn-eco btn-eco-terracotta" style="width:auto; padding:12px 32px;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                        Update Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-sm-eco btn-sm-eco-outline" data-dismiss="modal" style="width:auto; padding:10px 24px;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) { navbar.classList.add('scrolled'); }
        else { navbar.classList.remove('scrolled'); }
    });

    function revealOnScroll() {
        document.querySelectorAll('.reveal').forEach(el => {
            if (el.getBoundingClientRect().top < window.innerHeight - 80) {
                el.classList.add('active');
            }
        });
    }
    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('load', revealOnScroll);

    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
        } else {
            input.type = 'password';
            btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
        }
    }

    function validatePassword() {
        const newPwd = document.getElementById('newPassword').value;
        const confirmPwd = document.getElementById('confirmPassword').value;
        if (newPwd !== confirmPwd) { return false; }
        return true;
    }
</script>

</body>
</html>

<?php
// =============================================
// SESSION VARIABLES FOR CART.PHP
// (All POST processing is at the top now)
// =============================================
 $_SESSION['item'] = $item;
 $_SESSION['total_price'] = $total;
?>