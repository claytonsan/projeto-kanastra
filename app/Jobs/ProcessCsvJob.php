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

        $file = fopen($this->path, 'r');
        
        // Ignora o cabeçalho
        fgetcsv($file); 
 
        $errors = [];
        $savedCount = 0;

        while (($row = fgetcsv($file)) !== FALSE) {
             
            $boletoData = [
                'name' => $row[0] ?? null,
                'governmentId' => $row[1] ?? null,
                'email' => $row[2] ?? null,
                'debtAmount' => isset($row[3]) && is_numeric($row[3]) ? (float)$row[3] : null,
                'debtDueDate' => $row[4] ?? null,
                'debtId' => $row[5] ?? null,
            ];

            try {
                $this->validateBoletoData($boletoData);

                // Verifica se o boleto já foi gerado
                if (!Boleto::where('debtId', $boletoData['debtId'])->exists()) {
                    $boleto = Boleto::generate($boletoData);
                    Email::send($boletoData);
                    $savedCount++;
                } else {
                    $errors[] = "Boleto já gerado para: {$boletoData['name']} com ID de dívida: {$boletoData['debtId']}";
                }
            } catch (\Exception $e) {
                $errors[] = "Erro ao processar a linha: " . implode(", ", $row) . ". Erro: " . $e->getMessage();
            }
        }

        if ($savedCount === 0) {
            Log::error("Nenhum boleto foi salvo. Erros: " . implode("; ", $errors));
        } else {
            Log::info("Total de boletos salvos: {$savedCount}");
        }

        if (!empty($errors)) {
            // aqui caso haja necessidade podemos configurar um disparo de e-mail para falar que ocorreu um erro ao salvar
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
