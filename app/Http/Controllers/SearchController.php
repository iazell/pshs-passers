<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Passer;
use DB;
use App\Http\Resources\Passer as PasserResource;
use Illuminate\Http\Resources\Json\Resource;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($search)
    {
        $searchable = [
            'name',
            'school',
            'division'
        ];

        $columns = implode(',',$searchable);
        $searchableTerm = $this->fullTextWildcards($search);

        $passers = Passer::selectRaw("*, MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE) AS relevance_score", [$searchableTerm])
                        ->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $searchableTerm)
                        ->orderBy('relevance_score', 'asc')->get();

        return PasserResource::collection($passers);
    }

    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if(strlen($word) >= 1) {
                $words[$key] = '+' . $word . '*';
            }
        }

        $searchTerm = implode( ' ', $words);

        return $searchTerm;
    }

    
}
