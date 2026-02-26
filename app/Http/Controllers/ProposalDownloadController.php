<?php

namespace App\Http\Controllers;

use App\Models\BudgetTemplate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProposalDownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['web', 'auth', 'dsv']);
    }

    public function usermanual(): StreamedResponse
    {
        return Storage::disk('public')->download('PPManual.pdf');
    }

    public function budget(string $type): StreamedResponse
    {
        $template = BudgetTemplate::query()->first();
        $files = $template?->files ?? [];

        // Find the file matching the requested type
        $file = collect($files)->firstWhere('type', $type);

        abort_unless($file && !empty($file['path']), 404, 'Budget template file not found.');

        // Optional: pick a filename (fallback to basename)
        $filename = $file['name'] ?? basename($file['path']);

        return Storage::download($file['path'], $filename);
    }
}
