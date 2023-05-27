<?php
require('database.php');
class backend{
    public function doLogin($user,$pass){
        return self::login($user,$pass);
    }
    public function doregisterClient($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum){
        return self::registerClient($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum);
    }
    public function doregisterEmployee($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum){
        return self::registerEmployee($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum);
    }
    public function doregisterOwner($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum){
        return self::registerOwner($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum);
    }
    public function doaddProduct($prodname, $size, $price, $quantity,$path){
        return self::addProduct($prodname, $size, $price, $quantity,$path);
    }
    public function doviewProduct($search){
        return self::getProduct($search);
    }
    public function dodeleteUser($user_id){
        return self::deleteUser($user_id);
    }
    public function doviewAllUsers(){
        return self::getUser();
    }
    public function dogetOnline(){
        return self::getOnline();
    }
    public function viewUser(){
        return self::viewUserProfile();
    }
    public function doupdateProfile($fname,$lname,$address,$zipcode,$phoneNum){
        return self::updateProfile($fname,$lname,$address,$zipcode,$phoneNum);
    }
    public function doupdatePass($newpass){
        return self::updatePass($newpass);
    }
    public function doupdateProd($prod_id,$size,$price,$quantity){
        return self::updateProd($prod_id,$size,$price,$quantity);
    }
    
