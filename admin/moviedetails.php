<?php

include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>



<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Movie Details</h6>
        </div>
        <div class="card-body">
            <?php
            //$connection = mysqli_connect("localhost", "root", "", "moviemagic");
            if (!$connection) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            if (isset($_POST['edit_btn'])) {
                $id = $_POST['edit_id'];
                $query = "SELECT * FROM moviedetails WHERE id='$id'";
                $run = mysqli_query($connection, $query);
                if (!$run) {
                    die("Query failed: " . mysqli_error($connection));
                }
                $result = mysqli_query($connection, $query);

                if ($result && $row = mysqli_fetch_assoc($result)) {
                    
            ?>
                    <form action="code.php" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">

                        <div class="form-group">
                            <label> Title </label>
                            <input type="text" name="m_title" class="form-control" placeholder="Enter Movie Title">
                        </div>
                        <div class="form-group">
                            <label> Genre ID </label>
                            <input type="text" name="m_genreid" class="form-control checking_email" placeholder="Enter Genre ID">
                        </div>
                        <div class="form-group">
                            <label> Release Year </label>
                            <input type="text" name="m_year" class="form-control" placeholder="Enter Released Year">
                        </div>
                        <div class="form-group">
                            <label> Duration </label>
                            <input type="text" name="m_duration" class="form-control" placeholder="Enter Duration">
                        </div>
                        <div class="form-group">
                            <label>Poster Image</label>
                            <input type="file" name="m_img" class="form-control" placeholder="Enter Duration">
                        </div>
                        <div class="form-group">
                            <label> Quality </label>
                            <input type="text" name="m_quality" class="form-control" placeholder="Enter Duration">
                        </div>
                        <div class="form-group">
                            <label> User-Type </label>
                            <select name="update_usertype" class="form-control">
                                <option value="admin" <?php echo ($row['usertype'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="user" <?php echo ($row['usertype'] == 'user') ? 'selected' : ''; ?>>User</option>
                            </select>
                        </div>
                        <a href="register.php" class="btn btn-danger">CANCEL</a>
                        <button type="submit" name="updatebtn" class="btn btn-primary">Update</button>
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
