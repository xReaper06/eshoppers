<?php include"./includes/header.php";
$app = "<script src='js/registeredUsers.js'></script>";?>
<?php include"./navigation.php"?>
<div class="" id="register-app">
    <div class="card mt-3 m-3">
        <div class="card-body">
            <h4 class="card-title">Registered Users</h4>
            <div class="d-flex justify-content-center">
            <ul class="list-unstyled">
    <li v-for="online in onlineUsers">
        <i class="bi bi-person-circle" style="color: #39ff14;"></i> Users Online: {{ online.online_users }}
    </li>
</ul>
    </div>
            <div class="table-responsive">
                <table id="mytable" class="table m-2">
                    <thead>
                        <tr>
                            <th span="col">User ID</th>
                            <th span="col">First Name</th>
                            <th span="col">last Name</th>
                            <th span="col">User Name</th>
                            <th span="col">Email</th>
                            <th span="col">Role</th>
                            <th span="col">Gender</th>
                            <th span="col">Birthday</th>
                            <th span="col">Age</th>
                            <th span="col">Address</th>
                            <th span="col">Zip Code</th>
                            <th span="col">Phone Number</th>
                            <th span="col">User Status</th>
                            <th span="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include"./includes/footer.php";
?>