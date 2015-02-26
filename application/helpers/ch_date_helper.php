<?php

function offsetFromMySqlDate($mySqlFormat, $offset) {
    return date('Y-m-d', strtotime($offset, strtotime($mySqlFormat)));
}

function dateCompare($firstDate, $secondDate) {
    return strtotime($firstDate) > strtotime($secondDate);
}

function datePickerToMysqlFormat($date) {
    $date = str_replace('/', '-', $date);
    $date = explode('-', $date);
    return $date[2] . '-' . $date[1] . '-' . $date[0];
}

function mysqlToDatepickerFormat($date) {
    $date = str_replace('-', '/', $date);
    $date = explode('/', $date);
    return $date[2] . '/' . $date[1] . '/' . $date[0];
}

function mysqlToUserFriendlyFormat($date) {
    return date("l jS \of F Y",strtotime($date));
}

function mysqlTimeFormat($twentyFourHour) {
    return date("H:i:s", strtotime($twentyFourHour . ":00"));
}

function twelveHourFormat($twentyFourHour) {
    return date("g:i a", strtotime($twentyFourHour . ":00"));
}

function mysqlNow() {
    return date("Y-m-d H:i:s");
}