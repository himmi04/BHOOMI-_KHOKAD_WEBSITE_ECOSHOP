<?php
ob_start();
include 'connect.php';
session_start();
if (!isset($_SESSION['Sid'])) {
    // code...
    header("Location :p2.php");
    exit();
}

 
?>

<?php

$id = $_SESSION['Sid'];
$qry3 = "SELECT * FROM `Customer` WHERE `ID`= '$id'";
$result3=mysqli_query($connect, $qry3);
$data3=mysqli_fetch_assoc($result3);
$Pid=0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EcoShop</title>
    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css for Animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom Styles -->
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
        #shop { background: rgba(255, 255, 255, 0.9); }
        /* ... your existing styles ... */
    .modal-dialog { max-width: 500px; }  /* Make modal smaller */
    .card-body p { font-size: 0.9rem; }  /* Smaller text in cards */
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark animate__animated animate__bounceIn">
        <div class="container">
            <a class="navbar-brand" href="index.php">EcoShop</a>
            <div class="navbar-nav ml-auto">
                <span class="navbar-text">Welcome, <?php echo $data3['Full Name']; ?>!</span>
                <a class="nav-link" href="logout.php">Logout</a>

            </div>
        </div>
    </nav>

    <!-- Welcome Section -->
    <section class="jumbotron jumbotron-fluid text-center animate__animated animate__fadeInDown">
        <div class="container">
            <h1 class="display-4">Welcome to Your Dashboard, <?php echo $data3['Full Name']; ?>!</h1>
            <p class="lead">Manage your account and browse our eco-friendly products.</p>
        </div>
    </section>

    <!-- Dashboard Content -->
    <!-- Dashboard Content -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4 animate__animated animate__zoomIn">Your Dashboard</h2>
        <div class="row">
            <!-- Profile Overview -->
            <div class="col-md-4 mb-4">
                <div class="card animate__animated animate__fadeInUp">
                    <div class="card-header bg-primary text-white">
                        <h5>Your Profile</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($data3['Full Name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($data3['Email']); ?></p>
                        <button class="btn btn-custom btn-block" data-toggle="modal" data-target="#editProfileModal">Edit Profile</button>
                    </div>
                </div>
            </div>

            <?php

                $qry9="SELECT * FROM `cart` WHERE `customer_id`= '$id';";
                $result9=mysqli_query($connect, $qry9);
                $item=0;$total=0;

                if (mysqli_num_rows($result9)>0) {
                    // code...

                    while ($data9=mysqli_fetch_assoc($result9)) {
                        // code...
                        $item=1+$item;
                        $total=$total+$data9['price'];
                    }

                }//mysqli_num_rows (rows 0 se jydada hai mtlb uske cart mai items hai)
                else{
                    $item=0;
                    $total=0.00;
                }

             ?>

            <!-- Cart -->
            <div class="col-md-4 mb-4">
                <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <div class="card-header bg-success text-white">
                        <h5>Your Cart</h5>
                    </div>
                    <div class="card-body">
                        <p id="cart-count">Items: <?php echo $item; ?></p>
                        <p id="cart-total">Total: $<?php echo $total; ?></p>
                        <a href="cart.html" class="btn btn-custom btn-block" target="_blank">View Cart</a>  <!-- Opens in new window/tab -->
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div class="col-md-4 mb-4">
                <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <div class="card-header bg-warning text-white">
                        <h5>Order History</h5>
                    </div>
                    <div class="card-body">
                        <p>No recent orders. Start shopping below!</p>
                        <button class="btn btn-custom btn-block" onclick="document.getElementById('shop').scrollIntoView();">Browse Products</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Shop Section -->
    <!-- Shop Section -->
    <<section id="shop" class="py-3">  <!-- Reduced padding -->
    <div class="container">
        <h2 class="text-center mb-3 animate__animated animate__fadeIn">Browse Our Products</h2>  <!-- Smaller heading margin -->
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
                <div class="col-lg-4 col-md-6 mb-3">  <!-- Reduced bottom margin -->
                    <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: <?php echo ($counter % 3) * 0.2; ?>;">
                        <img src="<?php echo "images/" . $product['image']; ?>" class="card-img-top" alt="<?php echo $product['product_title']; ?>" style="max-height: 300px;">  <!-- Smaller image -->
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['product_title']; ?><span class="badge badge-primary">New</span></h5>
                            <p class="card-text"><?php echo substr($product['Description'], 0, 50) . '...'; ?></p>  <!-- Even shorter description -->
                            <button class="btn btn-custom btn-sm mr-2" data-toggle="modal" data-target="#<?php echo $modalId; ?>">Quick View</button>  <!-- Smaller button -->
                            
                            <form  method="post" style="display: inline;">
                                <input type="hidden" name="product_title" value="<?php echo $product['product_title']; ?>">
                                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['Pid']); ?>">
                                <button type="submit" class="btn btn-success btn-sm" name="add_to_cart">Add to Cart</button>  <!-- Smaller button -->
                            </form>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted"><?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?></small>
                        </div>
                    </div>
                </div>

                <!-- Product Modal (Smaller) -->
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
                                <img src="<?php echo "images/" . $product['image']; ?>" class="img-fluid mb-3" alt="<?php echo $product['product_title']; ?>" style="max-height: 200px;">
                                <h4><?php echo $product['product_title']; ?></h4>
                                <p><strong>Price:</strong> $<?php echo $product['price']; ?></p>
                                <p><strong>Description:</strong> <?php echo $product['Description']; ?></p>
                                <p><strong>Stock:</strong> <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                <form  method="post"  style="display: inline;">
                                    
                                    <input type="hidden" name="product_title" value="<?php echo $product['product_title']; ?>">
                                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['Pid']); ?>">
                                    <button type="submit" class="btn btn-success" name="add_to_cart">Add to Cart</button>
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
    <footer class="footer animate__animated animate__fadeInUp">
        <div class="container text-center">
            <p>&copy; 2023 EcoShop. All rights reserved. | <a href="index.php#contact" class="text-light">Contact Us</a></p>
        </div>
    </footer>

    <!-- Bootstrap 4 JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Initialize tooltips and popovers

    </script>

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
                <!-- Accordion for Profile and Password Sections -->
                <div id="editAccordion">
                    <!-- Profile Edit Section -->
                    <div class="card">
                        <div class="card-header" id="profileHeading">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#profileCollapse" aria-expanded="true" aria-controls="profileCollapse">
                                    Edit Profile Details
                                </button>
                            </h5>
                        </div>
                        <div id="profileCollapse" class="collapse show" aria-labelledby="profileHeading" data-parent="#editAccordion">
                            <div class="card-body">
                                <p class="text-muted">Fields are pre-filled with your current data. Update only what you want to change.</p>
                                <form id="profileForm" method="post">
                                    <div class="form-group">
                                        <label for="editName">Full Name</label>
                                        <input type="text" class="form-control" id="editName" name="full_name" value="<?php echo htmlspecialchars($data3['Full Name']); ?>" required>  <!-- Pre-populated with current value -->
                                    </div>
                                    <div class="form-group">
                                        <label for="editEmail">Email</label>
                                        <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($data3['Email']); ?>" required>  <!-- Pre-populated with current value -->
                                    </div>
                                    <button type="submit" name="update_profile" class="btn btn-custom">Update Profile</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Password Update Section (Unchanged) -->
                    <div class="card">
                        <div class="card-header" id="passwordHeading">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#passwordCollapse" aria-expanded="false" aria-controls="passwordCollapse">
                                    Update Password
                                </button>
                            </h5>
                        </div>
                        <div id="passwordCollapse" class="collapse" aria-labelledby="passwordHeading" data-parent="#editAccordion">
                            <div class="card-body">
                                <form id="passwordForm" method="post" onsubmit="return validatePassword()">
                                    <div class="form-group">
                                        <label for="oldPassword">Old Password</label>
                                        <input type="password" class="form-control" id="oldPassword" name="old_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" class="form-control" id="newPassword" name="new_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                                    </div>
                                    <button type="submit" name="update_password" class="btn btn-custom" name="update_password">Update Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Client-Side Validation (Unchanged) -->
