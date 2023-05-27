<?php include"./includes/header.php";
$app = "<script src='./js/income.js'></script>"?>
<?php include"./navigation.php"?>
<div id="income-app">
<div>
  <div class="card mt-4 m-3">
    <div class="card-body">
      <h4 class="card-title">Income</h4>
      <div class="container my-4">
        <div class="row mb-5">
          <div class="col-12">
            <h3 class="mb-3"><i class="bi bi-calendar3"></i> Select Interval</h3>
            <div class="form">
              <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-calendar2-date"></i></span>
                <input type="date" name="interval" id="date" v-model="interval" required pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" class="form-control">
                <label for="date" class="form-label">Date</label>
              </div>
              <button type="button" @click.prevent="getIncome" class="btn btn-primary"><i class="bi bi-graph-up"></i> Show Income</button>
            </div>
            <p v-for="months in daily" class="fs-5"><i class="bi bi-calendar2-week"></i> Total Income: Date:{{ months.created_at }} ₱ {{ months.total_income }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label><i class="bi bi-calendar"></i> Select a Month:</label>
            <select class="form-select" v-model="selectedMonth" @click="getTotalIncome">
              <option :value="1">January</option>
              <option :value="2">February</option>
              <option :value="3">March</option>
              <option :value="4">April</option>
              <option :value="5">May</option>
              <option :value="6">June</option>
              <option :value="7">July</option>
              <option :value="8">August</option>
              <option :value="9">September</option>
              <option :value="10">October</option>
              <option :value="11">November</option>
              <option :value="12">December</option>
            </select>
          </div>
        </div>
        <div class="row" v-for="incomes in income" :key="incomes.total_income">
          <div class="col-md-6 mb-3">
            <p><i class="bi bi-currency-dollar"></i> Total Income for {{ selectedMonth }}: ₱ {{ incomes.total_income }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</div>
<?php include"./includes/footer.php"; ?>