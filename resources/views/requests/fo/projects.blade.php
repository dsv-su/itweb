@extends('layouts.app')
@section('page-navigation')
@include('dsvheader')
@include('navbar.navbar')
@endsection
@section('content')
<section id="fo-projects" lang="sv" aria-labelledby="projects-heading" class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-6">
    <div class="mx-auto max-w-screen-xl space-y-6 text-gray-800 dark:text-gray-100">
        <a href="{{ route('settings') }}" class="inline-flex min-h-11 items-center text-blue-700 underline dark:text-blue-300">Tillbaka till ekonomiinställningar</a>
        <h1 id="projects-heading" class="text-3xl font-bold">Projekt</h1>
        @if(session('status'))
            <div role="status" tabindex="-1" @if(!$errors->any()) autofocus @endif class="rounded-lg border border-green-300 bg-green-50 p-4 font-semibold text-green-800 shadow-sm dark:border-green-700 dark:bg-green-900 dark:text-green-100">
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div role="alert" tabindex="-1" autofocus class="rounded border border-red-500 bg-red-50 p-4 text-red-800 dark:bg-red-950 dark:text-red-200">
                <p>Rätta följande fel.</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->messages() as $field => $messages)
                        @foreach($messages as $error)
                            <li>
                                @if(in_array($field, ['project', 'description', 'projectleader', 'status', 'file']))
                                    <a href="#{{ $field }}" class="inline-flex min-h-6 items-center underline">{{ $error }}</a>
                                @else
                                    {{ $error }}
                                @endif
                            </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow">
            <h2 class="text-xl font-semibold mb-4">{{ $editing ? 'Redigera projekt' : 'Lägg till projekt' }}</h2>
            <form method="POST" action="{{ $editing ? route('fo.projects.update', $editing) : route('fo.projects.store') }}" class="space-y-4">
                @csrf
                @if($editing) @method('PUT') @endif
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach(['project' => 'Projektnummer', 'description' => 'Projektbenämning', 'projectleader' => 'Projektledare', 'status' => 'Status'] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block mb-1">{{ $label }} {{ $field !== 'status' ? '(obligatoriskt)' : '(valfritt)' }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $editing?->{$field}) }}" @required($field !== 'status') maxlength="255" aria-invalid="{{ $errors->has($field) ? 'true' : 'false' }}" @if($errors->has($field)) aria-describedby="{{ $field }}-error" @endif class="w-full min-h-11 rounded border border-gray-500 bg-white px-3 py-2 dark:border-gray-400 dark:bg-gray-900">
                            @error($field)
                                <p id="{{ $field }}-error" class="mt-2 text-red-700 dark:text-red-300">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
                <button class="min-h-11 rounded bg-blue-700 px-4 py-2 text-white" type="submit">{{ $editing ? 'Spara ändringar' : 'Lägg till projekt' }}</button>
                @if($editing)
                    <a href="{{ route('fo.projects') }}" class="ml-3 inline-flex min-h-11 items-center text-blue-700 underline dark:text-blue-300">Avbryt redigering</a>
                @endif
            </form>
        </div>
        <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow">
            <h2 class="text-xl font-semibold mb-2">Importera Excel</h2>
            <p id="file-help" class="mb-4">Ladda upp en .xlsx- eller .xls-fil (högst 10 MB). Det första kalkylbladet ska ha följande rubriker på första raden: Projekt, Projektbenämning, Projektledare, Status. Alla fyra fält är obligatoriska. Formatera projektnummer som text för att behålla inledande nollor.</p>
            <p class="mb-4">Befintliga projekt med matchande projektnummer uppdateras och nya projekt läggs till. Projekt som saknas i filen behålls. Om filen innehåller dubbla projektnummer eller ogiltiga rader avbryts hela importen.</p>
            <form method="POST" action="{{ route('fo.projects.import') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <label for="file" class="block">Excel-fil (obligatoriskt)</label>
                <input type="file" id="file" name="file" accept=".xlsx,.xls" required aria-describedby="file-help{{ $errors->has('file') ? ' file-error' : '' }}" aria-invalid="{{ $errors->has('file') ? 'true' : 'false' }}" class="block min-h-11 w-full min-w-0">
                @error('file')
                    <p id="file-error" class="text-red-700 dark:text-red-300">{{ $message }}</p>
                @enderror
                <button type="submit" class="min-h-11 rounded bg-blue-700 px-4 py-2 text-white">Importera och uppdatera projekt</button>
            </form>
        </div>
        <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow">
            <form method="GET" action="{{ route('fo.projects') }}" role="search" class="mb-4 space-y-2">
                <label for="search" class="block font-semibold">Sök projekt</label>
                <p id="search-help" class="text-sm">Sök på projektnummer, projektbenämning eller projektledare.</p>
                <input type="search" aria-describedby="search-help" id="search" name="search" value="{{ $search }}" class="w-full min-h-11 rounded border border-gray-500 bg-white px-3 py-2 dark:border-gray-400 dark:bg-gray-900">
                <button type="submit" class="min-h-11 rounded bg-blue-700 px-4 py-2 text-white">Sök</button>
            </form>
            <div role="region" aria-label="Projektlista" tabindex="0" class="overflow-x-auto p-1">
                <table class="w-full text-left">
                    <caption class="sr-only">Projekt med projektledare, status och åtgärder</caption>
                    <thead><tr>
                        @foreach(['Projektnummer', 'Projektbenämning', 'Projektledare', 'Status', 'Åtgärder'] as $heading)
                            <th scope="col" class="p-3 border-b">{{ $heading }}</th>
                        @endforeach
                    </tr></thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <th scope="row" class="p-3 border-b font-normal">{{ $project->project }}</th>
                                <td class="p-3 border-b">{{ $project->description }}</td>
                                <td class="p-3 border-b">{{ $project->projectleader }}</td>
                                <td class="p-3 border-b">{{ $project->status }}</td>
                                <td class="p-3 border-b">
                                    <a href="{{ route('fo.projects', ['edit' => $project->id]) }}" class="inline-flex items-center justify-center min-h-11 rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600" aria-label="Redigera projekt {{ $project->project }}">Redigera</a>
                                    <form method="POST" action="{{ route('fo.projects.destroy', ['project' => $project, 'page' => $projects->currentPage(), 'search' => $search]) }}" class="mt-2"
                                          data-confirm="Vill du ta bort projekt {{ $project->project }}? Det går inte att ångra."
                                          onsubmit="return confirm(this.dataset.confirm)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center min-h-11 rounded bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600" aria-label="Ta bort projekt {{ $project->project }}">Ta bort</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-3">Inga projekt hittades.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $projects->links() }}</div>
        </div>
    </div>
</section>
@endsection
