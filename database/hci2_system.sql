-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2023 at 12:44 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hci2_system`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addProduct` (IN `p_id` INT, IN `p_product_name` VARCHAR(255), IN `p_size` VARCHAR(255), IN `p_price` DECIMAL, IN `p_quantity` INT, IN `p_path` TEXT)   INSERT into product(user_id,product_name,size,price,quantity,path,created_at)VALUES(p_id,p_product_name,p_size,p_price,p_quantity,p_path,now())$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addtoCart` (IN `p_prod_id` INT, IN `p_user_id` INT, IN `p_status` VARCHAR(255))   IF EXISTS (SELECT * FROM `order` WHERE prod_id = p_prod_id) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Product ID already exists';
ELSEIF (SELECT quantity FROM product WHERE prod_id = p_prod_id) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Product is out of stock';
ELSE
    INSERT INTO `order` (prod_id, user_id, quantity, status, created_at)
    VALUES (p_prod_id, p_user_id, 1, p_status, NOW());
END IF$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_adminCancelOrders` (IN `check_id` INT)   Update `checkout` set status = "canceled" where checkout_id = check_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_adminHistory` ()   SELECT a.*,b.* from checkout a JOIN users b on a.user_id = b.id where a.status = "delivered"$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_adminRemove` (IN `check_id` INT)   Delete from checkout where checkout_id = check_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_adminSideOrders` ()   SELECT a.*, b.*
FROM checkout a 
JOIN users b ON a.user_id = b.id 
WHERE a.status IN ('checkout', 'shipping', 'canceled')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_afterCheckout` (IN `p_user_id` INT, IN `p_prod_id` INT, IN `p_quantity` INT)   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Error occurred, changes were not saved.' AS message;
    END;

    START TRANSACTION;

    DELETE FROM `order` WHERE user_id = p_user_id AND prod_id = p_prod_id;

    IF ROW_COUNT() = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Delete failed.';
    END IF;

    UPDATE product SET quantity = quantity - p_quantity WHERE prod_id = p_prod_id;

    IF ROW_COUNT() = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Update failed.';
    END IF;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_allOrders` ()   SELECT * from checkout a JOIN users b on a.user_id = b.id where a.status = "checkout"$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_allShipped` ()   SELECT * from checkout a JOIN users b on a.user_id = b.id where a.status = "shipping"$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_callCart` (IN `p_user_id` INT)   SELECT a.id,b.path,a.prod_id,b.size,b.price,b.created_at,b.product_name, a.quantity from `order` a join product b on a.prod_id = b.prod_id where a.user_id = p_user_id and a.status = "added"$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_changePassword` (IN `p_id` INT, IN `p_password` VARCHAR(255), IN `p_updated_at` TIMESTAMP)   UPDATE users set password = p_password,updated_at = p_updated_at where id = p_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_checkOut` (IN `p_user_id` INT, IN `p_selected_products` TEXT, IN `p_total_price` DECIMAL(10,2))   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Error occurred, changes were not saved.' as message;
    END;

    START TRANSACTION;
    
    IF (p_user_id IS NULL OR p_selected_products IS NULL OR p_total_price IS NULL) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'All parameters must be provided.';
    END IF;

    INSERT INTO checkout (user_id,selected_products,total_price, status, mode_of_delivery, created_at)
    VALUES (p_user_id, p_selected_products, p_total_price, "checkout", "cash on delivery", NOW());

    IF ROW_COUNT() = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Insert failed.';
    END IF;


    COMMIT;
   
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_checkOutGCash` (IN `p_user_id` INT, IN `p_selected_products` TEXT, IN `p_total_price` DECIMAL(10,2))   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Error occurred, changes were not saved.' as message;
    END;

    START TRANSACTION;
    
    IF (p_user_id IS NULL OR p_selected_products IS NULL OR p_total_price IS NULL) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'All parameters must be provided.';
    END IF;

    INSERT INTO checkout (user_id,selected_products,total_price, status, mode_of_delivery, created_at)
    VALUES (p_user_id, p_selected_products, p_total_price, "checkout", "Gcash", NOW());

    IF ROW_COUNT() = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Insert failed.';
    END IF;
    COMMIT;
   
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_countOnline` ()   SELECT COUNT(*) AS online_users
FROM users
WHERE user_status = 'online'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteProd` (IN `p_prod_id` INT)   DELETE FROM product WHERE prod_id = p_prod_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delivered` (IN `check_id` INT, IN `p_user_id` INT, IN `p_total_price` DECIMAL(10,2), IN `p_mode_of_delivery` VARCHAR(255))   BEGIN
    DECLARE continue_handler INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        SET continue_handler = 1;
    END;

    START TRANSACTION;

    IF p_mode_of_delivery = 'Gcash' THEN
        UPDATE checkout
        SET status = 'delivered', updated_at = NOW()
        WHERE checkout_id = check_id;
    ELSE
        UPDATE checkout
        SET status = 'delivered', updated_at = NOW()
        WHERE checkout_id = check_id;

        INSERT INTO monthly_income (user_id, month, total_income, created_at)
        SELECT p_user_id, NOW(), AVG(p_total_price), NOW()
        FROM checkout
        WHERE user_id = p_user_id
        GROUP BY user_id;
    END IF;

    IF continue_handler = 0 THEN
        COMMIT;
    ELSE
        ROLLBACK;
        UPDATE checkout
        SET status = 'error'
        WHERE checkout_id = check_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delUser` (IN `p_id` INT)   DELETE FROM users where id=p_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_generateToken` (IN `email` VARCHAR(255), OUT `token` VARCHAR(32))   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE new_token VARCHAR(32);
    DECLARE new_email VARCHAR(255);

    SELECT email INTO new_email FROM users u
    WHERE NOT EXISTS (
        SELECT 1 FROM password_reset_tokens f WHERE f.email = u.email
    ) LIMIT 1;

    IF new_email IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No valid email found.';
    ELSE
        -- Insert the email into the password_reset_tokens table
        INSERT INTO password_reset_tokens (email) VALUES (new_email);

        -- Generate a unique token
        REPEAT
            SET new_token = MD5(RAND());
            SELECT COUNT(*) INTO done FROM password_reset_tokens WHERE token = new_token;
        UNTIL done = 0 END REPEAT;

        -- Insert the token into the password_reset_tokens table
        UPDATE password_reset_tokens SET token = new_token, created_at = NOW() WHERE email = new_email;

        -- Return the token to the caller
        SELECT new_token AS token;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getMontlyIncome` (IN `p_interval` INT)   SELECT SUM(total_income) AS total_income 
