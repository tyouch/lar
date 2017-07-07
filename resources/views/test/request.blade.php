<?php
/**
 * Created by PhpStorm.
 * User: CH
 * Date: 2017/6/9
 * Time: 20:48
 */
?>
<?php
/**
 * Created by PhpStorm.
 * User: CH
 * Date: 2017/6/9
 * Time: 20:47
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style>
        body{margin-top: 30px;}
    </style>
</head>
<body>
<div class="container">
    <form name="ogwForm" method="post" action="<?= url('hxcalling/tx') ?>">
        <input type="hidden" name="RequestData" value='1' encoding="utf-8">
        <input type="hidden" name="transCode" value="2">
        {{ csrf_field() }}
        <input type="submit" class="btn btn-warning" value="TEST">
    </form>
</div>
</body>
</html>

