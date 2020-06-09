<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Covidpp extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'github', 'twitter', 'location', 'latest_article_published'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function getCovidHtml(){
        $html = file_get_contents('https://stackoverflow.com/questions/ask');

        var_dump($html);die;

        return($html);
    }
}