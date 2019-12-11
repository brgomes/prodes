<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Liga extends Model
{
    protected $table 	= 'liga';
    protected $fillable = ['nome', 'codigo', 'tipo', 'datainicio', 'datafim', 'regulamento', 'datalimiteentrada',
        'pontosacertoplacar', 'pontosacertovencedor', 'temcoringa', 'consolidar', 'dataconsolidacao', 'created_by', 'updated_by'];

    public function rodadas()
    {
        return $this->hasMany(Rodada::class)->with(['partidas', 'classificacao']);
    }

    public function jogadores()
    {
        return $this->hasMany(Jogador::class);
    }

    public function administradores()
    {
        return $this->hasMany(Jogador::class)->where('admin', true);
    }

    public function rodada($id = null)
    {
        if (isset($id)) {
            return Rodada::with(['partidas', 'classificacao'])->where('liga_id', $this->id)->find($id);
        }

        $liga = Rodada::where('liga_id', $this->id)
                ->where('datainicio', '>=', Carbon::now()->setTimezone(config('app.timezone')))
                ->orderBy('datafim')
                ->with(['partidas', 'classificacao'])
                ->first();

        if ($liga) {
            return $liga;
        }

        return Rodada::where('liga_id', $this->id)
                ->where('datainicio', '<=', Carbon::now()->setTimezone(config('app.timezone')))
                ->orderBy('datafim', 'DESC')
                ->with(['partidas', 'classificacao'])
                ->first();
    }

    public function consolidar()
    {
        $jogadores  = $this->jogadores;
        $rodadas    = $this->rodadas;

        foreach ($jogadores as $jogador) {
            $pontosDisputadosLiga   = 0;
            $pontosGanhosLiga       = 0;
            $bonusDisputados        = 0;
            $bonusGanhos            = 0;
            $rodadasJogadas         = 0;
            $totalPartidasLiga      = 0;
            $totalPontos            = 0;

            foreach ($rodadas as $rodada) {
                $pontosDisputadosRodada = 0;
                $pontosGanhosRodada     = 0;
                $totalPartidasLiga      += $rodada->partidas->count();

                $palpites = Palpite::where('rodada_id', $rodada->id)
                            ->where('jogador_id', $jogador->id)
                            ->with('partida')
                            ->get();

                if ($palpites->count() == 0) {
                    $aproveitamentoRodada = null;
                } else {
                    foreach ($palpites as $palpite) {
                        if ($palpite->partida->temresultado) {
                            if ($this->tipo == 'P') {
                                $pontosDisputadosLiga   += $this->pontosacertoplacar;
                                $pontosDisputadosRodada += $this->pontosacertoplacar;

                                $golsM = (int) $palpite->partida->golsmandante;
                                $golsV = (int) $palpite->partida->golsvisitante;

                                if (($palpite->palpitegolsm == $golsM) && ($palpite->palpitegolsv == $golsV)) {
                                    $pontosGanhosLiga   += $this->pontosacertoplacar;
                                    $pontosGanhosRodada += $this->pontosacertoplacar;

                                    $pontuacao      = $this->pontosacertoplacar;
                                    $nivelacerto    = 'T';
                                } else {
                                    if ($golsM > $golsV) {
                                        $vencedor = 'M';
                                    } elseif ($golsV > $golsM) {
                                        $vencedor = 'V';
                                    } else {
                                        $vencedor = 'E';
                                    }

                                    if ($palpite->palpitegolsm > $golsV) {
                                        $palpite_vencedor = 'M';
                                    } elseif ($palpite->palpitegolsv > $golsM) {
                                        $palpite_vencedor = 'V';
                                    } else {
                                        $palpite_vencedor = 'E';
                                    }

                                    if ($vencedor == $palpite_vencedor) {
                                        $pontosGanhosLiga   += $this->pontosacertovencedor;
                                        $pontosGanhosRodada += $this->pontosacertovencedor;

                                        $pontuacao      = $this->pontosacertovencedor;
                                        $nivelacerto    = 'P';
                                    } else {
                                        $pontuacao      = 0;
                                        $nivelacerto    = 'Z';
                                    }
                                }
                            } elseif ($this->tipo == 'V') {
                                $pontosDisputadosLiga   += $this->pontosacertovencedor;
                                $pontosDisputadosRodada += $this->pontosacertovencedor;

                                if ($palpite->palpite == $palpite->partida->vencedor) {
                                    $pontosGanhosLiga   += $this->pontosacertovencedor;
                                    $pontosGanhosRodada += $this->pontosacertovencedor;

                                    $pontuacao      = $this->pontosacertovencedor;
                                    $nivelacerto    = 'T';
                                } else {
                                    $pontuacao      = 0;
                                    $nivelacerto    = 'Z';
                                }
                            }

                            if (($this->temcoringa == 1) && ($palpite->coringa == 1)) {
                                $pontuacao *= 2;
                            }

                            $palpite->update(['consolidado' => true, 'nivelacerto' => $nivelacerto, 'pontos' => $pontuacao]);
                        } else {
                            $palpite->update(['consolidado' => false, 'nivelacerto' => null, 'pontos' => null]);
                        }
                    }

                    if ($pontosDisputadosRodada > 0) {
                        $rodadasJogadas++;

                        $aproveitamentoRodada = round((($pontosGanhosRodada * 100) / $pontosDisputadosRodada), 2);
                    }
                }

                if ($pontosDisputadosRodada > 0) {
                    $where = [
                        'liga_id'       => $rodada->liga_id,
                        'rodada_id'     => $rodada->id,
                        'jogador_id'    => $jogador->id,
                    ];

                    $values = [
                        'pontosdisputados'  => $pontosDisputadosRodada,
                        'pontosganhos'      => $pontosGanhosRodada,
                        'aproveitamento'    => $aproveitamentoRodada,
                    ];

                    Classificacao::updateOrCreate($where, $values);
                }
            }

            if ($pontosDisputadosLiga == 0) {
                $aproveitamentoLiga = 0;
            } else {
                $aproveitamentoLiga = round((($pontosGanhosLiga * 100) / $pontosDisputadosLiga), 2);
            }

            // Calcular AQUI os pontos bônus

            $totalPontos = $pontosGanhosLiga + $bonusGanhos;

            $jogador->update([
                'rodadasjogadas'    => $rodadasJogadas,
                'pontosdisputados'  => $pontosDisputadosLiga,
                'pontosganhos'      => $pontosGanhosLiga,
                'totalpontos'       => $totalPontos,
                'aproveitamento'    => $aproveitamentoLiga,
            ]);
        }

        foreach ($rodadas as $rodada) {
            $rodada->rankear();
        }

        // Se a rodada já não tiver partidas abertas, calcula a quantidade de vencedores
        foreach ($jogadores as $jogador) {
            $liderancas = Classificacao::where('liga_id', $this->id)
                            ->where('jogador_id', $jogador->id)
                            ->where('lider', 1)
                            ->get();

            $jogador->update(['rodadasvencidas' => $liderancas->count()]);
        }

        $this->rankear();

        $this->update(['consolidar' => false, 'dataconsolidacao' => Carbon::now()->setTimezone(config('app.timezone'))]);

        return true;
    }

    public function rankear()
    {
        $itens = Jogador::addSelect(['primeironome' => Usuario::select('primeironome')->whereColumn('usuario.id', 'jogador.usuario_id')])
                    ->where('liga_id', $this->id)
                    ->orderBy('totalpontos', 'DESC')
                    ->orderBy('rodadasvencidas', 'DESC')
                    ->orderBy('aproveitamento', 'DESC')
                    ->orderBy('primeironome')
                    ->get();

        $i = 1;

        foreach ($itens as $item) {
            $item->update(['posicao' => $i]);

            $i++;
        }

        return true;
    }
}
