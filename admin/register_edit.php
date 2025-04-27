<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Admin Profile</h6>
        </div>
        <div class="card-body">
            <?php
            if (!$connection) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            if (isset($_POST['edit_btn'])) {
                $id = $_POST['edit_id'];
                $query = "SELECT * FROM register WHERE id='$id'";
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
                            <label> Username </label>
                            <input type="text" name="edit_username" value="<?php echo $row['username']; ?>" class="form-control" placeholder="Enter Username">
                        </div>
                        <div class="form-group">
                            <label> Email </label>
                            <input type="email" name="edit_email" value="<?php echo $row['email']; ?>" class="form-control" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <label> Password </label>
                            <input type="password" name="edit_password" value="<?php echo $row['password']; ?>" class="form-control" placeholder="Enter Password">
                        </div>
                        <div class="form-group">
                            <label> User-Type </label>
                            <select name="update_usertype" class="form-control">
                                <option value="admin" <?php echo ($row['usertype'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="user" <?php echo ($row['usertype'] == 'user') ? 'selected' : ''; ?>>User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label> Location </label>
                            <div class="input-group">
                                <input type="text" id="location" name="location" class="form-control" placeholder="Enter Location (e.g., New York, NY)" value="<?php echo isset($row['location']) ? $row['location'] : ''; ?>">
                                <button type="button" class="btn btn-primary" onclick="getCurrentLocation()">Get Current Location</button>
                            </div>
                            <small class="text-muted">Click the button to get your current location coordinates, or enter manually below</small>
                            <div id="locationHelp" class="text-muted mt-2" style="display: none;">
                                <strong>If location is not working:</strong>
                                <ol>
                                    <li>Make sure your device's GPS is turned on</li>
                                    <li>Check if location services are enabled in your browser</li>
                                    <li>Ensure you have granted location permission to this website</li>
                                    <li>Try using a different browser</li>
                                    <li>If all else fails, enter coordinates manually below</li>
                                </ol>
                            </div>
                        </div>
                        <div class="form-group">
                            <label> Latitude </label>
                            <input type="text" id="latitude" name="edit_latitude" class="form-control" placeholder="Enter Latitude manually (e.g., 40.7128)" value="<?php echo isset($row['latitude']) ? $row['latitude'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label> Longitude </label>
                            <input type="text" id="longitude" name="edit_longitude" class="form-control" placeholder="Enter Longitude manually (e.g., -74.0060)" value="<?php echo isset($row['longitude']) ? $row['longitude'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label> Last Location Updated </label>
                            <input type="datetime-local" name="edit_last_location_updated" class="form-control" value="<?php echo isset($row['last_location_updated']) ? date('Y-m-d\TH:i', strtotime($row['last_location_updated'])) : ''; ?>">
                        </div>
                        <a href="register.php" class="btn btn-danger">CANCEL</a>
                        <button type="submit" name="userupdatebtn" class="btn btn-primary">Update</button>
                    </form>

                    <script>
                        function getCurrentLocation() {
                            if (navigator.geolocation) {
                                // Show loading message and help
                                const locationInput = document.getElementById('location');
                                const locationHelp = document.getElementById('locationHelp');
                                locationInput.value = "Getting location...";
                                locationHelp.style.display = 'block';
                                
                                navigator.geolocation.getCurrentPosition(
                                    function(position) {
                                        document.getElementById('latitude').value = position.coords.latitude;
                                        document.getElementById('longitude').value = position.coords.longitude;
                                        document.getElementById('location').value = "Current Location";
                                        // Update the last location updated timestamp
                                        const now = new Date();
                                        document.querySelector('input[name="edit_last_location_updated"]').value = 
                                            now.toISOString().slice(0, 16); // Format: YYYY-MM-DDThh:mm
                                        locationHelp.style.display = 'none';
                                    },
                                    function(error) {
                                        let errorMessage = "Unable to get your location. ";
                                        switch(error.code) {
                                            case error.PERMISSION_DENIED:
                                                errorMessage += "Please enable location services in your browser settings and try again.";
                                                break;
                                            case error.POSITION_UNAVAILABLE:
                                                errorMessage += "Please check your internet connection and GPS settings.";
                                                break;
                                            case error.TIMEOUT:
                                                errorMessage += "The request timed out. Please try again.";
                                                break;
                                            default:
                                                errorMessage += "An unknown error occurred.";
                                                break;
                                        }
                                        alert(errorMessage);
                                        document.getElementById('location').value = "";
                                        locationHelp.style.display = 'block';
                                    },
                                    {
                                        enableHighAccuracy: true,
                                        timeout: 10000, // Increased timeout to 10 seconds
                                        maximumAge: 0
                                    }
                                );
                            } else {
                                alert("Your browser doesn't support geolocation. Please enter the coordinates manually.");
                                document.getElementById('locationHelp').style.display = 'block';
                            }
                        }

                        // Add event listener to hide help when manually entering location
                        document.getElementById('location').addEventListener('input', function() {
                            if (this.value.trim() !== '') {
                                document.getElementById('locationHelp').style.display = 'none';
                            }
                        });
                    </script>
            <?php
                } else {
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
?>