const app = Vue.createApp({
    data() {
        return {
          email: '',
        };
      },
      methods: {
          submitForm() {
            const data = new FormData()
            data.append("choice",'forgotPass')
            data.append("email",this.email)
          axios.post('./src/router.php',data)
            .then(response => {
              if(response.data == "200"){
                  alert('An email has been sent to your address with instructions to reset your password.');
              }
            })
            .catch(error => {
              alert('There was an error sending the email. Please try again later.'+error);
            });
        },
      },
});
app.mount('#forgot-app');