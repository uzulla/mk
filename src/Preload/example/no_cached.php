<?php

function heavy_calc_sample(){
    usleep(10*1000);
    return ini_get_all();
}

$val = heavy_calc_sample();

// 単なるテスト出力
var_export($val);
