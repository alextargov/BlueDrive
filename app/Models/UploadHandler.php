<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use App\Models\File;
use App\Models\Filecontent;
use Auth;

class UploadHandler extends Model{
    function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        $bytes /= (1 << (10 * $pow)); 
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }
    
    public function uploadAttributes(Request $request) {
        mb_internal_encoding('UTF-8');
        $fileModel = new File();
        $incrementFileId = $fileModel->incrementFileId($request);
        $getUploadFileName = $fileModel->getUploadFileName($request);
        $hash_getUploadFileName = hash('md5', $getUploadFileName).'!';
        $getUserId = $fileModel->getUserId();
        $getFileId = $fileModel->getFileId($request);
        $getFileNamesFromDb = $fileModel->getFileNamesFromDb($request);
        $checkIfUserHasFiles = $fileModel->checkIfUserHasFiles();
        $getUserFileNamesFromDb = $fileModel->getUserFileNamesFromDb($request);
        
        $filecontentModel = new Filecontent();
        $incrementFileVersion = $filecontentModel->incrementFileVersion($request);
        $user = Auth::id();
        $filesize= $request->file('file')->getClientSize();
        $fileMimeType = $request->file('file')->getClientMimeType();
        $filePath = 'uploads/'.$user.'/'.$request->file('file')->getClientOriginalName();
        $convFilesize = $this->formatBytes($filesize);  
        
        //
        if(!strcmp($getUploadFileName, $getUserFileNamesFromDb)){
            // Does the User have any files? 
            if($checkIfUserHasFiles>0){
                // The User has some files
                $filecontentsData = [
                'fileid' => $getFileId,
                'userid' => $user,
                'filesize' => $filesize,
                'conv_filesize' => $convFilesize,    
                'version' => $incrementFileVersion,
                'hashed_name' => $getUserId.$hash_getUploadFileName.$incrementFileVersion,
                'filePath' => $filePath,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
                ];
            }
            else {
                // The User doesn't have any files, so the starting id is 1
                $filecontentsData = [
                'fileid' => 1,
                'userid' => $user,
                'filesize' => $filesize,
                'conv_filesize' => $convFilesize,
                'version' => $incrementFileVersion,
                'hashed_name' => $getUserId.$hash_getUploadFileName.$incrementFileVersion,
                'filePath' => $filePath,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
                ];
            } 
            DB::table('filecontents')->insert($filecontentsData);
        }
        else{
            //
            if($checkIfUserHasFiles>0){
                $fileData = [
                    'filename' => $getUploadFileName,
                    'filesize' => $filesize,   
                    'conv_filesize' => $convFilesize,
                    'mimetype' => $fileMimeType,
                    'userid' => $user,
                    'fileid' => $incrementFileId,
                    'folder' => 'Всички',
                    'filePath' => $filePath,   
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];
                $filecontentsData = [
                    'fileid' => $incrementFileId,
                    'userid' => $user,
                    'filesize' => $filesize,
                    'conv_filesize' => $convFilesize,
                    'version' => $incrementFileVersion,
                    'hashed_name' => $getUserId.$hash_getUploadFileName.$incrementFileVersion,
                    'filePath' => $filePath,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];
                $filePermission = [
                    'fileid' => $incrementFileId,
                    'userid' => $getUserId,
                    'filePermission' => 0
                ];
            }
            else {
                $fileData = [
                    'filename' => $getUploadFileName,
                    'filesize' => $filesize,
                    'conv_filesize' => $convFilesize,
                    'mimetype' => $fileMimeType,
                    'filePath' => $filePath,
                    'userid' => $user,
                    'folder' => 'Всички',
                    'fileid' => 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];
                $filecontentsData = [
                    'fileid' => 1,
                    'userid' => $user,
                    'filesize' => $filesize,
                    'conv_filesize' => $convFilesize,
                    'version' => $incrementFileVersion,
                    'hashed_name' => $getUserId.$hash_getUploadFileName.$incrementFileVersion,
                    'filePath' => $filePath,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];
                $filePermission = [
                    'fileid' => 1,
                    'userid' => $getUserId,
                    'filePermission' => 0
                ];
            }
            
            DB::table('files')->insert($fileData);
            DB::table('filecontents')->insert($filecontentsData);
            DB::table('file_permission')->insert($filePermission);   
            
        }
    }
}