<?php ?>
<!DOCTYPE html>
<html lang="en">
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

        <script src="./views/js/client.js"></script>

    </head>

    <body onload="init()">
        <div class="container">
            <div class="row">
                <div id="joueurs" class="col-md-4">
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
                        <svg xmlns="http://www.w3.org/2000/svg">
                        <circle id="greencircle" cx="30" cy="30" r="30" fill="green" />
                        </svg>
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
                            <li style="color:green;">Lama</li>
                            <li style="color:red;">Dark Magenta</li>
                            <li style="color:blue;">Yo</li>
                            <li style="color:red;">Blou</li>
                        </ol>
                        <form class="form-inline">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Parlez ici..."/></div>
                            <button type="button" class="btn btn-primary btn-sm">Envoyer</button>
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
                <div class="text-center">
                    <div class="col-md-2 col-md-offset-1">
                        <svg xmlns="http://www.w3.org/2000/svg">
                        <circle id="a" cx="30" cy="30" r="25" stroke="black" stroke-width="4" fill="white" />
                        </svg>
                    </div>
                    <div class="col-md-2">
                        <svg xmlns="http://www.w3.org/2000/svg">
                        <circle id="b" cx="30" cy="30" r="25" stroke="black" stroke-width="4" fill="white" />
                        </svg>
                    </div><div class="col-md-2">
                        <svg xmlns="http://www.w3.org/2000/svg">
                        <circle id="c" cx="30" cy="30" r="25" stroke="black" stroke-width="4" fill="white" />
                        </svg>
                    </div><div class="col-md-2">
                        <svg xmlns="http://www.w3.org/2000/svg">
                        <circle id="d" cx="30" cy="30" r="25" stroke="black" stroke-width="4" fill="white" />
                        </svg></div>
                </div>
            </div>
            <!-- /.container -->

            <footer class="footer">
                <div class="container">
                </div>
            </footer>

            <!-- Bootstrap core JavaScript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    </body>
</html>
