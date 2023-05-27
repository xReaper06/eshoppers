const app = Vue.createApp({
    data() {
        return {
          products: [],
          editprod: {
            id: null,
            size: '',
            price: '',
            quantity: '',
          },
          search:'',
        };
      },
      mounted() {
        this.getprod();
        // this.checkAuth()
      },
      methods: {
        getprod() {
          const vm = this;
          const data = new FormData();
          data.append('choice', 'getProd');
          data.append('search', vm.search);
          axios.post('../src/router.php', data).then((response) => {
            this.products = response.data;
          });
        },
        editproducts(product) {
          const vm = this;
          $('#editModal').modal('show');
          vm.editprod.id = product.prod_id;
          vm.editprod.size = product.size;
          vm.editprod.price = product.price;
          vm.editprod.quantity = product.quantity;
        },
      updateProduct(e) {
  const vm = this;
  e.preventDefault();
  const data = new FormData();
  data.append("choice", 'updateProd');
  data.append('prod_id', vm.editprod.id);
  data.append('size', vm.editprod.size);
  data.append('price', vm.editprod.price);
  data.append('quantity', vm.editprod.quantity);
  axios
    .post('../src/router.php', data)
    .then(function (res) {
      if (res.data == '200') {
        Swal.fire({
          icon: 'success',
          title: 'Product Edited Successfully',
        }).then(() => {
          $('#editModal').modal('hide');
          vm.getprod();
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: res.data,
        });
      }
    })
},
deleteprod(product) {
  const vm = this;
  const data = new FormData();
  data.append('choice', 'deleteProd');
  data.append('prod_id', product.prod_id);
  axios
    .post('../src/router.php', data)
    .then(function (res) {
      if (res.data == '200') {
        Swal.fire({
          icon: 'success',
          title: 'Product Deleted Successfully',
        }).then(() => {
          vm.getprod();
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Something went wrong!',
        });
      }
    })
    .catch((error) => {
      console.log(error);
    });
},

        addprod() {
          window.location.href="./addproduct.php";
        },
      },        
});
app.mount('#Main-app');