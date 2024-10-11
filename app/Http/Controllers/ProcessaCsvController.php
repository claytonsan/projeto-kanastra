<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessCsvJob;
use Illuminate\Support\Facades\Log; 

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
    
    //nesse metodo eu recebo o csv através de um request

    public function processCsv(Request $request)
    {
        // validação do arquivo CSV
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);
        
        // armazena o arquivo e inicia o processamento na fila
        $path = $request->file('file')->store('csv_files');
         
        //aqui como o arquivo pode ter um tamanho grande, utilizo um job, para que a tarefa seja realizada em segundo plano, de forma assíncrona, ou seja, sem bloquear a execução do resto do aplicativo
        ProcessCsvJob::dispatch($path);

        return response()->json(['message' => 'Arquivo enviado, iniciando processamento.'], 200);
    }

    // Já nesse método eu pego o arquivo csv de exemplo que foi disponibilizado no teste

    public function processCsvLocal()
    {
        // rota do csv
        $path = public_path('inputOld.csv');
        
        // verifica se o arquivo CSV existe
        if (!file_exists($path)) {
            return response()->json(['message' => 'Arquivo CSV não encontrado.'], 404);
        }
 
        //aqui como o arquivo pode ter um tamanho grande, utilizo um job, para que a tarefa seja realizada em segundo plano, de forma assíncrona, ou seja, sem bloquear a execução do resto do aplicativo
        ProcessCsvJob::dispatch($path); 

        return response()->json(['message' => 'Arquivo enviado, iniciando processamento.'], 200);

    }
}
