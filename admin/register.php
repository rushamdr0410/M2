<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Admin Data</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="adminRegisterForm" action="code.php" method="POST" onsubmit="return validateAdminForm()">
      <div class="modal-body">
        <div class="form-group">
            <label> Username </label>
            <input type="text" id="adminUsername" name="username" class="form-control" placeholder="Enter Username">
            <small id="adminUsernameError" class="text-danger"></small>
        </div>
        <div class="form-group">
            <label> Email </label>
            <input type="email" id="adminEmail" name="email" class="form-control checking_email" placeholder="Enter Email">
            <small id="adminEmailError" class="text-danger"></small>
        </div>
        <div class="form-group">
            <label> Password </label>
            <input type="password" id="adminPassword" name="password" class="form-control" placeholder="Enter Password">
            <small id="adminPasswordError" class="text-danger"></small>
        </div>
        <div class="form-group">
            <label> Confirm Password </label>
            <input type="password" id="adminConfirmPassword" name="confirmpassword" class="form-control" placeholder="Confirm Password">
            <small id="adminConfirmPasswordError" class="text-danger"></small>
        </div>
        <input type="hidden" name="usertype" value="admin">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="registerbtn" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Admin Profile
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addadminprofile">
                    Add Admin Profile
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
                $query = "SELECT * FROM register";
                $result = mysqli_query($connection, $query);
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>USERNAME</th>
                            <th>EMAIL</th>
                            <th>PASSWORD</th>
                            <th>User-Type</th>
                            <th>EDIT</th>
                            <th>DELETE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['password']; ?></td>
                                    <td><?php echo $row['usertype']; ?></td>
                                    <td>
                                        <form action="register_edit.php" method="POST">
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
                        } else {
                            echo "No Records Found!";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.text-success {
    color: #1cc88a !important;
}
.text-danger {
    color: #e74a3b !important;
}
.small {
    font-size: 80%;
    font-weight: normal;
}
</style>

<script>
    // Real-Time Validation for Admin Registration Form

    // Username Validation
    document.getElementById('adminUsername').addEventListener('input', function () {
        const username = this.value.trim();
        const usernameError = document.getElementById('adminUsernameError');
        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;

        if (username.length < 3 || username.length > 20) {
            usernameError.innerText = 'Username must be 3-20 characters long.';
        } else if (!usernameRegex.test(username)) {
            usernameError.innerText = 'Username can only contain letters, numbers, and underscores.';
        } else {
            usernameError.innerText = '';
        }
    });

    // Email Validation
    document.getElementById('adminEmail').addEventListener('input', function () {
        const email = this.value.trim();
        const emailError = document.getElementById('adminEmailError');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            emailError.innerText = 'Please enter a valid email address.';
        } else {
            emailError.innerText = '';
        }
    });

    // Password Validation
    document.getElementById('adminPassword').addEventListener('input', function () {
        const password = this.value.trim();
        const passwordError = document.getElementById('adminPasswordError');
        const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

        if (password.length < 8) {
            passwordError.innerText = 'Password must be at least 8 characters long.';
        } else if (!passwordRegex.test(password)) {
            passwordError.innerText = 'Password must include at least one uppercase letter, one lowercase letter, and one number.';
        } else {
            passwordError.innerText = '';
        }
    });

    // Confirm Password Validation
    document.getElementById('adminConfirmPassword').addEventListener('input', function () {
        const confirmPassword = this.value.trim();
        const password = document.getElementById('adminPassword').value.trim();
        const confirmPasswordError = document.getElementById('adminConfirmPasswordError');

        if (password !== confirmPassword) {
            confirmPasswordError.innerText = 'Passwords do not match.';
        } else {
            confirmPasswordError.innerText = '';
        }
    });

    // Form Submission Validation
    function validateAdminForm() {
        const username = document.getElementById('adminUsername').value.trim();
        const email = document.getElementById('adminEmail').value.trim();
        const password = document.getElementById('adminPassword').value.trim();
        const confirmPassword = document.getElementById('adminConfirmPassword').value.trim();

        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

        let isValid = true;

        // Validate Username
        if (!usernameRegex.test(username)) {
            document.getElementById('adminUsernameError').innerText = 'Username must be 3-20 characters long and can only contain letters, numbers, and underscores.';
            isValid = false;
        }

        // Validate Email
        if (!emailRegex.test(email)) {
            document.getElementById('adminEmailError').innerText = 'Please enter a valid email address.';
            isValid = false;
        }

        // Validate Password
        if (!passwordRegex.test(password)) {
            document.getElementById('adminPasswordError').innerText = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one number.';
            isValid = false;
        }

        // Validate Confirm Password
        if (password !== confirmPassword) {
            document.getElementById('adminConfirmPasswordError').innerText = 'Passwords do not match.';
            isValid = false;
        }

        return isValid;
    }
</script>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>