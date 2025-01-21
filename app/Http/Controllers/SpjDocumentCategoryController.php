<?php

namespace App\Http\Controllers;

use App\Models\SpjDocumentCategory;
use Illuminate\Http\Request;

class SpjDocumentCategoryController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
        ]);

        SpjDocumentCategory::create([
            "nama" => $request->nama,
        ]);

        return redirect()->back();
    }
}
