<?php
session_start();
if(empty($_SESSION['username'] && $_SESSION['password'] && $_SESSION['firstname'] && $_SESSION['lastname'] && $_SESSION['email'])) {
    header('Location: ../');
    exit;
}elseif($_SESSION['role'] != 1){
    header('Location: ../');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
    <title>User Dashboard</title>
    <style>
        body{
            background-color: black;

        }
        .clicked {
    color: whitesmoke !important;
        }
    </style>
</head>
<body>
