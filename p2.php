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
    <!-- Animate.css for Animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom Styles for Stunning Colors -->
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #e3f2fd, #f3e5f5); margin: 0; padding: 0; }
        .navbar { background: linear-gradient(90deg, #1e88e5, #8e24aa); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .jumbotron { background: linear-gradient(135deg, #ff6f00, #ff9800); color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); margin-bottom: 0; }
        .card { transition: transform 0.3s, box-shadow 0.3s; border: 2px solid transparent; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.2); }
        .card:nth-child(1) { border-color: #1e88e5; }
        .card:nth-child(2) { border-color: #ff6f00; }
        .card:nth-child(3) { border-color: #8e24aa; }
        .btn-custom { background: linear-gradient(45deg, #ff6f00, #e53935); border: none; color: white; }
        .btn-custom:hover { background: linear-gradient(45deg, #e53935, #d32f2f); }
        .footer { background: linear-gradient(90deg, #4a148c, #311b92); color: white; padding: 20px 0; margin-top: 50px; }
        .alert { background: linear-gradient(45deg, #4caf50, #66bb6a); color: white; border: none; }
        .carousel-item img { filter: brightness(0.9); }
        #shop { background: rgba(255, 255, 255, 0.9); /* Semi-transparent white to blend with body gradient */ }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark animate__animated animate__bounceIn">
        <div class="container">
            <a class="navbar-brand" href="#home">EcoShop</a>
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

    <!-- Home Section (Jumbotron) -->
    <section id="home" class="jumbotron jumbotron-fluid text-center animate__animated animate__fadeInDown">
        <div class="container">
            <h1 class="display-4">Welcome to EcoShop</h1>
            <p class="lead">Sustainable products for a greener future. Shop eco-friendly items and join our community.</p>
            <a href="#shop" class="btn btn-custom btn-lg">Explore Shop</a>
        </div>
    </section>

    <!-- Shop Section -->
    <section id="shop" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4 animate__animated animate__fadeIn">Shop Our Products</h2>
            <?php

              $qry3="SELECT * FROM `products`;";
              $result3=mysqli_query($connect, $qry3);
              $data3=mysqli_fetch_assoc($result3);


             ?>
            <!-- Product Grid -->
            <!-- Shop Section -->
    <div class="row">
    <?php
    $qry3 = "SELECT * FROM `products`;";
    $result3 = mysqli_query($connect, $qry3);
    mysqli_data_seek($result3, 0);
    $counter = 0;
    while ($product = mysqli_fetch_assoc($result3)) {
        if ($counter % 3 == 0 && $counter > 0) { //after 3 product row change 
            echo '</div><div class="row">';
        }
        $modalId = "productModal" . $product['Pid'];  // Unique modal ID per product
    ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: <?php echo ($counter % 3) * 0.2; ?>s;">
                <img src="<?php echo "images/" . $product['image']; ?>" class="card-img-top" alt="<?php echo $product['product_title']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['product_title']; ?><span class="badge badge-primary">New</span></h5>
                    <p class="card-text"><?php echo $product['Description']; ?></p>
                    <button class="btn btn-custom mr-2" data-toggle="modal" data-target="#<?php echo $modalId; ?>">Quick View</button>
                    <?php if (isset($_SESSION['Sid'])): ?>
                        <form action="add_to_cart.php" method="post" style="display: inline;">
                            <input type="hidden" name="product" value="<?php echo $product['product_title']; ?>">
                            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                            <button type="submit" class="btn btn-success">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <a href="p2.php#customer" class="btn btn-success">Add to Cart</a>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <small class="text-muted"><?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?></small>
                </div>
            </div>
        </div>

        <!-- Product Modal (Generated per product) -->
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
                                <img src="<?php echo "images/" . $product['image']; ?>" class="img-fluid" alt="<?php echo $product['product_title']; ?>">
                            </div>
                            <div class="col-md-6">
                                <h4><?php echo $product['product_title']; ?></h4>
                                <p><strong>Price:</strong> $<?php echo $product['price']; ?></p>
                                <p><strong>Description:</strong> <?php echo $product['Description']; ?></p>
                                <p><strong>Stock:</strong> <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <?php if (isset($_SESSION['Sid'])): ?>
                            <form action="add_to_cart.php" method="post" style="display: inline;">
                                <input type="hidden" name="product" value="<?php echo $product['product_title']; ?>">
                                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                <button type="submit" class="btn btn-success">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <a href="p2.php#customer" class="btn btn-success">Add to Cart</a>
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
    <section id="customer" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4 animate__animated animate__zoomIn">Customer Portal</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card animate__animated animate__slideInLeft">
                        <div class="card-header bg-primary text-white">
                            <h5>Register</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="form-group">
                                    <label for="regName">Full Name</label>
                                    <input type="text" class="form-control" id="regName" name="Full_Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="regEmail">Email</label>
                                    <input type="email" class="form-control" id="regEmail" name="Email"required>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" placeholder="Password" id="regPassword" name="Password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">@</span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-custom btn-block" name="Register">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card animate__animated animate__slideInRight">
                        <div class="card-header bg-warning text-dark">
                            <h5>Login</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="form-group">
                                    <label for="loginEmail">Email</label>
                                    <input type="email" class="form-control" id="loginEmail" name="Email" required>
                                </div>
                                <div class="form-group">
                                    <label for="loginPassword">Password</label>
                                    <input type="password" class="form-control" id="loginPassword" name="Password" required>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember Me</label>
                                </div>
                                <button type="submit" class="btn btn-custom btn-block" name="Login">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Alert Example -->
            <div class="alert alert-success mt-4 animate__animated animate__bounceIn" role="alert">
                <strong>Success!</strong> Welcome back to EcoShop. Your account is active.
            </div>
        </div>
    </section>

    <!-- Modals -->
    <div class="modal fade" id="productModal1" tabindex="-1" role="dialog" aria-labelledby="productModal1Label" aria-hidden="true">
        <div class="modal-dialog animate__animated animate__zoomIn" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="productModal1Label">Bamboo Toothbrush</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="toothbrush.png" class="img-fluid mb-3" alt="Bamboo Toothbrush">
                    <p>Detailed description: This eco-friendly toothbrush is made from sustainable bamboo and features soft bristles for gentle cleaning.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-custom">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer animate__animated animate__fadeInUp">
        <div class="container text-center">
            <p>&copy; 2023 EcoShop. All rights reserved. | <a href="#contact" class="text-light">Contact Us</a></p>
        </div>
    </footer>

    <!-- Bootstrap 4 JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</body>
</html>

<?php 


if(isset($_POST['Register'])) 
{        
  $fn=$_POST['Full_Name'];
  $em=$_POST['Email'];
  $pwd=$_POST['Password'];
  echo $fn;

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

}//if register

if (isset($_POST['Login'])) {
    // code...

    $lem=$_POST['Email'];
    $lpwd=$_POST['Password'];

    $qry2="SELECT * FROM `Customer`;";
    $result2=mysqli_query($connect, $qry2);
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


<!-- copy paste in p1.php -->
<?php 
// include 'connect.php'; 
?>
    <!-- php -->
    <!-- get data -->
    <?php
    // if (isset($_POST['submit_button'])) {
    //   // code...
    //   $n=$_POST["Farmer_name"]; // post ke andr input ka name hona chahiye
    //   $gt=$_POST["Goods_taken"];
    //   $qn=$_POST["quantity"];
    //   $tp=$_POST["total_price"];

    //   echo $n;

      

    //   $qry =  "INSERT INTO `Bhoomi_agency`(`Farmer_name`, `Goods_taken`, `quantity`, `total_price`) VALUES ('$n','$gt','$qn','$tp')";



    //   $result = mysqli_query($connect , $qry);

      // if ($result) {
      //   // code...
      //   echo "register sucess".mysqli_error($connect);
      // }

      // else {
      //   echo "failed";
      // }

      // } // IF 
    
    ?>