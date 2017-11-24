<script>
    function TiXian(shangjia, trade_type, trade_no, trade_items, sender) {
        this.shangjia = shangjia;
        this.trade_type = trade_type;
        this.trade_no = trade_no;
        this.trade_items = trade_items;
        this.sender = sender;
    }

    function tradeTypeFormatter(value, row, index) {
        // 1: zaijiahe, 2: fujin, 3: maijiu
        if (value == 1) {
            return '在家';
        } else if (value == 2) {
            return '附近';
        } else if (value == 3) {
            return '买酒';
        }
    }

    function tradeNoFormatter(value, row, index) {
        return 'XXX-' + value;
    }

    function tradeItemsFormatter(value, row, index) {
        var result = '<div class="btn-group">';
        result += '<button class="btn btn-default">Trading Items</button>';
        result += '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></button>';
        result += '<ul class="dropdown-menu">';

        for (var i = 0; i < value.length; i++) {
            result += '<li>' + value[i].name + '  x  ' + value[i].count + '</li>';
        }
        result += '</ul>';
        result += '</div>';

        return result;
    }

    function payAmountFormatter(value, row, index) {
        var amount = 0;
        for (var i = 0; i < row.trade_items.length; i++) {
            amount += row.trade_items[i].price * row.trade_items[i].count;
        }

        return amount / 100 * 95;
    }

    function totalAmountFormatter(value, row, index) {
        var amount = 0;
        for (var i = 0; i < row.trade_items.length; i++) {
            amount += row.trade_items[i].price * row.trade_items[i].count;
        }

        return amount;
    }

    function operationFormatter(value, row, index) {
        return '<input type="button" class="btn btn-success btn-sm btn-problem" value="交易完成">';
    }

    function makeTixian (trade_type, trade_no) {
        // send SMS

        $.ajax({
            url : "tixian/update",
            type: 'post',
            dataType : "json",
            data : {
                "trade_type": trade_type,
                "trade_no" : trade_no
            },
            success : function(response){
                if (response.status == true) {
                    alert('successfully accepted');
                    $('#table').bootstrapTable('remove', {
                        field: 'trade_no',
                        values: [trade_no]
                    });
                } else {
                    alert('error occured');
                }
            }
        });
    }

    function setupTable(data) {
        $('#table').bootstrapTable('destroy');
        $('#table').bootstrapTable({
            columns: [
                {
                    field: 'shangjia',
                    title: '商家'
                },
                {
                    field: 'trade_type',
                    formatter: 'tradeTypeFormatter',
                    title: '交易类型'
                },
                {
                    field: 'trade_no',
                    formatter: 'tradeNoFormatter',
                    title: '交易代码'
                },
                {
                    field: 'trade_items',
                    formatter: 'tradeItemsFormatter',
                    title: '订单内容'
                },
                {
                    field: 'sender',
                    title: '买单用户'
                },
                {
                    field: 'amount',
                    formatter: 'totalAmountFormatter',
                    title: '支付金额'
                },
                {
                    field: 'fund',
                    formatter: 'payAmountFormatter',
                    title: '提现金额'
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

        $('#table tbody').on( 'click', '.btn-problem', function () {
            var index = $(this).parents('tr').data('index');
            var data = $('#table').bootstrapTable('getData')[index];
            var sj_id = data.sj_id;

            makeTixian(data.trade_type, data.trade_no);
        });
    }
</script>

<div class="panel panel-info">
    <div class="panel-heading">
        <h4>商家提现目录</h4>
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

    <?php foreach ($data as $tixian) { ?>
    var d = new TiXian(
        <?php echo $tixian['shangjia']; ?>,
        <?php echo $tixian['trade_type']; ?>,
        <?php echo $tixian['trade_no']; ?>,
        JSON.parse('<?php echo json_encode($tixian['trade_items']); ?>'),
        <?php echo $tixian['sender']; ?>
    );
    tblData.push(d);
    <?php } ?>

    setupTable(tblData);
</script>