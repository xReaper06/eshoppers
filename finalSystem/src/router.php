<?php
session_start();
require('backend.php');
if(isset($_POST['choice'])){
    switch ($_POST['choice']) {
        case 'login':
            $backend = new backend();
            echo $backend->doLogin($_POST['username'],$_POST['password']);
            break;
        case 'registerClient':
            $backend = new backend();
            echo $backend->doregisterClient($_POST['firstname'],$_POST['lastname'],$_POST['username'],$_POST['email'],$_POST['password'],$_POST['gender'],$_POST['birthday'],$_POST['age'],$_POST['address'],$_POST['zipcode'],$_POST['phonenumber']);
            break;
        case 'registerEmployee':
            $backend = new backend();
            echo $backend->doregisterEmployee($_POST['firstname'],$_POST['lastname'],$_POST['username'],$_POST['email'],$_POST['password'],$_POST['gender'],$_POST['birthday'],$_POST['age'],$_POST['address'],$_POST['zipcode'],$_POST['phonenumber']);
            break;
        case 'registerAdmin':
            $backend = new backend();
            echo $backend->doregisterOwner($_POST['firstname'],$_POST['lastname'],$_POST['username'],$_POST['email'],$_POST['password'],$_POST['gender'],$_POST['birthday'],$_POST['age'],$_POST['address'],$_POST['zipcode'],$_POST['phonenumber']);
        break;
        case 'updateProfile':
            $backend = new backend();
            echo $backend->doupdateProfile($_POST['firstname'],$_POST['lastname'],$_POST['address'],$_POST['zipcode'],$_POST['phonenumber']);
            break;
        case 'changePass':
            $backend = new backend();
            echo $backend->doupdatePass($_POST['password']);
            break;
        case 'addproduct':
            $backend = new backend();
            echo $backend->doaddProduct($_POST['name'],$_POST['size'],$_POST['price'],$_POST['quantity'],$_FILES['file']['name']);
            break;
        case 'getProd':
            $backend = new backend();
            echo $backend->doviewProduct($_POST['search']);
            break;
        case 'callCart':
            $backend = new backend();
            echo $backend->docallCart();
            break;
        case 'updateProd':
            $backend = new backend();
            echo $backend->doupdateProd($_POST['prod_id'],$_POST['size'],$_POST['price'],$_POST['quantity']);
            break;
        case 'updateRole':
            $backend = new backend();
            echo $backend->doupdateRole($_POST['id'],$_POST['role']);
            break;
        case 'delUser':
            $backend = new backend();
            echo $backend->dodelUser($_POST['id']);
            break;
        case 'deleteProd':
            $backend = new backend();
            echo $backend->dodeleteProd($_POST['prod_id']);
            break;
        case 'ship':
            $backend = new backend();
            echo $backend->doshippingProd($_POST['check_id']);
            break;
        case 'removeCart':
            $backend = new backend();
            echo $backend->doremoveProd($_POST['id']);
            break;
        case 'addtoCart':
            $backend = new backend();
            echo $backend->doaddtoCart($_POST['prod_id']);
            break;
        case 'checkout':
            $backend = new backend();
            echo $backend->docheckOut($_POST['selectedProducts'],$_POST['total_price']);
            break;
        case 'ordersCheckout':
            $backend = new backend();
            echo $backend->docallOrders();
            break;
        case 'getOnline':
            $backend = new backend();
            echo $backend->dogetOnline();
            break;
        // case 'forgotPass':
        //     $backend = new backend();
        //     echo $backend->doforgotPass($_POST['email']);
        //     break;
        case 'Shipping':
            $backend = new backend();
            echo $backend->docallShipping();
            break;
        case 'AdminSide':
            $backend = new backend();
            echo $backend->docallAdminSide();
            break;
        case 'Adminhistory':
            $backend = new backend();
            echo $backend->docallAdminHistory();
            break;
        case 'getMonthlyIncome':
            $backend = new backend();
            echo $backend->docallMonthly();
            break;
        case 'showIncome':
            $backend = new backend();
            echo $backend->doshowIncome($_POST['interval']);
            break;
        case 'getTotalIncomeByMonth':
            $backend = new backend();
            echo $backend->dogetTotalIncomeByMonth($_POST['month']);
            break;
        case 'cancelOrders':
            $backend = new backend();
            echo $backend->docancelOrders($_POST['check_id']);
            break;
        case 'validation':
            $backend = new backend();
            echo $backend->dovalidationGcash($_POST['transaction_id'],$_POST['amount'],$_POST['confirmation_code'],$_POST['user_id'],$_POST['email'],$_POST['firstname'],$_POST['lastname'],$_POST['phonenumber']);
            break;
        case 'AdminRemoveOrders':
            $backend = new backend();
            echo $backend->doAdminRemoveOrders($_POST['check_id']);
            break;
        case 'delivered':
            $backend = new backend();
            echo $backend->doDelivered($_POST['check_id'],$_POST['user_id'],$_POST['total_price'],$_POST['mode_of_delivery']);
            break;
        case 'checkoutGcash':
            $backend = new backend();
            echo $backend->doCheckoutGcash($_POST['selectedProducts'],$_POST['totalPrice']);
            break;
        case 'getuserID':
            $backend = new backend();
            echo $backend->viewUser();
            break;
        case 'allOrders':
            $backend = new backend();
            echo $backend->doallOrders();
            break;
        case 'history':
            $backend = new backend();
            echo $backend->docallOrdersHistory();
            break;
        case 'logout':
            $backend = new backend();
            echo $backend->dologgingOut();
            break;
        default:
            # code...
            break;
    }
}
?>