<script>
</script>

</body>
</html>

<?php
// update profile

if(isset($_POST['update_profile']))
{
$ufn=$_POST['full_name'];
$uem=$_POST['email'];

$qry4="UPDATE `Customer` SET `Full Name`='$ufn',`Email`='$uem' WHERE `ID`='$id';";
$result4=mysqli_query($connect, $qry4);
print_r($result4);

if ($result4) {
    // code...
    echo "<script>alert('Updated Profile')</script>;";
    header('Location: ' .$_SERVER['PHP_SELF']); 
    exit();
}

}//update profile

//update password
if (isset($_POST['update_password'])) {
    // code...
    $np=$_POST['new_password'];
    $cp=$_POST['confirm_password'];

    if(($_POST['old_password']) == ($data3['Password']))
    {

        if($np == $cp)
        {
            $qry5="UPDATE `Customer` SET `Password`='$cp' WHERE `ID`='$id';";
            $result5=mysqli_query($connect, $qry5);
            print_r($result5);
            if($result5)
            {
                echo "<script>alert('password change successfull')</script>";
            }

        }//$np == $cp
        else{
            echo "<script>alert('old password and confirm_password is not same')</script>";
        }

    }//old pwd
    else{
        echo "<script>alert('please check your old password')</script>";
    }

}//update password

