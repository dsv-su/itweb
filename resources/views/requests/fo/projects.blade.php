@extends('layouts.app')
@section('content')
@include('dsvheader')
@include('navbar.navbar')
<section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-6">
    <div class="mx-auto max-w-screen-xl space-y-6 text-gray-800 dark:text-gray-100">
        <a href="{{ route('settings') }}" class="text-blue-600 underline">Tillbaka till ekonomiinställningar</a>
        <h1 class="text-3xl font-bold">Projekt</h1>
        @if(session('status'))
            <p role="status" class="text-green-700">{{ session('status') }}</p>
        @endif
        @if($errors->any())
            <div role="alert" class="rounded border border-red-400 p-4 text-red-700">
                <p>Rätta följande fel.</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
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
                            <label for="{{ $field }}" class="block mb-1">{{ $label }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $editing?->{$field}) }}" @required($field !== 'status') maxlength="255" class="w-full rounded border-gray-300 dark:bg-gray-900">
                        </div>
                    @endforeach
                </div>
                <button class="rounded bg-blue-600 px-4 py-2 text-white" type="submit">{{ $editing ? 'Spara ändringar' : 'Lägg till projekt' }}</button>
                @if($editing)
                    <a href="{{ route('fo.projects') }}" class="ml-3 text-blue-600 underline">Avbryt redigering</a>
                @endif
            </form>
        </div>
        <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow">
            <h2 class="text-xl font-semibold mb-2">Importera Excel</h2>
            <p class="mb-4">Ladda upp en .xlsx- eller .xls-fil (högst 10 MB). Det första kalkylbladet ska ha följande rubriker på första raden: Projekt, Projektbenämning, Projektledare, Status. Alla fyra fält är obligatoriska. Formatera projektnummer som text för att behålla inledande nollor.</p>
            <p class="mb-4">Befintliga projekt med matchande projektnummer uppdateras och nya projekt läggs till. Projekt som saknas i filen behålls. Om filen innehåller dubbla projektnummer eller ogiltiga rader avbryts hela importen.</p>
            <form method="POST" action="{{ route('fo.projects.import') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <label for="file" class="block">Excel-fil</label>
                <input type="file" id="file" name="file" accept=".xlsx,.xls" required class="block w-full">
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Importera och uppdatera projekt</button>
            </form>
        </div>
        <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow">
            <form method="GET" action="{{ route('fo.projects') }}" class="flex gap-3 mb-4">
                <label for="search" class="sr-only">Sök projekt</label>
                <input id="search" name="search" value="{{ $search }}" placeholder="Projektnummer, projektbenämning eller projektledare" class="w-full rounded border-gray-300 dark:bg-gray-900">
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Sök</button>
            </form>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead><tr>
                        @foreach(['Projektnummer', 'Projektbenämning', 'Projektledare', 'Status', 'Åtgärder'] as $heading)
                            <th scope="col" class="p-3 border-b">{{ $heading }}</th>
                        @endforeach
                    </tr></thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td class="p-3 border-b">{{ $project->project }}</td>
                                <td class="p-3 border-b">{{ $project->description }}</td>
                                <td class="p-3 border-b">{{ $project->projectleader }}</td>
                                <td class="p-3 border-b">{{ $project->status }}</td>
                                <td class="p-3 border-b"><a href="{{ route('fo.projects', ['edit' => $project->id]) }}" class="text-blue-600 underline" aria-label="Redigera projekt {{ $project->project }}">Redigera</a></td>
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
