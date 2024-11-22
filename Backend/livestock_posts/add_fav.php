<?php
include("../db/db_connect.php");
if(isset($_POST['add_fav'])){
    $stock_id = $_POST['stock_id'];
    $user_id = $_POST['user_id'];

    $sql = "INSERT INTO favorite(user_id,stock_id) VALUES ('$user_id','$stock_id')";
    if(mysqli_query($con,$sql)){
        echo "Add to favorite";
        header("location: ../../Frontend/Buyer/browse_livestock.php");
    }else{
        echo "Fialed to add";
        header("location: ../../Frontend/Buyer/browse_livestock.php");

    }
}