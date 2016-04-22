<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Input;

class ImageThreadAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return 'ImageThreadAPIController index!';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title = $request->input('title');
     
        $fieldname = 'image';
        if (!$request->hasFile($fieldname)) {
            return response(['code' => 400, 'description' => 'No file uploaded'], 400);
        }
        $file = $request->file($fieldname);
        if (!$file->isValid()) {
            return response(['code' => 400, 'description' => 'Invalid file uploaded'], 400);
        }
        
        $clientExtension = $file->getClientOriginalExtension();
        $extension = $clientExtension;
        switch ($clientExtension) {
            case 'png':
            case 'jpg':
            case 'jpeg':
            case 'gif':
                break;
            default:
                return response(['code' => 400, 'description' => 'Uploaded file is not an image'], 400);
        }

        /*
        if (true) {
            return response(['code' => 400, 'description' => 'upload max is ' . $file->getMaxFilesize()], 400);
        }
         */
        
        $limit = 200000;
        if ($file->getSize() > $limit) {
            return response(['code' => 400, 'description' => 'Uploaded file is bigger than ' . $limit . ' bytes'], 400);
        }
        
        $imageType = exif_imagetype($file->getRealPath());
        switch ($imageType) {
            case IMAGETYPE_GIF:
            case IMAGETYPE_JPEG:
            case IMAGETYPE_PNG:
                break;
            default:
                return response(['code' => 400, 'description' => 'Uploaded file is not a correct image'], 400);
        }
        
        $tries = 0;
        do {
            $finalname = uniqid('', true) . '.' . $extension;
        } while (file_exists(base_path() . '/public/uploads/' . $finalname) || ($tries++ < 16));
        
        if (!$file->move('uploads/', $finalname)) {
            return response(['code' => 500, 'description' => 'Server problem!'], 500);
        }
        
        return 'ImageThreadAPIController create! ' . $title;
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
        return 'ImageThreadAPIController show!';
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
        return 'ImageThreadAPIController edit!';
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
        return 'ImageThreadAPIController update!';
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
        return 'ImageThreadAPIController destroy!';
    }
}
