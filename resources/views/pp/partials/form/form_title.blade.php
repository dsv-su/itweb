<h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
@php
    if (!function_exists('badge')) {
        function badge($text, $color = 'blue') {
            return '<span class="bg-'.$color.'-100 text-'.$color.'-800 text-normal font-medium me-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-'.$color.'-400 border border-'.$color.'-400">'
                . $text . '</span>';
        }
        }
        /*
    inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-lg  font-medium   dark:bg-blue-800/30 dark:text-blue-500
    */
        $labels = [
            'preapproval' => isset($proposal) ? badge(__("New: ")) . 'Project Proposal' : __("New"),
            'complete' => isset($proposal) ? badge(__("Complete: ")) . $proposal['name'] : __("Complete"),
            'resume' => isset($proposal) ? badge(__("Resume: "), 'yellow') . $proposal['name'] : __("Resume"),
            'view' => isset($proposal) ? badge(__("View: ")) . $proposal['name'] : __("View"),
            'edit' => isset($proposal) ? badge(__("Edit: "), 'green') . $proposal['name'] : __("Edit"),
            'review' => isset($proposal) ? badge(__("Review: ")) . $proposal['name'] : __("Review"),
        ];
@endphp

{!! $labels[$type] ?? __("Project Proposal") !!}

</h2>
