<?php
namespace App\Http\Controllers;
use Str;
use Storage;
use App\Picture;
use Illuminate\Http\Request;
use App\Http\Requests\PictureStoreRequest;

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
        return view("pictures.index")->with(["pictures"=>$pictures]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("pictures.create");
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
        $picture->storage_path=$request->picture->store('pictures', 's3');
        $picture->save();
        return redirect()->route("pictures.show", compact('picture'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Picture $picture)
    {
        if(Str::startsWith ($request->header("Accept"), "image")){
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
