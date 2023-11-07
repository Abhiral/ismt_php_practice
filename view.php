<?php
    include "config.php";

    $sql = "SELECT * FROM register";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>User Id</th>
                <th>Photo</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    if($result -> num_rows > 0){
                        while($row = $result -> fetch_assoc()){
                            ?>
            <tr>
                <td><?php echo $row['userid']; ?></td>
                <td><img src="photo/<?php  echo $row['photo'];?>" width="80" height="80"></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td>
                    <a href="update.php?userid=<?php echo $row['userid']; ?>">Edit</a> &nbsp;
                    <a href="delete.php?userid=<?php echo $row['userid']; ?>&&ppic=<?php echo $row['photo'];?>">Delete</a>
                </td>
            </tr>
                <?php
                        }
                    }
                    else {
                        echo "<td colspan='6'>No records found.</td>";
                    }
                ?>
        </tbody>
    </table>
</body>
</html>