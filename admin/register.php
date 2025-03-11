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
      <form id="adminRegisterForm" action="code.php" method="POST" onsubmit="return validateForm()">
      <div class="modal-body">
        <div class="form-group">
            <label> Username </label>
            <input type="text" name="username" class="form-control" placeholder="Enter Username">
        </div>
        <div class="form-group">
            <label> Email </label>
            <input type="email" name="email" class="form-control checking_email" placeholder="Enter Email">
            <small class="error_email" style="color: red;"></small>
        </div>
        <div class="form-group">
            <label> Password </label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password">
        </div>
        <div class="form-group">
            <label> Confirm Password </label>
            <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password">
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
        
        $query="SELECT* FROM register";
        $result=mysqli_query($connection, $query);
    
    ?>
        <table class="table table-bordered" id="dataTable" with="100%" cellspacing="0">
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
                    if(mysqli_num_rows($result)>0)
                    {
                        while($row=mysqli_fetch_assoc($result))
                        { 
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
<script>
    function validateForm() {
        console.log("Validation function called"); // Debugging statement

        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirmpassword').value.trim();

        console.log("Username:", username); // Debugging statement
        console.log("Email:", email); // Debugging statement
        console.log("Password:", password); // Debugging statement
        console.log("Confirm Password:", confirmPassword); // Debugging statement

        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

        let isValid = true;

        // Validate Username
        if (!username) {
            console.log("Username is empty"); // Debugging statement
            document.getElementById('usernameError').textContent = 'Username is required.';
            isValid = false;
        } else if (!usernameRegex.test(username)) {
            console.log("Username validation failed"); // Debugging statement
            document.getElementById('usernameError').textContent = 'Username must be 3-20 characters long and can only contain letters, numbers, and underscores.';
            isValid = false;
        }

        // Validate Email
        if (!email) {
            console.log("Email is empty"); // Debugging statement
            document.getElementById('emailError').textContent = 'Email is required.';
            isValid = false;
        } else if (!emailRegex.test(email)) {
            console.log("Email validation failed"); // Debugging statement
            document.getElementById('emailError').textContent = 'Please enter a valid email address.';
            isValid = false;
        }

        // Validate Password
        if (!password) {
            console.log("Password is empty"); // Debugging statement
            document.getElementById('passwordError').textContent = 'Password is required.';
            isValid = false;
        } else if (!passwordRegex.test(password)) {
            console.log("Password validation failed"); // Debugging statement
            document.getElementById('passwordError').textContent = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one number.';
            isValid = false;
        }

        // Validate Confirm Password
        if (!confirmPassword) {
            console.log("Confirm Password is empty"); // Debugging statement
            document.getElementById('confirmPasswordError').textContent = 'Confirm Password is required.';
            isValid = false;
        } else if (password !== confirmPassword) {
            console.log("Confirm Password validation failed"); // Debugging statement
            document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
            isValid = false;
        }

        console.log("Validation result:", isValid); // Debugging statement
        return isValid;
    }
</script>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>