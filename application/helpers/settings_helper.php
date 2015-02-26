<?php

function getTimesInDayMapper() {
    $hours = array();
    for ($i = 1; $i <= 24; $i++) {
        $hours[mysqlTimeFormat($i)] = twelveHourFormat($i);
    }
    return $hours;
}

function getPositionMapper() {
    return array(
        1 => '1st',
        2 => '2nd',
        3 => '3rd',
        4 => '4th',
        5 => '5th',
        6 => '6th',
        7 => '7th',
        8 => '8th',
        9 => '9th',
        10 => '10th',
    );
}

function renderInfluencesSettings($influences, $tableClass) {
    $form = form_open('influences',  array('class' => 'influences-settings', 'id' => 'influences-settings', 'action' => '/settings'));
    $timeMapper = getTimesInDayMapper();
    $timeMapperDomReady = json_encode($timeMapper);
    $form .= '<div class="hidden times">' . $timeMapperDomReady . '</div>';
    $data = array();
    $data[] = array('Name', 'Time', 'Actions');
    foreach ($influences as $influence) {
        $p_test = '<p class="can-edit editable_textarea" id="influence-name-' . $influence->id . '">' . $influence->name . '</p>';
        $currentText = 'Current (' . $timeMapper[$influence->time_taken] . ')';
        $select_test = '<b class="can-edit editable_select time_select" id="influence-time_taken-' . $influence->id . '" style="display: inline">' . $currentText . '</b>';

        $remove = '<span class="can-edit remove remove-influence" data-id="' . $influence->id . '">Remove</span>';
        $add = '<span class="can-edit add add-influence" data-id="' . $influence->id . '">Add New</span>';
        $dataEntry = array($p_test, $select_test, implode(' | ', array($remove, $add)));
        $data[] = $dataEntry;
    }
    $form .= $tableClass->generate($data);
    $form .= form_close();
    return $form;
}

function renderAspectsSettings($aspects, $tableClass) {
    $form = form_open('influences',  array('class' => 'influences-settings', 'id' => 'influences-settings', 'action' => '/settings'));
    $positionMapper = getPositionMapper();
    $positionMapperDomReady = json_encode($positionMapper);
    $form .= '<div class="hidden positions">' . $positionMapperDomReady . '</div>';
    $data = array();
    $data[] = array('Name', 'Order', 'Actions');
    foreach ($aspects as $aspect) {
        $p_test = '<p class="can-edit editable_textarea" id="health_aspect-name-' . $aspect->id . '">' . $aspect->name . '</p>';
        $currentText = 'Current (' . $positionMapper[$aspect->display_order] . ')';
        $select_test = '<b class="can-edit editable_select display_order" id="influence-display_order-' . $aspect->id . '" style="display: inline">' . $currentText . '</b>';

        $remove = '<span class="can-edit remove remove-aspect" data-id="' . $aspect->id . '">Remove</span>';
        $add = '<span class="can-edit add add-aspect" data-id="' . $aspect->id . '">Add New</span>';
        $dataEntry = array($p_test, $select_test, implode(' | ', array($remove, $add)));
        $data[] = $dataEntry;
    }
    $form .= $tableClass->generate($data);
    $form .= form_close();
    return $form;
}

function prepareUpdateValue($field, $value) {
    switch ($field) {
        case 'name':
            return trim($value);
            break;
        case 'display_order':
            return (int) $value;
            break;
        case 'time_taken':
            return $value;
            break;
    }
}

function prepareReturnValue($field, $value) {
    switch ($field) {
        case 'name':
            return $value;
            break;
        case 'display_order':
            $positionMapper = getPositionMapper();
            return 'Current (' . $positionMapper[$value] . ')';
        case 'time_taken':
            $value = (int) $value;
            return 'Current (' . twelveHourFormat($value) . ')';
            break;
    }
}