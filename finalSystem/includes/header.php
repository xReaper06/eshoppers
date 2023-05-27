<?php session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> 
    <link rel="manifest" href="manifest.json">
    <script>
        if('serviceWorker' in navigator){
            navigator.serviceWorker.register('./sw.js')
        }
    </script>
    <title>Landing Page</title>
    <style>
        body{
            background-color: black;
        }
        .showpassword{
            position:absolute;
            right:15px;
            top:35px;
            cursor:pointer;
            color:black;
            
        }
    </style>
</head>
<body>
