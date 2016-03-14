<?php

namespace App\Http\Controllers;
use App\Models\Filecontent;
use Illuminate\Http\Request;
use View;
use Auth;
use Storage;
use App\Models\FilePermission;
use App\Models\Folder;
use App\Models\File;
use App\Models\UserFiles;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    
    public function __construct()
{
    $this->middleware('auth');
}
    /**
     * Update the user's profile.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updateProfile(Request $request)
    {
        if ($request->user()) {
            // $request->user() returns an instance of the authenticated user...
        }
    }
    protected $table = 'files';
    public function show (){
        $userid = Auth::id();
        $query = File::orderBy('created_at', 'desc')->where('userid', $userid)->get();
        $getPermission = FilePermission::where('userid', $userid)->orderBy('fileid', 'desc')->lists('filePermission', 'fileid');
        return View::make('index')
            ->with('images', File::all())
            ->with('files', $query)
            ->with('filecontent', Filecontent::all())
            ->with('folders', Folder::where('userid', $userid)->get())
            ->with('permissions', $getPermission);
    }
    public function getFiles() {
    $folder = $_POST['folder'];
    $userid = Auth::id();
    $query = File::orderBy('created_at', 'desc')->where('userid', $userid)->where('folder', $folder)->get();
    // foreach for many result returned by $query
    $json = array();
    foreach($query as $result){
        $arr = array();
        $arr['filename'] = $result->filename;
        $arr['id'] = $result->fileid;
        $arr['size'] = $result->conv_filesize;
        $json[] = $arr;
    }    
    echo json_encode($json);
}
    
    public function preview (){   
        return View::make('preview');           
    }
    
    public function deleteUserFiles() {
        $getModel = new UserFiles();
        $getModel->deleteUserFile();
    }
    public function downloadUserFiles() {
        $getModel = new UserFiles();
        $getMethod = $getModel->downloadUserFile();
        return $getMethod;
    }
    public function downloadVersions() {
        $getModel = new UserFiles();
        $getMethod = $getModel->downloadVersion();
        return $getMethod;
    }  
    public function routingFiles(){
        $getModel = new UserFiles();
        $getMethod = $getModel->routingFile();
        return $getMethod;
    }
    public function createFolder() {
        $getModel = new UserFiles();
        $getMethod = $getModel->createFolders();
        return $getMethod;
    }
    public function deleteFolder() {
        $getModel = new UserFiles();
        $getMethod = $getModel->deleteFolders();
        return $getMethod;
    }
    public function fileInfo(){
        $getModel = new UserFiles();
        $getMethod = $getModel->fileInfo();
        return $getMethod;
    }
    
    /**
     * Returns selected by the user file's name and version
     *
     * @var json array
     */
    public function selectedFiles(){
        $userid = Auth::id();
        $ds = DIRECTORY_SEPARATOR;
        $filename = $_POST['filename'];
        $getFile = File::where('userid', $userid)->where('filename', $filename)->FirstOrFail();
        $getFilecontent = Filecontent::where('userid', $userid)->where('fileid', $getFile->fileid)->orderBy('version', 'desc')->lists('version');
        foreach ($getFilecontent as $version) {
            $onlyFilename = mb_substr($filename, 0, mb_strrpos($filename, "."));
            $type = \Illuminate\Support\Facades\File::extension(storage_path().'uploads'.$ds.$userid.$ds.$filename);
            $arr = array(
                'filename' => $filename,
                'version' => $version,
                'extension' => $type,
                'userid' => $userid,
                'onlyName' => $onlyFilename);
            return json_encode($arr);    
        }       
    }
    
    /**
     * Returns a file image
     *
     * @var 
     */
    public function getImage($filename){
	$ds = DIRECTORY_SEPARATOR;
        $userid = Auth::id();
        $entry = File::where('filename', $filename)->where('userid', $userid)->firstOrFail();
        $getPermission = FilePermission::where('userid', $userid)->where('fileid', $entry->fileid)->firstOrFail();
                
        $onlyFilename = substr($filename, 0, strrpos($filename, "."));
        $type = \Illuminate\Support\Facades\File::extension(storage_path().'uploads'.$ds.$userid.$ds.$filename);
        $filenameVersion = $onlyFilename.'_0'.'.'.$type;
                
	$file = Storage::disk()->get('uploads'.$ds.$userid.$ds.$filenameVersion);
        if(substr($entry->mimetype, 0, 5) === 'image') {
          if($getPermission->filePermission === 0) {
            return (new \Illuminate\Http\Response($file, 200))->header('Content-Type', $entry->mimetype);
            }
            else {
                return abort(404, 'Unauthorized action.');
            }  
        }       
    }
    
     /**
     * Returns a valid share URL
     *
     * @var string
     */
    public function getShareUrl (){
        $getModel = new UserFiles();
        $getMethod = $getModel->getShareUrls();
        return $getMethod;
    }
    
     /**
     * Returns a valid download URL
     *
     * @var string
     */
    
    public function ShareDownload($hashed_name){
        
    }
    public function previewFile($filename) {
       $ds = DIRECTORY_SEPARATOR;
       $userid = Auth::id(); 
    }
    public function getFolder(){
        $folder = $_POST['folder'];
        $filename = $_POST['filename'];
        $getModel = new File();
        $getMethod = $getModel->moveToFolder($filename, $folder);
        return $getMethod;
    }
    
     /**
     * Converts bytes 
     *
     * @var string
     */
    function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        $bytes /= (1 << (10 * $pow)); 
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }
    /*
    public function selectedUserFiles($userid, $fileid){
        $getModel = new UserFiles();
        $getMethod = $getModel->selectedUserFile($userid, $fileid);
        return $getMethod;
    }*/
}