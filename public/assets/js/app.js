$(document).ready(function() {
    displayFiles();
    $("table tbody.toBeSelected").selectable({
   // $("table").on("selectable", "tbody.toBeSelected", function() {    
        stop: function() {
            $(".ui-selected", this)
                .each(function() {
                    var index = $("table tbody").index(this);   
                });    
            getImage();  
            //selectedFile();
        }
    });
    $('.container').click(function(){
        var toBeSelected = $( "table tbody.toBeSelected" ).selectable( "instance" );     
        // clear the selected list
        toBeSelected.selectees = [];
        // remove the selected class
        toBeSelected.element.find('.ui-selected').removeClass('ui-selected');
    });
    
    $("table tbody.folder").selectable({
        stop: function() {
            $(".ui-selected", this)
                .each(function() {
                    var index = $("table tbody").index(this);   
                });
            displayFiles();    
        }
    });   
    $( "#folders" ).selectmenu({
        width: 150
    });
    
    
    /*  All Dialogs   */
     
    var dialogFolder;
    dialogFolder = $( "#dialog-folder" ).dialog({
        autoOpen: false,
        resizable: false,
        draggable: false,
        height: 130,
        width: 300,
        modal: true,
        title: 'Създай нова папка',
        buttons: {
            "Създай": function() {
                var csrf = $('input[name=_token]').val();
                $.ajax({
                    async: true,                      
                    method: 'POST',
                    url: '../public/createfolder',
                    dataType: 'json',
                    data: {foldername: $('#folder-name').val(), "_token": csrf},
                    complete: function(response) {
                        console.log(response);
                        $("tbody.folder").prepend("<tr class='folder-name ui-selectee'><td class='ui-selectee'><i class='fa fa-folder-open'></i>" + response.responseJSON + "</td></tr>");
                    
                        //$('.folder').append(data.responseJSON.filename);
                    }
                });
                
                dialogFolder.dialog( "close" );
                $('#folder-name').val('');
                $('#create-upload').removeClass('ui-state-focus');
            },
            "Затвори": function() {
                dialogFolder.dialog( "close" );
                $('#folder-name').val('');
                $('#create-upload').removeClass('ui-state-focus');
            }
        },
        close: function() {
            dialogFolder.dialog( "close" );
            $('#folder-name').val('');
            $('#create-upload').removeClass('ui-state-focus');
        }
    });
       
    var dialogUpload;
    dialogUpload = $( "#dialog-upload" ).dialog({
        autoOpen: false,
        resizable: false,
        draggable: false,
        height: 250,
        width: 600,
        modal: true,
        title: 'Качи файл',
        close: function() {
            dialogUpload.dialog( "close" );
            $('.dz-preview').remove();
            $('.dz-message').css('display', 'block');
            $('#create-upload').removeClass('ui-state-focus');
        }
    });
    $( "#create-upload" ).button().on( "click", function() {
        dialogUpload.dialog( "open" );
    });
    
    var dialogInfo;
    dialogInfo = $( "#dialog-info" ).dialog({
        autoOpen: false,
        resizable: false,
        draggable: false,
        height: 200,
        width: 400,
        modal: true,
        title: 'Информация',
        close: function() {
            dialogInfo.dialog( "close" );
            $('.dialog-filename').empty();
            $('.dialog-size').empty();
            $('.dialog-type').empty();
            $('.dialog-created').empty();
        }
    });
    
    var dialogMoveTo;
    dialogMoveTo = $( "#dialog-moveto" ).dialog({
        autoOpen: false,
        resizable: false,
        draggable: false,
        width: 200,
        modal: true,
        title: 'Премести',
        close: function() {
            dialogMoveTo.dialog( "close" );
        },
        buttons: {
            "Премести": function() {
                var csrf = $('input[name=_token]').val();
                var classElements = document.querySelectorAll("tr.ui-selected td.filename");
                var result = classElements[0].innerHTML;
                var getSelect = document.getElementById("folders");
                var seletedOption = getSelect.options[getSelect.selectedIndex].text;
                    $.ajax({
                        async: true,                      
                        method: 'POST',
                        url: '../public/getfolder',
                        data: {"folder": seletedOption, "filename": result, "_token": csrf},
                        complete: function(response) {
                            console.log(response);     
                            $( "tr.tabledata.ui-selected" ).remove();
                        },
                        error: function() {
                            alert('Файлът не успя да се премести.');
                        }
                    });    
                dialogMoveTo.dialog( "close" );
            }
        }
    });
    
    var dialogVersion;
    dialogVersion = $( "#dialog-version" ).dialog({
        autoOpen: false,
        resizable: false,
        draggable: false,
        width: 400,       
        modal: true,
        title: 'Версии',
        close: function() {
            dialogVersion.dialog( "close" );
            $('.version-row').empty();      
        }
    });
    
    var dialogShare;
    dialogShare = $( "#dialog-share" ).dialog({
        autoOpen: false,
        resizable: false,
        draggable: false,
        height: 100,
        width: 400,
        modal: true,
        title: 'Вземи адрес за споделяне',
        close: function() {
            dialogShare.dialog( "close" );
        }
    });
    
    
    /*  Context Menu For All Files*/
    var context = $('table tbody.toBeSelected').nuContextMenu({
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
            if(key === "info") {
                fileInfo();
                dialogInfo.dialog('open');
            };
            if(key === "share") {
               share();
               dialogShare.dialog('open');
            };
            if(key === 'version') {
                dialogVersion.dialog('open');
                selectedFile();
            }
            if(key === 'move') {
                
                dialogMoveTo.dialog('open');
            }
        },
        menu: [
        {
            name: 'download',
            title: 'Изтегли',
            icon: 'cloud-download',
            class: 'download'
        },	
        {
            name: 'void'
        },    
        {
            name: 'delete',
            title: 'Изтрий',
            icon: 'trash',
            class: 'delete'
        },
        {
            name: 'info',
            title: 'Информация',
            icon: 'info-circle',
            class: 'info'
        },
        {
            name: 'share',
            title: 'Сподели',
            icon: 'share-alt',
            class: 'share'
        },
        {
            name: 'version',
            title: 'Версии',
            icon: 'eye',
            class: 'version'
        },
        {
            name: 'move',
            title: 'Премести',
            icon: 'exchange',
            class: 'move'
        }]

    });

    /*  Context Menu For All The Folders*/
    var context = $('table tbody.folder').nuContextMenu({

      hideAfterClick: true,

      items: 'tr.folder-name',
      callback: function(key, element) { 
            if(key === "create") {
                dialogFolder.dialog( "open" );
            };
            if(key === "delete"){
                var classElements = document.querySelectorAll("tbody.folder tr.ui-selected td span");
                var csrf = $('input[name=_token]').val();
                for(var x = 0;x < classElements.length;x++){
                    var result;
                    result = classElements[x].innerHTML;
                    $.ajax({
                        async: true,                      
                        method: 'POST',
                        url: '../public/deletefolder',
                        data: { foldername: result, "_token": csrf  },
                        success: function(response) {
                           $( "tr.ui-selected" ).remove();              
                        }
                    });
                };
            }
        },

      menu: [
        {
          name: 'create',
          title: 'Create',
          icon: 'archive'
        },

        {
          name: 'void'
        },

        {
          name: 'delete',
          title: 'Delete',
          icon: 'trash'
        }
      ]
    });
           
}); 
            
