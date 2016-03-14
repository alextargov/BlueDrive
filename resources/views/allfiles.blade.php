<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                background-color:#269abc;
            }

            .container {
                text-align: center;
                
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
       
        <div class="container">
            <div class="content" oncontextmenu="return false;">
                <div class="title">Welcome to BlueDrive, {{ Auth::user()->username }}</div>
                <h3><a href="auth/logout">Logout</a></h3>
                @foreach($files as $file)
                <br>{{ $file->filename }}
                @endforeach
        </div>
    </body>
</html>


