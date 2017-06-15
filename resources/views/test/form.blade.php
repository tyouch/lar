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
    <title>GetFormData</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style>
        body{margin-top: 30px;}
        .breakLine{width: 100%; word-break: break-all;}
    </style>
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">Form</div>
        <table class="table">
            <tr>
                <td>action：</td>
                <td>{{ $data['action'] }}</td>
            </tr>
            <tr>
                <td>RequestData：</td>
                <td class="breakLine"><?=str_replace(chr(32), '&nbsp;', htmlspecialchars($data['RequestData'])) ?></td>
            </tr>
            <tr>
                <td>plainText：</td>
                <td id="plainText">{{ htmlspecialchars($data['plainText']) }}</td>
            </tr>
            <tr>
                <td>transCode：</td>
                <td>{{ $data['transCode'] }}</td>
            </tr>
        </table>
    </div>

    <form name="ogwForm" method="post" action="<?= $data['action'] ?>">
        <input type="hidden" name="RequestData" value='<?= $data['RequestData'] ?>' encoding="utf-8">
        <input type="hidden" name="transCode" value="<?= $data['transCode'] ?>">
        <input type="submit" class="btn btn-warning" value="TEST">
    </form>

    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script>
        //var plainText = $("#plainText").html();
        //var plainText2 = plainText.replace('channelFlow', '<span sytle="color: red;">channelFlow</span>');
        //$("#plainText").html(plainText2);
    </script>
</div>
</body>
</html>
