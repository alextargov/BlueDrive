<?php
namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class File extends Model{
/*
|--------------------------------------------------------------------------
| Model dealing with the files table
|--------------------------------------------------------------------------
| functions: getUploadFileName, getUserId, getUserFileNamesFromDb,
|            getFileNameFromDb, getFileId, checkIfUserHasFiles,
|            incrementFileId, getSize;
*/  
    
    
    protected $table = 'files';
    protected $primaryKey = 'fileid';
    
    public function getUploadFileName(Request $request) {
       $fileName = $request->file('file')->getClientOriginalName();
       return $fileName;
    }
    public function getUserId(){
        $userid = Auth::id();
        return $userid;
    }
    public function getUserFileNamesFromDb(Request $request) {
        $fileName = $request->file('file')->getClientOriginalName();
        $userid = Auth::id();
        $getFileName = File::where('filename', $fileName)->where('userid', $userid)->get();
        foreach ($getFileName as $name) {
            return $fileNames = $name->filename;
        }
    }
    public function getFileNamesFromDb(Request $request){
        $fileName = $request->file('file')->getClientOriginalName();
        $query = File::where('filename', $fileName)->get();
        foreach($query as $name){
            return $fileName = $name->filename;         
        }
    }
    public function getFileId(Request $request){
        $userid = Auth::id();
        $fileName = $request->file('file')->getClientOriginalName();
        $files = File::where('filename', $fileName)->where('userid', $userid)->get(); //$fileName
        foreach ($files as $file) {
            $fileid = $file->fileid;
            $request->file('file')->fileid = $file->fileid; // слага fileid като атрибут на обекта file       
            return $fileid;                   
        }       
    }   
    public function checkIfUserHasFiles(){
        $userid = Auth::id();
        $files = File::where('userid', $userid)->count();
        return $files;
    }  
    public function incrementFileId(){
        $userid = Auth::id();
        $files = File::where('userid', $userid)->orderBy('fileid', 'desc')->take(1)->get();
        foreach($files as $file){
            $incremented = $file->fileid + 1;
            return $incremented;
        }
    }
    public function getSize(){
        $userid = Auth::id();
        $sizes = File::where('userid', $userid)->take(1)->get();
        foreach ($sizes as $size){
            $result = $size->filesize;
            return $result;
        }
    }
    public function moveToFolder($filename, $folder){
        $userid = Auth::id();
        return File::where('userid', $userid)->where('filename', $filename)->update(['folder' => $folder]);
    }
}