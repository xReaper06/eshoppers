const app = Vue.createApp({
    data() {
        return {
          product: {
            name: '',
            size: '',
            price: '',
            quantity: '',
          },
        };
      },
      methods: {
        addprod(e) {
          e.preventDefault();
          var form = e.currentTarget;
          const data = new FormData(form);
          data.append('choice', 'addproduct');
          axios.post('../src/router.php',data)
            .then(function (res) {
              if (res.data == '200') {
                alert('Product Added Successfully');
                location.reload();
              } else {
                alert('Error ' + res.data);
              }
            }).catch(error =>{
                console.log(error);
            })
        },
      },
});
app.mount('#addproduct-app');