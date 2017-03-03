<?php ?>
<!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="Risk">
        <meta name="author" content="grpSwig">
        <link rel="icon" href="glyphicon glyphicon-star">

        <title>RISK</title>

        <!-- Bootstrap core CSS -->
        <link href="./views/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="./views/css/style.css" rel="stylesheet">


    </head>

    <body onload="init()">
        <div class="container">
            <div class="row">
                <div  id="joueurs" class="col-md-4">
                </div>
                <div
                    class="col-md-4">
                    <h2 id="phase"></h2>
                </div>
                <div class="col-md-4"><div id="troupes">
                        <img src="./views/ressources/rocketship.svg" alt="rocket" width="40px" height="40px"></span> <span id="troop">0</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div id="plateau">
                        <?php
                        require_once './views/ressources/map/svg.php';
                        ?>
                    </div>
                </div>

                <div class="col-md-3 col-md-offset-1 blog-sidebar">
                    <div class="sidebar-module">
                        <h4>Objectifs</h4>
                        <div id="objectifs" class="bg-success">
                            <p>-Conquérir 5 planètes</p>
                            <p>-Ne pas décéder</p>
                            <p>-Achever un ennemi</p>
                        </div>
                    </div>
                    <div class="sidebar-module">
                        <h4>Chat</h4>
                        <ol class="list-unstyled" id="chat">

                        </ol>
                        <form class="form-inline" action="/" method="post" id="formulaire_chat">
                            <div class="form-group">
                                <input type="text" class="form-control" id="message" placeholder="Parlez ici..."/>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm" >Envoyer</button>
                        </form>
                    </div>
                    <div class="sidebar-module">
                        <div class="text-center">
                            <h4 id="instructions">Cliquez sur les planètes pour y déployer vos flottes</h4>
                            <p><input id="nextPhase" class="btn btn-success" type="button" onclick="send(format(1, ''));"value="Prochaine phase"></p>
                            <p><input id="adandon" class="btn btn-danger" type="button" value="Abandonner"></div></p>
                    </div>
                </div><!-- /.blog-sidebar -->
            </div><!-- /.row -->
            <div class="row">
                <div class="text-center" id="phX">
                    <div class="col-md-1 col-md-offset-1" id="ph0">
                        <img src="./views/ressources/ss4.jpg" alt="rocket" width="40px" height="40px" >
                    </div>
                    <div class="col-md-1">
                        <img src="./views/ressources/arrow.png" alt="arrow" width="40px" height="40px">
                    </div>
                    <div class="col-md-1" id="ph1">
                        <img src="./views/ressources/ss1.jpg" alt="rocket" width="40px" height="40px" >
                    </div>
                    <div class="col-md-1" >
                        <img src="./views/ressources/arrow.png" alt="arrow" width="40px" height="40px">
                    </div>
                    <div class="col-md-1" id="ph2">
                        <img src="./views/ressources/ss2.jpg" alt="rocket" width="40px" height="40px" >
                    </div>
                    <div class="col-md-1">
                        <img src="./views/ressources/arrow.png" alt="arrow" width="40px" height="40px">
                    </div>
                    <div class="col-md-1" id="ph5">
                        <img src="./views/ressources/ss3.jpg" alt="rocket" width="40px" height="40px" >
                    </div>
                </div>
            </div>
            <!-- /.container -->

            <footer class="footer">
                <div class="container">
                </div>
            </footer>

    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="./views/js/bootstrap.min.js"></script>
    <script src="./views/js/client.js"></script>
</html>
