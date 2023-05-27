<?php include"./includes/header.php";
$app = "<script src='./js/shipping.js'></script>";?>
<?php include"./navigation.php";?>
<div class="" id="shipping-app">
<div class="card m-2">
  <div class="card-body">
    <h4><i class="fas fa-shopping-cart"></i> Orders</h4>
    <table id="myTable" class="table table-responsive m-2">
      <thead>
        <tr>
        <th>Order number</th>
            <th>User Profile</th>
            <th>Items Ordered</th>
            <th>Total Cost</th>
            <th>Payment Mode</th>
            <th>Order Status</th>
            <th>Date checkouted</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody v-for="order in orderCheckouts" :key="order.checkout_id">
          <tr>
            <td>{{ order.checkout_id }}</td>
            <td>
              <button type="button" class="btn btn-primary" @click="viewProf(order)">View Profile</button>
            </td>
            <td v-for="selected in JSON.parse(order.selected_products)">
            <button type="button" class="btn btn-primary" @click="viewItem(selected)">Items</button>
            </td>
            <td>₱: {{ order.total_price }}</td>
            <td>{{ order.mode_of_delivery }}</td>
            <td>{{ order.status }}</td>
            <td>{{ order.created_at }}</td>
            <td>
              <button type="button" class="btn btn-primary" @click="itemDeliver(order)">Deliver Order</button>
            </td>
          </tr>
        </tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="modalProfile" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label" for="">First name</label>
          <input type="text" name="" class="form-control" id="" v-model="profile.firstname" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label" for="">Last name</label>
          <input type="text" name="" class="form-control" id="" v-model="profile.lastname" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label" for="">Birthday</label>
          <input type="text" name="" class="form-control" id="" v-model="profile.birthday" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label" for="">Age</label>
          <input type="text" name="" class="form-control" id="" v-model="profile.age" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label" for="">Email</label>
          <input type="text" name="" class="form-control" id="" v-model="profile.email" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label" for="">Address</label>
          <input type="text" name="" class="form-control" id="" v-model="profile.address" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label" for="">Zipcode</label>
          <input type="text" name="" class="form-control" id="" v-model="profile.zipcode" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label" for="">Phone Number</label>
          <input type="text" name="" class="form-control" id="" v-model="profile.phonenumber" readonly>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>





<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div class="modal fade" id="modalItems" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Items</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="mb-3">
      <img style="height:100px;width:100px;object-fit:cover;" :src="'../src/uploads/'+select.path" alt="Title">
          </div>
        <div class="mb-3">
        <label class="form-label" for="">ID</label>
          
          <input type="text" class="form-control" name="" id="" v-model="select.prod_id" readonly>
          </div>
        <div class="mb-3">
        <label class="form-label" for="">Name</label>
          
          <input type="text" class="form-control" name="" id="" v-model="select.product_name" readonly>
          </div>
        <div class="mb-3">
        <label class="form-label" for="">Price</label>
          
          ₱: <input type="text" class="form-control" name="" id="" v-model="select.price" readonly>
          </div>
        <div class="mb-3">
        <label class="form-label" for="">Size</label>

          <input type="text" class="form-control" name="" id="" v-model="select.size" readonly>
          
          </div>
          <div class="mb-3">
          <label class="form-label" for="">Quantity</label>
            <input type="text" class="form-control" name="" id="" v-model="select.quantity" readonly>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
</div>
<?php include"./includes/footer.php";?>