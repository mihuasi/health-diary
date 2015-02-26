<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of trackerViewHelper
 *
 * @author Joe
 */

function setSuggestions($items, $suggestions) {
    foreach ($suggestions as $itemId => $suggestion) {
        foreach ($items as $item) {
            if ($item->id == $itemId) {
                $item->suggestions = $suggestion;
            }
        }
    }
    return $items;
}

function setCurrent($items, $current) {
    foreach ($current as $itemId => $element) {
        foreach ($items as $item) {
            if ($item->id == $itemId) {
                $item->current = $element;
            }
        }
    }
    return $items;
}

function setCurrentRatings($items, $current) {
    foreach ($current as $itemId => $element) {
        foreach ($items as $item) {
            if ($item->id == $itemId) {
                $item->rating = $element;
            }
        }
    }
    return $items;
}

function renderFormSection($aspects, $class, $includeRating = false) {
    $aspectsEl = '<ul class="use-tags ' . $class . '">';
    foreach ($aspects as $aspect) {
        $suggestions = isset($aspect->suggestions) ? implode(DB_DELIMITER, $aspect->suggestions) : '';
        $dataSuggestions = ' data-suggestions=\'' . $suggestions . '\' ';
        $aspectDomId = 'aspect-' . $aspect->id;
        $aspectsEl .= '<ol id="' . $class . '-' . $aspect->id . '" ' . $dataSuggestions . '>';
        $aspectsEl .= form_label($aspect->name, $aspectDomId);
        if (isset($aspect->current)) {
            $aspectsEl .= '<li>' . implode('</li><li>', $aspect->current) . '</li>';
        }
        if ($includeRating) {
            $class = 'rating rating-' . $aspectDomId;
            $name = 'rating-' . $aspectDomId;
            $selectedRating = (isset($aspect->rating)) ? (int) $aspect->rating : 10;
            $aspectsEl .= upToRating($class, $name, 10, $selectedRating);
        }
        $aspectsEl .= '</ol>';
    }
    $aspectsEl .= '</ul>';
    return $aspectsEl;
}

function upToRating($class, $name, $to = 10, $selected = 10) {
    $html = '<select name="' . $name . '" class="' . $class . '">';
    for ($i = 0; $i <= $to; $i++) {
        $selText = ($i === $selected) ? ' selected="selected" ' : '';
        $html .= '<option value="' . $i . '" ' . $selText . '>' . $i . '</option>';
    }
    $html .= '</select>';
    return $html;
}

function renderForm($currentDay, $aspects, $influences, $isNew = true) {
    $form = form_open('tracker',  array('class' => 'tracker_new_entry', 'id' => 'tracker_new_entry', 'action' => '/tracker'));

    $dateFieldData = array(
              'name'        => 'Date',
              'id'          => 'datepicker',
              'value'       => mysqlToDatepickerFormat($currentDay)
            );
    $textualDate = '<span class="textualDate" data-isnew="' . (($isNew) ? '1' : '0') . '">' . mysqlToUserFriendlyFormat($currentDay) . '</span>';
    $textualDateExplain = ' [' . (($isNew) ? 'NEW' : 'EDIT') . '] ';
    $textualDateFull = '<h2 class="textualDateFull">' . $textualDate . $textualDateExplain . '</h2>';

    $dateField = '<div class="dateContainer">' . form_input($dateFieldData) . $textualDateFull . '</div>';
    
    $aspectsEl = renderFormSection($aspects, 'aspects', true);
    $influencesEl =  renderFormSection($influences, 'influences');

    $data = array(
    'name' => 'submitEntry',
    'id' => 'submitEntry',
    'content' => 'Save'
    );

    $form .= $dateField . $influencesEl . $aspectsEl . form_button($data);

    $form .= form_close();

    return $form;
}

function renderPreviousDays($previousDays) {
    $output = '';
    foreach ($previousDays as $date => $previousDay) {
        $datePickerFormatted = mysqlToDatepickerFormat($date);
        $dayOutput = '<ul name="slide-to-' . $datePickerFormatted . '" class="previous-day previous-day-' . $date . '">';
        $editIcon = '<img src="' . base_url() . 'images/edit-4.png" alt="Edit" title="Edit Entry" data-date="' . $date . '" class="editEntry" />';
        $dayOutput .= '<h2>' . mysqlToUserFriendlyFormat($date) . $editIcon . '</h2>';

        $dayOutput .= listStaticItems($previousDay->influences);
        $dayOutput .= listStaticItems($previousDay->aspects);
        $dayOutput .= '</ul>';
        $output .= $dayOutput;
    }
    return $output;
}

function listStaticItems($items) {
    $output = '<li>';
    if (!empty($items)) {
        foreach ($items as $item) {
            $values = '<li>' . implode('</li><li>', $item->comments) . '</li>';
            if (is_numeric($item->value)) {
                $values =  '<li>' . $item->value . '</li>' . $values;
            }
            $output .= '<ol class="dayInfluence dayItem readOnlyTags">';
            $output .= '<span class="category">' . $item->name . '</span> ' . $values;
            $output .= '</ol>';
        }
    }
    $output .= '</li>';
    return $output;
}