<?php

namespace App\Http\Controllers;

use App\Covidpp;
use Illuminate\Http\Request;

class CovidppController extends Controller
{


    const POPULACAO_PRUDENTE = 207610;
    const LETALIDADE_REAL = 0.012;

    public function getBaseData()
    {
        $this->getCovidHtml();
        return response()->json($this->getCovidHtml());
    }

    public function getCovidHtml(){
        $html="";
        try {
            $html = file_get_contents('https://www.inovaprudente.com.br/coronavirus');
        } catch (\Throwable $th) {
            return $th;
        }

        $pos = strpos($html, "const source_datasets = ") + 24;
        $pos2 = strpos($html, ";", $pos);

        $html = substr($html, $pos, ($pos2-$pos));

        $json = json_decode($html);
        $return = array();

        foreach($json as $variable){
            $return[strtolower($this->stripAccents($variable->label))] = $variable->data;
        }

        $initialDate = '2020-03-18';
        $dateVariable      = strtotime($initialDate);

        for ($i=0; $i < sizeof($return['obitos']); $i++) {
            $return['obitos'][$i]=intval($return['obitos'][$i]);
            $return['confirmados'][$i]=intval($return['confirmados'][$i]);
            $return['hospitalizados'][$i]=intval($return['hospitalizados'][$i]);
            $return['aguardando_resultado'][$i]=intval($return['aguardando_resultado'][$i]);
            $return['descartados'][$i]=intval($return['descartados'][$i]);
            $return['notificacoes'][$i]=intval($return['notificacoes'][$i]);
            $return['curados'][$i]=intval($return['curados'][$i]);
            $return['data'][$i] = ($i == 0) ? $initialDate
            : date('Y-m-d', strtotime('+ ' . ($i == 1)? $i .' day': $i .' days', $dateVariable));
            $return['descartados_percentual'][$i] = $return['descartados'][$i] / $return['notificacoes'][$i];
            $return['confirmados_percentual'][$i] = $return['confirmados'][$i] / $return ['notificacoes'][$i];
            $return['testados_percentual'][$i] = ($return['confirmados'][$i] +
                                                $return['descartados'][$i]) / $return['notificacoes'][$i];
            $return['prevalencia'][$i] = $return['confirmados'][$i] - $return['curados'][$i] - $return['obitos'][$i];
            $return['prevalencia'][$i] = ($return['prevalencia'][$i] >= 0) ? $return['prevalencia'][$i] : 0;
            $return['letalidade'][$i] = ($return['confirmados'][$i] > 0)
                                        ? $return['obitos'][$i] / $return['confirmados'][$i] : 0;
            $return['mortalidade'][$i] = ($return['obitos'][$i] / self::POPULACAO_PRUDENTE) * 100000;
            $return['taxa_subnotificacao'][$i] = $return['letalidade'][$i] / self::LETALIDADE_REAL;
            $return['subnotificacao_prevalencia'][$i] = $return['prevalencia'][$i] * $return['taxa_subnotificacao'][$i];
            $return['subnotificacao_total'][$i] = $return['confirmados'][$i] * $return['taxa_subnotificacao'][$i];
            $return['assintomaticos_prevalencia'][$i] = $return['subnotificacao_prevalencia'][$i] -
                                                        $return['prevalencia'][$i];
            $return['assintomaticos_total'][$i] = $return['subnotificacao_total'][$i] -
                                                $return['confirmados'][$i] - $return['obitos'][$i] -
                                                $return['curados'][$i];
            $return['assintomaticos_total'][$i] = ($return['assintomaticos_total'][$i] >= 0)
                                                    ? $return['assintomaticos_total'][$i] : 0;
        }

        return(($return));
    }

    private function stripAccents($str){
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ '), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY_');
    }

    public function showOneAuthor($id)
    {
        return response()->json(Author::find($id));
    }

    public function create(Request $request)
    {
        $author = Author::create($request->all());

        return response()->json($author, 201);
    }

    public function update($id, Request $request)
    {
        $author = Author::findOrFail($id);
        $author->update($request->all());

        return response()->json($author, 200);
    }

    public function delete($id)
    {
        Author::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}