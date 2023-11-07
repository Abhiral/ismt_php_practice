<?php
include "config.php";

if (isset($_GET["userid"])){
    $user_id = $_GET['userid'];
    $profilepic=$_GET['ppic'];
    $ppicpath="photo"."/".$profilepic;

    unlink($ppicpath);
    $sql = "DELETE FROM register WHERE userid=$user_id";
    $result = $conn->query($sql);

    if($result == true){
        echo("Record Deleted Successfully!");
    }
    else{
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
}

?>
