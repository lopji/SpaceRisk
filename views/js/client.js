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
                    console.log("login: " + data[1]);
                    break;
                //Logout
                case 1:
                    console.log("logout: " + data[1]);
                    break;
                //Kick
                case 2:
                    kick();
                    break;
                //List player
                case 3:
                    data[1].forEach(function(ps) {
                        $('#joueurs').append('<p>' + ps +'</p>');
                    })
                    break;
                //State
                case 4:
                    console.log("State");
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

function kick() {
    console.log("You have been kicked !");
    socket.close();
    socket = null;
}

function send(message) {
    socket.send(JSON.stringify(message));
    console.log("Send: " + message);
}