<script>
    function RenZheng (rzId, rzDate, rzName, rzContactNumber, rzLicense, rzAgreeDate, rzStatus) {
        this.rzId = rzId;
        this.rzDate = rzDate;
        this.rzName = rzName;
        this.rzContactNumber = rzContactNumber;
        this.rzLicense = rzLicense;
        this.rzAgreeDate = rzAgreeDate;
        this.rzStatus = rzStatus;
    }

    function operationFormatter(value, row, index) {
        var btnAccept = "";
        var btnDeny = "";

        /*
        if (row.rzStatus == 1) {

        }
        */
        btnAccept = '<button class="btn btn-success btn-xs btn-accept">通过</button>';
        btnDeny = '<button class="btn btn-danger btn-xs btn-deny">失败</button>';

        return '<div style="white-space: nowrap;">' + btnAccept + "&nbsp;&nbsp;&nbsp;" + btnDeny + '</div>';
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

    function setupTable(tblData) {
        $('#table').bootstrapTable('destroy');
        $('#table').bootstrapTable({
            columns: [
                {
                    field: 'rzDate',
                    title: '申请日期'
                },
                {
                    field: 'rzName',
                    title: '用户名'
                },
                {
                    field: 'rzImage',
                    title: '用户 image'
                },
                {
                    field: 'rzContactNumber',
                    title: '联系人电话'
                },
                {
                    field: 'rzLicense',
                    title: '营业执照'
                },
                {
                    field: 'rzAgreeDate',
                    title: '审核通过日期'
                },
                {
                    field: 'operation',
                    formatter: 'operationFormatter',
                    title: '操作'
                }
            ],
            data: tblData
        });
        $('#table').bootstrapTable('refresh');

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

<script>
    var tblData = [];




    var rz1 = new RenZheng(1, "2017/11/2", "王一", "18715250377", "LN_185616616", "2017/11/4", "通过");
    tblData.push(rz1);
    var rz2 = new RenZheng(2, "2017/11/3", "王一", "18715250377", "LN_185616616", "2017/11/4", "通过");
    tblData.push(rz2);
    var rz3 = new RenZheng(3, "2017/11/4", "王一", "18715250377", "LN_185616616", "2017/11/4", "通过");
    tblData.push(rz3);
    var rz4 = new RenZheng(4, "2017/11/5", "王一", "18715250377", "LN_185616616", "2017/11/4", "通过");
    tblData.push(rz4);

    setupTable(tblData);
</script>