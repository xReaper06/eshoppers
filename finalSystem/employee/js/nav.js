const app2 = Vue.createApp({
    data(){
        return{
            selectedIndex: null,
            update:{
                firstname:"",
                lastname:"",
                address:"",
                zipcode:"",
                phoneNumber:"",
            },
            changePass:{
                password:"",
                confirmpassword:"",
            },

        }
    },
    mounted() {
        const links = document.querySelectorAll('.nav-link');
        links.forEach((link, i) => {
          if (link.href === window.location.href) {
            this.selectedIndex = i;
            link.classList.add('clicked');
          }
        });
      },
    methods: {
        changeColor(index) {
            this.selectedIndex = index;
            const links = document.querySelectorAll('.nav-link');
            links.forEach((link, i) => {
              if (i === index) {
                link.classList.add('clicked');
              } else {
                link.classList.remove('clicked');
              }
            });
          },
        UpdateProfile(){
            const vm = this;
            const formdata = new FormData();
            formdata.append("choice",'updateProfile');
            formdata.append("firstname",vm.update.firstname);
            formdata.append("lastname",vm.update.lastname);
            formdata.append("address",vm.update.address);
            formdata.append("zipcode",vm.update.zipcode);
            formdata.append("phonenumber",vm.update.phoneNumber);
            axios.post("../src/router.php",formdata).then(function (res) {
                if (res.data =="200") {
                    Swal.fire({
                        title: 'Profile Updated Successfully you need to log in again',
                        icon: 'success',
                        timer: 2000
                    }).then(() => {
                        $('#updateProfile').modal('hide');
                        vm.logout();
                    });
                }else{
                    Swal.fire({
                        title: 'Error',
                        text: res.data,
                        icon: 'error'
                    });
                }
            });
        },
        
        changePassword(){
            const vm = this;
            if (vm.changePass.password === vm.changePass.confirmpassword) {
                const formdata = new FormData();
                formdata.append("choice",'changePass');
                formdata.append("password",vm.changePass.password);
                axios.post('../src/router.php',formdata).then(function(res){
                    if(res.data=="200"){
                        Swal.fire({
                            title: 'Change Password Successfully You need to login again',
                            icon: 'success',
                            timer: 2000
                        }).then(() => {
                            $('#changePass').modal('hide');
                            vm.logout()
                        });
                    }else{
                        Swal.fire({
                            title: 'Error',
                            text: res.data,
                            icon: 'error'
                        });
                    }
                });
            }else{
                Swal.fire({
                    title: 'Password Doesn\'t Match',
                    icon: 'warning'
                });
            }
        },
    
    logout(){
        const vm = this;
        const data = new FormData();
        data.append("choice",'logout');
        // Send logout request to server
        axios.post('../src/router.php',data)
            .then(function(res){
                if (res.data == '200') {
                    Swal.fire({
                        title: 'Logging out Successfully',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href="../";
                    });
                }
                // Show success message
            })
            .catch(error => {
                // Show error message
                Swal.fire({
                    title: 'Error',
                    text: error,
                    icon: 'error'
                });
            });
    },
    
        },
});
app2.mount('#empNav-app')