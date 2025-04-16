<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit About Us</h6>
        </div>
        <div class="card-body">
            <?php
            $connection = mysqli_connect("localhost", "root", "", "moviemagic");
            if (!$connection) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            if (isset($_POST['edit_btn'])) {
                $id = $_POST['edit_id'];
                $query = "SELECT * FROM about WHERE id='$id'";
                $result = mysqli_query($connection, $query);
                
                if (!$result) {
                    die("Query failed: " . mysqli_error($connection));
                }

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
            ?>
                    <form action="code.php" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                        
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="edit_title" value="<?php echo htmlspecialchars($row['title']); ?>" class="form-control" placeholder="Enter Title">
                        </div>
                        
                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" name="edit_subtitle" value="<?php echo htmlspecialchars($row['subtitle']); ?>" class="form-control" placeholder="Enter Subtitle">
                        </div>
                        
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="edit_description" class="form-control" placeholder="Enter Description" rows="5"><?php echo htmlspecialchars($row['description']); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Links</label>
                            <input type="text" name="edit_links" value="<?php echo htmlspecialchars($row['links']); ?>" class="form-control" placeholder="Enter Links">
                        </div>
                        
                        <!-- Modified button container - changed from text-right to text-left -->
                        <div class="form-group text-left mt-4">
                            <a href="about.php" class="btn btn-danger mr-3">CANCEL</a>
                            <button type="submit" name="update_btn" class="btn btn-primary">Update</button>
                        </div>
                    </form>
            <?php
                } else {
                    echo "<div class='alert alert-danger'>No record found with ID: $id</div>";
                }
            } else {
                echo "<div class='alert alert-warning'>Invalid request. Please select an item to edit.</div>";
            }
            ?>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');