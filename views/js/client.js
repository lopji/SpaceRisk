var socket;

var troops;
var seltroops;
var move_from;
var move_to;
var actualState = 5;
var firstInit = true;
var tmap = 0;

function format(type, data) {
    return [type, data];
}

function checkTitle() {
    return document.title === "RISK";
}

function init() {
    var host = "ws://127.0.0.1:666"; // SET THIS TO YOUR SERVER
    try {
        socket = new WebSocket(host);
        console.log('WebSocket - status ' + socket.readyState);
        socket.onopen = function (msg) {
            console.log("Welcome - status " + this.readyState);
            send(format(0, Math.round(Math.random() * 100)));
        };
        socket.onmessage = function (msg) {
            console.log("Received: " + msg.data);
            var data = JSON.parse(msg.data);
            switch (parseInt(data[0])) {
                //Login
                case 0:
                    break;
                //Logout
                case 1:
                    break;
                //Kick
                case 2:
                    kick();
                    break;
                //List player
                case 3:
                    data[1].forEach(function (ps) {
                        $('#joueurs').append('<span style="color:' + ps[0] + ';">' + ps[1] + ' </span>');
                    });
                    break;
                //State
                case 4:
                    state(data[1]);
                    actualState = data[1];
                    break;
                //Troop
                case 5:
                    troops = data[1];
                    $('#troupes').html(data[1]);
                    break;
                // DisplayTroops on planet
                case 6:
                    if (firstInit) {
                        data[1].forEach(function (planet) {
                            console.log('test: #' + planet[0]);
                            var txt = makeSVG('text', {x: $('#' + planet[0]).attr('cx'), id: 'lblTrpPlnt' + planet[0],
                                y: $('#' + planet[0]).attr('cy'), fill: 'white', 'text-anchor': 'middle',
                                'font-family': 'sans-serif', 'font-size': '20px'});
                            txt.innerHTML = planet[2];
                            document.getElementById('layer5').appendChild(txt);
                            tmap++;
                        });
                        firstInit = false;
                    } else {
                        data[1].forEach(function (planet) {
                            $('#lblTrpPlnt' + planet[0]).html(planet[2]);
                        });
                    }

                    if (checkTitle()) {
                        data[1].forEach(function (ps) {
                            if (ps[1] == true) {
                                document.title = "Player " + ps[0] + " - " + document.title;
                            }
                        });
                    }
                    break;
                // Chat
                case 7:
                    if ($("#chat > div").length === 5) {
                        $('#chat').find('div').first().remove();
                    }
                    $('#chat').append('<li style="color:' + data[1][0] + ';">' + data[1][1] + '</li>');
                    break;
                // Objectifs
                case 8:
                    data[1].forEach(function (ps) {
                        $('#objectifs').append('<li>' + ps + '</li>');
                    });
                    break;
            }
        };
        socket.onclose = function (msg) {
            console.log("Disconnected - status " + this.readyState);
        };
    } catch (ex) {
        console.log(ex);
    }
}

function makeSVG(tag, attrs) {
    var el = document.createElementNS('http://www.w3.org/2000/svg', tag);
    for (var k in attrs)
        el.setAttribute(k, attrs[k]);
    return el;
}

function state(id) {
    phase(id);
}

function phase(id) {
    switch (id) {
        case 0:
            $('#phase').html("Deployment");
            grayFilterPhase(id);
            break;
        case 1:
            $('#phase').html("Move");
            grayFilterPhase(id);
            break;
        case 2:
            $('#phase').html("Attack");
            grayFilterPhase(id);
            break;
        case 3:
            $('#phase').html("Game");
            break;
        case 4:
            $('#phase').html("Score");
            $('#score-modal').modal('show');
            $('#score-modal-body p').html("");  // TODO: Ajouter dans cette balise si le joueur a gagné ou perdu
            $('#score-modal').click(function(){
                send(format(1, ''));
            });
            break;
        case 5:
            $('#phase').html("Wait");
            grayFilterPhase(id);
            break;
    }
}
$('#formulaire_chat').submit(function () {
    message = $('#message').val();
    send(format(3, message));
    $('#message').val('').focus();
    return false;
});

function kick() {
    console.log("You have been kicked !");
    socket.close();
    socket = null;
}

function send(message) {
    socket.send(JSON.stringify(message));
    console.log("Send: " + message);
}

function grayFilterPhase(idPhase) {
    $("#phX").children().css("filter", "grayscale(100%)");
    $("#ph" + idPhase).css("filter", "grayscale(0%)");
}

var self;
$('#layer3 ellipse').on({
    mouseenter: function () {
        self = $(this);
        switch (actualState) {
            //Déploiement de troupes
            case 0:
                $(this).popover('destroy');
                var content = '<div class="select"><select id="sel-deploy" class="form-control">';
                for (var i = 1; i <= troops; i++) {
                    content += '<option value="' + i + '">' + i + '</option>';
                }
                content += "</select><button type='button' id='btnSend' class='btn btn-primary' onClick='send(format(2, [self.attr(\"id\"),"
                        + "$(\"#sel-deploy\").val()]));'>Déployer troupes</button></div>";
                $(this).popover({container: 'body', html: true, content: content, title: 'Deploy',
                    template: '<div class="popover" role="tooltip"><div class="arrow"></div>' +
                            '<h3 class="popover-title"></h3><div class="popover-content"></div></div>'});
                break;
                //Déplacement : Sélection de la 1ère planètes et des troupes
            case 1:
                $(this).popover('destroy');
                var content = '<div class="select"><select id="sel-move_from" class="form-control">';
                tr = $('#lblTrpPlnt' + self.attr('id')).html();
                for (var i = 1; i <= tr; i++) {
                    content += '<option value="' + i + '">' + i + '</option>';
                }
                content += "</select><button type='button' id='btnSendMoveFrom' class='btn btn-primary' \n\
                                onClick='troops = $(\"#sel-move_from\").val();\n\
                                move_from = " + self.attr('id') + "; actualState++;'>Sélectionner troupes</button></div>";
                $(this).popover({container: 'body', html: true, content: content, title: 'Select',
                    template: '<div class="popover" role="tooltip"><div class="arrow"></div>' +
                            '<h3 class="popover-title"></h3><div class="popover-content"></div></div>'});
                break;
                //Déplacement : Sélection de la 2ème planète
            case 2:
                $(this).popover('destroy');
                if (parseInt(self.attr('id')) !== move_from) {
                    var content = "<button type='button' id='btnSendMoveTo' class='btn btn-primary' \n\
                                data-toggle='modal' data-target='#combatModal'\n\
                                move_to = " + self.attr('id') + ";'>\n\
                                Déplacer/Attaquer</button></div>";
                    $(this).popover({container: 'body', html: true, content: content, title: troops + ' troupes',
                        template: '<div class="popover" role="tooltip"><div class="arrow"></div>' +
                                '<h3 class="popover-title"></h3><div class="popover-content"></div></div>'});
                }
                break;
        }

        $(this).css('filter', 'url(#dropshadow)').css('stroke', '#ffffff');

    },
    mouseleave: function () {
        $(this).css('filter', '').css('stroke', '#000000');
    }
});
