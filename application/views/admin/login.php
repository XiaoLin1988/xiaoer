    <div class="row text-center ">
        <div class="col-md-12">
            <br /><br />
            <h2>小二上酒管理平台</h2>
            <br />
        </div>
    </div>
    <div class="row ">

        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>请输入账号密码</strong>
                </div>
                <div class="panel-body">
                    <form method="post" action="<?php echo base_url('index.php/admin/login/doLogin');?>" role="form">
                        <br />
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
                            <input type="text" name="username" class="form-control" placeholder="用户名" required />
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                            <input type="password" name="password" class="form-control"  placeholder="密码" required />
                        </div>
                        <div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox" /> 记住密码
                            </label>
                            <!--
                            <span class="pull-right">
                                   <a href="#" >忘记密码?</a>
                            </span>
                            -->
                        </div>

                        <input type="submit" class="btn btn-primary" value="登 录"/>
                        <hr />
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>