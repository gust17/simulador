<?php

namespace App\Jobs;

use App\Models\Arquivo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportArquivosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $arquivos = Arquivo::whereIn('id', range(10001, 12000))->get();
        //$arquivos = Arquivo::all();a
        //$filename = 'arquivos_exportados.csv';

        //  $contents = "matricula;cpf;nome;verba;solicitacao;valor_parcela;valor_realizado\n";
        $file = fopen("agora.txt", "a"); // Open the text file in "append" mode

        foreach ($arquivos as $arquivo) {
            // Build the line to be written to the text file
            $line = "{$arquivo->pessoa->servidores->where('nr_matricula', $arquivo->matricula)->first()->nr_matricula};{$arquivo->cpf};{$arquivo->pessoa->nm_pessoa};{$arquivo->cod_verba};";

            if (!empty($arquivo->solicitacao())) {
                $line .= "{$arquivo->solicitacao()->cd_solicitacao};{$arquivo->solicitacao()->vl_parcela};";
            } else {
                $line .= "0;0;";
            }

            $line .= "{$arquivo->valor_realizado}\n";

            fwrite($file, $line); // Write the line to the text file
        }

        fclose($file);
        //   fwrite($file, $line); // Write the line to the text file
        /*
          foreach ($arquivos as $arquivo) {
              $servidor = $arquivo->pessoa->servidores->where('nr_matricula', $arquivo->matricula)->first();

              $solicitacao = $arquivo->solicitacao();
              $cd_solicitacao = $solicitacao ? $solicitacao->cd_solicitacao : 0;
              $vl_parcela = $solicitacao ? $solicitacao->vl_parcela : 0;

              $contents .= "{$servidor->nr_matricula};{$arquivo->cpf};{$arquivo->pessoa->nm_pessoa};{$arquivo->cod_verba};{$cd_solicitacao};{$vl_parcela};{$arquivo->valor_realizado}\n";
          } */

        //   \Storage::disk('local')->append($filename, $contents);
    }
}
