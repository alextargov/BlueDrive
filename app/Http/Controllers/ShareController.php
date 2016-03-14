<?php

namespace App\Http\Controllers;

use View;
use Auth;
use Storage;
use App\Models\File;
//use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpWord\Settings;


class ShareController extends Controller {
    public function share($hashed_name){

        $ds = DIRECTORY_SEPARATOR;
        $userid = Auth::id();
        
        $filecontentModel = \App\Models\Filecontent::where('hashed_name', $hashed_name)->where('userid', $userid)->first();
        $fileModel = File::where('fileid', $filecontentModel->fileid)->firstOrFail();
        $filename = $fileModel->filename;
        
        $onlyFilename = substr($filename, 0, strrpos($filename, "."));
        $type = \Illuminate\Support\Facades\File::extension(storage_path().'uploads'.$ds.$userid.$ds.$filename);
        $filenameVersion = $onlyFilename.'_0'.'.'.$type;
            
        $file = Storage::disk()->get('uploads'.$ds.$userid.$ds.$filenameVersion);
        $header = array("Content-Disposition" => " attachement; filename=\"" . basename($filename) . "\"",
            'Content-Type'=>$fileModel->mimetype);//attachement
        $source = storage_path('uploads\\'.$userid.'\\'.$onlyFilename.'_0.sql');
       return (new \Illuminate\Http\Response($file, 200))->withHeaders($header);
       /*
        echo view('share')->with('name', 'Victoria');
       echo '<iframe src="http://docs.google.com/gview?url=dox.abv.bg/files/fdw?eid=95751081&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
';      
        //$phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        $domPdfPath = base_path().$ds.'vendor'.$ds.'phpoffice'.$ds.'phpword'.$ds.'src'.$ds.'PhpWord'.$ds.'Writer'.$ds.'PDF'.$ds.'DomPDF.php';

        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('DomPDF');

        //$load = IOFactory::load($source, 'Word2007');
        //$objWriter = IOFactory::createWriter($load, 'HTML');
        //$objWriter->save('file.html', $format = 'Word2007', true);
        
$phpWord = \PhpOffice\PhpWord\IOFactory::load($source, 'Word2007');
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
$objWriter->getContent();
*/
       
    }
    public function shareit($hashed_name){
        mb_internal_encoding('UTF-8');
        $ds = DIRECTORY_SEPARATOR;
        $userid = Auth::id();
        $filecontentModel = Filecontent::where('hashed_name', $hashed_name)->where('userid', $userid)->firstOrFail();
        return View::make('share');
        
    }
    
    
}
