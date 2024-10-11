<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;  
use App\Mail\BoletoGerado;

class Boleto extends Model
{
    protected $table = 'boletos';

    protected $fillable = [
        'name',
        'governmentId',
        'email',
        'debtAmount',
        'debtDueDate',
        'debtId',
    ];

    public static function generate($data)
    {
        Log::info("Gerando boleto para {$data['name']}, valor: R$ " . number_format($data['debtAmount'], 2, ',', '.'));
        
        // cria o registro do boleto
        $boleto = self::create($data);

        // envia o e-mail
        Mail::to($data['email'])->send(new BoletoGerado($boleto));

        return $boleto;  
    }
}

