<?php include"./includes/header.php";
$app = "<script src='./js/main.js'></script>";?>
<?php include"./navigation.php";?>
<div id="main-app">
<center>
    <h5 class="text text-white"><i class="fas fa-list text-white"></i> Product List</h5>
</center>
    <div class="d-flex justify-content-center w-25 float-end">
  <div class="input-group mb-3">
    <input type="text" class="form-control" v-model="search" placeholder="Search..." @keyup="getprod">
    <button class="btn btn-outline-secondary" type="button" @click="getprod"><i class="fas fa-search"></i></button>
  </div>
</div>

    <div class="mt-5">
    <div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col" v-for="product in products" :key="product.prod_id">
    <div class="card h-100">
    <img scope="row" :src="'../src/uploads/'+product.path" style="height:70px;width:70px;object-fit:cover;" alt="title">
    <div class="card-body">
    <h5 class="card-title">Product Name: {{ product.product_name }}</h5>
    <p class="card-text">Type:           {{ product.size }}</p>
    <p class="card-text">Price: ₱{{ product.price }}</p>
    <div class="d-flex justify-content-between align-items-center">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-primary" @click="addtocart(product)"><i class="fas fa-cart-plus"></i> Add to Cart</button>
      </div>
      <small class="text-muted">{{ product.quantity }} left</small>
    </div>
    </div>
    </div>
    </div>
    </div>

        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="AddtocartModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Submitting</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit="submitCart($event)">
                        <div class="modal-body">
                            <h2>Are you sure you want to Add this Product to your Cart?</h2>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">no</button>
                            <button type="button" class="btn btn-primary" @click="submitCart($event)">yes</button>
                        </div>
                    </form>
                </div>
            </div>
            <input type="hidden" class="form-control" name="prod_id" id="prod_id" v-model="addto.id">
        </div>

</div>
<?php include"./includes/footer.php";?>