FROM monthly_income
WHERE MONTH(created_at) = p_interval AND YEAR(created_at) = YEAR(NOW())$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getProd` (IN `p_search` VARCHAR(255))   BEGIN

    IF p_search = '' THEN
        SELECT * FROM product;
    ELSE
        SELECT * FROM product WHERE product_name LIKE CONCAT('%', p_search, '%');
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getUserID` (IN `p_id` INT)   BEGIN
select * from users where id = p_id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getUsersId` (IN `p_id` INT)   SELECT * from users where id = p_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_loginUser` (IN `p_user` VARCHAR(255), IN `p_pass` VARCHAR(255))   BEGIN
  SELECT * FROM users WHERE username = p_user AND password = p_pass;
  
  UPDATE users SET user_status = 'online' WHERE username = p_user AND password = p_pass;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_logout` (IN `p_user_id` INT)   update users set user_status = "offline" where id = p_user_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_orders` (IN `p_user_id` INT)   SELECT * from checkout a JOIN users b on a.user_id = b.id where a.user_id = p_user_id AND a.status = "checkout" or a.status = "shipped"$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ordersDelivered` (IN `p_user_id` INT)   SELECT a.*,b.* from checkout a JOIN users b on a.user_id = b.id where a.user_id = p_user_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_removeProd` (IN `p_id` INT)   DELETE FROM `order` WHERE id = p_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_saveGcashTransaction` (IN `p_transaction_id` VARCHAR(255), IN `p_amount` INT, IN `p_confirmation_code` VARCHAR(255), IN `p_user_id` INT, IN `p_email` VARCHAR(255), IN `p_firstname` VARCHAR(255), IN `p_lastname` VARCHAR(255), IN `p_phone_number` VARCHAR(255))   BEGIN
INSERT into transactions(transaction_id,amount,confirmation_code,user_id,email,firstname,lastname,phone_number,status,created_at)Values(p_transaction_id,p_amount,p_confirmation_code,p_user_id,p_email,p_firstname,p_lastname,p_phone_number,"validated",now());

INSERT INTO monthly_income (user_id, month, total_income, created_at)
    SELECT p_user_id, NOW(), p_amount/100, NOW()
    FROM checkout
    WHERE user_id = p_user_id
    GROUP BY user_id, NOW();
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_saveUser` (IN `pFirstName` VARCHAR(255), IN `pLastName` VARCHAR(255), IN `pUserName` VARCHAR(255), IN `pEmail` VARCHAR(255), IN `pPassword` VARCHAR(255), IN `pRole` INT, IN `pGender` VARCHAR(255), IN `pBirthday` VARCHAR(255), IN `pAge` VARCHAR(255), IN `pAddress` VARCHAR(100), IN `pZipCode` VARCHAR(255), IN `pPhoneNumber` VARCHAR(255))   BEGIN
    INSERT INTO users (
        firstname,
        lastname,
        username,
        email,
        password,
        role,
        gender,
        birthday,
        age,
        address,
        zipcode,
        phonenumber,
user_status,
        created_at
    ) VALUES (
        pFirstName,
        pLastName,
        pUserName,
        pEmail,
        pPassword,
        pRole,
        pGender,
        pBirthday,
        pAge,
        pAddress,
        pZipCode,
        pPhoneNumber,
"newly Registered",
        NOW()
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_shipping` (IN `check_id` INT)   UPDATE `checkout` set status = "shipping" where checkout_id = check_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_showIncome` (IN `p_interval` VARCHAR(10))   SELECT SUM(total_income) AS total_income,created_at
FROM monthly_income
WHERE month = p_interval$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateProd` (IN `p_prod_id` INT, IN `p_size` VARCHAR(255), IN `p_price` DECIMAL, IN `p_quantity` INT, IN `p_updated_at` TIMESTAMP)   UPDATE product SET size = p_size, price = p_price, quantity = p_quantity, updated_at = p_updated_at where prod_id = p_prod_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateProfile` (IN `p_id` INT, IN `p_firstname` VARCHAR(255), IN `p_lastname` VARCHAR(255), IN `p_address` VARCHAR(255), IN `p_zipcode` VARCHAR(255), IN `p_phonenumber` VARCHAR(255))   UPDATE users set firstname = p_firstname, lastname = p_lastname,address = p_address,zipcode = p_zipcode,phonenumber = p_phonenumber ,updated_at = now() where id = p_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateRole` (IN `p_id` INT, IN `p_role` INT)   UPDATE users SET role = p_role, updated_at = now() where id = p_id$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

CREATE TABLE `checkout` (
  `checkout_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selected_products` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode_of_delivery` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checkout`
--

INSERT INTO `checkout` (`checkout_id`, `user_id`, `selected_products`, `quantity`, `total_price`, `status`, `mode_of_delivery`, `created_at`, `updated_at`) VALUES
(316, 12, '[{\"prod_id\":12,\"product_name\":\"white Tshirt\",\"price\":\"100.00\",\"quantity\":1}]', 0, '100.00', 'delivered', 'cash on delivery', '2023-05-24', '2023-05-24'),
(317, 12, '[{\"prod_id\":13,\"product_name\":\"nike Red shoes\",\"size\":\"55\",\"price\":\"999.00\",\"quantity\":1}]', 0, '999.00', 'delivered', 'cash on delivery', '2023-05-24', '2023-05-24'),
(318, 12, '[{\"prod_id\":14,\"product_name\":\"yellow short\",\"size\":\"M\",\"price\":\"199.00\",\"quantity\":1}]', 0, '199.00', 'shipping', 'cash on delivery', '2023-05-24', NULL),
(319, 12, '[{\"prod_id\":12,\"product_name\":\"white Tshirt\",\"size\":\"M\",\"price\":\"100.00\",\"quantity\":1}]', 0, '100.00', 'checkout', 'cash on delivery', '2023-05-24', NULL),
(320, 12, '[{\"prod_id\":12,\"path\":\"shirt-1.jpg\",\"product_name\":\"white Tshirt\",\"size\":\"M\",\"price\":\"100.00\",\"quantity\":1}]', 0, '100.00', 'checkout', 'cash on delivery', '2023-05-24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_04_26_163818_product', 2),
(6, '2023_04_29_150859_order', 3),
(7, '2023_05_03_152429_update_order', 4),
(8, '2023_05_04_165241_checkout', 5),
(9, '2023_05_04_175026_update_checkout', 6),
(10, '2023_05_04_185507_mode_of_delivery', 7),
(11, '2023_05_04_213241_monthly_income', 8),
(12, '2023_05_12_230044_transactions', 9);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_income`
--

CREATE TABLE `monthly_income` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `month` date NOT NULL,
  `total_income` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `monthly_income`
--

INSERT INTO `monthly_income` (`id`, `user_id`, `month`, `total_income`, `created_at`, `updated_at`) VALUES
(370, 12, '2023-05-18', '3094.00', '2023-05-18 11:58:00', NULL),
(371, 12, '2023-05-24', '100.00', '2023-05-23 22:09:30', NULL),
(372, 12, '2023-05-24', '999.00', '2023-05-23 22:30:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prod_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('helldeadpool456@gmail.com', 'f1c0f4c5a2bf0548fcb740d666d6c8da', '2023-05-08 20:34:50'),
('sia3a.group2@gmail.com', 'f1c0f4c5a2bf0548fcb740d666d6c8da', '2023-05-08 20:34:50');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `prod_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prod_id`, `user_id`, `product_name`, `size`, `price`, `quantity`, `path`, `created_at`, `updated_at`) VALUES
(12, 15, 'white Tshirt', 'shirt', '100.00', 962, 'shirt-1.jpg', '2023-05-18 02:51:01', NULL),
(13, 15, 'nike Red shoes', 'shoes', '999.00', 87, 'nike-shoe-1.jpg', '2023-05-18 02:51:38', NULL),
(14, 15, 'yellow short', 'short', '199.00', 95, 'short-1.jpg', '2023-05-18 11:53:58', NULL),
(15, 15, 'apple', 'shoes', '55.00', 100, 'logo-apple-2048.png', '2023-05-18 12:00:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `confirmation_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_id`, `amount`, `confirmation_code`, `user_id`, `email`, `firstname`, `lastname`, `phone_number`, `status`, `created_at`, `updated_at`) VALUES
(1, 'pi_7GoSjBaQbRYbX6Ba4vhmSDWp', 51400, 'pi_7GoSjBaQbRYbX6Ba4vhmSDWp_client_ArLc7nV7fn6bEwsqeNTkPP56', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-12 23:41:08', NULL),
(2, 'pi_DR9SLs5zieuhNMyY7ARwDUX9', 230900, 'pi_DR9SLs5zieuhNMyY7ARwDUX9_client_cAd9255aEVC2ZLmNxFGdfCE8', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-12 23:49:47', NULL),
(3, 'pi_KQz8ob6R4CscxfnKdHgtiqqa', 51800, 'pi_KQz8ob6R4CscxfnKdHgtiqqa_client_EKbwxBGKXHEMvznAVgJiYY6r', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-13 01:42:55', NULL),
(4, 'pi_EmjHvtJ7HwEVvdTSGsG4v2Ku', 25500, 'pi_EmjHvtJ7HwEVvdTSGsG4v2Ku_client_Wc8YevKFKaHqVutScT8BLQL8', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-13 02:52:23', NULL),
(5, 'pi_jPwKAFasx2AhchmRrz9bLK6u', 25500, 'pi_jPwKAFasx2AhchmRrz9bLK6u_client_BGFqhh4QWwj9Hz2qq7b72eH9', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-13 02:54:52', NULL),
(6, 'pi_RTwQ9yLTmRFEHbkbJZJGFhPK', 25900, 'pi_RTwQ9yLTmRFEHbkbJZJGFhPK_client_mUw7ZXnVBqsqc5akhbEZ8ySK', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 11:24:50', NULL),
(7, 'pi_rJvGz668t8acRcL9SfkRHWb9', 205000, 'pi_rJvGz668t8acRcL9SfkRHWb9_client_pqED6vggUR6zmYHq4pLXpso8', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 12:11:55', NULL),
(8, 'pi_NnQB3SFmoxyPX8BpFFLcEkkY', 25500, 'pi_NnQB3SFmoxyPX8BpFFLcEkkY_client_qmqQ7uxf5NKhC9NoAujwkppW', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 12:15:15', NULL),
(9, 'pi_7TjETVdbDT2M715iFhb9HPLN', 230900, 'pi_7TjETVdbDT2M715iFhb9HPLN_client_cPs3Ew2UyJXoFijPAxS59Vr8', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 15:30:54', NULL),
(10, 'pi_xS2HtWBRHWCUUwkT243MrJkU', 25900, 'pi_xS2HtWBRHWCUUwkT243MrJkU_client_n5FGAd9RBc3WVqVszWY2F3Kx', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 15:31:20', NULL),
(11, 'pi_AAgiGyJL6x6xb78BEUFs6vHP', 51400, 'pi_AAgiGyJL6x6xb78BEUFs6vHP_client_YXcxdTkfC7kGj2B7aDnFHFPf', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 15:31:50', NULL),
(12, 'pi_bsmBbcym7Ym7FH5EJ5or1nwR', 435500, 'pi_bsmBbcym7Ym7FH5EJ5or1nwR_client_hU9P7u998hi6Tfr6wYMrAv2x', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 15:34:41', NULL),
(13, 'pi_nRtEyhQssnpVLKwp3JXDvm8z', 225500, 'pi_nRtEyhQssnpVLKwp3JXDvm8z_client_xGmS5osT86NYtqfXmgTwL5BK', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 15:35:13', NULL),
(14, 'pi_eiExQJ4W52wDsWGWL9ETqVtY', 200000, 'pi_eiExQJ4W52wDsWGWL9ETqVtY_client_qSqYDfzvCqFnAVz5ybTGsJ4c', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 15:37:46', NULL),
(15, 'pi_qNRDCMmbptN6oScDHBKibheC', 51400, 'pi_qNRDCMmbptN6oScDHBKibheC_client_B2NAGtU12opmHhfZuSUJe9wh', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 16:42:13', NULL),
(16, 'pi_JGr6rWMHanBX5XLbBsheWUA6', 25900, 'pi_JGr6rWMHanBX5XLbBsheWUA6_client_wyYmU9tSxP682Ek1ybwGNiys', 12, 'user@user.com', 'user', 'user', '091234123214', 'validated', '2023-05-15 16:46:01', NULL),
(17, 'pi_xvLt4NH2Yr16pe9evEiU6rsv', 225500, 'pi_xvLt4NH2Yr16pe9evEiU6rsv_client_tYykDntKMS5mPLExp6aZ3TUh', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-15 18:08:44', NULL),
(18, 'pi_xpSUrJVNqPHKqwAogXhL4BBm', 405000, 'pi_xpSUrJVNqPHKqwAogXhL4BBm_client_1a8ETc2ksvQzdkYLrD4MiVNf', 12, 'user@user.com', 'John Francis', 'Astillero', '81231241', 'validated', '2023-05-15 19:47:22', NULL),
(19, 'pi_YPLvWLffsH8U8x4Ggk5as6aN', 405000, 'pi_YPLvWLffsH8U8x4Ggk5as6aN_client_25aJCrnvABVzKqHCiEWXpvWB', 12, 'user@user.com', 'John Francis', 'Astillero', '12312512', 'validated', '2023-05-15 19:51:14', NULL),
(20, 'pi_v4Te8SQ8j4d8xwYd64ktKdyn', 405000, 'pi_v4Te8SQ8j4d8xwYd64ktKdyn_client_RtRzeR2vVVfhNt7ki6rgjwvn', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-15 19:51:54', NULL),
(21, 'pi_JYqxx1r8yuNUrRGGMRwAe2Vi', 405000, 'pi_JYqxx1r8yuNUrRGGMRwAe2Vi_client_BLqWeAvAKvxAykxjaHes3pPg', 12, 'user@user.com', 'John Francis', 'Astillero', '8123124', 'validated', '2023-05-15 20:25:56', NULL),
(22, 'pi_o1GYAmmdBdnfphH896BfzuEy', 405000, 'pi_o1GYAmmdBdnfphH896BfzuEy_client_rWVyfVPeuosV5iMwjffFFAd5', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-15 20:26:43', NULL),
(23, 'pi_wjheEfCjSMEUGVbCRFeMo15U', 405000, 'pi_wjheEfCjSMEUGVbCRFeMo15U_client_6pUnchKFYWrbDgwbxyHJAYky', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-15 20:27:18', NULL),
(24, 'pi_oUKdsg2JbmCNGqNGNDjiAM5F', 405000, 'pi_oUKdsg2JbmCNGqNGNDjiAM5F_client_MYxUypsjRNaqLUK1cxGKGNSX', 12, 'user@user.com', 'John Francis', 'Astillero', '12312512', 'validated', '2023-05-15 20:46:56', NULL),
(25, 'pi_awaaiDvDuS4sxWpCrxCCGwfU', 405000, 'pi_awaaiDvDuS4sxWpCrxCCGwfU_client_jEwMmJ3sHhs5pwkpcEo7btdZ', 12, 'user@user.com', 'John Francis', 'Astillero', '1231214', 'validated', '2023-05-15 20:47:27', NULL),
(26, 'pi_hL3aBmYj2c1REdQShjuzpxQS', 405000, 'pi_hL3aBmYj2c1REdQShjuzpxQS_client_6P2CNQqG6MYbN9Nn71CLfbi1', 12, 'user@user.com', 'John Francis', 'Astillero', '123123', 'validated', '2023-05-15 20:53:20', NULL),
(27, 'pi_97F9UVpuhL8s6F1SYznWAq8c', 405000, 'pi_97F9UVpuhL8s6F1SYznWAq8c_client_M7EXvfhoVjc3Urv6FqjaErP2', 12, 'user@user.com', 'John Francis', 'Astillero', '12312', 'validated', '2023-05-15 20:53:53', NULL),
(28, 'pi_jYsKY4ZgSRj5c4R1gk7j7KAv', 405000, 'pi_jYsKY4ZgSRj5c4R1gk7j7KAv_client_Cz8MmE4XEs6Gszr9Lr8BQkze', 12, 'user@user.com', 'John Francis', 'Astillero', '712312412', 'validated', '2023-05-15 20:58:25', NULL),
(29, 'pi_NmXbD8QWAn5BhhLLE4tH5aqZ', 405000, 'pi_NmXbD8QWAn5BhhLLE4tH5aqZ_client_eqoAkpWb9Ddh5DHFNYxWZgeQ', 12, 'user@user.com', 'John Francis', 'Astillero', '2342123', 'validated', '2023-05-15 21:00:08', NULL),
(30, 'pi_JeemktXagZgd69W7JzdvviSd', 405000, 'pi_JeemktXagZgd69W7JzdvviSd_client_6sXcJcDAu41h9f14kujo2WeM', 12, 'user@user.com', 'John Francis', 'Astillero', '81231241', 'validated', '2023-05-15 21:01:23', NULL),
(31, 'pi_hUUXSU9uZsQ7VuSUXJyV3rst', 405000, 'pi_hUUXSU9uZsQ7VuSUXJyV3rst_client_MoPaHhKorTQckrEYtBSvesAL', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-15 21:01:47', NULL),
(32, 'pi_n7ZBnfg8DJGHcK1vny5BFoqf', 405000, 'pi_n7ZBnfg8DJGHcK1vny5BFoqf_client_UmqLKRjMf2sQsjbYDZSZoBzw', 12, 'user@user.com', 'John Francis', 'Astillero', '182312123', 'validated', '2023-05-15 21:02:46', NULL),
(33, 'pi_4d4rzyQsFPDS1Y1o27UG6Yes', 405000, 'pi_4d4rzyQsFPDS1Y1o27UG6Yes_client_8zjuEUHbMynLPNMPGS5nCn3m', 12, 'user@user.com', 'John Francis', 'Astillero', '234121', 'validated', '2023-05-15 21:06:16', NULL),
(34, 'pi_2SXC55nQ7SeUytKeRB9v3GKo', 405000, 'pi_2SXC55nQ7SeUytKeRB9v3GKo_client_PGBCeggSZ2ixqkjACb4v9G3r', 12, 'user@user.com', 'John Francis', 'Astillero', '72131244', 'validated', '2023-05-15 21:09:33', NULL),
(35, 'pi_EhuB7HB1dr2zmowgZNSMjvwC', 405000, 'pi_EhuB7HB1dr2zmowgZNSMjvwC_client_sJ2ywQoguFm5SfsgGEX6AboR', 12, 'user@user.com', 'John Francis', 'Astillero', '123123123', 'validated', '2023-05-15 21:34:32', NULL),
(36, 'pi_3vQSz7irke2148TQfpGtMi2o', 405000, 'pi_3vQSz7irke2148TQfpGtMi2o_client_TztANv4iTKWq43iRNgd6RFgQ', 12, 'user@user.com', 'John Francis', 'Astillero', '12312', 'validated', '2023-05-15 21:35:36', NULL),
(37, 'pi_sC7ohJNG3CDd1H28AV2w8Jwr', 430500, 'pi_sC7ohJNG3CDd1H28AV2w8Jwr_client_KAiSuWTUTUxDctTf95msvtc7', 12, 'user@user.com', 'John Francis', 'Astillero', '123124', 'validated', '2023-05-15 21:43:56', NULL),
(38, 'pi_jK12J86xnTm3HUTnRPYmt1mq', 405000, 'pi_jK12J86xnTm3HUTnRPYmt1mq_client_y2NFPQBKr1YrvLdEQP6jucq5', 12, 'user@user.com', 'John Francis', 'Astillero', '1238124', 'validated', '2023-05-15 21:46:52', NULL),
(39, 'pi_M44eex8M7ozw7EzQkKgAigzr', 256000, 'pi_M44eex8M7ozw7EzQkKgAigzr_client_fVHJTC8xmgHoWus6qx1uw9x7', 12, 'user@user.com', 'John Francis', 'Astillero', '91934568834', 'validated', '2023-05-17 17:35:26', NULL),
(40, 'pi_9xrarVhFreLar5LbofAvAS4j', 76500, 'pi_9xrarVhFreLar5LbofAvAS4j_client_AfRNcXgtRrHK3Rdhuhn95F8t', 12, 'user@user.com', 'John Francis', 'Astillero', '91231212', 'validated', '2023-05-18 00:45:29', NULL),
(41, 'pi_A1VtUYEETQvfYY7cZsaZSF5a', 25900, 'pi_A1VtUYEETQvfYY7cZsaZSF5a_client_wA3VqZynetN8KRmjJHCrG5zi', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-18 00:47:23', NULL),
(42, 'pi_waJxUAMU6pHxnMtiHiuxD19H', 25900, 'pi_waJxUAMU6pHxnMtiHiuxD19H_client_RxvLFGeG8EDwVbZZqiCJxsBJ', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-18 00:55:27', NULL),
(43, 'pi_UwLntWBTwihrjGyrosW72ZdE', 205000, 'pi_UwLntWBTwihrjGyrosW72ZdE_client_5gRJNakR8FRoHSeyrjsZumzt', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-18 00:55:48', NULL),
(44, 'pi_QeSwgMDAwiDcKLBwooawfCLZ', 230900, 'pi_QeSwgMDAwiDcKLBwooawfCLZ_client_keoZU9N3vroD18Y5znm9WTxJ', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-18 00:57:34', NULL),
(45, 'pi_NKrH4jL6v9CxdoNySA3XkZpL', 51400, 'pi_NKrH4jL6v9CxdoNySA3XkZpL_client_ciP1HqFQCJUhY9cKssrhn2MH', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-18 01:47:28', NULL),
(46, 'pi_MMN4PHj87r38F7dRjd6EK3Qu', 230500, 'pi_MMN4PHj87r38F7dRjd6EK3Qu_client_y7LKXf7TatSYgSeXUc68uHgp', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-18 01:49:27', NULL),
(47, 'pi_tGtC4h4B2MBu8hvTbCjwQUXv', 39800, 'pi_tGtC4h4B2MBu8hvTbCjwQUXv_client_itTZsqhBzKssknGfWJyAwRX9', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-18 02:42:23', NULL),
(48, 'pi_7vAWZqR4KyajWMPKuLbmE557', 219800, 'pi_7vAWZqR4KyajWMPKuLbmE557_client_xMM4WJBYtmUd6QRYzyCkDrmN', 12, 'user@user.com', 'John Francis', 'Astillero', '+09165177301', 'validated', '2023-05-18 02:53:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(11) NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `email_verified_at`, `password`, `role`, `gender`, `birthday`, `age`, `address`, `zipcode`, `phonenumber`, `user_status`, `remember_token`, `created_at`, `updated_at`) VALUES
(12, 'John Francis', 'Astillero', 'user', 'user@user.com', NULL, 'c93958709bb024fa092de17b0175fcc4', 1, 'male', '2001-02-28', '22', 'Camolinas Poblacion Cordova Cebu', '6017', '+09165177301', 'offline', NULL, '2023-05-06 15:43:26', '2023-05-15 18:05:38'),
(14, 'admin', 'admin', 'admin', 'admin@admin.com', NULL, '21232f297a57a5a743894a0e4a801fc3', 3, 'male', '2001-02-22', '22', 'user user', '6017', '0912374234', 'offline', NULL, '2023-05-06 15:57:03', NULL),
(15, 'hello', 'world', 'employee1', 'employee1@gmail.com', NULL, 'c93958709bb024fa092de17b0175fcc4', 2, 'male', '2001-02-21', '22', 'camolinas', '6017', '09165177301', 'offline', NULL, '2023-05-08 04:19:37', '2023-05-08 07:51:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`checkout_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_income`
--
ALTER TABLE `monthly_income`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkout`
--
ALTER TABLE `checkout`
  MODIFY `checkout_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `monthly_income`
--
ALTER TABLE `monthly_income`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=373;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=274;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
