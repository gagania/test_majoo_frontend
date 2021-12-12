<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="author" content="protc" />
        <meta name="description" content="">
        <!-- Document title -->
        <title>Demo</title>
        <!-- Stylesheets & Fonts -->
        <?php include('css-javascript.php') ?>
        <link rel="stylesheet" href="<?=base_url()?>assets/polo/datepicker/datepicker3.css">
        <link rel="stylesheet" href="<?= base_url() ?>assets/polo/css/lightslider.css">
    </head>
    <body>
        <!-- Wrapper -->
        <div id="wrapper">

            <header id="header" class="header-colored dark">
                <div id="header-wrap">
                    <div class="container">
                        <!--Logo-->
                        <div id="logo">
                            <a href="<?= base_url() ?>" class="logo" >
                            </a>
                        </div>
                        <!--Top Search Form-->
                        <div id="top-search">
                            <form action="search-results-page.html" method="get">
                                <input type="text" name="q" class="form-control" value="" placeholder="Start typing & press  &quot;Enter&quot;">
                            </form>
                        </div>
                        <!--end: Top Search Form-->

                        <!--Header Extras-->
                        <div class="header-extras">
                            <ul>
                                <li></li>
                            </ul>
                        </div>
                        <!--Navigation Resposnive Trigger-->
                        <div id="mainMenu-trigger">
                            <button class="lines-button x"> <span class="lines"></span> </button>
                        </div>
                        <!--end: Navigation Resposnive Trigger-->

                        <!--Navigation-->
                        <div id="mainMenu" class="light">
                            <div class="container">
                                <nav>
                                    <ul>
                                        <li><a href="<?=base_url()?>">HOME</a></li>
                                        <li><a href="<?= base_url().$this->router->fetch_class() ?>/merchant">Merchant</a></li>
                                        <li><a href="<?= base_url().$this->router->fetch_class() ?>/outlet">Outlet</a></li>
                                        <li><a href="<?= base_url().$this->router->fetch_class() ?>/merch_report">Merchant Report</a></li>
                                        <li><a href="<?= base_url().$this->router->fetch_class() ?>/outlet_report">Outlet Report</a></li>
                                        <li><a href="<?= base_url() ?>login/logout">LOGOUT</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!--end: Navigation-->
                    </div>
                </div>
            </header>
            <!-- end: Header -->
            <?php $this->load->view($content); ?></div>
        </div>
        <!-- end: Wrapper -->
        <a id="goToTop"><i class="fa fa-angle-up top-icon"></i><i class="fa fa-angle-up"></i></a>
        <script src="<?= base_url() ?>assets/polo/js/jquery.js"></script>
        <script src="<?= base_url() ?>assets/polo/js/plugins.js"></script>
        <script src="<?= base_url() ?>assets/polo/js/functions.js"></script>
        <script src="<?=base_url()?>assets/polo/datepicker/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/polo/js/lightslider.js"></script>
    </body>
</html>