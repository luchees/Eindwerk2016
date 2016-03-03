<?php

// ALS JE JE VERVEELT KAN JE BUILD_TABLE FUNCTIE HERSCHRIJVEN NAAR 1 FUNCTIE D.M.V. BOOLEAN OF CSS INCLUDEN MET PARAMETERS

function build_table($array)
{
    // start table
    $html = '<table id="table1">';
    // header row
    $html .= '<tr>';
    foreach ($array[0] as $key => $value) {
        $html .= '<th>' . $key . '</th>';
    }
    $html .= '</tr>';

    // data rows
    foreach ($array as $key => $value) {
        $html .= '<tr>';
        foreach ($value as $key2 => $value2) {
            $html .= '<td>' . $value2 . '</td>';
        }
        $html .= '</tr>';
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

function build_table_css($array)
{
    // start table
    $html = '<table style="border-collapse: collapse;width: 400px; border: 1px solid black;" id="table1">';
    // header row
    $html .= '<tr>';
    foreach ($array[0] as $key => $value) {
        $html .= '<th style="border: 1px solid black; font-size: 11px; background-color: #d2edf4; background-image: linear-gradient(to bottom, #d0edf5, #e1e5f0 100%); border-right: 1px solid #C1DAD7; border-left: 1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; border-top: 1px solid #C1DAD7; letter-spacing: 2px; text-transform: uppercase; padding: 6px 6px 6px 12px;">' . $key . '</th>';
    }
    $html .= '</tr>';

    // data rows
    foreach ($array as $key => $value) {
        $html .= '<tr>';
        foreach ($value as $key2 => $value2) {
            $html .= '<td style="border: 1px solid black; border-right: 1px solid; border-bottom: 1px solid; border-left: 1px solid; background: #fff; padding: 6px 6px 6px 12px; color: #6D929B; margin: auto;">' . $value2 . '</td>';
        }
        $html .= '</tr>';
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

?>