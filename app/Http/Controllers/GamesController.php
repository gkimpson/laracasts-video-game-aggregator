<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class GamesController extends Controller
{
    public $apiUrl = 'https://api.igdb.com/v4/games';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $before = Carbon::now()->subMonth(2)->timestamp;
        $after = Carbon::now()->addMonth(2)->timestamp;
        $current = Carbon::now()->timestamp;

        $highestRatedGames = $this->getHighestRatedGames($before, $after, $current);
        $recentlyReviewedGames = $this->getRecentlyReviewed($before, $after);
        $comingSoonGames = $this->getComingSoonGames($current);
        dump($comingSoonGames);

        return view('index', [
            'highestRatedGames' => $highestRatedGames,
            'recentlyReviewedGames' => $recentlyReviewedGames,
            'comingSoonGames' => $comingSoonGames
        ]);
    }

    public function getHighestRatedGames($before, $after, $current) {
        // gets current gen games
        return Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
        ])
            ->withToken(env('IGDB_ACCESS_TOKEN'))
            ->withBody(
                "fields *, cover.url, first_release_date, platforms.abbreviation, rating;
                        where rating != null & platforms = (48, 49, 130, 6)
                        & (first_release_date > {$before} & first_release_date < {$after});
                        sort rating desc;
                        limit 10;",'raw')
            ->post($this->apiUrl)->json();
    }

    public function getRecentlyReviewed($before, $current) {
        return Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
        ])
            ->withToken(env('IGDB_ACCESS_TOKEN'))
            ->withBody(
                "fields *, cover.url, first_release_date, platforms.abbreviation, rating;
                        where rating != null & platforms = (48, 49, 130, 6)
                        & rating_count > 10
                        & (first_release_date > {$before} & first_release_date < {$current});
                        sort rating desc;
                        limit 3;",'raw')
            ->post($this->apiUrl)->json();
    }

    public function getComingSoonGames($current) {
        return Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
        ])
            ->withToken(env('IGDB_ACCESS_TOKEN'))
            ->withBody(
                "fields *, cover.url, first_release_date, platforms.abbreviation, rating;
                        where rating != null & platforms = (48, 49, 130, 6)
                        & rating_count > 10;
                        sort first_release_date desc;
                        limit 3;",'raw')
            ->post($this->apiUrl)->json();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
