<section id="page-content" class="sidebar-left">
    <div class="container">
        <div class="row">
            <!-- post content -->
            <div class="content col-lg-12">
                <!-- Portfolio -->
                    <!-- portfolio item -->
                        <form id="absence_report_form" method="post" action="<?= base_url() ?>attendance_report/export_csv" enctype="multipart/form-data">
                            <div class="col-md-12" style="padding:10px;">
                                <div class="form-group col-md-5" style="padding-top:20px">
                                    <div class="btn-group">
                                        <button type="button" onclick="javascript:process_overtime('<?= base_url() ?>', '<?= $this->router->fetch_class() ?>', 'absence_report_form', 'merchant_list');" class="btn btn-primary">Proses<i class="icon-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-hover table-bordered" id="merchant_list">
                            <thead>
                                <tr>
                                    <th>Merchant Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($merchant){
                                    foreach($merchant as $index => $value){?>
                                <tr><td><?=$value['merchant_name']?></td></tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            <div style="width:20%;margin:0 auto;">
                                <table class="footer-table">
                                    <tbody>
                                        <tr>
                                            <td><button onclick="updatelist_absen('merchant_list', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'first');" class="btn-first" type="button">&nbsp;</button></td>
                                            <td><button onclick="updatelist_absen('merchant_list', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'prev');" class="btn-prev" type="button">&nbsp;</button></td>
                                            <td><span class="ytb-sep"></span></td>
                                            <td><span class="ytb-text">Page</span></td>
                                            <td><input type="text" onkeyup="updatelist_absen('merchant_list', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'page', this.value);" size="3" value="<?php echo ($pnumber > 0) ? $pnumber : 1; ?>" class="pnumber"></td>
                                            <td><span class="ytb-text" id="totaldata_view">of <?php echo ceil($totaldata / $limit) ?></span></td>
                                            <td><span class="ytb-sep"></span></td>
                                            <td><button onclick="updatelist_absen('merchant_list', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'next');" class="btn-next" type="button">&nbsp;</button></td>
                                            <td><button onclick="updatelist_absen('merchant_list', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'last');" class="btn-last" type="button">&nbsp;</button></td>
                                            <td>
                                                <input type="hidden" id="limit" name="limit" value="0"/>
                                                <input type="hidden" id="limit_page" name="limit_page" value="<?= $limit ?>"/>
                                                <input type="hidden" id="totaldata" name="totaldata" value="<?php echo $totaldata; ?>"/>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
            </div>
            <!-- end: post content -->
            <!-- Sidebar-->
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yyyy'
//            startDate: '-3d'
        });
    });
</script>