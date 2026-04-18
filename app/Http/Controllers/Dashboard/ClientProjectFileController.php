<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ClientProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\ClientProject;
use App\Models\ClientProjectFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientProjectFileController extends Controller
{
    public function store(Request $request, ClientProject $clientProject): RedirectResponse
    {
        if ($clientProject->status === ClientProjectStatus::Cancelled) {
            return redirect()
                ->route('dashboard.client-projects.show', $clientProject)
                ->with('info', 'لا يمكن إضافة ملفات لمشروع ملغي.');
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,docx,xlsx,png,jpg,jpeg,gif,webp', 'max:10240'],
        ]);

        $uploaded = $request->file('file');
        $path = $uploaded->store('client-project-files/' . $clientProject->id, 'local');

        $clientProject->files()->create([
            'original_name' => $uploaded->getClientOriginalName(),
            'path'          => $path,
            'mime_type'     => $uploaded->getMimeType(),
            'size'          => $uploaded->getSize(),
        ]);

        return redirect()
            ->route('dashboard.client-projects.show', $clientProject)
            ->with('success', 'تم رفع الملف بنجاح.');
    }

    public function download(ClientProject $clientProject, ClientProjectFile $file): mixed
    {
        abort_unless($file->client_project_id === $clientProject->id, 404);

        if (!Storage::disk('local')->exists($file->path)) {
            abort(404);
        }

        return Storage::disk('local')->download($file->path, $file->original_name);
    }

    public function destroy(ClientProject $clientProject, ClientProjectFile $file): RedirectResponse
    {
        abort_unless($file->client_project_id === $clientProject->id, 404);

        Storage::disk('local')->delete($file->path);
        $file->delete();

        return redirect()
            ->route('dashboard.client-projects.show', $clientProject)
            ->with('success', 'تم حذف الملف.');
    }
}
