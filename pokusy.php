<?php
output('jeden');
sleep(5);
output('dva');
sleep(5);
output('tri');
function output($str) {
    ob_implicit_flush(true);
    echo $str;
    ob_end_flush();
    //ob_flush();
    flush();
    ob_start();
}