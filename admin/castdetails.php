<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
include('includes/constants/constant.php');

?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Cast Details</h6>
        </div>
        <div class="card-body">
            <?php
            if (!$connection) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            if (isset($_POST['edit_btn'])) {
                $id = $_POST['edit_id'];
                $query = "SELECT * FROM cast_info WHERE cast_id='$id'";
                $result = mysqli_query($connection, $query);

                if (!$result) {
                    die("Query failed: " . mysqli_error($connection));
                }

                // Check if any row is fetched
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $current_image = $row['cast_image'];
            ?>
                    <form action="code.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="castedit_id" value="<?php echo $row['cast_id']; ?>">

                        <div class="form-group">
                            <label>Cast Name</label>
                            <input type="text" name="m_title" class="form-control" placeholder="Enter Cast Name" value="<?php echo $row['cast_name']; ?>">
                        </div>
                        <div class="form-group">
                            <label> Biography</label>
                            <textarea name="biography"  class="form-control" required rows="3"><?= $row['biography']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Birth Date</label>
                            
                            <input type="text" name="dob" class="form-control" placeholder="Enter Date of Birth" value="<?php echo $row['birth_date']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Birth Place</label>
                            <input type="text" name="pob" class="form-control" placeholder="Enter Birth Place" value="<?php echo $row['birth_place'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label> Current Cast Image</label><br>
                            <td>
                                <!-- Display image if available -->
                                <?php 
                                    if($current_image == "")
                                    {
                                        echo "<h6>Image not Available</h6>";
                                    }
                                    else
                                    {
                                        echo '<img src="upload/'.$row['cast_image'].'" width="100px;" height="100px;" alt="Movie Poster">';
                                    }
                                ?>
                            </td>
                        </div>
                        <div class="form-group">
                            <label> New Image</label>
                            <input type="file" name="c_img" id="c_img" class="form-control" placeholder="Insert Image">
                        </div>
                        <a href="cast.php" class="btn btn-danger">CANCEL</a>
                        <button type="submit" name="castupdatebtn" class="btn btn-primary">Update</button>
                    </form>
            <?php
                } else {
                    echo "No data found for ID: " . $id;
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
