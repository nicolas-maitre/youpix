<?php

namespace App\Http\Controllers;

use Str;
use Storage;
use App\Picture;
use Illuminate\Http\Request;
use App\Http\Requests\PictureStoreRequest;
use Aws\S3\S3Client;
use Aws\S3\PostObjectV4;
class PictureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pictures = Picture::all();
        return view("pictures.index")->with(["pictures" => $pictures]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => ['key'    => env('AWS_ACCESS_KEY_ID'), 'secret' => env('AWS_SECRET_ACCESS_KEY'),],
        ]);

        $bucket = env('AWS_BUCKET');

        $key = 'picturesNM/'.Str::random(40);
        $formInputs = [
            'acl' => 'private', 
            'key' => $key
        ];

        $options = [
            ['acl' => 'private'], 
            ['bucket' => $bucket], 
            ['eq', '$key', $key]
        ];

        $expires = '+5 minutes';

        $postObject = new PostObjectV4($client, $bucket, $formInputs, $options, $expires);

        $formAttributes = $postObject->getFormAttributes();

        $formInputs = $postObject->getFormInputs();
        return view("pictures.create", compact("formAttributes", "formInputs"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PictureStoreRequest $request)
    {
        $picture = new Picture();
        $picture->fill($request->all());
        $picture->storage_path = $request->picture->store('pictures', 's3');
        $picture->save();
        return redirect()->route("pictures.show", compact('picture'));
    }

    public function apiStore(Request $request)
    {
        $picture = new Picture();
        $picture->title = $request->title;
        $picture->storage_path = $request->storage_path;
        $picture->save();
        return $picture;   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Picture $picture)
    {
        if (Str::startsWith($request->header("Accept"), "image")) {
            return redirect(Storage::disk('s3')->temporaryUrl($picture->storage_path, time() + 60));
        }
        return view("pictures.show")->with(compact('picture'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function edit(Picture $picture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Picture $picture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Picture $picture)
    {
        Storage::disk('s3')->delete($picture->storage_path);
        $picture->delete();
        return redirect()->route('pictures.index');
    }
}
