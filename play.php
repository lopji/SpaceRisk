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
                <div class="modal fade" id="combatModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Minigame time</h4>
                    </div>
                    <div class="modal-body">
                      <p>Vos troupes : </p>
                      <p>Troupes ennemies : </p>
                      <p><b>Combattre ?</b></p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-link" data-dismiss="modal">Nope</button>
                      <button type="button" class="btn btn-danger">Launch game</button>
                    </div>
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
              </div><!-- /.modal -->

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
                        <img src="./views/ressources/depl.png" alt="rocket" width="60px" height="60px" >
                    </div>
                    <div class="col-md-1">
                        <img src="./views/ressources/arrow.png" alt="arrow" width="40px" height="40px">
                    </div>
                    <div class="col-md-1" id="ph1">
                        <img src="./views/ressources/move.png" alt="rocket" width="60px" height="60px" >
                    </div>
                    <div class="col-md-1" >
                        <img src="./views/ressources/arrow.png" alt="arrow" width="40px" height="40px">
                    </div>
                    <div class="col-md-1" id="ph2">
                        <img src="./views/ressources/fight.png" alt="rocket" width="60px" height="60px" >
                    </div>
                    <div class="col-md-1">
                        <img src="./views/ressources/arrow.png" alt="arrow" width="40px" height="40px">
                    </div>
                    <div class="col-md-1" id="ph5">
                        <img src="./views/ressources/end.png" alt="rocket" width="60px" height="60px" >
                    </div>
                </div>

                <!-- Large modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>

                <div class="modal fade bs-example-modal-lg" id="game-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                      <div class="modal-header">
                              <h4 class="modal-title" id="myModalLabel">Game</h4>
                      </div>
                        <div class="modal-body text-center">
                              <button type="button" class="btn btn-primary" onclick="setup();"> Start</button>
                              <div id='gameCanvas' class="text-center"></div>

                              <div id='scoreboard' class="text-center">
                                <h1 id='scores'>Vie : x</h1>
                                <h1 id='title'>Pong</h1>
                                <h2 id='winnerBoard'></h2>
                                <span id="chronotime">0:00:00:00</span>
                              </div>
                        </div>
                    </div>
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
    <!-- Mini Game -->
    <script src='./views/js/three.min.js'></script>
    <script src='./views/js/keyboard.js'></script>
    <script src='./views/js/game.js'></script>
</html>