function getImage(){
    var classElements = document.querySelectorAll("tr.ui-selected td.filename");
    var csrf = $('input[name=_token]').val();
    var result;
    result = classElements[0].innerHTML;

    $.ajax({
        async: true,                      
        method: 'POST',
        dataType: 'json',
        url: '../public/routingfile',
        data: {filename: result, "_token": csrf},
        complete: function(response) {
            console.log(response.responseJSON);
            var mime = response.responseJSON.mime;
            if(mime.substring(0,5) === 'image'){
                $(".getImage").attr('src', response.responseJSON.route);  
            }
            else {
                $(".getImage").removeAttr('src'); 
            }
                          
        }
    });       
    $.ajax({
        async: true,  
        method: 'POST',
        dataType: 'json',
        url: '../public/downloadfile',
        data: {filename: result, "_token": csrf},
        complete: function(data) {
            console.log(data);                          
            $("li.download a").attr('href', 'http://localhost/bluedrive/drive/storage/uploads/'+data.responseJSON.path);
            $("li.download a").attr('download', data.responseJSON.filename);
        }
        /* children[1].cells[1].innerHTML */
    });       
};
function fileInfo(){
    var classElements = document.querySelectorAll("tr.ui-selected td.filename");
    var csrf = $('input[name=_token]').val();
    var result;
    result = classElements[0].innerHTML;
    $.ajax({
        async: true,  
        method: 'POST',
        dataType: 'json',
        url: '../public/fileinfo',
        data: {filename: result, "_token": csrf},
        complete: function(data) {
            console.log(data);
            $('.dialog-filename').append(data.responseJSON.filename);
            $('.dialog-size').append(data.responseJSON.size);
            $('.dialog-type').append(data.responseJSON.mime);
            $('.dialog-created').append(data.responseJSON.created.date);
        }
    });
}
function selectedFile(){
    var classElements = document.querySelectorAll("tr.ui-selected td.filename");
    var csrf = $('input[name=_token]').val();
    for(var x = 0;x < classElements.length;x++){
        var result;
        result = classElements[x].innerHTML;
        $.ajax({
            async: true,                      
            method: 'POST',
            dataType: 'json',
            url: '../public/selectedfiles',
            data: { filename: result, "_token": csrf  },
            success: function(response) {
                console.log(response);
                for(var i = 1; i <= response.version; i++){
                    var trueVersion = i-1;
                    $("#dialog-version .version-row").append('<tr><td> ' + i + '</td><td class="filename"><a href="http://localhost/bluedrive/drive/storage/uploads/'+response.userid+'/'+response.onlyName+'_'+trueVersion+'.'+response.extension+'">'+ response.filename + '</a></td></tr>');            
                }    
            }
        });
    };
}
function downloadVersion(){
    var classElements = document.querySelectorAll("#dialog-version  td.filename a");
    var csrf = $('input[name=_token]').val();
 
    var result;
    result = classElements[0].innerHTML;         
        
};
function share(){
    var classElements = document.querySelectorAll("tr.ui-selected td.filename");
    var csrf = $('input[name=_token]').val();
    for(var x = 0;x < classElements.length;x++){
        var result;
        result = classElements[x].innerHTML;
        $.ajax({
            async: true,                      
            method: 'POST',
            dataType: 'json',
            url: '../public/shareurl',
            data: { filename: result, "_token": csrf  },
            success: function(data) {
                console.log(data);
                $("input.shared-url").attr('value', data.route);
                $("input.shared-url").attr('download', data.filename);
            }
        });
    }; 
}

