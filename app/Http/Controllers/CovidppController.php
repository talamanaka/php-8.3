<?php

namespace App\Http\Controllers;

use App\Covidpp;
use Illuminate\Http\Request;

class CovidppController extends Controller
{

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
            $return[$variable->label] = $variable->data;
        }

        return(($return));
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