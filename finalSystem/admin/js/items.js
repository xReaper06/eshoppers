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
      }
    },
    mounted() {
      this.getProducts();
    },
    methods: {
      getProducts() {
        const self = this;
        const data = new FormData();
        data.append("choice", "getProd");
        data.append("search", self.search);
        axios.post("../src/router.php", data).then(function (res) {
          self.products = res.data; 
        })
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
                vm.getProducts()
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
                vm.getProducts();
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
      
},
  });
  app.mount('#items-app');
  