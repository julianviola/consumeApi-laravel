<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;


class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = Photo::all();

        return response()->json($photos)->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $responses = Http::acceptJson()->get('https://jsonplaceholder.typicode.com/photos/');
        $photos = $responses->json();

        foreach ($photos as $photo)
        { 
            $arrayPhoto = array('album_id' => $photo['albumId'], 'id' => $photo['id'],
                'title' => $photo['title'], 'url' => $photo['url'],
                'thumbnail_url' => $photo['thumbnailUrl']);

            Photo::create($arrayPhoto);
            echo "Created Photo:" . $photo['id'] ."<br>";   
        }
        
        return "<br>Finished";
    }
}
