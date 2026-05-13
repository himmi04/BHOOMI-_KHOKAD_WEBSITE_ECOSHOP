<?php

//display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';



session_start();
 $id = $_SESSION['Sid'];

 // cart ka logic increasing mai krna pdega . 
 // new logic likhna pdega 

 

 if (isset($_POST['add_to_cart'])) {
     // code...
    $Pid=$_POST['product_id'];

    //alias is used in mysql query
    $qry11="SELECT cart.`Pid`, cart.`customer_id`, cart.`quantity`, cart.`price` as 'total_price', products.`price` as 'product_price', products.`stock` from `cart` join `products` on cart.`Pid` = products.`Pid` where cart.`Pid`='$Pid' AND cart.`customer_id`='$id';";
    $result11=mysqli_query($connect, $qry11);
    $data11=mysqli_fetch_assoc($result11);
    $quantity=0;$stock=0;$price=0;
     
     $product_id = $data11['Pid'];
     $customer_id= $data11['customer_id'];

     if ($data11) {
         // code...
     
    if($Pid==$product_id && $id==$customer_id)
    {
        $quantity=1+$data11['quantity'];
        
        $price=$data11['total_price']+$data11['product_price'];

        //update
        $qry12="UPDATE `cart` SET `quantity`='$quantity',`price`='$price' WHERE `Pid`= '$product_id' AND `customer_id`='$customer_id';";
        $result12=mysqli_query($connect, $qry12);
        

        // $qry13="UPDATE `products` SET `stock`='$stock' WHERE `Pid`='$product_id';";
        // $result13=mysqli_query($connect, $qry13);
       
        

    }//if Pid

    }// data11

    // PRG => POST REDIRECT GET
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();

 }// add to cart


 // issue arised in past 
// I have written upwards of add to cart . hence value is not updating in cart page . because in product summary it was fetching old database not updated database . . 
$qry10 = "SELECT DISTINCT cart.`Pid`, products.`product_title`, products.`Description`, products.`price`, products.`image`, cart.`quantity`  FROM `cart` inner join `products` on cart.`Pid` = products.`Pid` WHERE cart.`customer_id`='$id'";
 $result10=mysqli_query($connect, $qry10);

 //still value was not updating in cart summary . so we did this 
 // reset the values of item and total price
$_SESSION['item']=0;
$_SESSION['total_price']=0;


while ($newdata = mysqli_fetch_assoc($result10)) {
    $_SESSION['item'] = $_SESSION['item'] + $newdata['quantity'];
    $_SESSION['total_price'] = $_SESSION['total_price'] + $newdata['price'] * $newdata['quantity'];
}

// Reset the result pointer so the HTML loop below can still use it
mysqli_data_seek($result10, 0);

//this values is already calculated in login.php and should be used in cart.php . 
 // to avoid rewriting logic
