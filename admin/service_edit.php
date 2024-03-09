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

        $connection=mysqli_connect("localhost","root","","moviemagic");
            if(isset($_POST['edit_btn']))
            {
                $id = $_POST['edit_id'];
                $query="SELECT * FROM service WHERE id='$id'";
                $result= mysqli_query($connection, $query);
                foreach($result as $row)
                {
                    ?>
                    <form action="code.php" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                        <div class="form-group">
                            <label> Title </label>
                            <input type="text" name="edit_title" value="<?php echo $row['title']; ?>" class="form-control" placeholder="Enter Title">
                        </div>
                        <div class="form-group">
                            <label> Description </label>
                            <input type="text" name="edit_description" value="<?php echo $row['description']; ?>" class="form-control" placeholder="Enter Description">
                        </div>
                        <div class="form-group">
                            <label> Links </label>
                            <input type="text" name="edit_links" value="<?php echo $row['links']; ?>" class="form-control" placeholder="Enter Links">
                        </div>
                        </div>
                        <a href="service.php" class="btn btn-danger btn-sm me-2">CANCEL</a>
                        <button type="submit" name="serviceupdate_btn" class="btn btn-primary btn-sm w-auto">Update</button>
                </form>
                     <?php
                }
            }
        
        ?>
    </div>
</div>
</div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
