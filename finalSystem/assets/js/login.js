const app = Vue.createApp({
    data() {
      return {
          username:'',
          showPasswordIcon: 'bi bi-eye',
          password: '',
          register:{
            firstName: "",
          lastName: "",
          username: "",
          email: "",
          password: "",
          confirmpassword:"",
          passwordError: '',
          gender: "",
          birthday: "",
          age: "",
          address: "",
          zipcode: "",
          phoneNumber: "",
          }
      };
    },
    methods: {
      showPassword() {
        const passwordInput = document.querySelector('.password');
        const showPasswordIcon = document.querySelector('.showpassword i');
    
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          showPasswordIcon.classList.remove('bi-eye');
          showPasswordIcon.classList.add('bi-eye-slash');
        } else {
          passwordInput.type = 'password';
          showPasswordIcon.classList.remove('bi-eye-slash');
          showPasswordIcon.classList.add('bi-eye');
        }
      },
      calculateAge() {
        let birthDate = new Date(this.register.birthday);
        let today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();
  
        if (
          months < 0 ||
          (months === 0 && today.getDate() < birthDate.getDate())
        ) {
          age--;
        }
  
        this.register.age = age;
      },
      validatePassword() {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
        if (!regex.test(this.register.password)) {
          this.register.passwordError = 'Password must have at least 1 uppercase letter, 1 lowercase letter, 1 number and a minimum of 8 characters';
        } else {
          this.register.passwordError = '';
        }
      },
      registerUser(){
        const vm = this;
        if(vm.register.password === vm.register.confirmpassword){
          const formData = new FormData();
          formData.append("choice",'registerClient');
          formData.append("firstname",vm.register.firstName);
          formData.append("lastname",vm.register.lastName);
          formData.append("username",vm.register.username);
          formData.append("email",vm.register.email);
          formData.append("password",vm.register.password);
          formData.append("gender",vm.register.gender);
          formData.append("birthday",vm.register.birthday);
          formData.append("age",vm.register.age);
          formData.append("address",vm.register.address);
          formData.append("zipcode",vm.register.zipcode);
          formData.append("phonenumber",vm.register.phoneNumber);
          axios.post("./src/router.php",formData).then(function(res){
            if(res.data == "200"){
              Swal.fire({
                icon: 'success',
                title: 'Account Registered Successfully',
                timer: 2000,
                showConfirmButton: false
              }).then(function(){
                window.location.href="./";
              });
            }else{
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
              });
            }
          })
        }else{
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Password does not match!',
          });
        }
      },
      handlelogin(event) {
        event.preventDefault();
        const data = new FormData();
        data.append("choice", 'login');
        data.append("username",this.username);
        data.append("password",this.password);
        axios.post("./src/router.php", data)
          .then(function(res){
            if(res.data=="user"){
              Swal.fire({
                icon: 'success',
                title: 'Welcome  '+ res.data,
                showConfirmButton: false,
                timer: 2000
              })
              .then(() => window.location.href="./user/");
            }else if (res.data =="employee") {
              Swal.fire({
                icon: 'success',
                title: 'Welcome  '+ res.data,
                showConfirmButton: false,
                timer: 2000
              })
              .then(() => window.location.href="./employee/");
            }else if (res.data =="admin") {
              Swal.fire({
                icon: 'success',
                title: 'Welcome  '+ res.data,
                showConfirmButton: false,
                timer: 2000
              })
              .then(() => window.location.href="./admin/");
            }else{
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'User is not registered'
              });
            }
          })
    },
    
      regloc(){
        window.location.href="./register.php";
      },
    },
  });
  
  app.mount("#login-app");
  