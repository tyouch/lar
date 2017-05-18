<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/5/17
 * Time: 10:27
 */
?>

<nav class="pager-top">
    <ul class="pager pull-right">
        <li class="previous"><a href="<?= $p>1 ? $p-1 : $p ?>">上一页</a></li>
        <li class=""><a href="javascript:;"><?= $p . '/' . $pm ?></a></li>
        <li class="next"><a href="<?= $p<$pm ? $p+1 : $pm ?>">下一页</a></li>
    </ul>
</nav>
