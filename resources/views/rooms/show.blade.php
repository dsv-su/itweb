@extends('layouts.app')
@include('dsvheader')
@include('navbar.navbar')
<!-- News -->
<div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
    <div class="max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <div class="flex w-full sm:items-center gap-x-5 sm:gap-x-3">
                <div class="grow">
                    <div class="flex justify-between items-center gap-x-2">
                        <div class="inline-block">
                            <div class="sm:mb-1 block text-start">
                                  <span class="text-xs text-gray-800 dark:text-gray-200">
                                    Reported by: {!! $page->author->name ?? 'DMC' !!}
                                  </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content -->
        @nocache('rooms.partials.room')
        <!-- End Content -->
    </div>
</div>
