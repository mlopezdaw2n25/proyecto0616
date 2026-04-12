<?php

namespace App\Http\Controllers;

use App\Models\UserCv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvController extends Controller
{
    /**
     * Upload or replace the authenticated user's CV (PDF only, max 5 MB).
     */
    public function store(Request $request)
    {
        $request->validate([
            'cv' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $user = Auth::user();

        // Delete existing file if one already exists
        if ($user->cv) {
            Storage::disk('public')->delete($user->cv->file_path);
            $user->cv->delete();
        }

        $file         = $request->file('cv');
        $originalName = $file->getClientOriginalName();
        // Sanitise the stored filename to prevent path traversal
        $safeName     = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.pdf';
        $path         = $file->storeAs('cvs/' . $user->id, $safeName, 'public');

        UserCv::create([
            'user_id'   => $user->id,
            'file_path' => $path,
            'file_name' => $originalName,
        ]);

        return back()->with('missatge', 'CV pujat correctament.');
    }

    /**
     * Delete the authenticated user's CV.
     */
    public function destroy()
    {
        $user = Auth::user();

        if (!$user->cv) {
            return back()->with('missatge', 'No tens cap CV per eliminar.');
        }

        Storage::disk('public')->delete($user->cv->file_path);
        $user->cv->delete();

        return back()->with('missatge', 'CV eliminat correctament.');
    }

    /**
     * Stream the CV PDF inline (for viewing) or as a download.
     * Any authenticated user can view any CV (read-only access).
     */
    public function show(int $userId, string $disposition = 'inline')
    {
        $cv = UserCv::where('user_id', $userId)->firstOrFail();

        if (!Storage::disk('public')->exists($cv->file_path)) {
            abort(404, 'Fitxer no trobat.');
        }

        $fullPath = Storage::disk('public')->path($cv->file_path);

        return response()->file($fullPath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => $disposition . '; filename="' . $cv->file_name . '"',
        ]);
    }

    /**
     * Force-download the CV.
     */
    public function download(int $userId)
    {
        return $this->show($userId, 'attachment');
    }
}
