<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Services</h6>
        </div>
        <div class="card-body">
            <?php
            $connection = mysqli_connect("localhost", "root", "", "moviemagic");
            if (!$connection) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            if (isset($_POST['edit_btn'])) {
                $id = $_POST['edit_id'];
                $query = "SELECT * FROM service WHERE id='$id'";
                $result = mysqli_query($connection, $query);
                
                if (!$result) {
                    die("Query failed: " . mysqli_error($connection));
                }

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
            ?>
                    <form action="code.php" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                        
                        <div class="form-group mb-3">
                            <label>Title</label>
                            <input type="text" name="edit_title" value="<?php echo htmlspecialchars($row['title']); ?>" class="form-control" placeholder="Enter Title" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label>Description</label>
                            <textarea name="edit_description" class="form-control" placeholder="Enter Description" rows="4" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label>Links</label>
                            <input type="text" name="edit_links" value="<?php echo htmlspecialchars($row['links']); ?>" class="form-control" placeholder="Enter Links" required>
                        </div>
                        
                        <!-- Buttons container with left alignment and spacing -->
                        <div class="buttons-container mt-4" style="margin-left: -8px;">
                            <a href="service.php" class="btn btn-danger">CANCEL</a>
                            <button type="submit" name="serviceupdate_btn" class="btn btn-primary">Update</button>
                        </div>
                    </form>
            <?php
                } else {
                    echo '<div class="alert alert-warning">No service found with this ID</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Invalid request. Please select a service to edit.</div>';
            }
            ?>
        </div>
    </div>
</div>

<style>
    /* Custom styling for buttons container */
    .buttons-container {
        display: flex;
        gap: 12px; /* Adds space between buttons */
    }
</style>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>