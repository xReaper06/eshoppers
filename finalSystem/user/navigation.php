
<div id="cliNav-app">
<nav class="navbar navbar-expand-sm navbar-light bg-secondary" id="nav-app">
  <div class="container">
    <a class="navbar-brand pe-auto hover-shadow" href="./"><img style="height:50px;weight:50px;" src="../src/uploads/eshoppers-nav-logo-removebg-preview.png" class="img-fluid rounded-top" alt="">Home</a>
    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavId">
    <ul class="navbar-nav me-auto mt-2 mt-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="addtocart.php" @click="changeColor(0)"><i class="fas fa-shopping-cart me-1"></i>My Cart <span class="visually-hidden">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="order.php" @click="changeColor(1)"><i class="fas fa-clipboard-list me-1"></i>Order Process<span class="visually-hidden">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="history.php" @click="changeColor(2)"><i class="fas fa-history me-1"></i>History<span class="visually-hidden">(current)</span></a>
          </li>
        </ul>
      <ol id="profileID" class="navbar-nav">
        <li class="nav-item dropdown pr-3">
          <a class="nav-link dropdown-toggle active" @click="changeColor(3)" href="#" id="dropdownId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded='false'>
            <i class="fas fa-user me-1"></i><?php echo ($_SESSION['username']); ?>
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
              <i class="fas fa-cog me-2"></i>Settings
            </button>
            <button type="button" class="btn btn-danger w-100" @click.prevent="logout()">
              <i class="fas fa-sign-out-alt me-2"></i>Logout
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
</div>
<script src="../assets/js/vue.js"></script>
<script src="./js/nav.js"></script>
