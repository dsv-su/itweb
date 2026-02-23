<?php
if (!function_exists('badge')) {
    function badge($text, $color = 'blue') {
        return '<span class="bg-'.$color.'-100 text-'.$color.'-800 text-normal font-medium me-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-'.$color.'-400 border border-'.$color.'-400">'
            . $text . '</span>';
    }
}
?>
