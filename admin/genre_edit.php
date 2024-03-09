<?php

include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>



<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Genre</h6>
        </div>
        <div class="card-body">
            <?php
            //$connection = mysqli_connect("localhost", "root", "", "moviemagic");
            if (!$connection) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            if (isset($_POST['edit_btn'])) {
                $id = $_POST['edit_id'];
                $query = "SELECT * FROM genre_info WHERE genre_id='$id'";
                $run = mysqli_query($connection, $query);
                if (!$run) {
                    die("Query failed: " . mysqli_error($connection));
                }
                $result = mysqli_query($connection, $query);

                if ($result && $row = mysqli_fetch_assoc($result)) {
                    
            ?>
                    <form action="code.php" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo $row['genre_id']; ?>">

                        <div class="form-group">
                            <label> Genre </label>
                            <input type="text" name="genre" value="<?php echo $row['genre_name']; ?>" class="form-control" placeholder="Enter Username">
                        </div>
                        <a href="register.php" class="btn btn-danger">CANCEL</a>
                        <button type="submit" name="genreupdatebtn" class="btn btn-primary">Update</button>
                    </form>
            <?php
                } 
                else 
                {
                    echo "Error: " . mysqli_error($connection);
                }
            }
            ?>
</div>
</div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
