<?php

namespace App\Mail;

use App\Models\Boleto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BoletoGerado extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct(Boleto $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('emails.boleto_gerado')
                    ->subject('Seu boleto foi gerado')
                    ->from(config('mail.from.address'), config('mail.from.name'))  
                    ->with([
                        'name' => $this->data['name'],
                        'debtAmount' => number_format($this->data['debtAmount'], 2, ',', '.'),
                        'debtDueDate' => $this->data['debtDueDate'],
                    ]);
    }
}
