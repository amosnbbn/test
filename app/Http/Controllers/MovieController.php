<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    private $apiKey = 'd75d24a6';

    public function index(Request $request)
    {
        $year = $request->input('year', '');
        $genreFilter = $request->input('genre', '');

        $movies = [];
        $sampleMovies = ['tt0111161','tt0068646','tt0071562','tt0468569','tt0050083'];

        foreach ($sampleMovies as $id) {
            $details = json_decode(@file_get_contents("http://www.omdbapi.com/?apikey={$this->apiKey}&i=".$id."&plot=full"), true);
            if (!$details) continue;

            if ($year && isset($details['Year']) && $details['Year'] != $year) continue;

            if ($genreFilter) {
                $movieGenres = explode(',', $details['Genre'] ?? '');
                $movieGenres = array_map('trim', $movieGenres);
                if (!in_array($genreFilter, $movieGenres)) continue;
            }

            $movies[] = $details;
        }

        return view('movies.index', [
            'movies'=>$movies,
            'year'=>$year,
            'genre'=>$genreFilter,
        ]);
    }

    public function toggleFavorite(Request $request, $imdbID)
    {
        $favorites = session('favorites', []);

        if (in_array($imdbID, $favorites)) {
            $favorites = array_diff($favorites, [$imdbID]);
        } else {
            $favorites[] = $imdbID;
        }

        session(['favorites' => $favorites]);
        return back();
    }

    public function favorites()
    {
        $favoritesIDs = session('favorites', []);
        $movies = [];

        foreach ($favoritesIDs as $id) {
            $details = json_decode(@file_get_contents("http://www.omdbapi.com/?apikey={$this->apiKey}&i=".$id."&plot=full"), true);
            if ($details) $movies[] = $details;
        }

        return view('movies.index', [
            'movies'=>$movies,
            'year'=>'',
            'genre'=>'',
        ]);
    }

    public function show($imdbID)
    {
        $details = json_decode(@file_get_contents("http://www.omdbapi.com/?apikey={$this->apiKey}&i=".$imdbID."&plot=full"), true);
        $isFav = in_array($imdbID, session('favorites', []));
        return view('movies.show', compact('details','isFav'));
    }

    public function changeLanguage($lang)
    {
        if (in_array($lang,['id','en'])){
            session(['locale'=>$lang]);
        }
        return back();
    }
}