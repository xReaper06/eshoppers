const app = Vue.createApp({
    data() {
        return {
            orderCheckouts:[],
            profile:{
              firstname:'',
              lastname:'',
              email:'',
              phonenumber:'',
              address:'',
              zipcode:'',
              birthday:'',
              age:''
          },
          select:{
              prod_id:'',
              path:'',
              product_name:'',
              price:'',
              size:'',
              quantity:''
          }
        }
    },
    mounted() {
      const self = this;
        self.getOrders();
    },
    methods: {
        getOrders() {
            const data = new FormData();
            data.append('choice', 'allOrders');
            axios.post('../src/router.php', data)
            .then(res => {
              this.orderCheckouts = res.data;
            })
            .catch(error => {
                console.log(error);
            });
        },
        itemship(order){
          const data = new FormData();
          data.append('choice', 'ship');
          data.append('check_id', order.checkout_id);
          axios.post("../src/router.php",data).then(res=>{
            if(res.data == "200"){
              swal.fire({
                title: "Success!",
                text: "Order has been shipped.",
                icon: "success",
                button: "OK",
              }).then(() => {
                this.getOrders();
              });
            }else{
              swal.fire({
                title: "Error!",
                text: "There was an error while shipping the order. Please try again later.",
                icon: "error",
                button: "OK",
              });
            }
            }).catch(error => {
              console.log(error);
            });
      },
      viewProf(order){
        const vm = this;
        vm.profile.firstname = order.firstname
        vm.profile.lastname = order.lastname
        vm.profile.birthday = order.birthday
        vm.profile.age = order.age
        vm.profile.email = order.email
        vm.profile.address = order.address
        vm.profile.zipcode = order.zipcode
        vm.profile.phonenumber = order.phonenumber

        $('#modalProfile').modal('show')
    },
    viewItem(selected){
        const vm = this;
        vm.select.path = selected.path
        vm.select.prod_id = selected.prod_id
        vm.select.product_name = selected.product_name
        vm.select.price = selected.price
        vm.select.size = selected.size
        vm.select.quantity = selected.quantity
        $('#modalItems').modal('show')
    }
    },
});
app.mount('#order-app');