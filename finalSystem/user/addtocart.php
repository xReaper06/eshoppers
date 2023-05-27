<?php include"./includes/header.php";
$app = "<script src='./js/addtocart.js'></script>";?>
<?php include"./navigation.php";?>
<div class="" id="addtocart-app">
<div class="card mt-4 m-3">
  <div class="card-body">
    <h4 class="card-title"><i class="fas fa-shopping-cart me-1"></i>My Cart</h4>
    <table class="table table-responsive table-stripe">
      <thead>
        <tr>
          <th>Select</th>
          <th>Image</th>
          <th>Order name</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Size</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="orderProd in ordersProd" :key="orderProd.id">
          <td><input type="checkbox" :value="orderProd" v-model="selectedProducts"></td>
          <td><img style="height:50px;width:50px;object-fit:cover;" :src="'../src/uploads/'+orderProd.path" alt="Title"></td>
          <td>{{ orderProd.product_name }}</td>
          <td>
            <div class="mb-3">
              <label for="" class="form-label">Quantity</label>
              <select class="form-select form-select-sm" name="" id="" v-model="orderProd.quantity">
                <option v-bind:selected="orderProd.quantity === 1" value="1">1</option>
                <option v-bind:selected="orderProd.quantity === 2" value="2">2</option>
                <option v-bind:selected="orderProd.quantity === 3" value="3">3</option>
                <option v-bind:selected="orderProd.quantity === 4" value="4">4</option>
              </select>
            </div>
          </td>
          <td>₱ {{ orderProd.price * orderProd.quantity }}</td>
          <td>
  <div class="mb-3">
    <label for="" class="form-label">Size</label>
    <div v-if="orderProd.size === 'shirt'|| orderProd.size === 'short' || orderProd.size === 'skirt'">
      <select class="form-select form-select-sm" name="size" id="size" value="" v-model="orderProd.inputSize" required>
        <option value="S">S</option>
        <option value="M">M</option>
        <option value="L">L</option>
        <option value="XL">XL</option>
        <option value="XXL">XXL</option>
      </select>
    </div>
    <div v-else-if="orderProd.size === 'shoes'">
      <input v-model="orderProd.inputSize" type="text" name="size" id="footLength" placeholder="Foot Length (cm)" value="" required>
    </div>
  </div>
</td>

          <td><button type="button" class="btn btn-primary" @click="removeProd(orderProd)">
              <i class="bi bi-x-lg"></i> Remove
            </button></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Checkout button with condition -->
<button v-if="selectedProducts.length > 0" type="button" class="btn btn-primary" @click.prevent="checkingout">
  <i class="bi bi-cart-check"></i> Checkout
</button>

  <!-- Modal Body -->
  <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
  <div class="modal fade" id="checkout" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId"><i class="bi bi-cart-check-fill me-2"></i>Check out</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
          <div v-if="selectedProducts.length > 0">
            <p><i class="bi bi-check-circle-fill me-2"></i>Selected products:</p>
            <ul style="list-style-type: none;">
  <li v-for="product in selectedProducts" :key="product.id">
    <i class="fas fa-shopping-cart"></i> {{ product.product_name }} - <i class="fas fa-dollar-sign"></i> {{ product.price }} - <i class="fas fa-tshirt"></i> {{ product.inputSize }} - <i class="fas fa-sort-numeric-up-alt"></i> {{ product.quantity }}
  </li>
</ul>
            <p><i class="bi bi-currency-dollar me-2"></i>Total price: ₱ {{ totalPrice.toFixed(2) }}</p>
          </div>
          <div v-else>
            <p><i class="bi bi-exclamation-circle-fill me-2"></i>No products selected.</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle-fill me-2"></i>Close</button>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkoutMethod"><i class="bi bi-cash-coin me-2"></i>Choose Checkout Methods</button>
        </div>
    </div>
  </div>
</div>
<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div class="modal fade" id="checkoutMethod" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Check Out Methods</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-unstyled">
          <li v-for="user in users">
            <div class="mb-3">
              <button type="button" class="btn btn-warning" @click.prevent="checkoutCOD">Cash on Delivery <img src="./COD-logo.png" class="img-fluid rounded-top" style="height:35px;weight:35px;" alt=""></button>
            </div>
            <button type="button" class="btn btn-dark" @click.prevent="checkoutGcash(user)">pay via <img src="./Gcash-logo.png" class="img-fluid rounded-top" style="height:35px;weight:35px;" alt=""></button>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Body -->
<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="gcashValidationModal" tabindex="-1" aria-labelledby="gcashValidationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="gcashValidationModalLabel"><img src="./Gcash-logo.png" class="img-fluid rounded-top" style="height:40px;weight:40px;" alt="">
           Transaction Validation</h5>
      </div>
      <div class="modal-body">
          <ul class="list-unstyled" v-if="transactions && transactions.length > 0" v-for="transaction in JSON.parse(transactions)">
            <li>
              <label for="transactionId" class="form-label">Transaction ID</label>
              {{ transaction.id }}
            </li>
            <li>
              <label for="amount" class="form-label">Amount</label>
              {{ transaction.attributes.amount }} IN cents
            </li>
            <li>
              <label for="confirmationCode" class="form-label">Confirmation Code</label>
            {{ transaction.attributes.client_key }}
            </li>
            <input type="hidden" v-model="confirm.id" name="id" readonly>
            <div class="mb-3">
            <label for="local-email" class="form-label">email</label>
            <input type="text" v-model="confirm.email" class="form-control" id="local-email" placeholder="First Name" readonly required>
          </div>
            <div class="mb-3">
            <label for="local-fname" class="form-label">Firstname</label>
            <input type="text" v-model="confirm.firstname" class="form-control" id="local-fname" placeholder="First Name" readonly required>
          </div>
          <div class="mb-3">
          <label for="local-lastname" class="form-label">Last Name</label>
            <input type="text" v-model="confirm.lastname" class="form-control" id="local-lastname" placeholder="Last Name" readonly required>
          </div>
          <div class="mb-3">
          <label for="local-number" class="form-label">Phone Number:</label>
            <input type="text" v-model="confirm.phonenumber" class="form-control" id="local-number" placeholder="phone Number" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click.prevent="validation(transaction)">Validate Transaction<img src="./Gcash-logo.png" class="img-fluid rounded-top" style="height:35px;weight:35px;" alt=""></button>
          </div>
        </ul>
        </div>
    </div>
  </div>
</div>
</div>
<?php include"./includes/footer.php";?>