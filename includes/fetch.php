<?php
  session_start();  
  
  
?>

<?php
include('dbconnection.php');


if(isset($_POST['view'])){


if($_POST["view"] != '')
{   $sql3 ="UPDATE `notifications` SET `read` = '1' WHERE `read` = '0';";
    $query3=$dbh->prepare($sql3);
    $query3->execute();
   
}
$uid=$_SESSION['obbsuid'];
$status_query = "SELECT * FROM  `notifications` WHERE `read` = '0' And `uid` = '$uid';";
$query2=$dbh->prepare($status_query);
$query2->execute();
$count=count($query2->fetchAll());
$data = array(
    
    'unseen_notification'  => $count
);

echo json_encode($data);

}

?>