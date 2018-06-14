<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台登录</title>
    <link href="./Public/css/admin_login.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="admin_login_wrap">
    <h1>后台管理</h1>
    <div class="adming_login_border">
        <div class="admin_input">
            <form  method="post">
                <ul class="admin_items">
                    <li>
                        <label for="user">用户名：</label>
                        <input type="text" name="username"  id="user" size="35" class="admin_input_style" />
                    </li>
                    <li>
                        <label for="pwd">密码：</label>
                        <input type="password" name="password"  id="pwd" size="35" class="admin_input_style" />
                    </li>
                    <li>
                        <input type="button" tabindex="3" value="提交" class="btn btn-primary" onclick="login.check()" />
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>
<script src="./Public/js/jquery.js"></script>
<script src="./Public/js/dialog/layer.js"></script>
<script src="./Public/js/dialog.js"></script>
<script src="./Public/js/admin/login.js"></script>
</body>
</html>