<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;
use App\Mail\BoletoGerado;
use App\Models\Boleto;
use App\Jobs\ProcessCsvJob;
use Tests\TestCase;
 

class BoletoTest extends TestCase
{
    use RefreshDatabase;

    /** primeiro teste é o teste de geração de boleto, aqui com um array simples é verificado se o boleto está sendo criado no banco de dados */
 
    public function testGerarBoleto()
    {
        $data = [
            'name' => 'Clayton Santos',
            'governmentId' => '11111111111',
            'email' => 'claytonsan@kanastra.com.br',
            'debtAmount' => 1000000.00,
            'debtDueDate' => '2022-10-12',
            'debtId' => '1adb6ccf-ff16-467f-bea7-5f05d494280f',
        ];

        Boleto::create($data);

        $this->assertDatabaseHas('boletos', [
            'governmentId' => '11111111111',
            'email' => 'claytonsan@kanastra.com.br',
            'debtAmount' => 1000000.00,
        ]);
    }

    /** segundo teste é o teste para verificar se o sistema irá duplicar boletos na hora da geração */

    public function testNaoGerarBoletoDuplicado()
    {
        $data = [
            'name' => 'Clayton Santos',
            'governmentId' => '11111111111',
            'email' => 'claytonsan@kanastra.com.br',
            'debtAmount' => 1000000.00,
            'debtDueDate' => '2022-10-12',
            'debtId' => '1adb6ccf-ff16-467f-bea7-5f05d494280f',
        ];

        Boleto::create($data);

        // tenta criar um duplicado e verifique se ta ok
        $this->expectException(\Illuminate\Database\QueryException::class);
        Boleto::create($data);
    }

    /** terceiro teste é o teste para verificar se o sistema está disparando e-mail na hora da geração do boleto */

    public function testDisparaEmailGeracao()
    {
        Mail::fake();

        $data = [
            'name' => 'John Doe',
            'governmentId' => '11111111111',
            'email' => 'claytonsantos13@hotmail.com', 
            'debtAmount' => 56.00,
            'debtDueDate' => '2022-10-12',
            'debtId' => '1adb6ccf-ff16-467f-bea7-5f05d494280f',
        ];
        
        // criar o boleto
        Boleto::generate($data);

        // verifique se o e-mail foi enviado
        Mail::assertSent(BoletoGerado::class, function ($mail) use ($data) {
            return $mail->hasTo($data['email']) &&  
                $mail->data->debtId === $data['debtId'];
        });

        // verifica se o boleto foi criado no banco de dados
        $this->assertDatabaseHas('boletos', [
            'debtId' => $data['debtId'],
            'name' => $data['name'],
        ]);
    }

    /** quarto teste é pego um arquivo que se encontra na pasta file e é percorrido para salvar as informações dos boletos no banco e disparar e-mails*/

    public function testProcessarBoletosCsv()
    {
        Mail::fake();

        // rota do csv
        $filePath = public_path('input.csv');
        
        // abrir o arquivo para leitura
        $file = fopen($filePath, 'r');
        
        // Ignora o cabeçalho
        fgetcsv($file); 
        while (($data = fgetcsv($file)) !== FALSE) {

            $boletoData = [
                'name' => $data[0],
                'governmentId' => $data[1],
                'email' => $data[2],
                'debtAmount' => (float) $data[3],
                'debtDueDate' => $data[4],
                'debtId' => $data[5],
            ];

            // validações básicas
            $this->assertNotEmpty($boletoData['name'], "O nome está vazio");
            $this->assertNotEmpty($boletoData['governmentId'], "O CPF/CNPJ está vazio");
            $this->assertNotEmpty($boletoData['email'], "O e-mail está vazio");
            $this->assertIsFloat($boletoData['debtAmount'], "O valor da dívida não é válido");
            $this->assertNotEmpty($boletoData['debtDueDate'], "A data de vencimento está vazia");
            $this->assertNotEmpty($boletoData['debtId'], "O ID da dívida está vazio");

             
            $boleto = Boleto::generate($boletoData);

            //verificar se o boleto foi criado no banco de dados
            $this->assertDatabaseHas('boletos', [
                'debtId' => $boletoData['debtId'],
                'name' => $boletoData['name'],
            ]);

            // verificar se o e-mail foi enviado
            Mail::assertSent(BoletoGerado::class, function ($mail) use ($boletoData) {
                return $mail->hasTo($boletoData['email']);
            });

            
         }

        // fechar o arquivo
        fclose($file);
    }

    /** nesse teste eu crio um arquivo csv sem as informações corretas para poder validar se salva as informações ou da erro */
    public function testSimulaArquivoCsv()
    {
        Queue::fake();

        // cria o arquivo fake
        $csvFilePath = public_path('inputOld.csv');
        $csvFile = fopen($csvFilePath, 'w');
        fwrite($csvFile, "header1,header2\nvalue1,value2");
        fclose($csvFile);
        
        $response = $this->postJson('/process-csv-local');
    
        // Verifica se a resposta foi bem-sucedida
        $response->assertStatus(200)
                ->assertJson(['message' => 'Arquivo enviado, iniciando processamento.']);

        // Verifica se o job foi disparado
        Queue::assertPushed(ProcessCsvJob::class, function ($job) use ($csvFilePath) {
            return $job->path === $csvFilePath;  
        });
    }

}
