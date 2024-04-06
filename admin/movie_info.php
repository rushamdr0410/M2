<?php

include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Movie Details</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="code.php" method="POST">
      <div class="modal-body">
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
        <input type="hidden" name="usertype" value="admin">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="m_insertbtn" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Movie Details
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addadminprofile">
                Add Movie Details
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
                    <th>TITLE</th>
                    <th>GENRE ID</th>
                    <th>RELEASE YEAR</th>
                    <th>DURATION</th>
                    <th>DESCRIPTION</th>
                    <th>POSTER IMAGE</th>
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
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['genre_id']; ?></td>
                                <td><?php echo $row['release_year']; ?></td>
                                <td><?php echo $row['duration']; ?></td>
                                <td><?php echo $row['DESCRIPTION']; ?></td>
                                <td><?php echo $row['poster_image']; ?></td>
                                <td>
                                    <form action="moviedetails.php" method="POST">
                                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="edit_btn" class="btn btn-success">EDIT</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="code.php" method="POST">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_btn" class="btn btn-danger">DELETE</button>
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