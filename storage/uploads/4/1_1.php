<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
        <script src=" {{ URL::asset('assets/js/jquery.js') }}"></script>
	<script src="{{ URL::asset('assets/js/jquery-ui.js') }}"></script>
        
        <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/basic.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
        
        <link rel="stylesheet" href="{{ asset('assets/css/nu-context-menu.css') }}">
        
        <script type="text/javascript">
            $(function() {
                               
                $("table tbody.toBeSelected").selectable({
                    stop: function() {
                        $(".ui-selected", this).each(function() {
                        var index = $("table tbody").index(this);
                        });
                    }
                });
                
            }); 
            function getImage(){
                var classElements = document.querySelectorAll("tr.ui-selected td.filename");
                var csrf = $('input[name=_token]').val();

                for(var x = 0;x < classElements.length;x++){
                    var result;
                    result = classElements[x].innerHTML;
                    $.ajax({
                        async: true,                      
                        method: 'POST',
                        dataType: 'json',
                        url: '../public/selecteduserfiles',
                        data: {filename: result, "_token": csrf},
                        success: function(response) {
                           console.log(response);
                           
                           $("img.getImage").attr('src', response);
                           
                        }
                    });
                };
                
                var classElements = document.querySelectorAll("tr.ui-selected td.filename");
                var csrf = $('input[name=_token]').val();
                for(var x = 0;x < classElements.length;x++){
                    var result;
                    result = classElements[x].innerHTML;
                    // on click make ajax request and then pass the response to the
                    $.ajax({
                        async: true,  
                        method: 'POST',
                        dataType: 'json',
                        url: '../public/downloadfile',
                        data: { filename: result, "_token": csrf },
                        complete: function(data) {
                           console.log(data);                          
                           $("li.download a").attr('href', 'http://localhost/bluedrive/drive/storage/uploads/'+data);
                           $("li.download a").attr('download', data.responseJSON.filename);
                        }
                        
                    });
                };
                
                
            }
        </script>
        <script src="{{ asset('assets/js/jquery.nu-context-menu.js') }}"></script>
        <script>
            
            $(function() {
               
      var context = $('table tbody.toBeSelected')
        .nuContextMenu({

          hideAfterClick: true,
          
          items: 'tr.tabledata',

          callback: function(key, element) {
            if(key === "delete"){
                var classElements = document.querySelectorAll("tr.ui-selected td.filename");
                var csrf = $('input[name=_token]').val();

                for(var x = 0;x < classElements.length;x++){
                    var result;
                    result = classElements[x].innerHTML;
                    $.ajax({
                        async: true,                      
                        method: 'POST',
                        url: '../public/deletefile',
                        data: { filename: result, "_token": csrf  },
                        success: function(response) {
                           $( "tr.ui-selected" ).remove(response);
                           
                        }
                    });
                };
            }
            if(key === "download") {
                
            };
            
          },

          menu: [

            {
              name: 'download',
              title: 'Download',
              icon: 'archive',
              class: 'download'
            },
			
			{
              name: 'void'
            },
            
            {
              name: 'delete',
              title: 'Delete',
              icon: 'trash',
              class: 'delete'
            }
          ]

        });
    });

        </script>
        
        
        <script src="{{ asset('assets/js/dropzone.js') }}"></script>
        <style>
            
        </style>
    </head>
    <body>       
        <div class="container">
            <div class="content" oncontextmenu="return false;">
                <div class="title">Welcome to BlueDrive, {{ Auth::user()->username }}</div>
                <h3><a href="auth/logout">Logout</a></h3>
                <form  enctype="multipart/form-data" action="{{ URL::to('upload') }}" class="dropzone" id="dropzoneFileUpload">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                </form>
                <a id="download" download="{}" ></a>
                
                
                <div id="results">
                    <table>
                        <tbody>
                            <tr>
                                <th>File ID:</th>
                                <th>File name:</th>
                                <th>File size:</th>
                                <th>Created at:</th>                           
                            </tr>
                        </tbody>
                        
                        <tbody class="toBeSelected">
                            @foreach($files as $file)
                            <tr class="tabledata" onclick="getImage()"> 
                                <td>{{ $file->fileid }}</td>
                                <td class="filename">{{ $file->filename }}</td>
                                <td>{{ $file->filesize }}</td>
                                <td>{{ $file->created_at }}</td>
                            </tr>  
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>     
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
            
           
            @section('sidebar')
                <div class="file_additional">       
                    
                        <!--<li><img src="{{URL::lh_storage()}}" class="getImage"></li>-->
                        <li><img class="getImage"></li>
                    
                </div>
            @show
        </div>
        
    </body>
</html>