<?php
    include "config.php";

    if (isset($_POST["update"])){ 
        $user_id = $_POST['userid'];
        $full_name = $_POST['fullname'];
        $email_address = $_POST['email'];
        $phone_number = $_POST['phone'];
        $address = $_POST['address'];
        $password = $_POST['password'];
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
        
            $sql = "UPDATE register SET fullname='$full_name', email='$email_address', phone='$phone_number', address='$address', password='$password', photo='$imgnewfile' WHERE userid=$user_id";
        
            $result = $conn->query($sql);

            if($result == true){
                echo("Record Updated Successfully!");
            }
            else{
                echo "Error:" . $sql . "<br>" . $conn->error;
            }
        }
    }

    if (isset($_GET["userid"])){
        $user_id = $_GET['userid'];

        $sql = "SELECT * FROM register WHERE userid = $user_id";
        
        $result = $conn->query($sql);

        if($result -> num_rows > 0){
            while($row = $result -> fetch_assoc()){
                $user_id = $row['userid'];
                $full_name = $row['fullname'];
                $email_address = $row['email'];
                $phone_number = $row['phone'];
                $address = $row['address'];
                $password = $row['password'];
                $pp = $row['photo'];
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
                <form method="POST"  enctype="multipart/form-data">
                    <input type="hidden" name="userid" value="<?php echo $user_id; ?>">
                    <label for="fullname">Full Name</label> <br>
                    <input type="text" name="fullname" value="<?php echo $full_name; ?>"> <br>
                    <label for="email">Email</label> <br>
                    <input type="text" name="email" value="<?php echo $email_address; ?>"> <br>
                    <label for="password">Password</label> <br>
                    <input type="password" name="password" value="<?php echo $password; ?>"> <br>
                    <label for="phone">Phone Number</label> <br>
                    <input type="text" name="phone" value="<?php echo $phone_number; ?>"> <br>
                    <label for="address">Address</label> <br>
                    <input type="text" name="address" value="<?php echo $address; ?>"> <br><br>
                    <div>
                        <img src="photo/<?php  echo $pp;?>" width="120" height="120">
		            </div>
                    <div>
                        <input type="file" name="photo"  required="true">
                        <span style="color:red; font-size:12px;">Only jpg / jpeg/ png /gif format allowed.</span>
                    </div>
                    <br>
                    <input type="submit" name="update" value="Update">
                </form>
            </body>
            </html>

<?php
        } 
        else {
            header('Location: view.php');
        }
    }
?>
