<?php

namespace App\Console\Commands;

use App\Models\Arquivo;
use Illuminate\Console\Command;

class ExportArquivosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-arquivos-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $arquivos = \App\Models\Arquivo::whereIn('id', range(1, 5))->get();

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

        fclose($file); // Close the text file

        $this->info('Arquivos data exported to arquivos.txt');
    }
}
