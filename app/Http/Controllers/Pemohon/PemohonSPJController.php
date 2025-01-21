<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\SpjDocument;
use App\Models\SpjRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemohonSPJController extends Controller
{
    public function index()
    {
        $spjs = Spj::query()
            ->where('user_id', Auth::user()->id)
            ->with(['proposal', 'user', 'rating'])
            ->paginate(5);

        return view('pemohon.spj.index', compact('spjs'));
    }

    public function create(Request $request)
    {
        $proposalId = $request->id;
        return view('pemohon.spj.create', compact('proposalId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proposal_id' => 'required|string',
            'jenis' => 'required|string',
            'categories' => 'required|array',
            'files' => 'required|array|max:600'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $spj = Spj::create([
                    "proposal_id" => $request->proposal_id,
                    "user_id" => $request->user()->id,
                    "jenis" => $request->jenis,
                    "status" => "Pending",
                    "tanggal_proses" => now(),
                ]);

                $uploadedFiles = $request->file('files');
                foreach ($uploadedFiles as $i => $file) {
                    if ($file->getSize() > 614400) {
                        throw new \Exception("Ukuran file '" . $file->getClientOriginalName() . "' tidak boleh lebih dari 600 KB.");
                    }

                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('spj', $fileName, 'public');

                    SpjDocument::create([
                        "spj_id" => $spj->id,
                        "spj_document_category_id" => $request['categories'][$i],
                        "file_url" => $filePath,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => $e->getMessage()])->withInput();
        }

        return redirect()->route('pemohon.proposals.index')->with('success', 'SPJ berhasil ditambahkan.');
    }

    public function show($id)
    {
        $spj = Spj::with(['proposal', 'user', 'documents.category'])->where('id', $id)->first();
        return view('pemohon.spj.show', compact('spj'));
    }

    public function edit($id, Request $request)
    {
        try {
            $spj = Spj::find($id);
            DB::transaction(function () use ($request, $spj) {
                $spj->update([
                    "jenis" => $request->jenis,
                    "status" => "Pending",
                    "tanggal_selesai" => null,
                    "updated_at" => now(),
                ]);

                $spjDocumentIds = $request->document_ids;
                $spjDocumentCategories = $request->categories;
                $uploadedFiles = $request->file('files');
                $updatedSpjDocumentIds = [];

                foreach ($spjDocumentIds as $i => $spjDocId) {
                    // Create new spj document if spjDocId is null
                    if (!$spjDocId) {
                        $file = $uploadedFiles[$i];
                        if ($file->getSize() > 614400) {
                            throw new \Exception("Ukuran file '" . $file->getClientOriginalName() . "' tidak boleh lebih dari 600 KB.");
                        }

                        $fileName = uniqid() . '_' . $file->getClientOriginalName();
                        $filePath = $file->storeAs('spj', $fileName, 'public');

                        $spjDocument = SpjDocument::create([
                            "spj_id" => $spj->id,
                            "spj_document_category_id" => $request['categories'][$i],
                            "file_url" => $filePath,
                        ]);
                        array_push($updatedSpjDocumentIds, $spjDocument->id);

                        // Next loop after logic done
                        continue;
                    }

                    // Update existing doc if spjDocId not null
                    $spjDocument = SpjDocument::find($spjDocId);
                    $fileUrl = $spjDocument->file_url;
                    array_push($updatedSpjDocumentIds, $spjDocument->id);

                    // Check if spj document is update
                    if (isset($uploadedFiles[$i])) {
                        $file = $uploadedFiles[$i];
                        if ($file->getSize() > 614400) {
                            throw new \Exception("Ukuran file '" . $file->getClientOriginalName() . "' tidak boleh lebih dari 600 KB.");
                        }

                        $fileName = uniqid() . '_' . $file->getClientOriginalName();
                        $fileUrl = $file->storeAs('spj', $fileName, 'public');
                    }

                    $spjDocument->update([
                        "spj_document_category_id" => $spjDocumentCategories[$i],
                        "file_url" => $fileUrl,
                        "updated_at" => now(),
                    ]);
                }

                // Remove document that not include on updated or new data
                SpjDocument::whereNotIn('id', $updatedSpjDocumentIds)->delete();
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => $e->getMessage()])->withInput();
        }

        return redirect()->route('pemohon.spj.index')->with('success', 'Data SPJ berhasil diubah.');
    }

    public function rating(Request $request)
    {
        SpjRating::create([
            'spj_id' => $request->spj_id,
            'user_id' => $request->user()->id,
            'rating' => (int)$request->rating,
            'catatan' => $request->catatan,
        ]);
        return redirect()->route('pemohon.spj.index')->with('success', 'Rating SPJ berhasil simpan.');
    }
}
