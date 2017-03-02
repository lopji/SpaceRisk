var socket;

function format(type, data) {
    return [type, data];
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
                        $('#joueurs').append('<p style="color:' + ps[0] + ';">' + ps[1] + '</p>');
                    });
                    break;
                    //State
                case 4:
                    state(data[1]);
                    break;
                    //Troop
                case 5:
                    $('#troupes').html(data[1]);
                    break;
                case 6:

                    break;
                case 7:
                    if ($("#chat > div").length === 5) {
                        $('#chat').find('div').first().remove();
                    }
                    $('#chat').append('<li style="color:' + data[1][0] + ';">' + data[1][1] + '</li>');
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


$('#layer3 ellipse').on({
    mouseenter: function () {
        $(this).css('filter', 'url(#dropshadow)').css('stroke', '#ffffff');
    },
    mouseleave: function () {
        $(this).css('filter', '').css('stroke', '#000000');
    },
    click: function () {
        send(format(2, [$(this).attr('id'), 10]));
    }
});