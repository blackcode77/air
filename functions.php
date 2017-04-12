<?php

function debug($arr, $message = null){
    echo '<pre>'.$message . print_r($arr, true) . '</pre>';
}

function debug_($arr, $message = null){
    echo '<pre>'.$message . print_r($arr, true) . '</pre>';
    die;
}