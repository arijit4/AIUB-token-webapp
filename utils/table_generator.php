<?php

class TableGenerator
{
    function generate_queue_table($data, $caption, $headers): string
    {
        $html = "<table class='app-table'>";
        $html .= "<caption>" . $caption . "</caption>";

        $html .= "<tr>";
        foreach ($headers as $header) {
            $html .= "<th>" . $header . "</th>";
        }
        $html .= "</tr>";

        foreach ($data as $row) {
            $html .= "<tr>";
            foreach ($row as $index => $cell) {
                if ($index === "token_id") {
                    $html .= "<td>#T-" . $cell . "</td>";
                } else {
                    $html .= "<td>" . $cell . "</td>";
                }
            }
            $html .= "</tr>";
        }

        $html .= "</table>";
        return $html;
    }

    function generate_table($data, $caption, $headers): string
    {
        $html = "<table class='app-table'>";
        $html .= "<caption>" . $caption . "</caption>";

        $html .= "<tr>";
        foreach ($headers as $header) {
            $html .= "<th>" . $header . "</th>";
        }
        $html .= "</tr>";

        foreach ($data as $row) {
            $html .= "<tr>";
            foreach ($row as $cell) {
                $html .= "<td>" . $cell . "</td>";
            }
            $html .= "</tr>";
        }

        $html .= "</table>";
        return $html;
    }
}

