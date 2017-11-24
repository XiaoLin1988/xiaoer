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
        return '<input type="button" class="btn btn-success btn-sm btn-problem" value="Complete">';
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
                    if (value == 1) {
                        alert('successfully accepted');
                        $('#table').bootstrapTable('remove', {
                            field: 'trade_no',
                            values: [sj_id]
                        });
                    } else if (value == 0) {
                        alert('successfully denied');
                    }
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
                    title: 'Shangjia'
                },
                {
                    field: 'trade_type',
                    formatter: 'tradeTypeFormatter',
                    title: 'Trade Type'
                },
                {
                    field: 'trade_no',
                    formatter: 'tradeNoFormatter',
                    title: 'Trade Number'
                },
                {
                    field: 'trade_items',
                    formatter: 'tradeItemsFormatter',
                    title: 'Trade Items'
                },
                {
                    field: 'sender',
                    title: 'Sender'
                },
                {
                    field: 'amount',
                    formatter: 'totalAmountFormatter',
                    title: 'Amount'
                },
                {
                    field: 'fund',
                    formatter: 'payAmountFormatter',
                    title: 'Amount to Pay'
                },
                {
                    field: 'operation',
                    formatter: 'operationFormatter',
                    title: 'Operation'
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