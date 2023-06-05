<?php

namespace App\Observers;

use App\Models\Models\RegraHistorico;
use App\Models\Regra;

class RegraObserver
{
    /**
     * Handle the Regra "created" event.
     */
    public function created(Regra $regra)
    {
        $this->createHistorico($regra, '1', $regra->usuario);
    }

    public function updated(Regra $regra)
    {
        $this->createHistorico($regra, '2', $regra->usuario);
    }

    public function deleted(Regra $regra)
    {
        $this->createHistorico($regra, '3', $regra->usuario);
    }

    /**
     * Handle the Regra "restored" event.
     */
    public function restored(Regra $regra): void
    {
        //
    }

    /**
     * Handle the Regra "force deleted" event.
     */
    public function forceDeleted(Regra $regra): void
    {
        //
    }


    private function createHistorico(Regra $regra, string $evento, $userId)
    {
        $historico = new RegraHistorico();
        $historico->regra_id = $regra->id;
        $historico->name = $regra->name;
        $historico->inicio = $regra->inicio;
        $historico->fim = $regra->fim;
        $historico->consignataria_cd_consignataria = $regra->consignataria_cd_consignataria;
        $historico->evento = $evento;
        $historico->usuario = $userId;

        $historico->save();
    }
}
