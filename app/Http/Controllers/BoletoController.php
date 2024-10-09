<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Mail\BoletoGerado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BoletoController extends Controller
{
    public function store(Request $request)
    {
        // Validação dadoss
        $data = $request->validate([
            'name' => 'required|string',
            'governmentId' => 'required|string',
            'email' => 'required|email',
            'debtAmount' => 'required|numeric',
            'debtDueDate' => 'required|date',
            'debtId' => 'required|uuid',
        ]);

        // logica para evitar duplicidade
        if (Boleto::where('debtId', $data['debtId'])->exists()) {
            return response()->json(['message' => 'Boleto já foi gerado.'], 422);
        }

        // cria boleto
        $boleto = Boleto::create($data);

        // envia e-mail
        Mail::to('claytonsantos13@hotmail.com')->send(new BoletoGerado($boleto));

        return response()->json(['message' => 'Boleto gerado e e-mail enviado.'], 201);
    }
}
