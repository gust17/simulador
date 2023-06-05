<?php

namespace App\Observers;


use App\Models\Models\TaxaHistorico;
use App\Models\Taxas;

class TaxaObserver
{
    /**
     * Handle the Taxa "created" event.
     */
    public function created(Taxas $taxa)
    {
        $this->createHistorico($taxa, 1, $taxa->usuario);
    }

    public function updated(Taxas $taxa)
    {
        $this->createHistorico($taxa, 2, $taxa->usuario);
    }

    public function deleted(Taxas $taxa)
    {
        $this->createHistorico($taxa, 3, $taxa->usuario);
    }

    /**
     * Handle the Taxa "restored" event.
     */
    public function restored(Taxas $taxa): void
    {
        //
    }

    /**
     * Handle the Taxa "force deleted" event.
     */
    public function forceDeleted(Taxas $taxa): void
    {
        //
    }

    private function createHistorico(Taxas $taxa, $evento, $userId)
    {
        $historico = new TaxaHistorico();
        $historico->prazo = $taxa->prazo;
        $historico->taxa = $taxa->taxa;
        $historico->consignataria_cd_consignataria = $taxa->consignataria_cd_consignataria;
        $historico->consignante_cd_consignante = $taxa->consignante_cd_consignante;
        $historico->regra_id = $taxa->regra_id;
        $historico->evento = $evento;
        $historico->usuario = $userId;

        $historico->save();
    }
}
