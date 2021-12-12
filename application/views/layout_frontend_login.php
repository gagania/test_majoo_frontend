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

            <!-- Header -->
            <header id="header" class="header-colored dark">
                <div id="header-wrap">
                    <div class="container">
                        <!--Logo-->
                        <div id="logo">
                            <a href="<?= base_url() ?>" class="logo" >
                                <!--<img src="<?= base_url() ?>assets/polo/images/QR_Lab_Logo.png" alt="">-->
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
                                <li>
                                    <!--top search-->
                                   <!-- <a id="top-search-trigger" href="#" class="toggle-item">
                                        <i class="fa fa-search"></i>
                                    </a>-->
                                    <!--end: top search-->
                                </li>
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
                                    <?php // include('menu-frontend.php') ?>
                                    <li><a href="<?=base_url()?>">HOME</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!--end: Navigation-->
                    </div>
                </div>
            </header>
            <!-- end: Header -->

            <!--FILES-->
            <section class="">
                <div class="container">
                    <div class="row"><?php $this->load->view($content); ?></div>
                </div>
            </section>
            <!-- Footer -->
            <?php include('footer.php') ?>
            <!-- end: Footer -->

        </div>
        <!-- end: Wrapper -->
        <div class="modal fade" id="success_modal" tabindex="-1" role="modal" aria-labelledby="modal-label-2" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="modal-label-2" class="modal-title">Informasi</h4>
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if($this->session->flashdata('message')) {?>
                                    <p><?=$this->session->flashdata('message')?></p>
                                <?php }?>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="float-left">
                            <button data-dismiss="modal" class="btn btn-b" type="button">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Go to top button -->
        <a id="goToTop"><i class="fa fa-angle-up top-icon"></i><i class="fa fa-angle-up"></i></a>

        <script src="<?= base_url() ?>assets/polo/js/jquery.js"></script>
        <script src="<?= base_url() ?>assets/polo/js/plugins.js"></script>
        <script src="<?=base_url()?>assets/polo/datepicker/bootstrap-datepicker.js"></script>
        <!--Template functions-->
        <script src="<?= base_url() ?>assets/polo/js/functions.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/polo/js/lightslider.js"></script>
    </body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
    });
    <?php if($this->session->flashdata('message')) {?>
        $("#success_modal").modal('show');
    <?php }?>
    
</script>