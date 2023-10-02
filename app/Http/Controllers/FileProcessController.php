<?php

namespace App\Http\Controllers;

use Exception;
use RuntimeException;
use App\Models\FileProcess;
use Illuminate\Http\Request;
use Owenoj\LaravelGetId3\GetId3;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreFileProcessRequest;
use App\Http\Requests\UpdateFileProcessRequest;

class FileProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = FileProcess::query()->get();

        if ($video) {
            return new JsonResponse([
                'data' => $videos,
                'statusCode' => 201
            ]);
        }else {
            return new JsonResponse([
                'error' => 'No video found',
                'statusCode' => 404
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //Getting the vidoe file
            $track = new GetId3(request()->file('video'));

            $video = $request->file('video');


            $fileSize = $video->getSize();
            $uploadedFile = $video->storePublicly('public');
            $fileNameHash = $video->hashName();
            $filenameWithoutExtension = pathinfo($fileNameHash, PATHINFO_FILENAME);

            //get playtime
            $duration = $track->getPlaytime();

            //Storage::putFile('vidoes', $video);
            $fileUrl = Storage::url('public/'.$fileNameHash);

            $videoUrl = 'http://127.0.0.1:8000'.$fileUrl;
            //$downloadLink = Storage::download($fileUrl);

            return new JsonResponse([
                'name' => $filenameWithoutExtension,
                'video-url' => $videoUrl,
                'file-size' => $fileSize.' bytes',
                'duration' => $duration. ' minutes'
            ], 201);
        } catch (RuntimeException $e) {
            // Handle FFMpeg runtime exceptions
            return new JsonResponse([
                'error' => 'Unable to process the video file.',
            ], 500);
        } catch (Exception $e) {
            // Handle other general exceptions
            return new JsonResponse([
                'error' => 'An error occurred while processing the video file.',
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(FileProcess $fileProcess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileProcessRequest $request, FileProcess $fileProcess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileProcess $fileProcess)
    {
        //Delete file(s)
        if (Storage::exists("vidoes/2JSRv8bbuEradhEGvCf67aHnHMGPGj8NntBWXvTg.jpg")) {
            Storage::delete("vidoes/2JSRv8bbuEradhEGvCf67aHnHMGPGj8NntBWXvTg.jpg");
        }else {
            return new JsonResponse([
                'error' => 'File not found',
                'statusCode' => 404
            ], 404);
        }

    }
}
