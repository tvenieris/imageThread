<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Input;

use App\Post;

use DB;

use App\Stats;

use ZipArchive;

use Excel;

class ImageThreadAPIController extends Controller
{
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
            case IMAGETYPE_GIF:
            case IMAGETYPE_JPEG:
            case IMAGETYPE_PNG:
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

    public function exportCSV() {
        
        if (true) {
            // zip file
            $csvHandle = tmpfile();
            $csvMetaData = stream_get_meta_data($csvHandle);
            fputcsv($csvHandle, ['Title', 'URL']);
            Post::chunk(1024, function($posts) use ($csvHandle) {
                foreach ($posts as $post) {
                    fputcsv ($csvHandle, $post->getArray());
                }
            });
            
            $zipExportFilename = 'export_posts.zip';
            $headers = array(
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename=' . $zipExportFilename
            );
            foreach ($headers as $header_name => $header_value) {
                header($header_name . ': ' . $header_value);
            }

            $zipHandle = tmpfile();
            $zipMetaData = stream_get_meta_data($zipHandle);
            $zip = new ZipArchive();
            if ($zip->open($zipMetaData['uri'], ZipArchive::CREATE) !== true) {
                abort(500, 'Failed to create ZIP file');
            }   
            if (!$zip->addFile($csvMetaData['uri'], 'posts.csv')) {
                abort(500, 'Failed to add CSV file to ZIP');
            }
            Post::chunk(1024, function($posts) use ($zip) {
                foreach ($posts as $post) {
                    // echo public_path() . '/uploads/' . $post->image_path;
                    $zip->addFile(public_path() . '/uploads/' . $post->image_path, $post->image_path);
                }
            });
            
            $zip->close();
            
            readfile($zipMetaData['uri']);
            die();
        }
        
        $export_filename = 'export_posts.csv';
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $export_filename
        );
        foreach ($headers as $header_name => $header_value) {
            header($header_name . ': ' . $header_value);
        }
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Title', 'URL']);
        Post::chunk(1024, function($posts) use ($out) {
            foreach ($posts as $post) {
                fputcsv ($out, $post->getArray());
            }
        });
        fclose($out);
        die();
    }
    
    public function exportXLS() {
        Excel::create('export', function ($excel) {
            $excel->sheet('Sheet1', function($sheet) {
                
                $row = 2;
                $pageSize = 1024;
                Post::chunk($pageSize, function($posts) use ($sheet, &$row) {
                    $page = [];
                    foreach ($posts as $post) {
                        $data = array_values($post->getArray());
                        $page[] = $data;
                        
                    }
                    if (!empty($page)) {
                        // $sheet->fromArray($page, null, ('A' . $row)); // doesnt work - dont know why
                        foreach ($page as $line) {
                            $sheet->SetCellValue('A' . $row, $line[0]);
                            $sheet->SetCellValue('B' . $row, $line[1]);
                            $row++;
                        }
                        // $row += $pageSize;
                    }
                });

                $sheet->setCellValue('A1', 'Title');
                $sheet->setCellValue('B1', 'Image');
                
                $header = 'A1:B1';
                $sheet->getStyle($header)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor();
                $style = array(
                    'font' => array('bold' => true,),
                    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
                    );
                $sheet->getStyle($header)->applyFromArray($style);
            });
        })->download('xlsx');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (empty($post)) {
            $response = ['code' => 404, 'description' => 'Post not found'];
            return response($response, $response['code']);
        }
        return response($post->getArray());
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        $r = [];
        Post::chunk(1024, function($posts) use (&$r) {
            foreach ($posts as $post) {
                $r[] = $post->getArray();
            }
        });
        return response($r);
    }
}
