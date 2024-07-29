<?php
include('includes/dbconnection.php');

if (isset($_GET["bookingid"]) && isset($_GET["uid"]) && isset($_GET["bid"])) {

    $uid = $_GET["uid"];
    $bookingid = $_GET["bookingid"];
    $bid = $_GET["bid"];

    // Fetch user details from the database using uid
    $sqlUser = "SELECT FullName, Email, MobileNumber FROM tbluser WHERE ID = :uid";
    $queryUser = $dbh->prepare($sqlUser);
    $queryUser->bindParam(':uid', $uid, PDO::PARAM_STR);
    $queryUser->execute();
    $userDetails = $queryUser->fetch(PDO::FETCH_ASSOC);

    // Fetch product details from the database using bid
    $sqlProduct = "SELECT ts.ServiceName, ts.ServicePrice, tb.* 
							   FROM tblservice ts 
							   JOIN tblbooking tb ON tb.ServiceID = ts.ID 
							   WHERE tb.BookingID = :bookingid";
    $queryProduct = $dbh->prepare($sqlProduct);
    $queryProduct->bindParam(':bookingid', $bookingid, PDO::PARAM_STR);
    $queryProduct->execute();
    $productDetails = $queryProduct->fetch(PDO::FETCH_ASSOC);

    // Prepare the payload for Khalti API
    $payload = json_encode([
        "return_url" => "http://localhost:8888/obbs/handle-payment.php",
        "website_url" => "http://localhost:8888/obbs/",
        "amount" => 1000,
        "purchase_order_id" => $bookingid,
        "purchase_order_name" => $productDetails['ServiceName'],
        "customer_info" => [
            "name" => $userDetails['FullName'],
            "email" => $userDetails['Email'],
            "phone" => $userDetails['MobileNumber']
        ],
        "product_details" => [
            [
                "identity" => $bid,
                "name" => $productDetails['ServiceName'],
                "total_price" => 1000,
                "quantity" => 1,
                "unit_price" => 1000,
            ]
        ],
        "merchant_username" => "merchant_name",
        "merchant_extra" => "merchant_extra"
    ]);

    // Initiate cURL session
    $ch = curl_init('https://a.khalti.com/api/v2/epayment/initiate/');

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Key ef30c9ab835c420cacbb9e857fb8f603' // Replace with your actual secret key
        )
    );
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Execute cURL request and get the response
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Check for a successful response
    if ($http_status === 200) {
        $response_data = json_decode($response, true);
        if (isset($response_data['payment_url'])) {

            // update the booking with its respective pidx

            $sql = "UPDATE tblbooking SET PaymentIDX = :pidx WHERE BookingID = :bookingid";

            $updateBooking = $dbh->prepare($sql);
            $updateBooking->bindParam(":pidx", $response_data['pidx'], PDO::PARAM_STR);
            $updateBooking->bindParam(":bookingid", $bookingid, PDO::PARAM_STR);
            $updateBooking->execute();

            // Redirect to the payment URL
            header('Location: ' . $response_data['payment_url']);
            exit();
        } else {
            echo '<script>alert("Payment initiation failed on response 200. Please try again")</script>';
        }
    } else {
        echo '<script>alert("Payment initiation failed. Please try again")</script>';
    }
}

?>