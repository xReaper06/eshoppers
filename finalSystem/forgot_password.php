<?php include"includes/header.php"; 
$app ="<script src='assets/js/forgotPass.js'></script>"?>
<div id="forgot-app">
    <h2>Forgot Password</h2>
    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" id="email" v-model="email" required>
      </div>
      <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
  </div>
<?php include"includes/footer.php"; ?>