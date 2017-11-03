<script>
    function RenZheng (sj_id, sj_name, sj_type, sj_stime, sj_etime, sj_addr, sj_phone, yh_id, yh_name, yh_headimgurl, renzhengImages, sj_ctime) {
        this.sj_id = sj_id;
        this.sj_name = sj_name;
        this.sj_type = sj_type;
        this.sj_stime = sj_stime;
        this.sj_etime = sj_etime;
        this.sj_addr = sj_addr;
        this.sj_phone = sj_phone;
        this.yh_id = yh_id;
        this.yh_name = yh_name;
        this.yh_headimgurl = yh_headimgurl;
        this.renzhengImages = renzhengImages;
        this.sj_ctime = sj_ctime;
    }

    function operationFormatter(value, row, index) {
        var btnAccept = "";
        var btnDeny = "";

        btnAccept = '<button class="btn btn-success btn-xs btn-accept">通过</button>';
        btnDeny = '<button class="btn btn-danger btn-xs btn-deny">失败</button>';

        return '<div style="white-space: nowrap;">' + btnAccept + "&nbsp;&nbsp;&nbsp;" + btnDeny + '</div>';
    }

    function imgLinkFormatter(value, row, index) {
        return '<a href="' + value + '">' + value + '</a>';
    }

    function setTableButtonListener() {
        $('#table tbody').on( 'click', '.btn-accept', function () {
            var index = $(this).parents('tr').data('index');
            var data = $('#table').bootstrapTable('getData')[index];
            var rzId = data.rzId;

            alert(rzId);
        });

        $('#table tbody').on('click', '.btn-deny', function () {
            var index = $(this).parents('tr').data('index');
            var data = $('#table').bootstrapTable('getData')[index];
            var rzId = data.rzId;

            alert(rzId);
        });
    }

    function openShangjiaModal(data) {
        document.getElementById("dlg_shangjia_name").innerHTML = data.sj_name;
        document.getElementById("dlg_shangjia_type").innerHTML = data.sj_type == 1 ? '지우로우' : '지우항';
        document.getElementById("dlg_shangjia_addr").innerHTML = data.sj_addr;
        document.getElementById("dlg_shangjia_phone").innerHTML = data.sj_phone;
        document.getElementById("dlg_shangjia_owner").innerHTML = data.yh_name;
        document.getElementById("dlg_shangjia_license").src = data.renzhengImages;

        $('#shangjiaModal').modal();
    }

    function setupTable(tblData) {
        $('#table').bootstrapTable('destroy');
        $('#table').bootstrapTable({
            columns: [
                {
                    field: 'sj_ctime',
                    title: '요청시간'
                },
                {
                    field: 'yh_name',
                    title: '창조한 사람'
                },
                {
                    field: 'sj_name',
                    title: '상점명'
                },
                {
                    field: 'sj_phone',
                    title: '련계전화번호'
                },
                {
                    field: 'renzhengImages',
                    formatter: 'imgLinkFormatter',
                    title: '영업허가증'
                },
                {
                    field: 'operation',
                    formatter: 'operationFormatter',
                    title: '조작'
                }
            ],
            data: tblData
        });
        $('#table').bootstrapTable('refresh');

        $('#table > tbody > tr').click(function() {
            var index = $(this).data('index');
            var data = $('#table').bootstrapTable('getData')[index];

            openShangjiaModal(data);
        });

        setTableButtonListener();
    }
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
            <div class="modal-body">
                <div class="row" style="text-align: center">
                    <img id="dlg_shangjia_preview" src="<?php echo base_url('assets/img/shangjia.png'); ?>" style="width: 80%;">
                </div>
                <div id="dlg_shangjia_name"></div>
                <div id="dlg_shangjia_type"></div>
                <div id="dlg_shangjia_addr"></div>
                <div id="dlg_shangjia_phone"></div>
                <div id="dlg_shangjia_owner"></div>
                <div>영업허가증</div>
                <img id="dlg_shangjia_license" src="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success">通过</button>
                <button type="button" class="btn btn-danger">失败</button>
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