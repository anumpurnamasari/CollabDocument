<!DOCTYPE html>
<html>
<head>
    <title>Realtime Collaboration</title>

    @vite(['resources/js/collab.js'])

    <style>
        body{
            font-family:sans-serif;
            padding:40px;
        }

        #editor{
            border:1px solid #ccc;
            min-height:300px;
            padding:20px;
            border-radius:10px;
        }
    </style>
</head>
<body>

<h1>Realtime Collaboration Test</h1>

<div id="editor"></div>

</body>
</html>