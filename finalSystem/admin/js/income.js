const app = Vue.createApp({
    data() {
        return {
            income:[],
            daily:[],
            interval:'month',
            totalIncome: null,
            selectedMonth: '',
            monthlyIncome:null,
        }
    },
    methods: {
        getIncome() {
            const self = this;
            let formatted = moment(self.interval).format('YYYY-MM-DD');
            const data = new FormData();
            data.append("choice", "showIncome");
            data.append("interval",formatted)
            axios.post("../src/router.php", data).then(function (res) {
              self.daily = res.data;
              
            }).catch(error => {
                console.error(error);
              });
        },
        getTotalIncome() {
            const self = this;
            const data = new FormData();
            data.append("choice", "getTotalIncomeByMonth");
            data.append("month", self.selectedMonth);
            axios.post("../src/router.php", data).then(function (res) {
              self.income = res.data;
            }).catch(error => {
              console.error(error);
            });
          },
      
    },
});
app.mount('#income-app')