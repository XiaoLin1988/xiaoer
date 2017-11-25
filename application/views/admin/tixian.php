<script>
    function TiXian(shangjia, trade_type, trade_no, trade_items, sender, yonghu_name, shangjia_name, card_avatar) {
        this.shangjia = shangjia;
        this.trade_type = trade_type;
        this.trade_no = trade_no;
        this.trade_items = trade_items;
        this.sender = sender;
        this.yonghu_name = yonghu_name;
        this.shangjia_name = shangjia_name;
        this.card_avatar = card_avatar;
    }

    function leftPad(number, targetLength) {
        var output = number + '';
        while (output.length < targetLength) {
            output = '0' + output;
        }
        return output;
    }

    function tradeTypeFormatter(value, row, index) {
        // 1: maijiu, 2: qingke, 3: fujin, 4: jicun
        if (value == 1) {
            return '买酒';
        } else if (value == 2) {
            return '请客';
        } else if (value == 3) {
            return '附近';
        } else if (value == 4) {
            return '寄存';
        }
    }

    function tradeNoFormatter(value, row, index) {
        var code = "";
        if (row.trade_type == 1)
            code = "MJ";
        else if (row.trade_type == 2)
            code = "QK";
        else if (row.trade_type == 3)
            code = "FJ";
        else if (row.trade_type == 4)
            code = "JC";
        return code + "-" + leftPad(value, 8);
    }

    function tradeItemsFormatter(value, row, index) {
        var result = '<div class="btn-group">';
        result += '<button class="btn btn-default">详细</button>';
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
        var amount = amount / 100 * 95;
        return amount.toFixed(2);
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

    function cardFormatter(value, row, index) {
        return '<a href="' + value + '">查看</a>';
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
                    swal("操作成功!", "", "success");
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
                    field: 'shangjia_name',
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
                },/*
                {
                    field: 'trade_items',
                    formatter: 'tradeItemsFormatter',
                    title: '订单内容'
                },*/
                {
                    field: 'yonghu_name',
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
                    field: 'card_avatar',
                    formatter: 'cardFormatter',
                    title: '银行卡照片'
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

            swal({
                title: "确定交易完成?",
                text: "在您确认提现完成的前提下进行确认!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "取消",
                confirmButtonText: "是的， 我已经完成提现",
                closeOnConfirm: false
            }, function(){
                makeTixian(data.trade_type, data.trade_no);
            });
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
        <?php echo $tixian['sender']; ?>,
        "<?php echo $tixian['yonghu_name']; ?>",
        "<?php echo $tixian['shangjia_name']; ?>",
        "<?php echo base_url($tixian['card_avatar']); ?>"
    );
    tblData.push(d);
    <?php } ?>

    setupTable(tblData);
</script>