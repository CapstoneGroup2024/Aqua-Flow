<link rel="stylesheet" href="assets/css/scrollbar.css">

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-4 fixed-start ms-3   bg-gradient-blue" id="sidenav-main">
     <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <p class="navbar-brand m-0" href="" target="_blank">
        <span class="ms-1 m-0 font-weight-bold text-dark-blue">Aqua Flow</span>
      </p>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main" style="overflow-x: hidden !important;">
      <ul class="navbar-nav">
        <!--------------- MARKETING --------------->
        <li class="nav-item" style="margin-left: 15px">
            <span class="nav-link-text ms-1" id="side-bar-sub-title">MARKETING</span>
        </li>
        <!--------------- DASHBOARD --------------->
        <li class="nav-item">
          <a class="nav-link text-dark-blue font-weight-bold bg-primary" href="index.php">
            <div class="text-dark-blue text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10" id="side-bar-icon">dashboard</i>
            </div>
            <span class="nav-link-text ms-1" id="side-bar-title">Dashboard</span>
          </a>
        </li>
                <!--------------- ORDERS --------------->
                <li class="nav-item">
          <a class="nav-link text-dark-blue " href="orders.php" id="side-bar-link-box">
            <div class="text-dark-blue text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10" id="side-bar-icon">shopping_bag</i>
            </div>
            <span class="nav-link-text ms-1" id="side-bar-title">Orders</span>
          </a>
        </li>
        <!--------------- ADD CATEGORY --------------->
        <li class="nav-item">
          <a class="nav-link text-dark-blue " href="category.php" id="side-bar-link-box">
            <div class="text-dark-blue text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10" id="side-bar-icon">category</i>
            </div>
            <span class="nav-link-text ms-1" id="side-bar-title">Categories</span>
          </a>
        </li>
        <!--------------- ADD CATEGORY --------------->
        <li class="nav-item">
          <a class="nav-link text-dark-blue " href="addCategory.php" id="side-bar-link-box">
            <div class="text-dark-blue text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10" id="side-bar-icon">add</i>
            </div>
            <span class="nav-link-text ms-1" id="side-bar-title">Add Category</span>
          </a>
        </li>
        <!--------------- PRODUCTS --------------->
        <li class="nav-item">
          <a class="nav-link text-dark-blue " href="product.php" id="side-bar-link-box">
            <div class="text-dark-blue text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10" id="side-bar-icon">local_drink</i>
            </div>
            <span class="nav-link-text ms-1" id="side-bar-title">Products</span>
          </a>
        </li>
        <!--------------- ADD PRODUCTS --------------->
        <li class="nav-item">
          <a class="nav-link text-dark-blue " href="addProduct.php" id="side-bar-link-box">
            <div class="text-dark-blue text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10" id="side-bar-icon">add</i>
            </div>
            <span class="nav-link-text ms-1" id="side-bar-title">Add Products</span>
          </a>
        </li>
        <!--------------- CUSTOMER --------------->
        <li class="nav-item">
          <a class="nav-link text-dark-blue " href="users.php" id="side-bar-link-box">
            <div class="text-dark-blue text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10" id="side-bar-icon">people_alt</i>
            </div>
            <span class="nav-link-text ms-1" id="side-bar-title">Users</span>
          </a>
        </li>
      </ul>
    </div>
    <!--------------- LOGOUT BUTTON --------------->
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <a class="btn bg-primary mt-4 w-100 text-white" href="../logout.php">Logout</a>
      </div> 
    </div>
    <!------------------- Custom Scroll Bar ------------------->
    <div class="scrollbar scrollbar-lady-lips">
      <div class="force-overflow"></div>
    </div>
  </aside>