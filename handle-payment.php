<?php
include('includes/dbconnection.php');


if (isset($_GET["pidx"]) && isset($_GET["status"])) {

    $status = $_GET["status"];

    if ($status != "Completed") {
        echo '<script>alert("Payment Failed. Please try again.")</script>';
        echo '<script>window.location.href = "booking-history.php";</script>';
        exit();
    }

    $query = "UPDATE tblbooking SET Status = 'Paid' WHERE PaymentIDX = :pidx";
    $update_booking = $dbh->prepare($query);
    $update_booking->bindParam(":pidx", $_GET["pidx"], PDO::PARAM_STR);
    $update_booking->execute();

    if ($update_booking->rowCount() > 0) {
        echo '<script>alert("Payment Successful. Thank you for booking with us.")</script>';
        echo '<script>window.location.href = "booking-history.php";</script>';
    } else {
        echo '<script>alert("Payment Failed. Please try again.")</script>';
        echo '<script>window.location.href = "booking-history.php";</script>';
    }
} else {
    echo '<script>alert("Payment Failed. Please try again.")</script>';
    echo '<script>window.location.href = "booking-history.php";</script>';
}

?>