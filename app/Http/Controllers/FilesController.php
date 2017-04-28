<?php

namespace App\Http\Controllers;
use Input;
use Image;
use Uuid;
use Illuminate\Auth\Access\Response;
use App\Models\File;
use Illuminate\Http\Request;

class FilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function uploadfile()
    {
        $files = Input::file('files');
        $video_extension = ['mp3','mp4'];
        $thumbail_extension = ['png','gif','jpe','jpg'];
        if ($files) {
            $fileObject = new File();
            $upload_folder = '/uploads/';
            $destinationPath = public_path() . $upload_folder;
            $results = [];
            foreach($files as $file){
                $extenstion = substr($file->getClientOriginalName(),-4);
                $filename = (Uuid::generate(1) . $extenstion);
                $fileObject->name_url = $filename;
                // copy to server
                $upload_success = $file->move($destinationPath, $filename);
                $result = new \stdClass();
                $result->url= $filename;
                array_push($results,$result);
                if ($upload_success) {
                    if (in_array($extenstion, $thumbail_extension)) {
                        Image::make($destinationPath . $filename)->resize(100, 100)->save($destinationPath . "100x100_" . $filename);
                    }
                    $fileObject->public = 1;
                    $fileObject->type = 1;
                    $fileObject->save();
                } else {
                    return Response::json('error', 400);
                }
            }
            return response()->json([
                'files' => $results
            ]);

        }else{
            return response()->json([
                'status' => 'fails',
                'value' => '400'
            ]);
        }
    }
}