$item=$_SESSION['item'];
$total_price=$_SESSION['total_price'];

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EcoShop Cart</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        .welcome-text {
            color: rgba(255,255,255,0.6) !important;
            font-size: 0.88rem;
            font-weight: 400;
            margin-right: 16px;
        }
        .welcome-text strong {
            color: var(--sage-light);
            font-weight: 600;
        }
        .btn-dashboard {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            color: #fff;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 10px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-dashboard:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* ===== HERO ===== */
        .hero-section {
            position: relative;
            background: linear-gradient(160deg, var(--forest) 0%, var(--forest-light) 50%, var(--moss) 100%);
            padding: 70px 0 60px;
            overflow: hidden;
        }
        .hero-section::before {
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
        .hero-section::after {
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

        .hero-content {
            position: relative;
            z-index: 2;
            color: #fff;
        }
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--sage-light);
            margin-bottom: 16px;
        }
        .hero-tag .tag-dot {
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
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            line-height: 1.1;
            margin-bottom: 14px;
            letter-spacing: -1px;
        }
        .hero-title .highlight {
            background: linear-gradient(135deg, var(--sage-light), var(--terracotta-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-subtitle {
            font-size: 1.05rem;
            font-weight: 300;
            color: rgba(255,255,255,0.55);
            max-width: 420px;
        }

        /* Floating leaves */
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

        /* ===== CART SUMMARY ===== */
        .cart-summary {
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.97);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            max-width: 340px;
        }
        .cart-summary-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }
        .cart-summary-icon {
            width: 48px;
            height: 48px;
            background: rgba(26, 60, 42, 0.06);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--forest);
        }
        .cart-summary-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            color: var(--forest);
        }
        .cart-summary-subtitle {
            font-size: 0.78rem;
            color: var(--sage);
            margin-top: 2px;
        }
        .cart-summary-divider {
            height: 1px;
            background: rgba(0,0,0,0.06);
            margin: 20px 0;
        }
        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }
        .cart-summary-label {
            font-size: 0.88rem;
            color: var(--sage);
            font-weight: 500;
        }
        .cart-summary-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--forest);
        }
        .cart-summary-total {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--forest);
            margin: 8px 0;
        }
        .cart-summary-total .currency {
            font-size: 1.1rem;
            font-weight: 400;
            color: var(--sage);
        }

        .btn-eco {
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.92rem;
            letter-spacing: 0.3px;
            transition: all 0.4s ease;
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
            box-shadow: 0 8px 25px rgba(26, 60, 42, 0.3);
        }
        .btn-eco-terracotta {
            background: var(--terracotta);
            color: #fff;
        }
        .btn-eco-terracotta:hover {
            background: var(--terracotta-light);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(196, 114, 78, 0.35);
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

        /* ===== PRODUCT SECTION ===== */
        #productSection {
            padding: 80px 0;
            position: relative;
        }
        #productSection::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(to bottom, rgba(26,60,42,0.04), transparent);
            pointer-events: none;
        }

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
            margin: 0 auto 48px;
            line-height: 1.7;
        }

        /* ===== PRODUCT CARDS ===== */
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
            transform: scale(1.06);
        }
        .product-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: var(--forest);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .product-badge .badge-dot {
            width: 6px;
            height: 6px;
            background: var(--sage-light);
            border-radius: 50%;
        }

        .product-card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .product-card-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: var(--forest);
        }
        .product-card-desc {
            font-size: 0.88rem;
            color: var(--sage);
            line-height: 1.65;
            margin-bottom: 20px;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        .btn-sm-eco {
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.82rem;
            transition: all 0.3s ease;
            text-decoration: none !important;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-sm-eco-terra {
            background: var(--terracotta);
            color: #fff;
        }
        .btn-sm-eco-terra:hover {
            background: var(--terracotta-light);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(196, 114, 78, 0.3);
        }

        /* ===== EMPTY CART ===== */
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }
        .empty-cart-icon {
            width: 80px;
            height: 80px;
            background: rgba(196, 114, 78, 0.08);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: var(--terracotta);
        }
        .empty-cart h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .empty-cart p {
            color: var(--sage);
            font-size: 0.95rem;
            margin-bottom: 24px;
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

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .hero-flex {
                flex-direction: column;
                gap: 32px;
            }
            .cart-summary {
                max-width: 100%;
            }
        }
        @media (max-width: 768px) {
            .hero-section {
                padding: 50px 0 40px;
            }
            .product-card-img {
                height: 220px;
            }
        }
    </style>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="Login.php">
            <span class="brand-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8c.7-1 1-2.2 1-3.5C18 2.5 16.5 1 14.5 1c-1 0-1.8.4-2.5 1C11.3 1.4 10.5 1 9.5 1 7.5 1 6 2.5 6 4.5 6 5.8 6.3 7 7 8"/><path d="M12 2v6"/><path d="M7 8c-2.8 2-5 5.5-5 9 0 4.4 3.6 8 8 8h4c4.4 0 8-3.6 8-8 0-3.5-2.2-7-5-9"/></svg>
            </span>
            EcoShop
        </a>
        <div class="ml-auto d-flex align-items-center">
            <a href="Login.php" class="btn-dashboard">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero-section">
    <div class="leaf" style="left:8%; animation-duration:14s; animation-delay:0s;"></div>
    <div class="leaf" style="left:22%; animation-duration:18s; animation-delay:2s; width:8px; height:8px;"></div>
    <div class="leaf" style="left:45%; animation-duration:16s; animation-delay:4s;"></div>
    <div class="leaf" style="left:68%; animation-duration:12s; animation-delay:1s; width:12px; height:12px;"></div>
    <div class="leaf" style="left:85%; animation-duration:15s; animation-delay:3s;"></div>

    <div class="container">
        <div class="d-flex justify-content-between align-items-start flex-wrap hero-flex" style="gap:40px;">
            <div class="hero-content">
                <div class="hero-tag">
                    <span class="tag-dot"></span>
                    Your Cart
                </div>
                <h1 class="hero-title">
                    Shopping<br><span class="highlight">Cart</span>
                </h1>
                <p class="hero-subtitle">
                    Explore your eco-friendly products with modern style. Review your items before checkout.
                </p>
            </div>

            <div class="cart-summary">
                <div class="cart-summary-header">
                    <div class="cart-summary-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div>
                    <div>
                        <h5 class="cart-summary-title">Cart Summary</h5>
                        <div class="cart-summary-subtitle">Order overview</div>
                    </div>
                </div>

                <div class="cart-summary-divider"></div>

               

                <div class="cart-summary-row">
                    <span class="cart-summary-label">Items</span>
                    <span class="cart-summary-value"><?php echo $item; ?> products</span>
                </div>

                <div class="cart-summary-divider"></div>

                <div class="cart-summary-label" style="margin-bottom:4px;">Total</div>
                <div class="cart-summary-total">
                    <span class="currency">Rs.</span> <?php echo $total_price; ?>
                </div>

                <button class="btn-eco btn-eco-primary" style="margin-top:8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    Proceed To Checkout
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Product Section -->
<div id="productSection">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <div class="section-tag">In Your Cart</div>
            <h2 class="section-title">Your Products</h2>
            <p class="section-desc">Review the eco-friendly items you've added to your cart.</p>
        </div>

        <div class="row">
        <?php
 $count=0;
while ($data10=mysqli_fetch_assoc($result10)) {
    if($count%3==0 && $count>0)
    {
        echo '</div><div class="row">';
    }
    
?>
            <div class="col-lg-4 col-md-6 mb-5 reveal" style="transition-delay: <?php echo ($count % 3) * 0.15; ?>s;">
                <div class="product-card">
                    <div class="product-card-img">
                        <img src="images/<?php echo $data10['image']; ?>" alt="<?php echo $data10['product_title']; ?>">
                        <span class="product-badge">
                            <span class="badge-dot"></span>
                            In Cart
                        </span>
                    </div>
                    <div class="product-card-body">
                        
                        <!-- ADDED: Quantity Badge next to Title -->
                        <h4 class="product-card-title">
                            <?php echo $data10['product_title']; ?>
                            <span class="qty-badge">x <?php echo $data10['quantity']; ?></span>
                        </h4>

                        <!-- when user click incart button, productid will be send to add to cart logic -->
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($data10['Pid']); ?>">
                        <p class="product-card-desc"><?php echo $data10['Description']; ?></p>
                        <div class="product-card-footer">
                            <span class="product-price"><span class="currency">Rs.</span> <?php echo $data10['price']; ?></span>
                            <button class="btn-sm-eco btn-sm-eco-terra" type="submit" name="add_to_cart">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                Add to cart
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php 
        $count++;

         }//while loop

        ?>
        </div>

        <?php
        // Show empty cart message if no items
        if($count == 0) {
        ?>
        <div class="empty-cart reveal">
            <div class="empty-cart-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <h3>Your Cart is Empty</h3>
            <p>You haven't added any eco-friendly products yet.<br>Start shopping and make a difference!</p>
            <a href="Login.php" class="btn-eco btn-eco-terracotta" style="width:auto; padding:14px 32px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Browse Products
            </a>
        </div>
        <?php
        }
        ?>
    </div>
</div>

<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="footer-brand">EcoShop</div>
                <p style="font-size:0.85rem; color:rgba(255,255,255,0.35); margin:0;">Sustainable products for a greener future.</p>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="Login.php" style="color:var(--sage-light); text-decoration:none; font-size:0.88rem; transition: color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--sage-light)'">Back to Dashboard</a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2024 EcoShop. All rights reserved. Crafted with care for the planet.
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

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
            const revealPoint = 80;
            if (elementTop < windowHeight - revealPoint) {
                el.classList.add('active');
            }
        });
    }
    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('load', revealOnScroll);

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>

</body>
</html>