//add to cart
if (isset($_POST['add_to_cart']) && !isset($_SESSION['cart_processed'])) {
    // code...
    $_SESSION['cart_processed'] = true;
    $Pid=$_POST['product_id'];
        //echo $Pid;
    

    $qry6="SELECT * FROM `products` WHERE `Pid` = '$Pid';";
    $result6=mysqli_query($connect, $qry6);
    $data6=mysqli_fetch_assoc($result6);
    
    
    if (!$result6 || mysqli_num_rows($result6)==0) {
        // code...
        echo "<script>alert('product not found')</script>";
        unset($_SESSION['cart_processed']);
        exit;
    }//if result6 me error aayi toh .

    $product_name=$data6['product_title'];
    $quantity=1;
    $price=$data6['price']; // price of product 


    $qry7="SELECT * FROM `cart` WHERE `Pid` = '$Pid' AND `customer_id` = '$id';";
    $result7=mysqli_query($connect, $qry7);
    $data7=mysqli_fetch_assoc($result7);
    echo mysqli_num_rows($result7);


    if(mysqli_num_rows($result7)>0)
    {

         $quantity_of_cart=$data7['quantity']+1;
         $price_of_cart=$data7['price']+$price; //$data7['price'] is previous price in cart
         $qry8 = "UPDATE `cart` SET `quantity`='$quantity_of_cart' ,`price`='$price_of_cart' WHERE `Pid` = '$Pid' AND `customer_id` = '$id';";
         $result8=mysqli_query($connect, $qry8);
         
         if ($result8) {
             // code...
         echo "<script>alert('increased quantity');</script>";
         header("Location: " . $_SERVER['PHP_SELF']);  // Redirect to clear POST
            exit();
        }//result8
        else{
            echo "<script>alert('updating quantity failed " . mysqli_error($connect) . "')</script>";
            unset($_SESSION['cart_processed']);
        }

    }//mysqli_num_rows

    else {


        $qry9="INSERT INTO `cart`(`Pid`, `customer_id`, `product_name`, `quantity`, `price`) VALUES ('$Pid','$id','$product_name','$quantity','$price');";
        $result9=mysqli_query($connect, $qry9);
        if($result9){
        echo "<script>alert('add to cart')</script>";
        header("Location: " . $_SERVER['PHP_SELF']);  // Redirect to clear POST
            exit();
        }//result9
        else{
            echo "<script>alert('inserting data  into cart failed " . mysqli_error($connect) . "')</script>";
            unset($_SESSION['cart_processed']);
        }
        

    }


}//add_to_cart

else{
    unset($_SESSION['cart_processed']);
}


ob_end_flush();

?>
