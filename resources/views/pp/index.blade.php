@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    <!-- PP header -->
    @include('pp.partials.header')

    @include('pp.partials.breadcrumb')

    @include('pp.partials.flashmessage')

    <!-- Content -->
    <div class="w-full">
        <div class="p-2 sm:p-4 space-y-2 sm:space-y-4">
            <livewire:pp.project-proposal-home />
            @switch ($page)
                @case ('my')
                    <livewire:pp.my-project-proposal-search />
                @break
                @case ('awaiting')
                    <livewire:pp.awaiting-project-proposal />
                @break
                @case ('all')
                    <livewire:pp.all-project-proposal-search />
                @break
            @endswitch
        </div>
    </div>
    <!-- End Content -->
@endsection

