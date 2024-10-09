<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessCsvJob;

class ProcessaCsvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function processCsv(Request $request)
    {
        // validação do arquivo CSV
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        // armazena o arquivo e inicia o processamento na fila
        $path = $request->file('file')->store('csv_files');
        // print_r($path);exit;
        ProcessCsvJob::dispatch($path);

        return response()->json(['message' => 'Arquivo enviado, iniciando processamento.'], 200);
    }
}
