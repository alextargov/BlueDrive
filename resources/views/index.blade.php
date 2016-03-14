<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
        <title>BlueDrive</title>
        <script src=" {{ URL::asset('assets/js/jquery.js') }}" charset="UTF-8"></script>
	<script src="{{ URL::asset('assets/js/jquery-ui.js') }}" charset="UTF-8"></script>
        <script src="{{ URL::asset('assets/js/app.js') }}"  charset="UTF-8"></script>
        <script src="{{ URL::asset('assets/js/jquery.nu-context-menu.js') }}"  charset="UTF-8"></script>
        <script src="{{ asset('assets/js/dropzone.js') }}" charset="UTF-8"></script>
        
        <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/basic.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/nu-context-menu.css') }}">
        
    </head>
    <body>  
        <div class="container">
            <div class="header">
                <div class="title">Добре дошъл в BlueDrive, {{ Auth::user()->username }}</div>
                <div class="ui-button ui-widget ui-state-default ui-corner-all logout ui-button-text-only">
                    <a href="{{route('getLogout')}}">
                        <span>Излез</span>
                    </a>
                </div>
                <button id="create-upload">
                    <span>
                        <i class="fa fa-cloud-upload"></i>
                        Качи
                    </span>
                </button>
            </div>
            
            <div class="clr"></div>
                
            <div class="folders block">
                <div class="ui-widget-content">
                        <table class="folders-list">
                            <tbody>
                               <tr><td style="text-align: center">Папки: </td></tr> 
                            </tbody>
                            <tbody class="folder">  
                                <tr class="folder-name ui-selected">
                                    <td>
                                        <i class="fa fa-folder-open"></i> 
                                        <span>Всички</span>  
                                    </td>
                                </tr>
                                @foreach($folders as $folder)
                                    <tr class="folder-name">
                                        <td>
                                            <i class="fa fa-folder-open"></i> 
                                            <span>{{ $folder->folder }}</span>  
                                        </td>
                                    </tr>          
                                @endforeach
                            </tbody>
                        </table>
                    
                </div>
            </div>
            <div class="content block">
                <div id="results">
                    <table>
                        <tbody>
                            <tr>
                                <th>Номер:</th>
                                <th>Име:</th>
                                <th>Размер:</th>                             
                            </tr>
                        </tbody>
                        <tbody class="toBeSelected">
                            @foreach($files as $file)
                            <tr class="tabledata">
                                <td>{{ $file->fileid }}</td>
                                <td class="filename">{{ $file->filename }}</td>
                                <td>{{ $file->conv_filesize }}</td>
                                
                            </tr>  
                            @endforeach
                        </tbody>
                    </table>      
                </div>     
            </div>
            <div class="preview block">
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
                <form  action="{{ URL::to('upload') }}" class="dropzone" id="dropzoneFileUpload">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                </form>
            </div>
        </div>
        <div id="dialog-share">
            <div class="share">  
                <input class="shared-url" onclick="this.select()" style="width:100%" autofocus type="text" readonly name="share-url" />
            </div>
        </div>
        <div id="dialog-version">
            <div class="version">
                <table><tr class=""><td>Версия</td><td>Име</td><tbody class="version-row"></tbody></table>
                
            </div>
        </div>
        <div id="dialog-moveto">
            <div class="moveto">
                <select name="folders" id="folders">
                    @foreach($folders as $folder)
                        <i class="fa fa-folder-open"></i> 
                            <option>{{ $folder->folder }}</option>  
                   @endforeach 
                </select>
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
        <div class="spinner">
            <span>Зареждане...</span>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div> 
        </div>

    </body>
</html>