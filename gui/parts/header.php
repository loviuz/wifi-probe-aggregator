<?php
$config = parse_ini_file('../config.ini');
?><!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>WiFi Probe Aggregrator</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="css/wpa.css" rel="stylesheet">

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <script>
    var reader_url = '<?php echo $config['reader_url']; ?>';
  </script>

  <script src="js/moment.min.js"></script>
  <script src="js/jquery.timeago.js"></script>
  <script src="js/jquery.timeago.it.js"></script>
  <script src="js/util.js"></script>
  <script src="js/wifi-probe-aggregator.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-wifi"></i>
        </div>
        <div class="sidebar-brand-text mx-3">WiFi Probe Aggregator</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item" id="menu-dashboard">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Cerca
      </div>

      <li class="nav-item" id="menu-mappa">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-map"></i>
          <span>Mappa</span>
        </a>
      </li>

      <li class="nav-item" id="menu-orari">
        <a class="nav-link" href="orari.php">
          <i class="fas fa-fw fa-clock"></i>
          <span>Orari</span>
        </a>
      </li>

      <li class="nav-item" id="menu-luoghi">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-marker"></i>
          <span>Luoghi</span>
        </a>
      </li>

      <li class="nav-item" id="menu-ap">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-signal"></i>
          <span>Access Point</span>
        </a>
      </li>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            