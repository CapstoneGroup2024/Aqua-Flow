<!----- START SESSION ----->
<?php session_start(); ?>
<!--------------- NAVBAR --------------->
<nav class="navbar shadow-sm fixed-top navbar-expand-lg py-0" id="navbg">
  <div class="container-fluid" id="navbar"> 
    <a class="navbar-brand"> <!----- LOGO ----->
    <img src="assets/images/Aquatic.png" style="margin-right: 10px;" alt="Bootstrap" width="40" height="40"class="d-inline-block align-text-center" id="aquaflowtext">Aqua Flow</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span> <!----- TOGGLER FOR NAVBAR ----->
    </button>
    <!--------------- OFF CANVAS NAVBAR --------------->
    <div class="offcanvas offcanvas-end bg-custom-success" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">&nbsp;</h5>
        <button type="button-navbar" class="btn-close btn-close-black" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body bg-custom-success">
        <!--------------- LINKS --------------->
        <ul class="navbar-nav ml-auto px-lg-1 justify-content-end align-items-center flex-grow-1 pe-3">
          <li class="nav-item px-lg-3">
            <a class="nav-link px-lg-1" aria-current="page" href="homepage.php#Home">Home</a>
          </li>
          <li class="nav-item px-lg-3">
            <a class="nav-link px-lg-1" href="homepage.php#Services">Services</a>
          </li>
          <li class="nav-item px-lg-3">
            <a class="nav-link px-lg-1" href="homepage.php#AboutUs">About Us</a>
          </li>
          <li class="nav-item px-lg-3">
            <a class="nav-link px-lg-1" id="contact" href="homepage.php#contacts">Contact</a>
          </li>
        <!--------------- DROP DOWN FOR LOGOUT --------------->
        <?php if(isset($_SESSION['auth'])) { // CHECKS IF USER LOGGED IN ?>
            <li class="nav-item dropdown d-none d-lg-block">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?= $_SESSION['auth_user']['name'] ?>
              </a>
              <ul class="dropdown-menu" style="margin-right:20px!important">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="manageAccount.php">Manage Account</a></li>
                <li><a class="dropdown-item" href="logout.php">Log out</a></li>
              </ul>
            </li>
          <?php } else { // DISPLAY REG AND LOGIN IF NOT LOGGED IN ?>
            <li class="nav-item">
              <a class="nav-link" href="register.php">Register</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="contact" href="index.php">Login</a>
            </li>
          <?php } ?>
          </ul>
        <!--------------- DIRECT LINKS FOR LOGOUT (ON SMALL SCREENS) --------------->
        <?php if(isset($_SESSION['auth'])) { // CHECKS IF USER LOGGED IN ?>
          <ul class="navbar-nav ml-auto px-lg-0 justify-content-end align-items-center flex-grow-1 pe-1 d-lg-none">
            <li class="nav-item px-lg-3">
              <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item px-lg-3">
              <a class="nav-link" href="manageAccount.php">Manage Account</a>
            </li>
            <li class="nav-item px-lg-3">
              <a class="nav-link" href="logout.php">Log out</a>
            </li>
          </ul>
        <?php } ?>
      </div>
    </div>
  </div>
</nav>