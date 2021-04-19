<?php
if(!function_exists('pp'))
{
    /**
     * 打印信息
     */
    function pp($params)
    {
        print_r($params);
        exit;
    }
}