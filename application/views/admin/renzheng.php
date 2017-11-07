<script>
    function RenZheng (sj_id, sj_name, sj_type, sj_stime, sj_etime, sj_addr, sj_phone, avatar, yh_id, yh_name, yh_headimgurl, renzhengImages, sj_ctime) {
        this.sj_id = sj_id;
        this.sj_name = sj_name;
        this.sj_type = sj_type;
        this.sj_stime = sj_stime;
        this.sj_etime = sj_etime;
        this.sj_addr = sj_addr;
        this.sj_phone = sj_phone;
        this.avatar = avatar;
        this.yh_id = yh_id;
        this.yh_name = yh_name;
        this.yh_headimgurl = yh_headimgurl;
        this.renzhengImages = renzhengImages;
        this.sj_ctime = sj_ctime;
    }

    function operationFormatter(value, row, index) {
        var btnAccept = "";
        var btnDeny = "";
        var btnDetail = "";

        btnAccept = '<button class="btn btn-success btn-xs btn-accept">通过</button>';
        btnDeny = '<button class="btn btn-danger btn-xs btn-deny">不通过</button>';
        btnDetail = '<button class="btn btn-info btn-xs btn-detail">查看资料</button>';

        return '<div style="white-space: nowrap;">' + btnAccept + "&nbsp;&nbsp;&nbsp;" + btnDeny + "&nbsp;&nbsp;&nbsp;" + btnDetail + '</div>';
    }

    function imgLinkFormatter(value, row, index) {
        return '<a href="' + value + '">' + value + '</a>';
    }

    function setTableButtonListener() {
        $('#table tbody').on( 'click', '.btn-accept', function () {
            var index = $(this).parents('tr').data('index');
            var data = $('#table').bootstrapTable('getData')[index];
            var sj_id = data.sj_id;

            approveShangjia(sj_id, 1);
        });

        $('#table tbody').on('click', '.btn-deny', function () {
            var index = $(this).parents('tr').data('index');
            var data = $('#table').bootstrapTable('getData')[index];
            var rzId = data.rzId;

            $.ajax({
                url : "renzheng/update",
                type: 'post',
                dataType : "json",
                data : {
                    "sj_aprd": 0,
                    "shangjiaId" : sj_id
                },
                success : function(response){
                    if (response.status == true) {
                        alert('successfully denied');
                    } else {
                        alert('deny failed');
                    }
                }
            });
        });

        $('#table tbody').on( 'click', '.btn-detail', function () {
            var index = $(this).parents('tr').data('index');
            var data = $('#table').bootstrapTable('getData')[index];

            openShangjiaModal(data);
        });
    }

    function approveShangjia(sj_id, value) {
        $.ajax({
            url : "renzheng/update",
            type: 'post',
            dataType : "json",
            data : {
                "sj_aprd": value,
                "shangjiaId" : sj_id
            },
            success : function(response){
                if (response.status == true) {
                    if (value == 1) {
                        alert('successfully accepted');
                        $('#table').bootstrapTable('remove', {
                            field: 'sj_id',
                            values: [sj_id]
                        });
                    } else if (value == 2) {
                        alert('successfully denied');
                    }
                } else {
                    alert('error occured');
                }
            }
        });
    }

    function openShangjiaModal(data) {
        document.getElementById("dlg_shangjia_preview").src = data.avatar;
        document.getElementById("dlg_shangjia_name").innerHTML = data.sj_name;
        document.getElementById("dlg_shangjia_type").innerHTML = data.sj_type == 1 ? '酒楼' : '酒行';
        document.getElementById("dlg_shangjia_addr").innerHTML = data.sj_addr;
        document.getElementById("dlg_shangjia_phone").innerHTML = data.sj_phone;
        document.getElementById("dlg_shangjia_owner").innerHTML = data.yh_name;
        document.getElementById("dlg_shangjia_license").src = data.renzhengImages;

        shangjia = data;
        $('#shangjiaModal').modal();
    }

    function setupTable(tblData) {
        $('#table').bootstrapTable('destroy');
        $('#table').bootstrapTable({
            columns: [
                {
                    field: 'sj_ctime',
                    title: '申请时间'
                },
                {
                    field: 'yh_name',
                    title: '门店经理'
                },
                {
                    field: 'sj_name',
                    title: '商家名'
                },
                {
                    field: 'sj_phone',
                    title: '联系电话'
                },
                {
                    field: 'renzhengImages',
                    formatter: 'imgLinkFormatter',
                    title: '营业执照'
                },
                {
                    field: 'operation',
                    formatter: 'operationFormatter',
                    title: ''
                }
            ],
            data: tblData
        });
        $('#table').bootstrapTable('refresh');
        /*
        $('#table > tbody > tr').click(function(e) {
            var index = $(this).data('index');
            var data = $('#table').bootstrapTable('getData')[index];

            openShangjiaModal(data);
        });
        */

        setTableButtonListener();
    }

    var shangjia = null;

    $(document).ready(function(){
        $('#btnAccept').on('click', function(e){
            approveShangjia(shangjia.sj_id, 1);

            $('#shangjiaModal').modal('hide');
        });

        $('#btnDeny').on('click', function(e){
            approveShangjia(shangjia.sj_id, 0);

            $('#shangjiaModal').modal('hide');
        });
    });
