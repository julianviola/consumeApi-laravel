<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

ini_set('max_execution_time', 360);

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $photos = Photo::all();

        return response()->json($photos)->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JSON Response 
     */
    public function store(): JsonResponse
    {
        $response = Http::retry(10, 100)->acceptJson()->get('https://jsonplaceholder.typicode.com/photos/');
        
        if($response->successful())
        {
            $photos = $response->json();
            
            foreach ($photos as $photo)
            { 
                $validator = $this->validatePhoto($photo);

                if($validator->fails())
                {
                    return response()->json(["message" => $validator->messages()])
                                            ->setStatusCode(400);
                }
                
                $arrayPhoto = array('album_id' => $photo['albumId'], 'id' => $photo['id'],
                     'title' => $photo['title'], 'url' => $photo['url'],
                     'thumbnail_url' => $photo['thumbnailUrl']);
                
                Photo::create($arrayPhoto);
            }

            return response()->json(['message' => 'OK', "status" => $response->status()])
                            ->setStatusCode($response->status());
        }
        else
        {
            return response()->json(['message' => 'ERROR', "status" => $response->status()])
                            ->setStatusCode($response->status());
        }
    }
    
    /**
     * Validate input data
     * 
     * @param  Array $photo
     * @return Object $validator
     */
    protected function validatePhoto(Array $photo): Object
    {
        $validator = Validator::make($photo, [
                    'albumId' => 'required|integer',
                    'id' => 'required|integer',
                    'title' => 'required|string|max:255',
                    'url' => 'required|string|max:255',
                    'thumbnailUrl' => 'required|string|max:255',
                ]);
        
        return $validator;
    }
}
