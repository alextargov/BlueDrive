<?php
namespace App\Http\Controllers;

use App\Models\Filecontent;
use App\Models\UploadHandler;
use Auth;
use Illuminate\Http\Request;
use App\Models\UserFiles;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller {
    public function upload(Request $request) {
        date_default_timezone_set('Europe/Sofia');
        if ($request->hasFile('file')) {
            $ds = DIRECTORY_SEPARATOR;
            $file = $request->file('file');
            $fileGetContents = file_get_contents($file->getRealPath());
            $user = Auth::id();
            $filename = $file->getClientOriginalName();
            //mb_convert_encoding($onlyFilename, "UTF-8");
            $extension = $file->getClientOriginalExtension();
            $onlyFilename = mb_substr($filename, 0, mb_strrpos($filename, "."));
            
            if (file_exists(storage_path('uploads'.$ds.$user.$ds.$onlyFilename.'_0.'.$extension))) {
                $counter = 1;
                while (file_exists(storage_path().'uploads'.$ds.$user.$ds.$onlyFilename.'_'.$counter.'.'.$extension)){               
                    $counter ++;                        
                }       
                Storage::disk('s3')->put('uploads'.$ds.$user.$ds.$onlyFilename.'_'.$counter.'.'.$extension, $fileGetContents);
                $uploadHandler = new UploadHandler();       
                $uploadAttributes = $uploadHandler->uploadAttributes($request);               
            }  
            else {
                Storage::disk('s3')->put('uploads'.$ds.$user.$ds.$onlyFilename.'_0.'.$extension, $fileGetContents);
                $uploadHandler = new UploadHandler();       
                $uploadAttributes = $uploadHandler->uploadAttributes($request);                
            }                     
        } 
    
    }

    
    public function currentfile() {
        $a = new UserFiles();
        $b = $a->getCurrentFile();
        echo $b;
    }
}