<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
include('includes/constants/constant.php');

?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Movie Details</h6>
        </div>
        <div class="card-body">
            <?php
            if (!$connection) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            if (isset($_POST['edit_btn'])) {
                $id = $_POST['edit_id'];
                $query = "SELECT * FROM moviedetails WHERE id='$id'";
                $result = mysqli_query($connection, $query);

                if (!$result) {
                    die("Query failed: " . mysqli_error($connection));
                }

                // Check if any row is fetched
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $current_genre = $row['genreid'];
                    $current_image = $row['poster_img'];
            ?>
                    <form action="code.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">

                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="m_title" class="form-control" placeholder="Enter Movie Title" value="<?php echo $row['title']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Genre</label>
                            <select name="gid" class="form-control">
                                <?php
                                    $sql = "SELECT * FROM genre_info WHERE active='1'";
                                    $res = mysqli_query($connection, $sql);
                                    $count = mysqli_num_rows($res);
                                    if($count>0)
                                    {
                                        while($col=mysqli_fetch_assoc($res)) 
                                        {
                                            $genre_title = $col['genre_name'];
                                            $genreid = $col['genreid'];
                                            ?>
                                            
                                            <option 
                                                <?php if($current_genre == $genreid) {echo "selected";} ?> value="<?php echo $genreid; ?>">
                                                <?php echo $genre_title; ?>
                                            </option>

                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<option value='0'>Category Not Available</option>";
                                    }
                                    

                                ?>
                                </select>
                        </div>
                        <div class="form-group">
                            <label>Release Year</label>
                            
                            <input type="text" name="m_year" class="form-control" placeholder="Enter Released Year" value="<?php echo $row['release_year']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Duration</label>
                            <input type="text" name="m_duration" class="form-control" placeholder="Enter Duration" value="<?php echo $row['duration'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" name="m_type" class="form-control" placeholder="Enter Type" value="<?php echo $row['type'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label> Current Poster Image</label><br>
                            <td>
                                <!-- Display image if available -->
                                <?php 
                                    if($current_image == "")
                                    {
                                        echo "<h6>Image not Available</h6>";
                                    }
                                    else
                                    {
                                        echo '<img src="upload/'.$row['poster_img'].'" width="100px;" height="100px;" alt="Movie Poster">';
                                    }
                                ?>
                            </td>
                        </div>
                        <div class="form-group">
                            <label> New Poster</label>
                            <input type="file" name="m_img" id="m_img" class="form-control" placeholder="Insert Image">
                        </div>
                        <div class="form-group">
                            <label>Quality</label>
                            <input type="text" name="m_quality" class="form-control" placeholder="Enter Duration" value="<?php echo $row['quality'] ?? ''; ?>">
                        </div>
                        <a href="movie_info.php" class="btn btn-danger">CANCEL</a>
                        <button type="submit" name="updatebtn" class="btn btn-primary">Update</button>
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
