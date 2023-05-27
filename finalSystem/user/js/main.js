const app = Vue.createApp({
    data(){
        return{
            products:[],
            search:'',
            addto:{
                id:null,
            },
        }
    },
    mounted(){
        this.getprod();
    },
    methods: {
        getprod() {
            const vm = this;
            const data = new FormData();
            data.append('choice', 'getProd');
            data.append('search',vm.search)
            axios.post('../src/router.php', data).then((response) => {
              vm.products = response.data;
            });
          },
          addtocart(product){
            const vm = this;
            $('#AddtocartModal').modal('show');
            vm.addto.id = product.prod_id;
          },
          submitCart(e) {
            const vm = this;
            e.preventDefault();
            const data = new FormData();
            data.append("choice", 'addtoCart');
            data.append("prod_id", vm.addto.id);
            
            axios.post('../src/router.php', data)
              .then(function(res) {
                if (res.data == "200") {
                  Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart Successfully',
                    showConfirmButton: false,
                    timer: 1500
                  })
                  $('#AddtocartModal').modal('hide');
                  vm.getprod();
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: 'Error in adding to cart',
                    text: res.data
                  })
                }
              })
              .catch(error => {
                console.log(error);
              })
          }
          
    },
});
app.mount('#main-app');