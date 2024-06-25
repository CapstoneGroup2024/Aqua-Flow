<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    PHP Ecommerce
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Suez+One&display=swap">
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.min copy.css" rel="stylesheet" />
  <!-- Alertify JS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
  <link rel="stylesheet" href="../assets/css/sidebar.css">
  <style>
    /* --------------- ADD CATEGORY ---------------*/
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Suez+One&display=swap');
    .form-control{
        border: 1px solid #6A6A66 !important;
        padding: 8px 10px;
        background-color: #DEEFF5 !important;
        border-radius: 8px;
        margin-bottom: 5px;
    }
    .form-select{
        border: 1px solid #6A6A66 !important;
        padding: 8px 10px;
        background-color: #DEEFF5 !important;
        border-radius: 8px;
        margin-bottom: 5px;
    }
    .addCategSave{
        color: red;
    }
    h4{
      margin-bottom: -20px;
    }
    #side-bar-title{
      color: #013D67;
      font-family: 'Poppins', sans-serif; 
      font-weight: 500;
      font-size: 18px;
    }
    
    #side-bar-sub-title{
      color: #6A6A66;
      font-weight: bold;
      font-size: 12px;
      font-family: 'Poppins', sans-serif;
    }
    #side-bar-icon{
      font-size: 22px;
    }
    #side-bar-Iicon{
      font-size: 28px;
      color: #013D67;
      margin-right: 10px;
    }
    #logo{
      margin-left: 20px!important;
      font-size: 28px;
      color: #013D67;
    }
    #loGo{
      margin-left: 0px!important;
      font-size: 28px;
      color: #013D67;
    }
    #side-bar-link-box:hover{
      background-color: #89C5F1; 
      transition: background-color 0.3s;
      border-radius: 14px;
    }
        /* Custom styles for offcanvas */
      .offcanvas-start {
        width: 80px; /* Adjust width as needed */
        background-color: #AAD7F6;
      }
  
      .offcanvas-body {
        padding: 10px; /* Adjust padding as needed */
      }

      .offC{
        width: 250px!important;
        border-top-right-radius: 30px;
        border-bottom-right-radius: 30px;
      }
      .nab-item{
        padding: 10px;
        margin-left: 5px;
      }
      .nab-link {
      display: flex;
      align-items: center;
      padding: 10px 8px;
      text-decoration: none;
      color: #000;
      border-radius: 14px;
      font-size: 22px;
      margin-top: 2px;
    }
    .logOut{
      margin-top: -40px;
    }

    .nab-link:hover {
      background-color: #89C5F1; 
      transition: background-color 0.3s;
      border-radius: 14px;
    }
    .link-body {
    width: 100%;
    max-width: 800px; /* Adjust max-width as needed */
    margin: 0 auto; /* Center align horizontally */
  }

.options {
  display: flex;
  justify-content: center; /* Center align links horizontally */
}

.links {
  text-align: center; /* Center align text inside each link column */
  font-family: 'Poppins';
  font-size: 18px;
}

.main-link {
  position: relative;
  text-decoration: none;
  color: #013D67;
  font-weight: 500;
  transition: color 0.3s;
  overflow: hidden; /* Ensures the overflow of the underline is hidden */
  margin-top: 10px;
  padding-bottom: 5px; /* Adds space below the text */
}

/* Underline styles */
.main-link::after {
  content: '';
  position: absolute;
  width: 100%;
  height: 2px;
  bottom: 0;
  left: 0;
  background-color: #013D67;
  transform: scaleX(0); /* Initially, hide the underline */
  transform-origin: bottom right; /* Set the origin to start from bottom right */
  transition: transform 0.3s ease-out;
}

/* Expand underline on hover */
.main-link:hover::after {
  transform: scaleX(1); /* Expand the underline on hover */
  transform-origin: bottom left; /* Change the origin to bottom left for expanding effect */
}

/* Underline for active link */
.main-link.active::after {
  transform: scaleX(1); /* Show the underline when link is active */
  transform-origin: bottom left; /* Ensure consistent origin for active state */
}

th{
  font-family: 'Poppins';
  font-size: 16px;
  color: #013D67;
}
td{
  font-family: 'Poppins';
  font-size: 15px;
  color: #013D67;
}

table, tr,td,th{
  border: none;
}

.BlueBtn{
  color: #013D67; 
  font-size: 13px; 
  font-family: 'Poppins';
  font-size: 500;
  background-color: #AAD7F6!important;
}
.RedBtn{
  color: white; 
  font-size: 13px; 
  font-family: 'Poppins';
  font-size: 500;
  background-color: #f43737;
}

.RedBtn:hover{
  background-color: #f43737;
  color: white; 
}
/* Default styles for error cell */
.error-cell {
    color: red;
    text-align: center;
}
.detailOd{
  font-weight: 400;
}

/* Media query for smaller screens */
@media (max-width: 768px) {
    .error-cell {
        font-size: 12px; /* Example: Adjust font size */
        padding: 2px; /* Example: Adjust padding */
    }
}

@media (max-width: 768px) {
    .error-row-large {
        display: none;
    }
}

/* Hide the simplified error row on large screens */
@media (min-width: 769px) {
    .error-row-small {
        display: none;
    }
}
  </style>
</head>

<body class="g-sidenav-show  bg-gray-200">
    <?php include('sidebar.php');?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <?php include('includes/navbar.php'); ?>