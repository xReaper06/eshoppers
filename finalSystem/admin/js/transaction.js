const app = Vue.createApp({
    data() {
        return {
            transactions:[],
        }
    },
    mounted() {
        $('#dataTable').DataTable({
            data:[],
            columns:[
                { title: 'Transaction ID', data: 'transaction_id' },
                { title: 'Amount IN Cents', data: 'amount' },
                { title: 'Confirmation Code', data: 'confirmation_code' },
                { title: 'User ID', data: 'user_id' },
                { title: 'Email', data: 'email' },
                { 
                    title: 'Name', 
                    data: null,
                    render: function(data, type, full, meta) {
                      return full.firstname + " " + full.lastname;
                    }
                  },
                { title: 'Phone Number', data: 'phone_number' },
                { title: 'Status', data: 'status' },
                { title: 'Date of Transaction', data: 'created_at' },           
            ]
        });
        this.getTransaction();
    },
    methods: {
        getTransaction(){
            axios.get('http://localhost:8000/api/transactions').then(response=>{
                $('#dataTable').DataTable().rows.add(response.data.transactions).draw();
            }).catch(error =>{
                console.log(error);
            })
        }
    },
});
app.mount('#transaction-app');