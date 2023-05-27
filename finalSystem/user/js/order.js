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
            this.getOrders();
        },
    methods: {
        getOrders() {
            const data = new FormData();
            data.append('choice', 'ordersCheckout');
            axios.post('../src/router.php', data)
            .then(res => {
              this.orderCheckouts = res.data;
            })
            .catch(error => {
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