<?php

namespace App\Http\Controllers;

use App\Models\Exporter;
use Illuminate\Http\Request;

class ExporterController extends Controller
{
    public function index()
    {
        $exporters = Exporter::all();
        return view('backend.exporter.index', compact('exporters'));
    }

    public function create()
    {
        return view('backend.exporter.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Exporter::create($request->all());
        return redirect()->route('exporters.index')->with('success', 'Exporter created successfully!');
    }

    public function edit(Exporter $exporter)
    {
        return view('backend.exporter.edit', compact('exporter'));
    }

    public function update(Request $request, Exporter $exporter)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $exporter->update($request->all());
        return redirect()->route('exporters.index')->with('success', 'Exporter updated successfully!');
    }

    public function destroy(Exporter $exporter)
    {
        $exporter->delete();
        return redirect()->route('exporters.index')->with('success', 'Exporter deleted successfully!');
    }
}

