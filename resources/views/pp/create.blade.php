@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @include('pp.partials.header')
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16 overflow-x-hidden">
            {{--}}
            @if(in_array($type, ['edit', 'review', 'view', 'resume']))
                @include(('pp.partials.overview'))
            @endif
            {{--}}
            @if(in_array($type, ['review']))
                @include('pp.partials.form.review_help')
            @endif

            @include('pp.partials.form.form_title')

            <!-- Progress stepper stage -->
            @include(('pp.partials.progress_stage'))

            <!-- Instruction -->
            {{--}}
            @if(in_array($type, ['preapproval', 'resume']))
                @include('pp.partials.form.help')
            @endif
            {{--}}

            <form method="post" action="{{route('pp.submit')}}">
                @csrf

                @if(in_array($type, ['preapproval', 'saved', 'complete', 'review', 'edit', 'resume', 'sent', 'granted', 'rejected']))
                    <input type="hidden" name="id" value="{{$proposal->id}}">
                @endif

                <input type="hidden" name="type" value="{{$type}}">
                <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                    {{--}}<div class="w-full sm:col-span-2 border border-blue-500 rounded-lg p-4"></div>{{--}}

                    <!-- Title -->
                    @include('pp.partials.form.title')

                    <!--Research subject-->
                    <div class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
                        Research subject
                    </div>

                    @include('pp.partials.form.research_subject')

                    <!-- Outline (objective)-->
                    @include('pp.partials.form.objective')

                    <div class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
                        Research collaborators
                    </div>

                    <!-- Principal Investigator-->
                    @include('pp.partials.form.principal_investigator')

                    <!-- Co Investigators -->
                    @if($type == 'preapproval')
                        <livewire:pp.co-investigators />
                    @elseif(in_array($type, ['complete', 'saved', 'edit', 'resume']))
                    <livewire:pp.co-investigators :proposal="$proposal" />
                    @else
                        @include('pp.partials.review.co_investigators')
                    @endif

                    <!-- Project organization -->
                    <div class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
                        Project organization
                    </div>
                    <!-- Funding organization -->
                    @if($type == 'preapproval')
                        <livewire:select2.Org-select2 proposal="" />
                    @elseif(in_array($type, ['complete', 'saved', 'edit', 'resume']))
                        <livewire:select2.Org-select2 :proposal="$proposal" />
                    @else
                        @include('pp.partials.review.funding_org')
                    @endif

                    <!-- Program call -->
                    @include('pp.partials.form.program_call')

                    <!--DSV coordinating -->
                    @if($type == 'preapproval')
                        <livewire:pp.dsv-coordination proposal="" />
                    @elseif(in_array($type, ['complete', 'saved', 'edit', 'resume']))
                        <livewire:pp.dsv-coordination :proposal="$proposal" />
                    @else
                        @include('pp.partials.review.dsvcoordination')
                    @endif

                    <!-- Eu project -->
                    @if($type == 'preapproval')
                        <livewire:pp.eu-project proposal="" />
                    @elseif(in_array($type, ['complete', 'saved', 'edit', 'resume']))
                        <livewire:pp.eu-project :proposal="$proposal" />
                    @else
                        @include('pp.partials.review.eu')
                    @endif

                    <!-- Eu Wallengenberg project -->
                    @if($type == 'preapproval')
                        <livewire:pp.eu-wallenberg-project proposal="" />
                    @elseif(in_array($type, ['complete', 'saved', 'edit', 'resume']))
                        <livewire:pp.eu-wallenberg-project :proposal="$proposal" />
                    @else
                        @include('pp.partials.review.eu_wallenberg')
                    @endif

                    <!-- Unit Head -->
                    @if(in_array($type, ['preapproval', 'complete', 'saved', 'edit', 'review', 'view', 'resume', 'sent', 'granted']))
                        <div class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
                            Unit Head
                        </div>
                        <div class="w-full sm:col-span-2 mb-4 mt-4 border border-blue-500 text-sm text-gray-500 rounded-lg p-5">
                            <div class="flex">
                                <svg class="flex-shrink-0 h-4 w-4 text-blue-600 mt-0.5 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 16v-4"></path>
                                    <path d="M12 8h.01"></path>
                                </svg>
                                <div class="ms-3">
                                    <h3 class="text-blue-600 font-semibold dark:font-medium dark:text-white">Please note!</h3>
                                    <p class="mt-2 text-gray-800 dark:text-slate-400">
                                        If more than one unit head needs to approve your proposal, add each required unit head to the approval list.
                                    </p>

                                </div>
                            </div>
                        </div>
                    <!--Unithead-->
                        @include('pp.partials.form.unit_head')
                    @endif

                    <!-- Project budget -->
                    @if(in_array($type, ['preapproval', 'complete', 'saved', 'review', 'edit', 'resume', 'view', 'sent', 'granted']))
                        <div id="project_budget" class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                    before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                    dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
                            Project budget
                        </div>
                        <!-- Currency -->
                        @include('pp.partials.form.currency')
                        <!-- Budget for complete project -->
                        @include('pp.partials.form.budget_project')
                        <!-- Budget for DSV -->
                        @include('pp.partials.form.budget_dsv')

                        <!-- Flashmessage for review update -->
                        <div class="w-full sm:col-span-2 flex items-center text-xs text-blue-500 uppercase
                        before:flex-1 after:flex-1 after:ms-6
                        dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
                            @include('pp.partials.flashmessage')
                        </div>

                        <div class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
                            CO financing
                        </div>
                        <!-- Percent OH-costs -->
                        <livewire:pp.ohcost :type="$type" :proposal="$proposal ?? null"/>
                        <br>

                        <!-- Co-financing -->
                        {{--}}
                        @if($type == 'preapproval')
                            <livewire:pp.cofinancing proposal="" />
                        @elseif ($type == 'edit' or $type == 'resume')
                            <livewire:pp.cofinancing :proposal="$proposal"/>
                        @else
                            @include('pp.partials.review.cofinancing')
                        @endif
                        {{--}}

                        <!-- Co financing needed -->
                        @include('pp.partials.form.cofinancing_needed')

                        <!-- Budget years of PHD -->
                        @include('pp.partials.form.budget_phd')

                        <!-- Cofinancing motivation -->
                        @include('pp.partials.form.cofinancing_motivation')

                    @endif

                    <!-- Project dates -->
                    <div class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
                        Project dates
                    </div>
                    {{--}}@if($type != 'preapproval'){{--}}
                        <!--Decision expected-->
                        @include('pp.partials.form.date_exp')
                        <!-- Start date -->
                        @include('pp.partials.form.date_start')
                        <!-- Submission deadline -->
                        @include('pp.partials.form.date_deadline')
                    {{--}}@endif{{--}}

                    <!-- Project duration -->
                    @include('pp.partials.form.duration')

                    <!-- Comments -->
                    <div class="w-full sm:col-span-2 py-3 flex items-center text-xs text-blue-500 uppercase
                                before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6
                                dark:text-blue-400 dark:before:border-neutral-600 dark:after:border-neutral-600">
                        Comments
                    </div>
                    <!-- Initial comments -->
                    @include('pp.partials.form.comments')
                </div>

                <!-- Upload component -->
                @include('pp.partials.form.upload')

                <!-- Grant section -->
                @if(in_array($type, ['granted', 'view']))
                    @include('pp.partials.form.granted')
                @endif

                <!-- Rejected section -->
                @if(in_array($type, ['rejected', 'view']))
                    @include('pp.partials.form.rejected')
                @endif

                <!-- Submit buttons -->
                @include('pp.partials.form.submit_buttons')
            </form> <!-- end form -->

            <!-- Review bar -->
            @include('pp.partials.form.reviewbar')

    </section>
    <script>
        /* Textarea autosize */
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('user_comments_history');

            const autoResize = () => {
                textarea.style.height = 'auto'; // Reset the height to auto to calculate the new height
                textarea.style.height = `${textarea.scrollHeight}px`; // Set the height to the scroll height
            };

            textarea.addEventListener('input', autoResize);
            autoResize(); // Call once on page load to set the initial height
        });
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('objective');

            const autoResize = () => {
                textarea.style.height = 'auto'; // Reset the height to auto to calculate the new height
                textarea.style.height = `${textarea.scrollHeight}px`; // Set the height to the scroll height
            };

            textarea.addEventListener('input', autoResize);
            autoResize(); // Call once on page load to set the initial height
        });

        /* Add unit head */
        const addButton = document.getElementById('add-unithead-button');

        if (addButton) {
            addButton.addEventListener('click', function () {
                const container = document.getElementById('unithead-container');

                // Clone the first row (select + remove button)
                const existingRow = container.querySelector('.unithead-row');
                const newRow = existingRow.cloneNode(true);

                // Clear selection in the cloned select
                const newSelect = newRow.querySelector('select');
                newSelect.selectedIndex = -1;

                // If the select had an id, remove it to avoid duplicate ids
                newSelect.removeAttribute('id');

                // Add spacing between rows if you want
                newRow.classList.add('mt-2');

                container.appendChild(newRow);
            });
        }
        /* Remove unit head  */
        document.addEventListener('click', function (e) {
            if (!e.target.classList.contains('remove-unithead-button')) return;

            const container = document.getElementById('unithead-container');
            const rows = container.querySelectorAll('.unithead-row');

            // Optional: prevent removing the last one if it's required
            if (rows.length <= 1) return;

            e.target.closest('.unithead-row').remove();
        });


    </script>

    <!-- Modals -->
    @include('pp.modals.pp_help')
@endsection
