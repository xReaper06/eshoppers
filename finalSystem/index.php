<?php include"includes/header.php"; 
$app ="<script src='assets/js/login.js'></script>"?>
      <div id="login-app" class="pt-5">
        <div class="d-flex justify-content-center pt-5">
          <div class="card p-4 rounded bg-light pt-5">
            <img src="./src/uploads/logo-eshoppers.png" style="height:250px;weight250:px" alt="logo">
            <div class="card-header text-center">
              <button type="button" class="btn btn-dark btn-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="bi bi-box-arrow-in-right"></i> Log in Now</button>
              </div>
              <div class="card-footer text-muted">
                <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#registrationModal">
                  <i class="bi bi-person-plus"></i> Register Now</button>
                </div>
                <!-- <a href="forgot_password.php">Forgot Password</a> -->
  </div>
</div>

<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div class="modal fade" id="loginModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content bg-secondary text-white">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="modalTitleId">Log in Form</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-secondary">
      <form @submit.prevent="handlelogin">
        <div class="mb-3">
          <label for="username" class="form-label"><i class="bi bi-person"></i> Username:</label>
          <input type="text" class="form-control" v-model="username" @keyup.enter="handlelogin" placeholder="Username" required />
        </div>
 <div class="position-relative mb-3">
   <label for="password" class="form-label"><i class="bi bi-lock"></i> Password:</label>
   <input type="password" class="form-control password" v-model="password" @keyup.enter="handlelogin" placeholder="Password" required />
   <div class="showpassword" @click="showPassword">
     <i :class="showPasswordIcon"></i>
    </div>
  </div> 
      <div class="mb-3">
          <button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Log in</button>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal trigger button -->


<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div class="modal fade" id="registrationModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="text-center position-absolute modal-title mt-3 mb-3" id="modalTitleId">
          <i class="fas fa-user-plus"></i> User Registration
        </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form @submit.prevent="registerUser" class="registration-form">
    <div class="mb-3 mt-2">
      <label for="firstName" class="form-label">
        <i class="fas fa-user"></i> First Name:
      </label>
      <input type="text" id="firstName" v-model="register.firstName" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="lastName" class="form-label">
        <i class="fas fa-user"></i> Last Name:
      </label>
      <input type="text" id="lastName" v-model="register.lastName" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="username" class="form-label">
        <i class="fas fa-user"></i> Username:
      </label>
      <input type="text" id="username" v-model="register.username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">
        <i class="fas fa-envelope"></i> Email:
      </label>
      <input type="email" id="email" v-model="register.email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">
        <i class="fas fa-lock"></i> Password:
      </label>
      <input type="password" class="form-control" id="password" @blur="validatePassword" v-model="register.password" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">
        <i class="fas fa-lock"></i> Confirm Password:
      </label>
      <input type="password" class="form-control" id="Cpassword" v-model="register.confirmpassword" required>
      <span v-if="register.passwordError" style="color: red">{{ register.passwordError }}</span>
    </div>
    <div class="mb-3">
      <label for="gender" class="form-label">
        <i class="fas fa-venus-mars"></i> Gender:
      </label>
      <select id="gender" v-model="register.gender" class="form-select" required>
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
      <input type="date" id="birthday" v-model="register.birthday" @change="calculateAge()" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="age" class="form-label">
        <i class="fas fa-birthday-cake"></i> Age:
      </label>
      <input type="number" id="age" v-model="register.age" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="address" class="form-label">
        <i class="fas fa-map-marker-alt"></i> Address:
  </label>
      <input type="text" id="address" v-model="register.address" class="form-control" required>
    </div>
    <div class="mb-3">
  <label for="zipcode" class="form-label"><i class="fas fa-map-marker-alt"></i> Zip Code:</label>
  <input type="text" id="zipcode" v-model="register.zipcode" class="form-control" required>
</div>
<div class="mb-3">
  <label for="phoneNumber" class="form-label"><i class="fas fa-phone"></i> Phone Number:</label>
  <input type="tel" id="phoneNumber" v-model="register.phoneNumber" class="form-control" required>
</div>
<button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register</button>
<div class="mt-2">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
    <i class="fas fa-sign-in-alt"></i> I already have an account
  </button>
</div>
</form>
</div>
</div>
</div>
</div>

    </div>
<?php include"includes/footer.php"; ?>