<?php
namespace App\Models;
use \Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\File;
use App\Models\Filecontent;
use App\Models\FilePermission;
use App\Models\Folder;
use Storage;
use DB;

class UserFiles extends Model{
    protected $table = 'files';
    
    function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        $bytes /= (1 << (10 * $pow)); 
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }
     
    public function getCurrentFile(){
        $userid = Auth::id();
        $getFiles = File::where('userid', $userid)->orderBy('created_at', 'desc')->firstOrFail();
        return $getFiles;
    }
    public function getUserFiles(){
        $userid = Auth::id();
        $getFiles = File::where('userid', $userid)->get();
        foreach($getFiles as $getFile) {
            $result = $getFile;        
            $encode = json_encode($result); //make it as json
            echo $encode;
            //return View::make('index')->with('result', $result);
        }
    }
    public function deleteUserFile(){ 
        $userid = Auth::id();
        $result = htmlspecialchars_decode($_POST['filename']);
        $ds = DIRECTORY_SEPARATOR;
        $getrow = Filecontent::where('filePath', 'uploads/'.$userid.'/'.$result )->where('userid', $userid)->firstOrFail();
        $count = Filecontent::where('filePath', 'uploads/'.$userid.'/'.$result )->where('userid', $userid)->select('version')->count();
        for ($i = 0; $i < $count; $i++) {
            $onlyFilename = substr($result, 0, strrpos($result, "."));
            $type = \Illuminate\Support\Facades\File::extension(storage_path().'uploads\\'.$userid.'\\'.$result);
            $filenameVersion = $onlyFilename.'_'.$i.'.'.$type;
            Storage::disk('local')->delete(['uploads'.$ds.$userid.$ds.$filenameVersion]);
        }
       
        File::where('filePath', 'uploads/'.$userid.'/'.$result )->where('userid', $userid)->delete();
        Filecontent::where('filePath', 'uploads/'.$userid.'/'.$result )->where('userid', $userid)->delete();
        FilePermission::where('fileid', $getrow->fileid)->where('userid', $userid)->delete();

    }
    
    public function downloadUserFile(){
        mb_internal_encoding('UTF-8');
        $ds = DIRECTORY_SEPARATOR;
        $userid = Auth::id();
        $filename = htmlspecialchars_decode($_POST['filename']);
        $entry = File::where('filename', $filename)->where('userid', $userid)->firstOrFail();
        
        $onlyFilename = substr($filename, 0, strrpos($filename, "."));
        $type = \Illuminate\Support\Facades\File::extension(storage_path().'uploads'.$ds.$userid.$ds.$filename);
        $filenameVersion = $onlyFilename.'_0'.'.'.$type;
            
        $file = Storage::disk()->get('uploads'.$ds.$userid.$ds.$filenameVersion);
        $path = $userid.'/'.$filenameVersion;
        $arr = array('path' => $path, 'filename' => $filename);
        return json_encode($arr);
    }
    
    public function downloadVersion(){
        mb_internal_encoding('UTF-8');
        $ds = DIRECTORY_SEPARATOR;
        $userid = Auth::id();
        $filename = htmlspecialchars_decode($_POST['filename']);
        $getFile = File::where('userid', $userid)->where('filename', $filename)->FirstOrFail();
        $getFileVersion = Filecontent::where('userid', $userid)->where('fileid', $getFile->fileid)->orderBy('version', 'asc')->lists('version');
/*
        for ($x = 0; $x <= $getFileVersion->last() - 1; $x++) {
            //$trueVersion = $getFileVersion->last() - 1;
            $onlyFilename = mb_substr($filename, 0, mb_strrpos($filename, "."));
            $type = \Illuminate\Support\Facades\File::extension(storage_path().'uploads'.$ds.$userid.$ds.$filename);
            $filenameVersion = $onlyFilename.'_'. $x . '.'.$type;
            $path = $userid.'/'.$filenameVersion;
            $arr = array(
                'path' => $path,
                'filename'  => $filename,
                'versions'  => $getFileVersion->last(),
                'extension' => $type,
                'onlyName'  => $onlyFilename,
                'userid'    => $userid);
            return json_encode($arr);
        }
 * 
 */
    }
    
    public function routingFile(){
        $filename = $_POST['filename'];
        $query = File::where('filename', $filename)->where('userid', Auth::id())->firstOrFail();
        $mime = $query->mimetype;
        $arr = array('route' => route('getImage', $query->filename), 'mime' => $mime );
        return json_encode($arr);        
    }
    
    public function getShareUrls(){
        $filename = $_POST['filename'];
        $fileModel = File::where('filename', $filename)->where('userid', Auth::id())->firstOrFail();
        $filecontentModel = Filecontent::where('fileid', $fileModel->fileid)->firstOrFail();
        $hashed_name = $filecontentModel->hashed_name;
        $mime = $fileModel->mimetype;
        $bytes = $fileModel->filesize;
        $filesize = $this->formatBytes($bytes);
        $arr = array('route' => route('share', $hashed_name),
            'mime' => $mime,
            'size' => $filesize,
            'filename' => $filename);
        return json_encode($arr);  
    }
       
    public function fileInfo(){
        $filename = $_POST['filename'];
        $query = File::where('filename', $filename)->where('userid', Auth::id())->firstOrFail();
        $mime = $query->mimetype;
        $created = $query->created_at;
        $bytes = $query->filesize;
        $filesize = $this->formatBytes($bytes);
        $arr = array(
            'filename' => $filename,
            'mime' => $mime,
            'created' => $created,
            'size' => $filesize
        );
        return json_encode($arr);    
    }
    
    public function createFolders(){
        $folder = $_POST['foldername'];
        $userid = Auth::id();
        $data = [
            'userid' => $userid,
            'folder' => $folder
        ];
        DB::table('folders')->insert($data);
        return json_encode($folder);
    }
    public function deleteFolders(){ 
        $userid = Auth::id();
        $result = $_POST['foldername'];
        Folder::where('userid', $userid)->where('folder', $result)->delete();
        return $result;
    }
}
