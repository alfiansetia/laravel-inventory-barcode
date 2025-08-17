 <!-- Sidebar -->
 <div class="sidebar" data-background-color="dark">
     <div class="sidebar-logo">
         <!-- Logo Header -->
         <div class="logo-header" data-background-color="dark">
             <a href="{{ route('home') }}" class="logo">
                 <img src="{{ asset('kai/assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                     height="20" />
             </a>
             <div class="nav-toggle">
                 <button class="btn btn-toggle toggle-sidebar">
                     <i class="gg-menu-right"></i>
                 </button>
                 <button class="btn btn-toggle sidenav-toggler">
                     <i class="gg-menu-left"></i>
                 </button>
             </div>
             <button class="topbar-toggler more">
                 <i class="gg-more-vertical-alt"></i>
             </button>
         </div>
         <!-- End Logo Header -->
     </div>
     <div class="sidebar-wrapper scrollbar scrollbar-inner">
         <div class="sidebar-content">
             <ul class="nav nav-secondary">
                 <li class="nav-item {{ $title == 'Dashboard' ? 'active' : '' }}">
                     <a class="" href="{{ route('home') }}">
                         <i class="fas fa-home"></i>
                         <p>Dashboard</p>
                     </a>
                 </li>
                 <li class="nav-section">
                     <span class="sidebar-mini-icon">
                         <i class="fa fa-ellipsis-h"></i>
                     </span>
                     <h4 class="text-section">Master</h4>
                 </li>
                 <li class="nav-item {{ $title == 'Product' ? 'active' : '' }}">
                     <a class="" href="{{ route('products.index') }}">
                         <i class="ti ti-box-multiple"></i>
                         <p>Product</p>
                     </a>
                 </li>

                 <li class="nav-item {{ $title == 'Vendor' ? 'active' : '' }}">
                     <a class="" href="{{ route('vendors.index') }}">
                         <i class="ti ti-truck-delivery"></i>
                         <p>Vendor</p>
                     </a>
                 </li>
                 <li class="nav-item {{ $title == 'Barcode' ? 'active' : '' }}">
                     <a class="" href="{{ route('barcodes.index') }}">
                         <i class="ti ti-barcode"></i>
                         <p>Barcode</p>
                     </a>
                 </li>

                 <li class="nav-section">
                     <span class="sidebar-mini-icon">
                         <i class="fa fa-ellipsis-h"></i>
                     </span>
                     <h4 class="text-section">Transaction</h4>
                 </li>

                 <li class="nav-item {{ $title == 'Purchase' ? 'active' : '' }}">
                     <a class="" href="{{ route('purchases.index') }}">
                         <i class="ti ti-transfer-in"></i>
                         <p>Purchase</p>
                     </a>
                 </li>

                 <li class="nav-item {{ $title == 'Scan' ? 'active' : '' }}">
                     <a class="" href="{{ route('barcodes.scan') }}">
                         <i class="ti ti-barcode"></i>
                         <p>Scan</p>
                     </a>
                 </li>

                 <li class="nav-item {{ $title == 'User' ? 'active' : '' }}">
                     <a class="" href="{{ route('users.index') }}">
                         <i class="ti ti-user"></i>
                         <p>User</p>
                     </a>
                 </li>

             </ul>
         </div>
     </div>
 </div>
 <!-- End Sidebar -->
