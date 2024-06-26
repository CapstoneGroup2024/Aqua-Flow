<div class="container-fluid mt-3">
  <div class="row justify-content-end">
    <div class="col-lg-auto">
      <!-- Toggle Button - Right Side -->
      <button class="btn BlueBtn d-lg-none" type="button" 
        style="position: fixed; top: 20px; right: 20px; z-index: 1000; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"
        data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft" aria-controls="offcanvasLeft">
        <i class="material-icons">grid_view</i>
    </button>


    </div>
  </div>
</div>

<!-- Offcanvas Content -->
<div class="offcanvas offcanvas-start offC d-lg-none " tabindex="-1" id="offcanvasLeft" aria-labelledby="offcanvasLeftLabel">
  <div class="offcanvas-header">
        <i class="fas fa-tint" id="logo"></i>
        <span class="ms-1 m-0 font-weight-bold text-dark-blue" style="font-size: 25px; font-family:'Suez One'; margin-top: 10px;">Aqua Flow</span>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-unstyled">
      <!-- Dashboard -->
      <li class="nav-item nab-item">
        <a href="index.php" class="nab-link">
          <i class="material-icons opacity-10" id="side-bar-Iicon">dashboard</i>
          <span class="nav-link-text" id="side-bar-title">Dashboard</span>
        </a>
      </li>
      <!-- Orders -->
      <li class="nav-item nab-item">
        <a href="orders.php" class="nab-link">
          <i class="material-icons opacity-10" id="side-bar-Iicon">shopping_bag</i>
          <span class="nav-link-text" id="side-bar-title">Orders</span>
        </a>
      </li>
      <!-- Categories -->
      <li class="nav-item nab-item">
        <a href="category.php" class="nab-link">
          <i class="material-icons opacity-10" id="side-bar-Iicon">category</i>
          <span class="nav-link-text" id="side-bar-title">Categories</span>
        </a>
      </li>
      <!-- Add Category -->
      <li class="nav-item nab-item">
        <a href="addCategory.php" class="nab-link">
          <i class="material-icons opacity-10" id="side-bar-Iicon">add</i>
          <span class="nav-link-text" id="side-bar-title">Add Category</span>
        </a>
      </li>
      <!-- Products -->
      <li class="nav-item nab-item">
        <a href="product.php" class="nab-link">
          <i class="material-icons opacity-10" id="side-bar-Iicon">local_drink</i>
          <span class="nav-link-text" id="side-bar-title">Products</span>
        </a>
      </li>
      <!-- Add Products -->
      <li class="nav-item nab-item">
        <a href="addProduct.php" class="nab-link">
          <i class="material-icons opacity-10" id="side-bar-Iicon">add</i>
          <span class="nav-link-text" id="side-bar-title">Add Products</span>
        </a>
      </li>
      <!-- Users -->
      <li class="nav-item nab-item">
        <a href="users.php" class="nab-link">
          <i class="material-icons opacity-10" id="side-bar-Iicon">people_alt</i>
          <span class="nav-link-text" id="side-bar-title">Users</span>
        </a>
      </li>
      <li class="nav-item nab-item">
        <a href="userMessage.php" class="nab-link">
          <i class="material-icons opacity-10" id="side-bar-Iicon">mail</i>
          <span class="nav-link-text" id="side-bar-title">Messages</span>
        </a>
      </li>
      <li class="nav-item nab-item mt-5">
        <div class="sidenav-footer logOut w-90">
            <a href="../logout.php" class="btn bg-primary w-100 BluBtn">Logout</a>
        </div>
      </li>
    </ul>
  </div>
</div>
