@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @include('pp.partials.header')
    <section class="bg-gray-50 p-3 sm:p-5 dark:bg-gray-900">
        <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
            <div class="rounded-xl bg-white shadow-md dark:bg-gray-800"
                 x-data="{
                     activeTab: 'general',
                     tabs: ['general', 'proposal-options', 'overhead', 'contacts', 'notifications'],
                     init() {
                         const hash = window.location.hash.replace('#settings-', '');
                         const previousTabs = { form: 'general', budget: 'general', research: 'proposal-options', funding: 'proposal-options', registrator: 'contacts', 'fo-eu': 'contacts', 'fo-other': 'contacts', sent: 'notifications', granted: 'notifications' };
                         const tab = previousTabs[hash] || hash;
                         if (this.tabs.includes(tab)) this.activeTab = tab;
                     },
                     selectTab(tab) {
                         this.activeTab = tab;
                         window.history.replaceState(null, '', '#settings-' + tab);
                     },
                     moveTab(event) {
                         const keys = ['ArrowRight', 'ArrowLeft', 'Home', 'End'];
                         if (!keys.includes(event.key)) return;
                         event.preventDefault();
                         let index = this.tabs.indexOf(this.activeTab);
                         if (event.key === 'ArrowRight') index = (index + 1) % this.tabs.length;
                         if (event.key === 'ArrowLeft') index = (index - 1 + this.tabs.length) % this.tabs.length;
                         if (event.key === 'Home') index = 0;
                         if (event.key === 'End') index = this.tabs.length - 1;
                         this.selectTab(this.tabs[index]);
                         this.$nextTick(() => document.getElementById('settings-tab-' + this.activeTab).focus());
                     }
                 }">
                <header class="px-5 py-8 sm:px-8">
                    <p class="mb-2 text-sm font-semibold text-blue-600 dark:text-blue-400">Vice Head</p>
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-white">Project Proposals Settings</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Manage proposal availability, recipients and project settings.</p>
                </header>
                <div class="border-b border-gray-200 px-5 pb-5 sm:px-8 dark:border-gray-700">
                    <div class="flex gap-1 overflow-x-auto rounded-xl bg-gray-100 p-1.5 dark:bg-gray-900/60" role="tablist" aria-label="Project proposal settings"
                         aria-orientation="horizontal" @keydown="moveTab($event)">
                        @foreach ([
                            'general' => 'General',
                            'proposal-options' => 'Proposal options',
                            'overhead' => 'Overhead',
                            'contacts' => 'Contacts',
                            'notifications' => 'Notifications',
                        ] as $tab => $label)
                            <button type="button" id="settings-tab-{{ $tab }}" role="tab"
                                    aria-controls="settings-panel-{{ $tab }}"
                                    :aria-selected="activeTab === '{{ $tab }}'"
                                    :tabindex="activeTab === '{{ $tab }}' ? 0 : -1"
                                    @click="selectTab('{{ $tab }}')"
                                    class="flex-1 shrink-0 whitespace-nowrap rounded-lg px-4 py-2.5 text-center text-sm font-semibold transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-blue-600"
                                    :class="activeTab === '{{ $tab }}' ? 'bg-white text-blue-700 shadow-sm dark:bg-gray-700 dark:text-blue-300' : 'text-gray-600 hover:bg-white/60 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white'">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @include('pp.partials.flashmessage')
                <div class="p-5 sm:p-8">
                    <section id="settings-panel-general" role="tabpanel" aria-labelledby="settings-tab-general"
                             tabindex="0" x-show="activeTab === 'general'">
                        <div class="space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
                            <div>
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Form availability</h2>
                                <livewire:pp.form-enable :fos="$fos" :vicehead="$vicehead" :oh="$oh"/>
                            </div>
                            <div class="pt-8">
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Budget template</h2>
                                <livewire:pp.budget-template-uploader :template="$template" />
                            </div>
                        </div>
                    </section>
                    <section id="settings-panel-proposal-options" role="tabpanel" aria-labelledby="settings-tab-proposal-options"
                             tabindex="0" x-show="activeTab === 'proposal-options'" style="display: none;">
                        <div class="space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
                            <div>
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Research subjects</h2>
                                <div class="mt-4 bg-blue-50 border border-blue-500 text-sm text-gray-500 rounded-lg p-5 dark:bg-blue-600/[.15]">
                                    <div class="flex">
                                        <svg class="flex-shrink-0 h-4 w-4 text-blue-600 mt-0.5 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M12 16v-4"></path>
                                            <path d="M12 8h.01"></path>
                                        </svg>
                                        <div class="ms-3">
                                            <h3 class="text-blue-600 font-semibold dark:font-medium dark:text-white">Please note!</h3>
                                            <p class="mt-2 text-gray-800 dark:text-slate-400">
                                                Removing a research area in production may impact existing project proposals in the system.
                                                However, you can safely edit a research area's name or add a new one without any issues.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <livewire:pp.research-area />
                            </div>
                            <div class="pt-8">
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Funding organizations</h2>
                                <livewire:pp.funding-org-edit />
                            </div>
                        </div>
                    </section>
                    <section id="settings-panel-overhead" role="tabpanel" aria-labelledby="settings-tab-overhead"
                             tabindex="0" x-show="activeTab === 'overhead'" style="display: none;">
                        <div class="space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
                            <div>
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Overhead settings</h2>
                                @include('requests.vice.partials.oh')
                            </div>
                        </div>
                    </section>
                    <section id="settings-panel-contacts" role="tabpanel" aria-labelledby="settings-tab-contacts"
                             tabindex="0" x-show="activeTab === 'contacts'" style="display: none;">
                        <div class="space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
                            <div>
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">DSV Registrator</h2>
                                @include('requests.vice.partials.registrator')
                            </div>
                            <div class="pt-8">
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Financial Officer · EU projects</h2>
                                @include('requests.vice.partials.fo_eu')
                            </div>
                            <div class="pt-8">
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Financial Officer · Other projects</h2>
                                @include('requests.vice.partials.fo')
                            </div>
                        </div>
                    </section>
                    <section id="settings-panel-notifications" role="tabpanel" aria-labelledby="settings-tab-notifications"
                             tabindex="0" x-show="activeTab === 'notifications'" style="display: none;">
                        <div class="space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
                            <div>
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Sent notifications</h2>
                                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Manage recipients for proposal sent updates.</p>
                                <livewire:pp.sent-notification-recipients />
                            </div>
                            <div class="pt-8">
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Granted notifications</h2>
                                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Manage recipients for proposal grant updates.</p>
                                <livewire:pp.grant-notification-recipients />
                            </div>
                            <div class="pt-8">
                                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Monthly statistics</h2>
                                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Proposal counts, research subjects, funding organizations and DSV budgets for sent/granted proposals with submission deadlines in the previous month.</p>
                                <livewire:pp.monthly-stats-recipients />
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection
