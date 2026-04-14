<?php 
$connect = mysqli_connect("localhost", "root" , "" , "farmer"); //space di this password ke jagah isliye error .
if ($connect) {
	
	echo "connected";
}

else
{
echo "not connected".mysqli_error($connect);
}
 ?>
