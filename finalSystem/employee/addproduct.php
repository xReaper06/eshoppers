<?php include"./includes/header.php";
$app = "<script src='./js/addproduct.js'></script>";?>
<?php include"./navigation.php";?>
<div id="addproduct-app">
 <div class="card border-primary mt-5 m-3">
  <div class="card-body">
    <form @submit="addprod($event)">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><i class="fas fa-plus-square"></i> Add Product</h4>
            <p class="card-text">
              <div class="form-group">
                <div class="mb-2">
                  <label for="name" class="form-label"><i class="fas fa-shopping-bag"></i> Product Name</label>
                  <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <div class="mb-2">
              <label for="size" class="form-label"><i class="fas fa-arrows-alt-h"></i> Product Type</label>
              <select class="form-control" name="size" id="size" required>
                <option value="" disabled selected>Select product type Category</option>
                <option value="shoes">Shoes</option>
                <option value="skirt">Skirt</option>
                <option value="shirt">Shirt</option>
                <option value="short">Short</option>
              </select>
            </div>

                <div class="mb-2">
                  <label for="price" class="form-label">₱: Product Price</label>
                  <input type="number" class="form-control" name="price" id="price" required>
                </div>
                <div class="mb-2">
                  <label for="quantity"><i class="fas fa-sort-numeric-up"></i> Product Quantity</label>
                  <input type="number" class="form-control" name="quantity" id="quantity" required>
                </div>
                <div class="mb-2">
                  <label for="file" class="form-label"><i class="fas fa-image"></i> Product Pic</label>
                  <input type="file" class="form-control" accept=".png,.jpg,.jpeg,.svg" name="file" id="file" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
              </div>
            </p>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

</div>
<?php include"./includes/footer.php";?>