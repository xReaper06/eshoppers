<?php include"./includes/header.php";
$app = "<script src='./js/main.js'></script>";?>
<?php include"./navigation.php";?>
<div id="Main-app">
  <div class="input-group mt-2 m-5">
    <span class="input-group-text"><i class="bi bi-search"></i></span>
    <input type="text" class="form-control" placeholder="Search Product" @keyup="getprod" v-model="search">
  </div>
  <div class="d-flex justify-content-end">
  <button type="button" @click="addprod" class="btn btn-primary">
    <i class="bi bi-plus-circle"></i> Add Products
  </button>
</div>
<div class="card mt-2 m-3">
  <div class="card-body">
    <h4 class="card-title">Products Added List</h4>
    <table id="myTable" class="table table-responsive table-striped m-2">
      <thead>
        <tr>
          <th><i class="bi bi-image"></i> Image</th>
          <th><i class="bi bi-hash"></i> Product ID</th>
          <th><i class="bi bi-person"></i> User ID</th>
          <th><i class="bi bi-tag"></i> Product Name</th>
          <th><i class="bi bi-list"></i> Type</th>
          <th><i class="bi bi-cash"></i> Price ₱</th>
          <th><i class="bi bi-box-seam"></i> Quantity</th>
          <th><i class="bi bi-calendar"></i> Date Created</th>
          <th><i class="bi bi-calendar-check"></i> Date Updated</th>
          <th><i class="bi bi-gear"></i> Actions</th>
        </tr>
      </thead>
      <tbody v-for="product in products" :key="product.prod_id">
        <tr>
          <td><img :src="'../src/uploads/'+product.path" style="height:50px;width:50px;object-fit:cover;" class="img-fluid rounded-top" alt="title"></td>
          <td>{{ product.prod_id }}</td>
          <td>{{ product.user_id }}</td>
          <td>{{ product.product_name }}</td>
          <td>{{ product.size }}</td>
          <td>₱ {{ product.price }}</td>
          <td>{{ product.quantity }}</td>
          <td>{{ product.created_at }}</td>
          <td>{{ product.updated_at }}</td>
          <td>
            <button type="button" class="btn btn-primary" @click="editproducts(product)"><i class="bi bi-pencil"></i></button>
            <button type="button" class="btn btn-danger" @click="deleteprod(product)"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table> 
  </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          <i class="bi bi-pencil-square"></i> Edit Product
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit="updateProduct($event)">
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">
              <i class="bi bi-info-circle"></i> Product ID:
            </label>
            <input type="hidden" id="id" v-model="editprod.id" required>
          </div>
          <div class="mb-3">
            <label for="size" class="form-label">
              <i class="bi bi-ruler"></i> Product Size:
            </label>
            <input type="text" id="size" v-model="editprod.size" required>
          </div>
          <div class="mb-3">
            <label for="price" class="form-label">
            ₱ Product Price:
            </label>
            <input type="number" id="price" v-model="editprod.price" required>
          </div>
          <div class="mb-3">
            <label for="quantity" class="form-label">
              <i class="bi bi-graph-up"></i> Product Quantity:
            </label>
            <input type="number" id="quantity" v-model="editprod.quantity" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Close
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include"./includes/footer.php";?>