    public function dodeleteProd($prod_id){
        return self::deleteProd($prod_id);
    }
    public function doremoveProd($prod_id){
        return self::removeProd($prod_id);
    }
    public function doAdminRemoveOrders($prod_id){
        return self::AdminRemove($prod_id);
    }
    public function doshippingProd($prod_id){
        return self::shippingProd($prod_id);
    }
    public function docancelOrders($prod_id){
        return self::AdminCancel($prod_id);
    }
    public function docallShipping(){
        return self::callShipping();
    }
    public function docallAdminSide(){
        return self::AdminSide();
    }
    public function docallAdminhistory(){
        return self::Adminhistory();
    }
    public function doDelivered($prod_id,$user_id,$total_price,$modeOfD){
        return self::delivered($prod_id,$user_id,$total_price,$modeOfD);
    }
    public function dodelUser($id){
        return self::delUser($id);
    }
    public function doupdateRole($id,$role){
        return self::updateRole($id,$role);
    }
    public function doaddtoCart($prod_id){
        return self::addToCart($prod_id);
    }
    public function docallCart(){
        return self::callCart();
    }
    public function dovalidationGcash($trans_id,$amount,$con_id,$user_id,$email,$fname,$lname,$phoneNum){
        return self::validationGcash($trans_id,$amount,$con_id,$user_id,$email,$fname,$lname,$phoneNum);
    }
    public function docheckOut($selected,$total_price){
        return self::checkOut($selected,$total_price);
    }
    public function docallOrders(){
        return self::ordersCheckout();
    }
    public function doallOrders(){
        return self::allOrders();
    }
    // public function doforgotPass($email){
    //     return self::forgotPass($email);
    // }
    public function docallOrdersHistory(){
        return self::ordersCheckoutHistory();
    }
    public function docallMonthly(){
        return self::MonthlyIncome();
    }
    public function doshowIncome($interval){
        return self::showMonthlyIncome($interval);
    }
    public function doCheckoutGcash($selected,$total_price){
        return self::checkoutGcash($selected,$total_price);
    }
    public function dogetTotalIncomeByMonth($month){
        return self::getMonthlyIncome($month);
    }
    public function dologgingOut(){
        return self::Logout();
    }
    private function validationGcash($trans_id,$amount,$con_id,$user_id,$email,$fname,$lname,$phoneNum){
        try {
            if($this->checkIfValid($_SESSION['username'],$_SESSION['password'])){
                $db = new database();
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare("call sp_saveGcashTransaction(?,?,?,?,?,?,?,?)");
                    $stmt->bindValue(1,$trans_id);
                    $stmt->bindValue(2,$amount);
                    $stmt->bindValue(3,$con_id);
                    $stmt->bindValue(4,$user_id);
                    $stmt->bindValue(5,$email);
                    $stmt->bindValue(6,$fname);
                    $stmt->bindValue(7,$lname);
                    $stmt->bindValue(8,$phoneNum);
                    $stmt->execute();
                    $result = $stmt->fetch();
                    if(!$result){
                        $db->closeConnection();
                        return "200";
                    }else{
                        $db->closeConnection();
                        return "404";
                    }
                }else {
                    return "403";
                }
            }else{
                return "403";
            }

        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function checkoutGcash($selected,$total_price){
        try {
            if($this->checkIfValid($_SESSION['username'],$_SESSION['password'])){
                $db = new database();
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://api.paymongo.com/v1/payment_intents",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                        "data" => [
                            "attributes" => [
                                "amount" => $total_price * 100, // convert to cents
                                "payment_method_allowed" => ["gcash"],
                                "currency" => "PHP",
                                "capture_type" => "automatic"
                                ],
                            ]
                    ]),
                    CURLOPT_HTTPHEADER => [
                        "accept: application/json",
                        "authorization: Basic " . base64_encode("sk_test_mcAkE1Mb2ivVbY3qHhrSpotR:"),
                        "content-type: application/json"
                    ],
                ]);
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                
                if ($err) {
                    return "404";
                } else {
                    if ($response) {
                        if ($db->getStatus()) {
                            $stmt1 = $db->getCon()->prepare("CALL sp_checkOutGCash(?,?,?)");
                            $stmt1->bindValue(1, $this->getId());
                            $stmt1->bindValue(2, $selected);
                            $stmt1->bindValue(3, $total_price);
                            $stmt1->execute();
                            $stmt1->closeCursor();
                            
                            foreach (json_decode($selected) as $product) {
                                $user_id = $this->getId();
                                $prod_id = $product->prod_id;
                                $quantity = $product->quantity;
                                $stmt2 = $db->getCon()->prepare("CALL sp_afterCheckout(?,?,?)");
                                $stmt2->bindValue(1, $user_id);
                                $stmt2->bindValue(2, $prod_id);
                                $stmt2->bindValue(3, $quantity);             
                                $stmt2->execute();
                                $result = $stmt2->fetch();
                                $stmt2->closeCursor();
                                if (!$result) {
                                    // continue to the next product
                                    continue;
                                } else {
                                    // exit the loop and return an error message
                                    $db->closeConnection();
                                    return "404";
                                }
                            }
                            // if all products have been processed successfully, return a success message
                            $db->closeConnection();
                            return json_encode($response);
                        
                        }                            
                        } else {
                            return "404"; // payment_intent creation failed or response format is invalid
                        }
                }                    
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    
    
    private function Logout(){
        try {
            if($this->checkIfValid($_SESSION['username'],$_SESSION['password'])){
                $db = new database();
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare("call sp_logout(?)");
                    $stmt->bindValue(1,$this->getId());
                    $stmt->execute();
                    $result = $stmt->fetch();
                    if(!$result){
                        $db->closeConnection();
                        session_unset();
                        session_destroy();
                        return "200";
                    }else{
                        $db->closeConnection();
                        return "404";
                    }
                }else {
                    return "403";
                }
            }else{
                return "403";

            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    
    private function checkOut($selected,$total_price){
        try{
        try {
            $tmp_u = $_SESSION['username'];
            $tmp_p = $_SESSION['password'];
            if ($this->checkIfValid($tmp_u,$tmp_p)) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt1 = $db->getCon()->prepare("CALL sp_checkOut(?,?,?)");
                    $stmt1->bindValue(1, $this->getId());
                    $stmt1->bindValue(2, $selected);
                    $stmt1->bindValue(3, $total_price);
                    $stmt1->execute();
                    $stmt1->closeCursor();
                    
                    $decoded = json_decode($selected);
                    foreach ($decoded as $product) {
                            $user_id = $this->getId();
                            $prod_id = $product->prod_id;
                            $quantity = $product->quantity;
                            $stmt2 = $db->getCon()->prepare("CALL sp_afterCheckout(?,?,?)");
                            $stmt2->bindValue(1, $user_id);
                            $stmt2->bindValue(2, $prod_id);
                            $stmt2->bindValue(3, $quantity);             
                            $stmt2->execute();
                            $result = $stmt2->fetch();
                            $stmt2->closeCursor();
                            if (!$result) {
                                // continue to the next product
                                continue;
                            } else {
                                // exit the loop and return an error message
                                $db->closeConnection();
                                return "404";
                            }
                    }
                    // if all products have been processed successfully, return a success message
                    $db->closeConnection();
                    return "200";
                    
                
                }else{
                    $db->closeConnection();
                    return "404";
                }
            }else{
                return "403";
            }
        
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    } catch (PDOException $th) {
        return $th->getMessage();
    }
}

    private function login($user, $pass) {
        try {
            if ($this->checkIfValid($user, $pass)) {
                $db = new Database();
                if ($db->getStatus()) {
                    $tmp = md5($pass);
                    $stmt = $db->getCon()->prepare("CALL sp_loginUser(:user, :pass)");
                    $stmt->execute([':user' => $user, ':pass' => $tmp]);
                    $result = $stmt->fetch();
                    if ($result) {
                        $_SESSION['firstname'] = $result['firstname'];
                        $_SESSION['lastname'] = $result['lastname'];
                        $_SESSION['email'] = $result['email'];
                        $_SESSION['user_status'] = $result['user_status'];
                        $_SESSION['role'] = $result['role'];
                        if ($result['role'] == 1) {
                            $_SESSION['username'] = $user;
                        $_SESSION['password'] = $tmp;
                            $db->closeConnection();
                            return "user";
                        } elseif ($result['role'] == 2) {
                            $_SESSION['username'] = $user;
                        $_SESSION['password'] = $tmp;
                            $db->closeConnection();
                            return "employee";
                        } elseif ($result['role'] == 3) {
                            $_SESSION['username'] = $user;
                        $_SESSION['password'] = $tmp;
                            $db->closeConnection();
                            return "admin";
                        }
                    } else {
                        $db->closeConnection();
                        return "404";
                    }
                } else {
                    $db->closeConnection();
                    return "403";
                }
            }else{
                return"403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    

    
    private function registerOwner($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum){
        try {
            if ($this->checkIfValidReg($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum)) {
                $db = new database();
                if ($db->getStatus()) {
                    $tmp = md5($pass);
                    $stmt = $db->getCon()->prepare("call sp_saveUser(:firstname,:lastname,:user,:email,:pass,:role,:gender,:birthday,:age,:address,:zipcode,:phonenumber)");
                    $stmt->execute([':firstname'=>$fname,':lastname'=>$lname,':user'=>$user,':email'=>$email,':pass'=>$tmp,':role'=>3,':gender'=>$gender,':birthday'=>$bday,':age'=>$age,':address'=>$add,':zipcode'=>$zip,':phonenumber'=>$phoneNum]);
                  $result = $stmt->fetch();
                    if (!$result) {
                        $db->closeConnection();
                        return "200";
                    }else{
                        $db->closeConnection();
                        return "404";
                    }
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
        private function registerEmployee($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum){
            try {
                if ($this->checkIfValidReg($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum)) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $tmp = md5($pass);
                        $stmt = $db->getCon()->prepare("call sp_saveUser(:firstname,:lastname,:user,:email,:pass,:role,:gender,:birthday,:age,:address,:zipcode,:phonenumber)");
                        $stmt->execute([':firstname'=>$fname,':lastname'=>$lname,':user'=>$user,':email'=>$email,':pass'=>$tmp,':role'=>2,':gender'=>$gender,':birthday'=>$bday,':age'=>$age,':address'=>$add,':zipcode'=>$zip,':phonenumber'=>$phoneNum]);
                      $result = $stmt->fetch();
                        if (!$result) {
                            $db->closeConnection();
                            return "200";
                        }else{
                            $db->closeConnection();
                            return "404";
                        }
                    }else{
                        return "403";
                    }
                } else {
                    return "403";
                }
            } catch (PDOException $th) {
                return $th->getMessage();
            }
    }

            private function registerClient($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum){
                try {
                    if ($this->checkIfValidReg($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum)) {
                        $db = new database();
                        if ($db->getStatus()) {
                            $tmp = md5($pass);
                            $stmt = $db->getCon()->prepare("call sp_saveUser(:firstname,:lastname,:user,:email,:pass,:role,:gender,:birthday,:age,:address,:zipcode,:phonenumber)");
                            $stmt->execute([':firstname'=>$fname,':lastname'=>$lname,':user'=>$user,':email'=>$email,':pass'=>$tmp,':role'=>1,':gender'=>$gender,':birthday'=>$bday,':age'=>$age,':address'=>$add,':zipcode'=>$zip,':phonenumber'=>$phoneNum]);
                          $result = $stmt->fetch();
                            if (!$result) {
                                $db->closeConnection();
                                return "200";
                            }else{
                                $db->closeConnection();
                                return "404";
                            }
                        }else{
                            return "403";
                        }
                    } else {
                        return "403";
                    }
                } catch (PDOException $th) {
                    return $th->getMessage();
                }
            }
    private function deleteUser($user_id) {
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new Database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare('CALL sp_deleteUser(:user_id)');
                    $stmt->execute([':user_id' => $user_id]);
                    $result = $stmt->rowCount();
                    $db->closeConnection();
                    if ($result == 0) {
                        return "404";
                    } else {
                        return "200";
                    }
                } else {
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function deleteProd($prod_id) {
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new Database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare('CALL sp_deleteProd(:prod_id)');
                    $stmt->execute([':prod_id' => $prod_id]);
                    $result = $stmt->rowCount();
                    $db->closeConnection();
                    if ($result == 0) {
                        return "404";
                    } else {
                        return "200";
                    }
                } else {
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return "501";
        }
    }
    private function removeProd($prod_id) {
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new Database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare('CALL sp_removeProd(:prod_id)');
                    $stmt->execute([':prod_id' => $prod_id]);
                    $result = $stmt->rowCount();
                    $db->closeConnection();
                    if ($result == 0) {
                        return "404";
                    } else {
                        return "200";
                    }
                } else {
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return "501";
        }
    }
    // private function forgotPass($email) {
    //     try {
    //         $db = new Database();
    //         if (!$db->getStatus()) {
    //             return "404"; // Database connection error
    //         }
    
    //         // SMTP settings
    //         $smtp_server = 'smtp.gmail.com';
    //         $smtp_port = 587;
    //         $smtp_username = 'sia3a.group2@gmail.com';
    //         $smtp_password = 'P@ssword345';
    //         $smtp_encryption = 'tls';
    
    //         // Initialize PHPMailer
    //         $mail = new PHPMailer();
    //         $mail->isSMTP();
    //         $mail->Host = $smtp_server;
    //         $mail->SMTPAuth = true;
    //         $mail->Username = $smtp_username;
    //         $mail->Password = $smtp_password;
    //         $mail->SMTPSecure = $smtp_encryption;
    //         $mail->Port = $smtp_port;
    
    //         // Call stored procedure to generate token
    //         $stmt = $db->getCon()->prepare("CALL sp_generateToken(:email, @token); SELECT @token AS token;");
    //         $stmt->bindParam(':email', $email);
    //         $stmt->execute();
    //         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //         $token = $result[0]['token'];
    
    //         // Set email parameters
    //         $to = $email;
    //         $subject = 'Reset Your Password';
    //         $message = 'Click the following link to reset your password: http://example.com/reset_password.php?token=' . $token;
    //         $mail->setFrom('sia3a.group2@gmail.com');
    //         $mail->addAddress($to);
    //         $mail->Subject = $subject;
    //         $mail->Body = $message;
    
    //         // Send email
    //         if ($mail->send()) {
    //             $db->closeConnection();
    //             return "200"; // Success
    //         } else {
    //             $db->closeConnection();
    //             return "500"; // Email sending error
    //         }
    //     } catch (PDOException $th) {
    //         return $th->getMessage();
    //     }
    // }
    
    private function AdminRemove($prod_id) {
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new Database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare('CALL sp_adminRemove(:prod_id)');
                    $stmt->execute([':prod_id' => $prod_id]);
                    $result = $stmt->rowCount();
                    $db->closeConnection();
                    if ($result == 0) {
                        return "404";
                    } else {
                        return "200";
                    }
                } else {
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return "501";
        }
    }

    
    private function addProduct($prodname, $size, $price, $quantity,$path) {
        try {
            try{
                $tmp_u = $_SESSION['username'];
                $tmp_p = $_SESSION['password'];
                if ($this->checkIfValid($tmp_u,$tmp_p)) {
                  $db = new database();
                  if ($db->getStatus()) {
                      $folder = 'uploads/';
                      $destination = $folder.$path;
                      move_uploaded_file($_FILES['file']['tmp_name'],$destination);
                    $stmt = $db->getCon()->prepare("CALL sp_addProduct(:user_id,:product_name,:size,:price,:quantity,:path)");
                    $stmt->execute([':user_id'=>$this->getId(),':product_name'=>$prodname,':size'=>$size,':price'=>$price,':quantity'=>$quantity,':path'=>$path]);
                    $result = $stmt->fetchAll();
                    if (!$result) {
                      $db->closeConnection();
                      return "200";
                    } else {
                      $db->closeConnection();
                      return "404";
                    }
                  } else {
                      $db->closeConnection();
                    return "403";
                  }
                } else {
                  return "403";
                }
            }catch(PDOException $th){
                return $th->getMessage();
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
      }
      
      private function callCart(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $tmp = $this->getId();
                    $stmt = $db->getCon()->prepare("call sp_callCart(:user_id)");
                    $stmt->execute([':user_id'=>$tmp]);
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function callShipping(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $tmp = $this->getId();
                    $stmt = $db->getCon()->prepare("call sp_allShipped()");
                    $stmt->execute([]);
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function AdminSide(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $tmp = $this->getId();
                    $stmt = $db->getCon()->prepare("call sp_adminSideOrders()");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function Adminhistory(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()){
                    $stmt = $db->getCon()->prepare("call sp_adminHistory()");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function ordersCheckout(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $tmp = $this->getId();
                    $stmt = $db->getCon()->prepare("call sp_orders(:user_id)");
                    $stmt->execute([':user_id'=>$tmp]);
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function allOrders(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare("call sp_allOrders()");
                    $stmt->execute([]);
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function MonthlyIncome(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare("call sp_monthlyIncome()");
                    $stmt->execute([]);
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function showMonthlyIncome($interval){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare("call sp_showIncome(?)");
                    $stmt->bindValue(1,$interval);
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function getMonthlyIncome($month){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare("call sp_getMontlyIncome(?)");
                    $stmt->bindValue(1,$month);
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function ordersCheckoutHistory(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $tmp = $this->getId();
                    $stmt = $db->getCon()->prepare("call sp_ordersDelivered(:user_id)");
                    $stmt->execute([':user_id'=>$tmp]);
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    
    private function getProduct($search){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare("call sp_getProd(?)");
                    $stmt->bindValue(1,$search);
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function updateProd($prod_id,$size,$price,$quantity){
        try {
            try {
                $tmp_u = $_SESSION['username'];
                $tmp_p =$_SESSION['password'];
                if ($this->checkIfValid($tmp_u,$tmp_p)) {
                    $db = new database();
                    if($db->getStatus()){
                        $stmt = $db->getCon()->prepare("call sp_updateProd(?,?,?,?,now())");
                        $stmt->bindValue(1,$prod_id);
                        $stmt->bindValue(2,$size);
                        $stmt->bindValue(3,$price);
                        $stmt->bindValue(4,$quantity);
                        $stmt->execute();
                        $result = $stmt->fetch();
                        if(!$result){
                            $db->closeConnection();
                            return "200";
                        }else{
                            $db->closeConnection();
                            return "404";
                        }
                    }else{
                        return"403";
                    }
                }else{
                    return"403";
                }
            } catch (PDOException $th) {
                return $th->getMessage();
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
   
    private function Delivered($prod_id,$user_id,$total_price,$modeOfD){
        try {
            try {
                $tmp_u = $_SESSION['username'];
                    $tmp_p = $_SESSION['password'];
                    if ($this->checkIfValid($tmp_u,$tmp_p)) {
                        $db = new database();
                        if ($db->getStatus()) {
                            $stmt = $db->getCon()->prepare("call sp_delivered(?,?,?,?)");
                            $stmt->bindValue(1,$prod_id);
                            $stmt->bindValue(2,$user_id);
                            $stmt->bindValue(3,$total_price);
                            $stmt->bindValue(4,$modeOfD);
                            $stmt->execute();
                            $result = $stmt->fetch();
                            if (!$result) {
                                $db->closeConnection();
                                return "200";
                            }else{
                                $db->closeConnection();
                                return "404";
                            }
                        }else{
                            return "403";
                        }
                    } else {
                        return "403";
                    }
            } catch (PDOException $th) {
                return $th->getMessage();
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function shippingProd($prod_id){
        try {
            try {
                $tmp_u = $_SESSION['username'];
                    $tmp_p = $_SESSION['password'];
                    if ($this->checkIfValid($tmp_u,$tmp_p)) {
                        $db = new database();
                        if ($db->getStatus()) {
                            $stmt = $db->getCon()->prepare("call sp_shipping(?)");
                            $stmt->bindValue(1,$prod_id);
                            $stmt->execute();
                            $result = $stmt->fetch();
                            if (!$result) {
                                $db->closeConnection();
                                return "200";
                            }else{
                                $db->closeConnection();
                                return "404";
                            }
                        }else{
                            return "403";
                        }
                    } else {
                        return "403";
                    }
            } catch (PDOException $th) {
                return $th->getMessage();
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function AdminCancel($prod_id){
        try {
            try {
                $tmp_u = $_SESSION['username'];
                    $tmp_p = $_SESSION['password'];
                    if ($this->checkIfValid($tmp_u,$tmp_p)) {
                        $db = new database();
                        if ($db->getStatus()) {
                            $stmt = $db->getCon()->prepare("call sp_adminCancelOrders(?)");
                            $stmt->bindValue(1,$prod_id);
                            $stmt->execute();
                            $result = $stmt->fetch();
                            if (!$result) {
                                $db->closeConnection();
                                return "200";
                            }else{
                                $db->closeConnection();
                                return "404";
                            }
                        }else{
                            return "403";
                        }
                    } else {
                        return "403";
                    }
            } catch (PDOException $th) {
                return $th->getMessage();
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function addtoCart($prod_id){
        try{
            try{
                    $tmp_u = $_SESSION['username'];
                    $tmp_p = $_SESSION['password'];
                    if ($this->checkIfValid($tmp_u,$tmp_p)) {
                        $db = new database();
                        if ($db->getStatus()) {
                            $stmt = $db->getCon()->prepare("call sp_addtoCart(?,?,?)");
                            $stmt->bindValue(1,$prod_id);
                            $stmt->bindValue(2,$this->getId());
                            $stmt->bindValue(3,"added");
                            $stmt->execute();
                            $result = $stmt->fetch();
                            if (!$result) {
                                $db->closeConnection();
                                return "200";
                            }else{
                                $db->closeConnection();
                                return "404";
                            }
                        }else{
                            return "403";
                        }
                    } else {
                        return "403";
                    }
            }catch(PDOException $th){
                return $th->getMessage();
            }
        }catch(PDOException $th){
            return $th->getMessage();
        }
    }
    private function updatePass($newpass){
            try {
                try {
                    $tmp_u = $_SESSION['username'];
                    $tmp_p =$_SESSION['password'];
                    if ($this->checkIfValid($tmp_u,$tmp_p)) {
                        $db = new database();
                        if ($db->getStatus()) {
                            $stmt = $db->getCon()->prepare("call sp_changePassword(?,?,now())");
                            $stmt->bindValue(1,$this->getId());
                            $stmt->bindValue(2,md5($newpass));
                            $stmt->execute();
                            $result = $stmt->fetch();
                            if (!$result) {
                                $db->closeConnection();
                                return "200";
                            }else{
                                $db->closeConnection();
                                return "404";
                            }
                        }else{
                            return "403";
                        }
                    } else {
                        return "403";
                    }
                } catch (PDOException $th) {
                    return $th->getMessage();
                }
            } catch (PDOException $th) {
                return $th->getMessage();
            }
    }
    private function updateProfile($fname,$lname,$address,$zipcode,$phoneNum){
        try {
            try {
                $tmp_u = $_SESSION['username'];
                $tmp_p =$_SESSION['password'];
                if ($this->checkIfValid($tmp_u,$tmp_p)) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getCon()->prepare("call sp_updateProfile(?,?,?,?,?,?)");
                        $stmt->bindValue(1,$this->getId());
                        $stmt->bindValue(2,$fname);
                        $stmt->bindValue(3,$lname);
                        $stmt->bindValue(4,$address);
                        $stmt->bindValue(5,$zipcode);
                        $stmt->bindValue(6,$phoneNum);
                        $stmt->execute();
                        $result = $stmt->fetch();
                        if (!$result) {
                            $db->closeConnection();
                            return "200";
                        }else{
                            $db->closeConnection();
                            return "404";
                        }
                    }else{
                        return "403";
                    }
                } else {
                    return "403";
                }
            } catch (PDOException $th) {
                return $th->getMessage();
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
}
private function updateRole($id,$role){
    try {
        if($this->checkIfValid($_SESSION['username'],$_SESSION['password'])){
            $db=new database();
            if($db->getStatus()){
                $stmt = $db->getCon()->prepare("Call sp_updateRole(:id,:role)");
                $stmt->execute([':id'=>$id,':role'=>$role]);
                $result = $stmt->fetch();
                if(!$result){
                    $db->closeConnection();
                    return"200";
                }else{
                    $db->closeConnection();
                    return "404";
                }
            }else{
                return"403";
            }

        }else {
            return"403";
        }

    } catch (PDOException $th) {
        return $th->getMessage();
    }
}
private function delUser($id){
    try {
        if($this->checkIfValid($_SESSION['username'],$_SESSION['password'])){
            $db=new database();
            if($db->getStatus()){
                $stmt = $db->getCon()->prepare("Call sp_delUser(:id)");
                $stmt->execute([':id'=>$id]);
                $result = $stmt->fetch();
                if($result){
                    $db->closeConnection();
                    return"200";
                }else{
                    $db->closeConnection();
                    return "404";
                }
            }else{
                return"403";
            }

        }else {
            return"403";
        }

    } catch (PDOException $th) {
        return $th->getMessage();
    }
}

    private function getUser(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare("call sp_getUsers()");
                    $stmt->execute([]);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $db->closeConnection();
                    return json_encode(array("data" => $result));
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function viewUserProfile(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare("call sp_getUserID(:id)");
                    $stmt->execute([':id'=>$this->getId()]);
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function getOnline(){
        try {
            if ($this->checkIfValid($_SESSION['username'], $_SESSION['password'])) {
                $db = new database();
                if ($db->getStatus()) {
                    $stmt = $db->getCon()->prepare("call sp_countOnline()");
                    $stmt->execute([]);
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "403";
                }
            } else {
                return "403";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    private function getId(){
        try {
            $db = new database();
            if ($db->getStatus()) {
                $stmt = $db->getCon()->prepare("call sp_loginUser(:user,:pass)");
                $stmt->execute([':user'=>$_SESSION['username'],':pass'=>$_SESSION['password']]);
                $tmp = null;
                while ($row = $stmt->fetch()) {
                    $tmp = $row['id'];
                }
                $db->closeConnection();
                return $tmp;
            }else{
                return "404";
            }
        } catch (PDOException $th) {
            return $th->getMessage();
        }        
    }
    private function checkIfValidReg($fname,$lname,$user,$email,$pass,$gender,$bday,$age,$add,$zip,$phoneNum){
        if($fname !="" && $lname !="" && $user !="" && $email !="" && $pass !="" && $gender !="" && $bday !=""  && $age !="" && $add !=""&& $zip !=""&& $phoneNum !=""){
            return true;
        }else{
            return false;
        }
    }
    private function checkIfValid($user, $pass)
    {
        if ($user != "" && $pass != "")
            return true;
        else
            return false;
    }
}
?>
