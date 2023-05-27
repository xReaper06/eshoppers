const app = Vue.createApp({
    data() {
        return {
            ordersProd: [],
            selectedProducts: [],
            users:[],
            transactions:[],
            confirm:{
              email:'',
              firstname:'',
              lastname:'',
              phonenumber:'',

            },
        }
    },
    computed: {
        totalPrice() {
            let total = 0;
            this.selectedProducts.forEach((product) => {
              total += parseInt(product.price) * parseInt(product.quantity);
            });
            return total;
          },
      },
    mounted() {
        this.getOrders();
        this.getuserID();
    },
    methods: {
      getuserID(){
        const vm = this;
        const data = new FormData();
        data.append('choice', 'getuserID');
        axios.post('../src/router.php', data)
        .then(res =>{
          vm.users = res.data;
        })
      },
        getOrders() {
          const vm = this;
            const data = new FormData();
            data.append('choice', 'callCart');
            axios.post('../src/router.php', data)
            .then(res => {
                vm.ordersProd = res.data;
            })
            .catch(error => {
                console.log(error);
            });
        },
        checkingout() {
            $('#checkout').modal('show');
        },
        
        
        checkoutGcash(user) {
          const vm = this;
          const selectedProductsData = vm.selectedProducts.map((product) => {
              return {
                prod_id: product.prod_id,
                path:product.path,
                product_name: product.product_name,
                size: product.inputSize,
                price: product.price,
                quantity: product.quantity
              };
          });
            const data = new FormData();
            data.append("choice", 'checkoutGcash');
            data.append('selectedProducts', JSON.stringify(selectedProductsData));
            data.append("totalPrice", vm.totalPrice);
          axios.post("../src/router.php", data)
            .then(res => {
              vm.transactions = res.data;
              console.log(res.data)
              $('#gcashValidationModal').modal('show');
              vm.confirm.id = user.id;
              vm.confirm.email = user.email;
              vm.confirm.firstname = user.firstname;
              vm.confirm.lastname = user.lastname;
              vm.confirm.phonenumber = user.phonenumber;
            })
            .catch(error => {
              console.error(error);
              // handle error here
            })
        },
        validation(transaction){
          const vm = this;
          const data = new FormData();
          data.append("choice",'validation');
          data.append("transaction_id",transaction.id)
          data.append("amount",transaction.attributes.amount)
          data.append("confirmation_code",transaction.attributes.client_key)
          data.append('user_id',vm.confirm.id)
          data.append('email',vm.confirm.email)
          data.append('firstname',vm.confirm.firstname)
          data.append('lastname',vm.confirm.lastname)
          data.append('phonenumber',vm.confirm.phonenumber)
          axios.post("../src/router.php",data).then(res=>{
            if(res.data=="200"){
              Swal.fire({
                icon: 'success',
                title: 'Order Placement successful',
                showConfirmButton: false,
                timer: 1500
              })
              $('#gcashValidationModal').modal('hide');
              $('#checkoutMethod').modal('hide')
              window.location.href="./order.php";
            }else{
              Swal.fire({
                icon: 'error',
                title: 'Checkout error',
                text: 'Your order has been Error!',
              })
            }
          })
        },        
        checkoutCOD() {
          const vm = this;
          const selectedProductsData = vm.selectedProducts.map((product) => {
            return {
              prod_id: product.prod_id,
              path:product.path,
              product_name: product.product_name,
              size: product.inputSize,
              price: product.price,
              quantity: product.quantity
            };
          });
            const data = new FormData();
            data.append('choice', 'checkout');
            data.append('selectedProducts', JSON.stringify(selectedProductsData));
            data.append('total_price', vm.totalPrice);
            axios.post('../src/router.php', data)
              .then(function(response) {
                if (response.data == "200") {
                  Swal.fire({
                    icon: 'success',
                    title: 'Order Placement Successful',
                    showConfirmButton: false,
                    timer: 1500
                  })
                  $('#checkout').modal('hide');
                  $('#checkoutMethod').modal('hide')
                  window.location.href="./order.php";
                }
              })
              .catch(function(error) {
                console.log(error);
              });
        },
        
        removeProd(ordersProd) {
          const vm = this;
          const data = new FormData();
          data.append('choice', 'removeCart');
          data.append('id', ordersProd.id);
          axios.post('../src/router.php', data)
            .then(function(res) {
              if (res.data == '200') {
                Swal.fire({
                  icon: 'success',
                  title: 'Product Removed',
                  text: 'The product has been removed from your cart!',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'OK'
                }).then((result) => {
                  if (result.isConfirmed) {
                    vm.getOrders();
                  }
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Something went wrong! Please try again later.',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'OK'
                });
              }
            })
            .catch((error) => {
              console.log(error);
            });
        },
        
       
    },
    created() {
        this.getOrders();
    }
});

app.mount('#addtocart-app');