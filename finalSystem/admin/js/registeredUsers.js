const app = Vue.createApp({
data() {
    return {
        users:[],
        onlineUsers:[],
    }
},
mounted() {
  const self = this;
  $('#mytable').DataTable({
    data:[],
    columns:[
      { title: 'User ID', data: 'id' },
      { title: 'First Name', data: 'firstname' },
      { title: 'last Name', data: 'lastname' },
      { title: 'User Name', data: 'username' },
      { title: 'Email', data: 'email' },
      { title: 'Role', data: 'role' },
      { title: 'Gender', data: 'gender' },
      { title: 'Birthday', data: 'birthday' },
      { title: 'Age', data: 'age' },
      { title: 'Address', data: 'address' },
      { title: 'Zip Code', data: 'zipcode' },
      { title: 'Phone Number', data: 'phonenumber' },
      { title: 'User Status', data: 'user_status' },
      {
        title: 'Action',
        data: null,
        render: function(data, type, full, meta) {
          return `<button class='btn btn-danger Delete-btn' value='${full.id}'><i class='fas fa-trash'></i></button>`;
        }
      },
    ]
  });
  $(document).ready(function() {
    $('#mytable').on('click', '.Delete-btn', function() {
      const userID = $(this).val(); // get the checkout_id value from the clicked button
      swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this item!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete.isConfirmed) {
          // Send a DELETE request to the Laravel backend
          axios.delete(`http://localhost:8000/api/users/${userID}/delete`)
            .then((response) => {
              swal.fire({
                title: "Deleted!",
                text: response.data.message,
                icon: "success",
              });
              location.reload()
            })
            .catch(error => {
              swal.fire({
                title: "Error!",
                text: error.response.data.message,
                icon: "error",
              });
              console.log(error);
            });
        }
      });
    });
  });
    self.getUsers();
    this.getUserOnline();
},
methods: {
    getUserOnline() {
        const data = new FormData();
        data.append("choice", "getOnline");
        axios.post("../src/router.php", data)
          .then(response => {
            // Assign the response to a variable instead of overwriting the function
            this.onlineUsers = response.data;
          })
          .catch(error => {
            console.log(error);
          });
      },
    getUsers(){
        axios.get("http://localhost:8000/api/users").then(response => {            
          $('#mytable').DataTable().rows.add(response.data.users).draw();   
        }).catch(error =>{
            console.log(error);
        })
    },
      
    },
});
app.mount('#register-app');