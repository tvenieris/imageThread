<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Input;

use App\Post;

use DB;

use App\Stats;

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

    private function smartResponse(Request $request, Array $response) {
        if ($request->wantsJson()) {
            return response($response, $response['code']);
        }
        $request->session()->flash('last_error', $response);
        return redirect('/');
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
            return $this->smartResponse($request, ['code' => 400, 'description' => 'No file uploaded']);
        }
        $file = $request->file($fieldname);
        if (!$file->isValid()) {
            // return response(['code' => 400, 'description' => 'Invalid file uploaded'], 400);
            return $this->smartResponse($request, ['code' => 400, 'description' => 'Invalid file uploaded']);
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
                // return response(['code' => 400, 'description' => 'Uploaded file is not an image'], 400);
                return $this->smartResponse($request, ['code' => 400, 'description' => 'Uploaded file is not an image']);
        }

        /*
        if (true) {
            return response(['code' => 400, 'description' => 'upload max is ' . $file->getMaxFilesize()], 400);
        }
         */
        
        $limit = 20000000;
        if ($file->getSize() > $limit) {
            // return response(['code' => 400, 'description' => 'Uploaded file is bigger than ' . $limit . ' bytes'], 400);
            return $this->smartResponse($request, ['code' => 400, 'description' => 'Uploaded file is bigger than ' . $limit . ' bytes']);
        }

        $badPicError = ['code' => 400, 'description' => 'Uploaded file is not a correct image'];
        $imageInfo = getimagesize($file->getRealPath());
        if (empty($imageInfo) || empty($imageInfo[3])) {
            // return response($badPicError, 400);
            return $this->smartResponse($request, $badPicError);
        }
        switch ($imageInfo[2]) {
            case IMG_GIF:
            case IMG_JPEG:
            case IMG_PNG:
                break;
            default:
                // return response($badPicError, 400);
                return $this->smartResponse($request, $badPicError);
        }
        
        $widthMax =  1920;
        $heightMax = 1080;
        if(($imageInfo[0] > $widthMax) || ($imageInfo[1] > $heightMax)) {
            /*
            return response(['code' => 400, 'description' => 'Uploaded file is '
                . $imageInfo[0] . 'x' . $imageInfo[1] . ', bigger than ' . $widthMax . 'x' . $heightMax], 400);
             */
            return $this->smartResponse($request, ['code' => 400, 'description' => 'Uploaded file is '
                . $imageInfo[0] . 'x' . $imageInfo[1] . ', bigger than ' . $widthMax . 'x' . $heightMax]);
        }
/*        
        $imageType = exif_imagetype($file->getRealPath());
        switch ($imageType) {
            case IMAGETYPE_GIF:
            case IMAGETYPE_JPEG:
            case IMAGETYPE_PNG:
                break;
            default:
                return response(['code' => 400, 'description' => 'Uploaded file is not a correct image'], 400);
        }
*/      
        $tries = 0;
        do {
            $finalname = uniqid('', true) . '.' . $extension;
        } while (file_exists(base_path() . '/public/uploads/' . $finalname) || ($tries++ < 16));
        
        if (!$file->move('uploads/', $finalname)) {
            // return response(['code' => 500, 'description' => 'Server problem!'], 500);
            return $this->smartResponse($request, ['code' => 500, 'description' => 'Server problem!']);
        }
        
        $post = new Post();
        if ($title) {
            $post->title = $title;
        }
        $post->image_path = $finalname;
        $post->save();
        
        if ($request->wantsJson()) {
            return response(['code' => 200, 'description' => 'Image uploaded!'], 200);
        }
        return redirect('/');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getStats()
    {
        $stats = Stats::getViews();
        return response([
            'posts_amount' => DB::table('posts')->count(),
            'views_amount' => $stats->views
        ]);
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
