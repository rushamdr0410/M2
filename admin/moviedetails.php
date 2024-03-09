<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMagic | Where Every Frame tells a Story</title>
</head>

<body>
    <?php include('security.php'); ?>
    <?php include('includes/header.php'); ?>
    <?php include('includes/navbar.php'); ?>

    <div class="modal fade" id="aboutus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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

    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td><label for="title">Title:</label></td>
                <td><input type="text" name="title"></td>
            </tr>

            <tr>
                <td><label for="genre_id">Genre ID:</label></td>
                <td><input type="text" name="genre_id"></td>
            </tr>

            <tr>
                <td><label for="release_year">Release Year:</label></td>
                <td><input type="text" name="release_year"></td>
            </tr>

            <tr>
                <td><label for="duration">Duration:</label></td>
                <td><input type="text" name="duration"></td>
            </tr>

            <tr>
                <td><label for="description">Description:</label></td>
                <td><input type="text" name="description"></td>
            </tr>

            <tr>
                <td><label >Poster Image:</label></td>
                <td><input type="file" name="image"></td>
            </tr>

            <tr>
                <td><label for="trailer_url">Trailer URL:</label></td>
                <td><input type="text" name="trailer_url"></td>
            </tr>

            <tr>
                <td><label for="quality">Quality:</label></td>
                <td><input type="text" name="quality"></td>
            </tr>

            <tr>
                <td><label for="link">Link:</label></td>
                <td><input type="text" name="link"></td>
            </tr>
        </table>

        <br><br>

        <button type="submit" name="submit">Submit</button>

        <div>
            <?php
            $res = mysqli_query($connection, "SELECT * FROM moviedetails");
            while ($row = mysqli_fetch_assoc($res)) {
            ?>
                <img src="Images/<?php echo $row['file'] ?>" alt="Movie Poster">
            <?php } ?>
        </div>
    </form>

    <?php include('includes/scripts.php'); ?>
    <?php include('includes/footer.php'); ?>
</body>

</html>
