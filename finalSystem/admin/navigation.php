<div id="nav-admin">
<nav class="navbar navbar-expand-sm navbar-light bg-secondary" id="nav-app">
  <div class="container">
    <a class="navbar-brand pe-auto hover-shadow" href="./">
    <img style="height:50px;weight:50px;" src="../src/uploads/eshoppers-nav-logo-removebg-preview.png" class="img-fluid rounded-top" alt=""> Order List
    </a>
    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavId">
      <ul class="navbar-nav me-auto mt-2 mt-lg-0">
        <li class="nav-item">
          <a class="nav-link active hover-shadow" @click="changeColor(0)" href="history.php">
            <i class="bi bi-clock-history"></i> History
          </a>
        </li>
        <div class="dropdown open">
          <button class="btn btn-success dropdown-toggle" type="button" id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bi bi-person-plus"></i> Registration
          </button>
          <div class="dropdown-menu" aria-labelledby="triggerId">
          <button type="button" class="btn btn-dark dropdown-item" data-bs-toggle="modal" data-bs-target="#emplregis">
            <i class="bi bi-person-badge"></i> Register Employee
</button>
            <button type="button" class="btn btn-dark dropdown-item" data-bs-toggle="modal" data-bs-target="#adminregis">
            <i class="bi bi-person-bounding-box"></i> Register Admin
</button>
          </div>
        </div>
        <li class="nav-item hover-overlay">
          <a class="nav-link active" href="items.php" @click="changeColor(1)">
            <i class="bi bi-basket2"></i> Items
          </a>
        </li>
        <li class="nav-item hover-overlay">
          <a class="nav-link active" href="income.php" @click="changeColor(2)">
            <i class="bi bi-cash"></i> Income
          </a>
        </li>
        <li class="nav-item hover-overlay">
          <a class="nav-link active" href="registeredUsers.php" @click="changeColor(3)">
            <i class="bi bi-people"></i> Registered Users
          </a>
        </li>
        <li class="nav-item hover-overlay">
          <a class="nav-link active" href="transaction.php" @click="changeColor(4)">
          <i class="fas fa-money-check"></i> <i class="bi bi-people"></i> Transactions
          </a>
        </li>
      </ul>
      <ol id="profileID" class="navbar-nav">
        <li class="nav-item dropdown pr-3">
          <a class="nav-link dropdown-toggle" @click="changeColor(5)" href="#" id="dropdownId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded='false'>
            <i class="bi bi-person-circle"></i> <?php echo($_SESSION['username']);?>
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownId">
            <div class="mt-3 mb-3">
              <h6 class="text-center">Name:
                <?php echo($_SESSION['firstname']." ".$_SESSION['lastname'])?>
  </h6>
            </div>
  <div class="mb-3">
    <p class="text-center">Email: <?php echo($_SESSION['email']);?></p>
  </div>
            <button type="button" class="dropdown-item mb-2" data-bs-toggle="modal" data-bs-target="#settingModal">
              <i class="bi bi-gear"></i> Settings
            </button>
            <button type="button" class="btn btn-danger w-100" @click.prevent="logout">
              <i class="bi bi-box-arrow-right"></i> Log out
            </button>
          </div>
        </li>
      </ol>
    </div>
  </div>
</nav>
<div class="modal fade" id="settingModal" tabindex="-1" data-bs-backdrop="script" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId"><i class="fas fa-cog"></i> Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <button type="button" class="mb-2 form-control" data-bs-toggle="modal" data-bs-target="#updateProfile"><i class="fas fa-user-edit"></i> Update Personal Info</button>
          <button type="button" class="mb-2 form-control" data-bs-toggle="modal" data-bs-target="#changePass"><i class="fas fa-key"></i> Change Password</button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="updateProfile" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          <i class="bi bi-person-circle"></i> UPDATE PERSONAL INFO
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
            </div>
            <input type="text" name="" class="form-control" placeholder="First Name" v-model="update.firstname" required>
          </div>
        </div>
        <div class="mb-2">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
            </div>
            <input type="text" name="" class="form-control" placeholder="Last Name" v-model="update.lastname" required>
          </div>
        </div>
        <div class="mb-2">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="bi bi-house"></i></span>
            </div>
            <input type="text" name="" class="form-control" placeholder="Address" v-model="update.address" required>
          </div>
        </div>
        <div class="mb-2">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
            </div>
            <input type="text" name="" class="form-control" placeholder="ZipCode" v-model="update.zipcode" required>
          </div>
        </div>
        <div class="mb-2">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            </div>
            <input type="text" name="" class="form-control" placeholder="Phone Number" v-model="update.phoneNumber" required>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" @click.prevent="UpdateProfile">
          <i class="fas fa-save"></i> Save
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-x-circle"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

  <!-- Optional: Place to the bottom of scripts -->
  <div class="modal fade" id="changePass" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId"><i class="fas fa-key"></i> CHANGE PASSWORD</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" name="" class="form-control" placeholder="New Password" v-model="changePass.password">
          </div>
        </div>
        <div class="mb-2">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" name="" class="form-control" placeholder="Confirm Password" v-model="changePass.confirmpassword">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" @click.prevent="changePassword"><i class="fas fa-save"></i> Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal trigger button -->


