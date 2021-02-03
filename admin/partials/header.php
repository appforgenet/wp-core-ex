<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the page header div.
 *
 * @package Neve
 * @since   1.0.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="h-100">

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <link rel="stylesheet" href="<?= plugin_dir_url( __FILE__ ) . '/../../css/style.css' ?>" >
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
        
    <?php #wp_head(); ?>
</head>

<body class="d-flex flex-column h-100" >
<?php #wp_body_open(); ?>
<header>
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light bg-appforge">
  <div class="container-fluid">
    <a class="navbar-brand" aria-current="page" src="/?coge"><img src="<?= plugin_dir_url( __FILE__ ) . '../img/logo-black.png' ?>"/>
    </a>
    

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
        <span>WordPress Active Record <br />Code Generator</span>
        </li>
      </ul>
    </div>
  </div>
</nav>
</header>
<div class="container-fluid">
<div class="row">
<nav id="sidebarMenu" class="col-md-2 col-lg-2 d-md-block bg-light sidebar collapse" style="">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="/wp-admin">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 256 256" version="1.1" preserveAspectRatio="xMidYMid" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">    <g fill="#000">        <path d="M18.1239675,127.500488 C18.1239675,170.795707 43.284813,208.211252 79.7700163,225.941854 L27.5938862,82.985626 C21.524813,96.5890081 18.1239675,111.643057 18.1239675,127.500488 L18.1239675,127.500488 Z M201.345041,121.980878 C201.345041,108.462829 196.489366,99.1011382 192.324683,91.8145041 C186.780098,82.8045528 181.583089,75.1745041 181.583089,66.1645528 C181.583089,56.1097886 189.208976,46.7501789 199.950569,46.7501789 C200.435512,46.7501789 200.89548,46.8105366 201.367935,46.8375935 C181.907772,29.0091707 155.981008,18.1239675 127.50465,18.1239675 C89.2919675,18.1239675 55.6727154,37.7298211 36.1147317,67.4258211 C38.6809756,67.5028293 41.0994472,67.5569431 43.1536911,67.5569431 C54.5946016,67.5569431 72.3043902,66.1687154 72.3043902,66.1687154 C78.2007154,65.8211382 78.8958699,74.4814309 73.0057886,75.1786667 C73.0057886,75.1786667 67.0803252,75.8759024 60.4867642,76.2213984 L100.318699,194.699447 L124.25574,122.909138 L107.214049,76.2172358 C101.323967,75.8717398 95.744,75.1745041 95.744,75.1745041 C89.8497561,74.8290081 90.540748,65.8169756 96.4349919,66.1645528 C96.4349919,66.1645528 114.498602,67.5527805 125.246439,67.5527805 C136.685268,67.5527805 154.397138,66.1645528 154.397138,66.1645528 C160.297626,65.8169756 160.990699,74.4772683 155.098537,75.1745041 C155.098537,75.1745041 149.160585,75.8717398 142.579512,76.2172358 L182.107577,193.798244 L193.017756,157.340098 C197.746472,142.211122 201.345041,131.34465 201.345041,121.980878 L201.345041,121.980878 Z M129.42361,137.068228 L96.6056585,232.43135 C106.404423,235.31187 116.76722,236.887415 127.50465,236.887415 C140.242211,236.887415 152.457366,234.685398 163.827512,230.68722 C163.534049,230.218927 163.267642,229.721496 163.049106,229.180358 L129.42361,137.068228 L129.42361,137.068228 Z M223.481756,75.0225691 C223.95213,78.5066667 224.218537,82.2467642 224.218537,86.2699187 C224.218537,97.3694959 222.145561,109.846894 215.901659,125.448325 L182.490537,222.04774 C215.00878,203.085008 236.881171,167.854829 236.881171,127.502569 C236.883252,108.485724 232.025496,90.603187 223.481756,75.0225691 L223.481756,75.0225691 Z M127.50465,0 C57.2003902,0 0,57.1962276 0,127.500488 C0,197.813073 57.2003902,255.00722 127.50465,255.00722 C197.806829,255.00722 255.015545,197.813073 255.015545,127.500488 C255.013463,57.1962276 197.806829,0 127.50465,0 L127.50465,0 Z M127.50465,249.162927 C60.4243252,249.162927 5.84637398,194.584976 5.84637398,127.500488 C5.84637398,60.4201626 60.4222439,5.84637398 127.50465,5.84637398 C194.582894,5.84637398 249.156683,60.4201626 249.156683,127.500488 C249.156683,194.584976 194.582894,249.162927 127.50465,249.162927 L127.50465,249.162927 Z"/>    </g></svg>
              Back to Wordpress
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
              Model
            </a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
              Orders
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
              Products
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
              Customers
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
              Reports
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
              Integrations
            </a>
          </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Saved reports</span>
          <a class="link-secondary" href="#" aria-label="Add a new report">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              Current month
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              Last quarter
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              Social engagement
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              Year-end sale
            </a>
          </li> -->
        </ul>
      </div>
    </nav>

    <main class="flex-shrink-0 col-md-10 col-lg-10">

	
