<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Laravel</title>
        <script src=" {{ URL::asset('assets/js/jquery.js') }}"></script>
	<script src="{{ URL::asset('assets/js/jquery-ui.js') }}"></script>
        <script src="{{ URL::asset('assets/js/app.js') }}"  charset="UTF-8"></script>
        <script src="{{ URL::asset('assets/js/jquery.nu-context-menu.js') }}"  charset="UTF-8"></script>
        <script src="{{ asset('assets/js/dropzone.js') }}"></script>
        
        <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/basic.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/nu-context-menu.css') }}">
        
    </head>
    <body>  
        <div class="container">
            <div class="content">
                <iframe src="http://localhost/bluedrive/drive/public/getimage/Mobile%20Roadie.pptx">
                <canvas id="page2" width="382" height="287" style="width: 382px; height: 287px;"></canvas>
                </iframe>
                <div id="results">
                    
                </div>     
            </div>
            <div class="preview">
                <img class="getImage">
            </div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            
        </div>
        
        <div id="dialog-folder">
            <div class="create-folder">
                <div class="input-field">
                    <input type="text" name="folderName" placeholder="Нова папка" id="folder-name" class="text ui-widget-content ui-corner-all">
                </div>
            </div>
        </div>
        <div id="dialog-upload">
            <div class="upload-file">  
                <form  enctype="multipart/form-data" action="{{ URL::to('upload') }}" class="dropzone" id="dropzoneFileUpload">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                </form>
            </div>
        </div>
        <div id="dialog-info">
            <div class="file-info">
                
                <div class="left">
                    <span>Име:</span>
                </div>
                <div class="dialog-filename right">
                    <span></span>
                </div>
                <div class="clr"></div>
                <hr>
                <div style='clear: both;' class="left">
                    <span>Размер:</span>
                </div>
                <div class="dialog-size right">
                    <span></span>
                </div>
                <div class="clr"></div>
                <hr>
                <div class="left">
                    <span>Тип:</span>
                </div>
                <div class="dialog-type right">
                    <span></span>
                </div>
                <div class="clr"></div>
                <hr>
                <div class="left">
                    <span>Създаден на:</span>
                </div>
                <div class="dialog-created right">
                    <span></span>
                </div>
            </div>
        </div>
    </body>
</html>