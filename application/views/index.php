<script type="text/javascript" src="<?= base_url() ?>assets/application.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/paging.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/template/js/plugins/notifications/sweet_alert.min.js"></script>
<!-- BEGIN PAGE CONTENT-->
<div class="panel-heading">
    <h5 class="panel-title"><?php echo $title; ?><a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
</div>
<div class="panel-body">
    <div class="form-group col-md-5">
        <label class="control-label col-lg-2" style="padding-top:9px;">Unit</label>
        <div class="col-lg-10">
            <select class="select-search" field="unit_search" id="unit_search" name="unit_search">
                <?php foreach ($unitdata as $indexUnit => $valueUnit) { ?>
                    <option value="<?= $indexUnit ?>"><?= $valueUnit ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="form-group col-md-5">
        <label class="control-label col-lg-4" style="padding-top:9px;">Jumlah baris perhalaman</label>
        <div class="col-lg-6">
            <input type="text" id="jml_baris" name="jml_baris" value="" class="form-control" placeholder="">
        </div>
    </div>
    <div class="form-group col-md-2">
        <div class="btn-group">
            <button class="btn btn-primary" onclick="javascript:searchdata('tt_master_pengelolaan_sektor', '<?= base_url() ?>', '<?php echo $this->router->fetch_class(); ?>', this);">
                Search <i class="icon-plus"></i>
            </button>
        </div>
    </div>
</div>
<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer">
    <div class="datatable-header">
        <div class="dataTables_filter col-md-12">
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_form_sektor_tt">Add</button>
            </div>
            <div class="btn-group">
                <button class="btn btn-primary" onclick="javascript:delete_data('<?= base_url() ?>', '<?php echo $this->router->fetch_class(); ?>');">
                    Delete <i class="icon-plus"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="datatable-scroll"> 
        <table class="table table-bordered table-xs" id="tt_master_pengelolaan_sektor" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
                <tr>
                    <th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#" /></th>
                    <th>Unit</th>
                    <th>Sektor</th>
                    <th>Nama Sektor</th>
                    <th>No. Account</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                if ($dataList) {
                    foreach ($dataList as $index => $value) {
                        ?>
                        <tr class="odd gradeX">
                            <td><input type="checkbox" class="delcheck" value="<?php echo $value['id']; ?>" /></td>
                            <td><a href="#modal_form_sektor_tt"  class="edit_sektor" data-toggle="modal" data-id="<?= $value['id'] ?>" data-id_sektor="<?= $value['id_sektor'] ?>" data-unit="<?= $value['unit'] ?>" data-acc_num="<?= $value['acc_num'] ?>" data-kode_sektor="<?= $value['kode_sektor'] ?>" data-nama_sektor="<?= $value['nama_sektor'] ?>" data-bse_name="<?= $value['bse_name'] ?>"><?php echo $value['unit']; ?></a></td>
                            <td><?php echo $value['id_sektor']; ?></td>
                            <td><?php echo $value['nama_sektor']; ?></td>
                            <td><?php echo $value['acc_num']; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                }
                ?>
            <input type="hidden" id="totalRow" name="totalRow" value="<?= $i ?>"/>
            </tbody>
        </table>
    </div>
    <div class="datatable-footer">
        <div style="width:20%;margin:0 auto;">
            <table class="footer-table">
                <tbody>
                    <tr>
                        <td><button onclick="updatelist('tt_master_pengelolaan_sektor', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'first');" class="btn-first" type="button">&nbsp;</button></td>
                        <td><button onclick="updatelist('tt_master_pengelolaan_sektor', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'prev');" class="btn-prev" type="button">&nbsp;</button></td>
                        <td><span class="ytb-sep"></span></td>
                        <td><span class="ytb-text">Page</span></td>
                        <td><input type="text" onkeyup="updatelist('tt_master_pengelolaan_sektor', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'page', this.value);" size="3" value="<?php echo ($pnumber) ? $pnumber : 1; ?>" class="pnumber"></td>
                        <td><span class="ytb-text" id="totaldata_view">of <?php echo ceil($totaldata / 10) ?></span></td>
                        <td><span class="ytb-sep"></span></td>
                        <td><button onclick="updatelist('tt_master_pengelolaan_sektor', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'next');" class="btn-next" type="button">&nbsp;</button></td>
                        <td><button onclick="updatelist('tt_master_pengelolaan_sektor', '<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', 'last');" class="btn-last" type="button">&nbsp;</button></td>
                        <td>
                            <input type="hidden" id="limit" name="limit" value="0"/>
                            <input type="hidden" id="totaldata" name="totaldata" value="<?php echo $totaldata; ?>"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="modal_form_sektor_tt" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Sektor</h5>
            </div>

            <form action="<?=base_url()?>Tt_Master_Pengelolaan_Sektor/save" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Unit</label>
                                <select class="form-control select-search" field="unit" id="unit" name="unit">
                                    <?php foreach ($unitdata as $indexUnit => $valueUnit) { ?>
                                        <option value="<?= $indexUnit ?>"><?= $valueUnit ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Kode Sektor</label>
                                <input type="text" id="kode_sektor" name="kode_sektor" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Nama Sektor</label>
                                <input type="text" id="nama_sektor" name="nama_sektor" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>No. Account</label>
                                <input type="text" id="acc_num" name="acc_num" placeholder="" class="form-control" maxlength="4">
                                <input type="hidden" id="id" name="id" value="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <!--<button type="button" onclick="javascript:save_sektor('<?=base_url()?>','<?php echo $this->router->fetch_class(); ?>')" class="btn btn-primary">Save</button>-->
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        <?php if ($this->session->flashdata('info_message')){?>
            swal({
                title: "<?=($this->session->flashdata('info_message'))?>",
                text: "",
                confirmButtonColor: "#66BB6A",
                type: "success"
            });
    
        <?php } ?>
    });
</script>
<script type="text/javascript">
$('.edit_sektor').click(function () {
        var table_id = $(this).data('id');
        var id_sektor = $(this).data('id_sektor');
        var unit = $(this).data('unit');
        var acc_num = $(this).data('acc_num');
        var kode_sektor = $(this).data('kode_sektor');
        var nama_sektor = $(this).data('nama_sektor');
        var bse_name = $(this).data('bse_name');

        $(".modal-body #id").val(table_id);
        $(".modal-body #id_sektor").val(id_sektor);
        $(".modal-body #unit").val(unit);
        $(".modal-body #select2-unit-container").html(unit+' - '+bse_name);
        //get account
        get_account('<?php echo base_url(); ?>', '<?php echo $this->router->fetch_class(); ?>', unit);
        $(".modal-body #acc_num").val(acc_num);
        $(".modal-body #kode_sektor").val(kode_sektor);
        $(".modal-body #nama_sektor").val(nama_sektor);
    });
</script>

