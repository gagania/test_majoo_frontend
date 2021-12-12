<script src="<?= base_url() ?>assets/polo/js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/application.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/paging.js"></script>
<section id="page-content" class="sidebar-left">
    <div class="container">
        <div class="row">
            <!-- post content -->
            <div class="content col-lg-12">
                <!-- Portfolio -->
                    <!-- portfolio item -->
                        <form id="merchant_omzet_form" method="post" action="?" enctype="multipart/form-data">
                            <div class="col-md-12" style="padding:10px;">
                                <div class="col-md-12 row">
                                    <div class="form-group row col-md-5">
                                        <label class="control-label col-lg-4" style="padding-top:9px;">Merchant</label>
                                        <div class="col-md-7">
                                            <select class="form-control select2" id="merchant_id" name="merchant_id"
                                                    onchange="javascript:get_outlets_by_merchant('<?=base_url()?>',
                                                                '<?=$this->router->fetch_class()?>',this)">
                                                    <option value="">-- Select All --</option>
                                                    <?php foreach($merchant as $index => $value){?>
                                                    <option value="<?=$value['id']?>"><?=$value['merchant_name']?></option>
                                                    <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row col-md-5">
                                        <label class="control-label col-lg-4" style="padding-top:9px;">Outlet</label>
                                        <div class="col-md-7">
                                            <select style="" class="form-control select2" id="outlet_id" name="outlet_id">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 row">
                                    <div class="form-group row col-md-5">
                                        <label class="control-label col-lg-4" style="padding-top:9px;">Dari Tanggal</label>
                                        <div class="col-md-7 input-group date" data-provide="datepicker">
                                            <input id="date_from" name="date_from" type="text" class="form-control datepicker" value="<?=date('d/m/Y')?>"/>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row col-md-5">
                                        <label class="control-label col-lg-4" style="padding-top:9px;">S.D</label>
                                        <div class="col-md-7 input-group date" data-provide="datepicker">
                                            <input id="date_to" name="date_to" type="text" class="form-control datepicker" value="<?=date('d/m/Y')?>"/>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-5" style="padding-top:20px">
                                    <div class="btn-group">
                                        <button type="button" onclick="javascript:process_outlet_data('<?= base_url() ?>', '<?= $this->router->fetch_class() ?>', 'merchant_omzet_form', 'outlet_list');" class="btn btn-primary">Proses<i class="icon-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-hover table-bordered" id="outlet_list">
                            <thead>
                                <tr>
                                    <th>Merchant Name</th>
                                    <th>Outlet Name</th>
                                    <th>Date</th>
                                    <th>Omzet</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            <div style="width:20%;margin:0 auto;">
                                <table class="footer-table">
                                    <tbody>
                                        <tr>
                                            <td><button onclick="updatelist_data('outlet_list', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'first');" class="btn-first" type="button">&nbsp;</button></td>
                                            <td><button onclick="updatelist_data('outlet_list', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'prev');" class="btn-prev" type="button">&nbsp;</button></td>
                                            <td><span class="ytb-sep"></span></td>
                                            <td><span class="ytb-text">Page</span></td>
                                            <td><input type="text" onkeyup="updatelist_data('outlet_list', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'page', this.value);" size="3" value="<?php echo ($pnumber > 0) ? $pnumber : 1; ?>" class="pnumber"></td>
                                            <td><span class="ytb-text" id="totaldata_view">of <?php echo ceil($totaldata / $limit) ?></span></td>
                                            <td><span class="ytb-sep"></span></td>
                                            <td><button onclick="updatelist_data('outlet_list', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'next');" class="btn-next" type="button">&nbsp;</button></td>
                                            <td><button onclick="updatelist_data('outlet_list', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'last');" class="btn-last" type="button">&nbsp;</button></td>
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
        initPaging();
        $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yyyy'
//            startDate: '-3d'
        });
    });
</script>