function displayFiles(){
    var classElements = document.querySelectorAll("table.folders-list tr.ui-selected td span");
    var csrf = $('input[name=_token]').val();
    for(var x = 0;x < classElements.length;x++){
        var result;
        result = classElements[x].innerHTML;
        $.ajax({
            async: false,                      
            method: 'POST',
            dataType: 'json',
            url: '../public/getfiles',
            data: { 'folder': result, "_token": csrf  },
            beforeSend: function() { 
                $('.spinner span').fadeOut(600);
                $('.spinner span').fadeIn(600);
                $('body').append('<div class="ui-widget-overlay ui-front" style="display: block; z-index: 101;"></div>');
                $('.spinner').toggle();
                console.log('before');
                
            },
            error: function() {
                $('body').append('<div class="ui-widget-overlay ui-front" style="display: block; z-index: 101;"></div>');
                alert('Нещо се обърка. Презаредете страницата');
            },
            complete: function(){
                console.log('after');
                setTimeout(function(){ 
                    
                    $('.spinner').toggle(); 
                    $('.ui-widget-overlay').css('display', 'none');
                    
                    },
                    1200
                );
            },
            success: function(data) {
                          
                $("tbody.toBeSelected").empty();
                for(var i = 0; i < data.length; i++){
                    var el = document.getElementsByClassName("toBeSelected");
                    var tableRow = document.createElement("tr");
                    var tableData_1 = document.createElement("td");
                    var tableData_2 = document.createElement("td");
                    var tableData_3 = document.createElement("td");
                    
                    var node_1 = document.createTextNode(data[i].id);
                    var node_2 = document.createTextNode(data[i].filename);
                    var node_3 = document.createTextNode(data[i].size);
                    
                    tableData_1.appendChild(node_1);
                    tableData_2.appendChild(node_2);
                    tableData_3.appendChild(node_3);
                    
                    el[0].appendChild(tableRow);
                    
                    document.querySelectorAll('tbody.toBeSelected tr')[i].className = "tabledata"; // or [0]
                    
                    var getTableRow = document.getElementsByClassName("tabledata");
                    getTableRow[i].appendChild(tableData_1);
                    getTableRow[i].appendChild(tableData_2);
                    getTableRow[i].appendChild(tableData_3);
                    
                    
                }
                for(var k = 1; k <= document.querySelectorAll('tbody.toBeSelected tr td').length; k = k + 3){
                    document.querySelectorAll('tbody.toBeSelected tr td')[k].className = "filename";
                }
            }
        });
    }; 
}