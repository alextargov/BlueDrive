<?php
namespace App\Http\Controllers;

use App\Models\UserFiles;
use App\Models\File;
use View;
use Input;
use Illuminate\Http\Request;
use Crypt;
use Auth;
use App\Models\FilePermission;

class FirstController extends Controller{
    public function index() {
        echo 'hello from index function in FirstController';
    }
    public function profile($id=null){
        echo View::make('profile');
    }
    public function fileName(Request $request){
        echo '<form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="" >
                    <label>Upload</label>
                    <input type="file" name="file[]" multiple="" /><br/>
                    <input class="upload_button" name="submit" type="submit" value="submit" />
                    <input type="hidden" value="'. csrf_token() .'" name="_token" />
                </form>';
        echo '<pre>'.print_r($request->user(), true).'</pre>';
        $userid = Auth::id();
        
        $file = $request->file('file');
        $filename = $file[0]->getClientOriginalName();
        
        $old = $filename;
        $new = substr($old, 0, strrpos($old, "."));
        
        $encrypted = Crypt::encrypt($new);
        $decrypted = Crypt::decrypt($encrypted);
        
        echo '<pre>'.print_r($encrypted, true).'</pre>';
        echo '<pre>'.print_r($decrypted, true).'</pre>';

        function formatBytes($bytes, $precision = 2) { 
            $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
            $bytes = max($bytes, 0); 
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
            $pow = min($pow, count($units) - 1); 
            // Uncomment one of the following alternatives
            // $bytes /= pow(1024, $pow);
            $bytes /= (1 << (10 * $pow)); 
            return round($bytes, $precision) . ' ' . $units[$pow]; 
        }
        
        

        function convertToBytes($from){
    $number=substr($from,0,-2);
    switch(strtoupper(substr($from,-2))){
        case "KB":
            return $number*1024;
        case "MB":
            return $number*pow(1024,2);
        case "GB":
            return $number*pow(1024,3);
        case "TB":
            return $number*pow(1024,4);
        case "PB":
            return $number*pow(1024,5);
        default:
            return $from;
    }
}


        $size = new File();
        $sizes = $size->getSize();
        echo $sizes.'b <br>';
        $bytes = $sizes;
        echo formatBytes($bytes).'<br>';
        $from = $sizes;
        echo convertToBytes($from).'<br>';
        $bytes = convertToBytes($from);
        echo formatBytes($bytes).'<br>';
        //echo '<pre>'.\print_r($model_data, true).'</pre>';
        
        
        
    }
}
