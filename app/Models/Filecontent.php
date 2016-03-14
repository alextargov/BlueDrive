<?php
namespace App\Models;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Auth;
class Filecontent extends Model {
    
    protected $table = 'filecontents';
      
    public function getFileVersion(Request $request){
        $userid = Auth::id();
        $model = new File();
        $getFileId = $model->getFileId($request);
        
        $files = Filecontent::where('fileid', $getFileId)->where('userid', $userid)->orderBy('version', 'desc')->take(1)->get();
        foreach($files as $file){
            $versions = $file->version;
            return $versions; // or ECHO!!!
        }
        
    }
    public function incrementFileVersion(Request $request){
        $model = new Filecontent();
        $getFileVersion = $model->getFileVersion($request);
        return $getFileVersion + 1;
    }
    public function getUserFiles(){
        $userid = Auth::id();
        $getFiles = Filecontent::where('userid', $userid)->get();
        foreach($getFiles as $getFile) {
            echo '<li>'. $getFile.'</li>';
        }
    }
    
}
//$getVersionsQueryTemplate = mysqli_query($connection, "SELECT version FROM filescontent WHERE fileID=(SELECT fileID FROM files WHERE fileName= '$name') ORDER BY version DESC LIMIT 1");