</script>

<div class="panel panel-info">
    <div class="panel-heading">
        <h4>商家认证目录</h4>
    </div>
    <div class="panel-body">
        <table id="table" class="table"
               data-height="800"
               data-search="false"
               data-show-columns="true"
               data-pagination="true"
               data-page-size="20"
               data-page-list="[10,20]"
               data-smart-display="true" >
        </table>
    </div>
</div>

<div id="shangjiaModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">商家信息</h5>
            </div>
            <div class="modal-body" style="height: 70vh; overflow-y: scroll;">
                <div class="row" style="text-align: center">
                    <img id="dlg_shangjia_preview" src="<?php echo base_url('assets/img/shangjia.png'); ?>" style="width: 95%; height: 250px;">
                </div>
                <div class="row">
                    <div id="dlg_shangjia_name" class="col-sm-9" style="font-size: 25px;"></div>
                    <div id="dlg_shangjia_type" style="font-size: 14px; display: table-cell; vertical-align: middle; height: 50px;"></div>
                </div>
                <div id="dlg_shangjia_addr" style="font-size: 14px;"></div>
                <div id="dlg_shangjia_phone" style="font-size: 14px;"></div>
                <div id="dlg_shangjia_owner" style="font-size: 14px;"></div>
                <div style="font-size: 14px;">营业执照</div>
                <img id="dlg_shangjia_license" style="width: 100%;">
            </div>
            <div class="modal-footer">
                <button type="button" id="btnAccept" class="btn btn-success">通过</button>
                <button type="button" id="btnDeny" class="btn btn-danger">不通过</button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<script>
    var tblData = [];

    <?php foreach ($data as $renzheng) { ?>
        var d = new RenZheng(
            <?php echo $renzheng['sj_id']; ?>,
            '<?php echo $renzheng['sj_name']; ?>',
            '<?php echo $renzheng['sj_type']; ?>',
            '<?php echo $renzheng['sj_stime']; ?>',
            '<?php echo $renzheng['sj_etime']; ?>',
            '<?php echo $renzheng['sj_addr']; ?>',
            '<?php echo $renzheng['sj_phone']; ?>',
            '<?php echo base_url($renzheng['avatar']); ?>',
            '<?php echo $renzheng['yh_id']; ?>',
            '<?php echo $renzheng['yh_name']; ?>',
            '<?php echo base_url($renzheng['yh_headimgurl']); ?>',
            '<?php echo base_url($renzheng['renzhengImages']); ?>',
            '<?php echo date('Y:m:d H:i:s', $renzheng['sj_ctime']); ?>'
        );
        tblData.push(d);
    <?php } ?>

    setupTable(tblData);
</script>