<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div class="modal fade" id="adminregis" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title text-center position-absolute">
      <i class="fas fa-user-plus"></i> Admin Registration
    </h5>
      </div>
      <div class="modal-body">
      <form @submit.prevent="registerAdmin" class="registration-form">
    <div class="mb-3 mt-2">
      <label for="firstName" class="form-label">
        <i class="fas fa-user"></i> First Name:
      </label>
      <input type="text" id="firstName" v-model="registerAdmin.firstName" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="lastName" class="form-label">
        <i class="fas fa-user"></i> Last Name:
      </label>
      <input type="text" id="lastName" v-model="registerAdmin.lastName" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="username" class="form-label">
        <i class="fas fa-user"></i> Username:
      </label>
      <input type="text" id="username" v-model="registerAdmin.username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">
        <i class="fas fa-envelope"></i> Email:
      </label>
      <input type="email" id="email" v-model="registerAdmin.email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">
        <i class="fas fa-lock"></i> Password:
      </label>
      <input type="password" id="password" v-model="registerAdmin.password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">
        <i class="fas fa-lock"></i> Confirm Password:
      </label>
      <input type="password" id="Cpassword" v-model="registerAdmin.confirmpassword" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="gender" class="form-label">
        <i class="fas fa-venus-mars"></i> Gender:
      </label>
      <select id="gender" v-model="registerAdmin.gender" class="form-select" required>
        <option value="">Please select</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="birthday" class="form-label">
        <i class="fas fa-calendar-alt"></i> Birthday:
      </label>
      <input type="date" id="birthday" v-model="registerAdmin.birthday" @change="calculateAge()" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="age" class="form-label">
        <i class="fas fa-birthday-cake"></i> Age:
      </label>
      <input type="number" id="age" v-model="registerAdmin.age" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="address" class="form-label">
        <i class="fas fa-map-marker-alt"></i> Address:
  </label>
      <input type="text" id="address" v-model="registerAdmin.address" class="form-control" required>
    </div>
    <div class="mb-3">
  <label for="zipcode" class="form-label"><i class="fas fa-map-marker-alt"></i> Zip Code:</label>
  <input type="text" id="zipcode" v-model="registerAdmin.zipcode" class="form-control" required>
</div>
<div class="mb-3">
  <label for="phoneNumber" class="form-label"><i class="fas fa-phone"></i> Phone Number:</label>
  <input type="tel" id="phoneNumber" v-model="registerAdmin.phoneNumber" class="form-control" required>
</div>
<div class="mt-2"></div>
</form>
</div>
<div class="modal-footer">
        <button type="submit" @click.prevent="registerAdmin" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="emplregis" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="text-center position-absolute">
      <i class="fas fa-user-plus"></i> Employee Registration
    </h5>
      </div>
      <div class="modal-body">
      <form @submit.prevent="registerEmployee" class="registration-form">
    <div class="mb-3 mt-2">
      <label for="firstName" class="form-label">
        <i class="fas fa-user"></i> First Name:
      </label>
      <input type="text" id="firstName" v-model="registerEmpl.firstName" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="lastName" class="form-label">
        <i class="fas fa-user"></i> Last Name:
      </label>
      <input type="text" id="lastName" v-model="registerEmpl.lastName" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="username" class="form-label">
        <i class="fas fa-user"></i> Username:
      </label>
      <input type="text" id="username" v-model="registerEmpl.username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">
        <i class="fas fa-envelope"></i> Email:
      </label>
      <input type="email" id="email" v-model="registerEmpl.email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">
        <i class="fas fa-lock"></i> Password:
      </label>
      <input type="password" id="password" v-model="registerEmpl.password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">
        <i class="fas fa-lock"></i> Confirm Password:
      </label>
      <input type="password" id="Cpassword" v-model="registerEmpl.confirmpassword" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="gender" class="form-label">
        <i class="fas fa-venus-mars"></i> Gender:
      </label>
      <select id="gender" v-model="registerEmpl.gender" class="form-select" required>
        <option value="">Please select</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="birthday" class="form-label">
        <i class="fas fa-calendar-alt"></i> Birthday:
      </label>
      <input type="date" id="birthday" v-model="registerEmpl.birthday" @change="calculateAge()" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="age" class="form-label">
        <i class="fas fa-birthday-cake"></i> Age:
      </label>
      <input type="number" id="age" v-model="registerEmpl.age" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="address" class="form-label">
        <i class="fas fa-map-marker-alt"></i> Address:
  </label>
      <input type="text" id="address" v-model="registerEmpl.address" class="form-control" required>
    </div>
    <div class="mb-3">
  <label for="zipcode" class="form-label"><i class="fas fa-map-marker-alt"></i> Zip Code:</label>
  <input type="text" id="zipcode" v-model="registerEmpl.zipcode" class="form-control" required>
</div>
<div class="mb-3">
  <label for="phoneNumber" class="form-label"><i class="fas fa-phone"></i> Phone Number:</label>
  <input type="tel" id="phoneNumber" v-model="registerEmpl.phoneNumber" class="form-control" required>
</div>
<div class="mt-2"></div>
</form>
</div>
<div class="modal-footer">
        <button type="submit" @click.prevent="registerEmployee" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


    </div>
    <script src="../assets/js/vue.js"></script>
    <script src="./js/nav.js"></script>
    