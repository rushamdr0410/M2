<?php

include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Cast Details</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="code.php" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        <div class="form-group">
            <label> Cast Name </label>
            <input type="text" name="c_name" class="form-control" placeholder="Enter Cast Name" required>
        </div>
        <div class="form-group">
            <label> Biography</label>
            <textarea name="biography" class="form-control" required rows="3"></textarea>
        </div>
        <div class="form-group">
            <label> Birth Date </label>
            <input type="text" name="dob" class="form-control" placeholder="Enter Date of Birth" required>
        </div>
        <div class="form-group">
            <label> Birth Place </label>
            <input type="text" name="pob" class="form-control" placeholder="Enter Place of Birth" required>
        <div class="form-group">
            <label>Cast Image</label>
            <input type="file" name="c_img" id="c_img" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="c_insertbtn" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Cast Details
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addadminprofile">
                Add Cast Details
            </button>
        </h6>
</div>
<div class="card-body">
    <?php
    if(isset($_SESSION['success']) &&  $_SESSION['success'] !=''){
        echo '<h2 class="bg-primary text-white">'.$_SESSION['success'].'</h2>';
        unset($_SESSION['success']);
    }
    if(isset($_SESSION['status']) &&  $_SESSION['status'] !=''){
        echo '<h2 class="bg_danger text-white">'.$_SESSION['status'].'</h2>';
        unset($_SESSION['status']);
    }
    ?>
    <div class="table-responsive">
    
    <?php
        
        $query="SELECT* FROM moviedetails";
        $result=mysqli_query($connection, $query);
    
    ?>
        <table class="table table-bordered" id="dataTable" with="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CAST NAME</th>
                    <th>BIRTH DATE</th>
                    <th>BIRTH PLACE</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(mysqli_num_rows($result)>0)
                    {
                        while($row=mysqli_fetch_assoc($result))
                        { 
                            ?>
                            
                            <tr>
                                <td><?php echo $row['cast_id']; ?></td>
                                <td><?php echo $row['cast_name']; ?></td>
                                <td><?php echo $row['birth_date']; ?></td>
                                <td><?php echo $row['birth_place']; ?></td>
                                <td>
                                    <form action="castdetails.php" method="POST">
                                        <input type="hidden" name="castedit_id" value="<?php echo $row['cast_id']; ?>">
                                        <button type="submit" name="castedit_btn" class="btn btn-success">EDIT</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="code.php" method="POST">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['cast_id']; ?>">
                                        <button type="submit" name="cast_delete_btn" class="btn btn-danger">DELETE</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    else
                    {
                        echo "No Records Found!";
                    }
                ?>
            </tbody>
        </table>
   
    </div>
</div>
</div>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>