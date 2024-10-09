<?php

namespace Tests\Unit;

use App\Models\Boleto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\BoletoGerado;
use Tests\TestCase;

class BoletoTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_email_is_sent_when_boleto_is_generated()
    {
        Mail::fake();

        $data = [
            'name' => 'John Doe',
            'governmentId' => '11111111111',
            'email' => 'claytonsantos13@hotmail.com', // Deve corresponder ao e-mail utilizado no envio
            'debtAmount' => 5666.00,
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

    public function test_processar_boletos_de_csv()
    {
        Mail::fake();

        // rota do csv
        $filePath = public_path('input.csv');

        // abrir o arquivo para leitura
        $file = fopen($filePath, 'r');
        
        // Ignora o cabeçalho
        fgetcsv($file);

        // Percorre cada linha do arquivo
        $lineCount = 0;
        $maxLines = 50; // limite para não pesar a máquina

        while (($data = fgetcsv($file)) !== FALSE && $lineCount < $maxLines) {

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

            $lineCount++;
         }

        // fechar o arquivo
        fclose($file);
    }

}
