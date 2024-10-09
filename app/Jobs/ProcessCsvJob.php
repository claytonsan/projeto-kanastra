<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;  
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Boleto;
use Illuminate\Support\Facades\Storage;  
use Illuminate\Support\Facades\Log; 

class ProcessCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function handle()
    {
        // Carrega e processa o CSV
        $file = Storage::get($this->path);
        $csvData = array_map('str_getcsv', explode("\n", $file));

        foreach ($csvData as $row) {
            
            $boletoData = [
                'name' => $row[0],
                'governmentId' => $row[1],
                'email' => $row[2],
                'debtAmount' => (float) $row[3],
                'debtDueDate' => $row[4],
                'debtId' => $row[5],
            ];
            
            try {
                $this->validateBoletoData($boletoData);

                // Verifica se o boleto já foi gerado
                if (!Boleto::where('debtId', $boletoData['debtId'])->exists()) {
                    $boleto = Boleto::generate($boletoData);
                    Email::send($boletoData); 
                } else {
                    Log::info("Boleto já gerado para: {$boletoData['name']} com ID de dívida: {$boletoData['debtId']}");
                }
            } catch (\Exception $e) {
                Log::error("Erro ao processar a linha: " . implode(", ", $row) . ". Erro: " . $e->getMessage());
            }
        }
    }

    protected function validateBoletoData(array $boletoData)
    {
        $errors = [];

        if (empty($boletoData['name'])) {
            $errors[] = "O nome está vazio";
        }
        if (empty($boletoData['governmentId'])) {
            $errors[] = "O CPF/CNPJ está vazio";
        }
        if (empty($boletoData['email'])) {
            $errors[] = "O e-mail está vazio";
        }
        if (!is_float($boletoData['debtAmount'])) {
            $errors[] = "O valor da dívida não é válido";
        }
        if (empty($boletoData['debtDueDate'])) {
            $errors[] = "A data de vencimento está vazia";
        }
        if (empty($boletoData['debtId'])) {
            $errors[] = "O ID da dívida está vazio";
        }

        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }
    }

}
