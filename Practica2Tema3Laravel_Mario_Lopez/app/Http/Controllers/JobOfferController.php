<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobOfferController extends Controller
{
    /**
     * Upload one or more PDF job offer files.
     */
    public function store(Request $request)
    {
        $request->validate([
            'job_offers'   => ['required', 'array', 'min:1'],
            'job_offers.*' => ['file', 'mimes:pdf', 'max:5120'],
        ]);

        $user = Auth::user();

        foreach ($request->file('job_offers') as $file) {
            $originalName = $file->getClientOriginalName();
            $safeName     = Str::slug(pathinfo($originalName, PATHINFO_FILENAME))
                            . '_' . uniqid() . '.pdf';
            $path = $file->storeAs('job_offers/' . $user->id, $safeName, 'public');

            JobOffer::create([
                'user_id'   => $user->id,
                'file_path' => $path,
                'file_name' => $originalName,
            ]);
        }

        return back()->with('missatge', 'Oferta(es) pujada(es) correctament.');
    }

    /**
     * Delete a single job offer PDF.
     */
    public function destroy(JobOffer $jobOffer)
    {
        if ($jobOffer->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($jobOffer->file_path);
        $jobOffer->delete();

        return back()->with('missatge', 'Oferta eliminada correctament.');
    }
}
