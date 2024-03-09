    <?php

include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="modal fade" id="aboutus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title" id="exampleModalLabel">Modal Title</h1>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
</div>
</div>
</div>
<?php

if(isset($_POST['submit'])) {
    $title = $_POST['title'];
        $genre_id = $_POST['genre_id'];
        $release_year = $_POST['release_year'];
        $duration = $_POST['duration'];
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $poster_image = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
        $trailer_url = isset($_POST['trailer_url']) ? $_POST['trailer_url'] : '';
        $quality = isset($_POST['quality']) ? $_POST['quality'] : '';
        $link = isset($_POST['link']) ? $_POST['link'] : '';


    // Check if the file upload was successful
    if(move_uploaded_file($tempname, $folder.$movie_info)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO movie_info (title, genre_id, release_year, duration, description, poster_image, trailer_url, quality, link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siissssss", $title, $genre_id, $release_year, $duration, $description, $poster_image, $trailer_url, $quality, $link);

    
        // Execute the prepared statement
        if($stmt->execute()) {
            echo "<h2>Movie information uploaded successfully</h2>";
        } else {
            echo "<h2>Error: " . $stmt->error . "</h2>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "<h2>File not uploaded.</h2>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=<device-width>, initial-scale=1.0">
    <title>Upload</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title: </label>
        <input type="text" name="title"/><br>
        <label for="Genre_id">Genre_id: </label>
        <input type="text" name="genre_id"/><br>
        <label for="release_year">Release_year: </label>
        <input type="varchar" name="release_year"/><br>
        <label for="duration">Duration: </label>
        <input type="varchar" name="duration"/><br>
        <label for="Description">Description: </label>
        <input type="text" name="Description"/><br>
        <label for="poster_image">Poster_image: </label>
        <input type="file" name="image"/><br>
        <label for="Trailer_url">Trailer_url: </label>
        <input type="text" name="Trailer_url"/><br>
        <label for="quality">Quality: </label>
        <input type="text" name="quality"/><br>
        <label for="link">Link: </label>
        <input type="text" name="Link"/><br>
        
        <br/><br/>
        <button type="submit" name="submit">Submit</button>
        <div>
            <?php
            $res= mysqli_query($conn,"select * from movie_info");
            
            while($row=mysqli_fetch_assoc($res)){
            ?>
            <img src="Images/<?php echo $row['file']?>"/>
            <?php } ?>
        </div>
    </form>
</body>
</html>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>