<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="./javascript/client.js"></script>
    </head>
    <body onload="init()">
        <p>Id player</p>
        
        <input value="0" id="data" type="text" /> 
        <input type="button" value="deployment" onclick="send(format(1, document.getElementById('data').value))" id="deployment" />
         
    </body>
</html>