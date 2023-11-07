<?php
    include "config.php";

    if (isset($_POST["submit"])){ 
        $full_name = $_POST['fullname'];
        $email_address = $_POST['email'];
        $password = $_POST['password'];
        $phone_number = $_POST['phone'];
        $address = $_POST['address'];
        $ppic=$_FILES['photo']['name'];
        // get the image extension
        $extension = substr($ppic,strlen($ppic)-4,strlen($ppic));
        // allowed extensions
        $allowed_extensions = array(".jpg","jpeg",".png",".gif");
        // Validation for allowed extensions .in_array() function searches an array for a specific value.
        if(in_array($extension,$allowed_extensions))
        {
            //rename the image file
            // $imgnewfile=md5($imgfile).time().$extension;
            $imgnewfile='ismt'.time().$extension;
            // Code for move image into directory
            move_uploaded_file($_FILES["photo"]["tmp_name"],"photo/".$imgnewfile);
            // Query for data insertion
            // $query=mysqli_query($con, "insert into tblusers(FirstName,LastName, MobileNumber, Email, Address,photo) value('$fname','$lname', '$contno', '$email', '$add','$imgnewfile' )");
        
            $sql = "INSERT INTO register (fullname,email,password,phone,address,photo) VALUES ('$full_name','$email_address','$password','$phone_number','$address','$imgnewfile')";
            
            $result = $conn->query($sql);
            
            if($result == true){
                echo("User registered Successfully!");
            }
            else{
                echo "Error:" . $sql . "<br>" . $conn->error;
            }
            
            $conn->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data" >
        <h2>Register User</h2>
        <label for="fullname">Full Name</label> <br>
        <input type="text" name="fullname"> <br>
        <label for="email">Email</label> <br>
        <input type="text" name="email"> <br> 
        <label for="password">Password</label> <br>
        <input type="password" name="password"> <br>
        <label for="phone">Phone Number</label> <br>
        <input type="text" name="phone"> <br>
        <label for="address">Address</label> <br>
        <input type="text" name="address"> <br><br>
        <label>Photo</label> <br>
        <input type="file" name="photo">
        <span style="color:red; font-size:12px;">Only jpg / jpeg/ png /gif format allowed.</span> <br><br>
        <input type="submit" name="submit" value="Register">
    </form>
</body>
</html>