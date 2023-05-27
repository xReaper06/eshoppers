<?php include"./includes/header.php";
$app = "<script src='./js/transaction.js'></script>"?>
<?php include"./navigation.php"?>
<div class="" id="transaction-app">
<div class="card mt-3 m-4">
  <div class="card-body">
    <h4 class="card-title">Transactions</h4>
    <div class="table-responsive">
      <table id="dataTable" class="table w-100 dataTable">
        <thead>
          <tr>
            <th>Transaction ID</th>
            <th>Amount IN Cents</th>
            <th>Confirmation Code</th>
            <th>User ID</th>
            <th>Email</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Status</th>
            <th>Date of Transaction</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>
<?php include"./includes/footer.php";?>