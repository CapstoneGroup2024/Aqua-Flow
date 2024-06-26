<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <!--------------- BOOTSTRAP CSS --------------->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <!--------------- CSS FILES --------------->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
  <link rel="stylesheet" href="assets/css/style.css">
  <!--------------- FONTS AND ICONS --------------->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Suez+One&display=swap">
  <!--------------- FONT AWESOME ICONS --------------->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!--------------- MATERIAL ICONS --------------->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!--------------- FONT AWESOME --------------->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!--------------- JAVASCRIPT --------------->
  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
  <!--------------- CUSTOM CSS ---------------> 
  <style>
    .ajs-modal { /* CHANGE DESIGN OF MODAL */
      border-radius: 20px;
      font-family: 'Poppins', sans-serif;
    }
    .ajs-content { /* CHANGE DESIGN OF MODAL CONTENT */
      color: black; 
      font-family: 'Poppins', sans-serif;
    }
    .ajs-message.ajs-success { /* CHANGE DESIGN OF MODAL MESSAGE */
      border-radius: 8px; 
      font-family: 'Poppins', sans-serif;
      font-size: 18px;
    }
    .ajs-message.ajs-error { /* CHANGE DESIGN OF MODAL MESSAGE */
      border-radius: 8px; 
      font-family: 'Poppins', sans-serif;
      font-size: 18px;
    }
    @keyframes rotate-scale-up {
      0% {
        transform: scale(0) rotate(0deg);
      }
      50% {
        transform: scale(1.5) rotate(180deg);
      }
      100% {
        transform: scale(1) rotate(360deg);
      }
    }
    .dropdown-menu{
      margin-left: -80px!important;
    }
  </style>
</head>
<body>
  