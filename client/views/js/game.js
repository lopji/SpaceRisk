
//*** Variable Global

var renderer, scene, camera, pointLight, spotLight;

// Terrain de Jeu
var fieldWidth = 400, fieldHeight = 200;

// Palette
var paddleWidth, paddleHeight, paddleDepth, paddleQuality;
var paddle1DirY = 0, paddleSpeed = 3;

// Ball
var ball, paddle1, paddle2;
var ballDirX = 1, ballDirY = 1, ballSpeed = 1;

// Increment entre chaque balle
var difficulty = 0.5;

// Vie Max
var maxLife = 2;

// *** Timer
var startTime = 0
var start = 0
var end = 0
var diff = 0
var timerID = 0
function chrono(){
	end = new Date()
	diff = end - start
	diff = new Date(diff)
	var msec = diff.getMilliseconds()
	var sec = diff.getSeconds()
	var min = diff.getMinutes()
	var hr = diff.getHours()-1
	if (min < 10){
		min = "0" + min
	}
	if (sec < 10){
		sec = "0" + sec
	}
	if(msec < 10){
		msec = "00" +msec
	}
	else if(msec < 100){
		msec = "0" +msec
	}
	document.getElementById("chronotime").innerHTML = hr + ":" + min + ":" + sec + ":" + msec
	timerID = setTimeout("chrono()", 10)
}

function chronoStart(){
	start = new Date()
	chrono()
}

function chronoStop(){
	clearTimeout(timerID)
}


//*** Jeu

// Point d'entree du Script
function setup()
{
	 difficulty = 0.5;
	 maxLife = 2;

	document.getElementById("game_btn").style.display='none';
	document.getElementById("winnerBoard").innerHTML = "Survivre";
	document.getElementById("scores").innerHTML = "Vie(s) : " + maxLife;

	// Cree les elements 3d
	createScene();
	// Place la camera
	cameraPos();
	chronoStart();
	// Lance le jeu
	draw();
}

// Cree la scene
function createScene()
{
	//Taille de la scene
	var WIDTH = 640;
	var HEIGHT = 360;

	// Camera variable
	var VIEW_ANGLE = 50;
	var ASPECT = WIDTH / HEIGHT;
	var NEAR = 0.5;
	var FAR = 10000;

	// Cree le rendu , la camera et la  scene
	renderer = new THREE.WebGLRenderer();
	camera = new THREE.PerspectiveCamera(VIEW_ANGLE,ASPECT,NEAR,FAR);
	scene = new THREE.Scene();

	// Ajoute la camera dans la scene
	scene.add(camera);

	// Lance le rendu
	renderer.setSize(WIDTH, HEIGHT);

	// Cherche le canvas html
	var c = document.getElementById("gameCanvas");
	c.appendChild(renderer.domElement);

	//Surface de jeu
	var planeWidth = fieldWidth;
	var planeHeight = fieldHeight;
	var planeQuality = 10;

        //Texture importées
        var textureBall = new THREE.TextureLoader().load("./views/ressources/s_terre.png");
        var texturePaddle = new THREE.TextureLoader().load("./views/ressources/p_neptune.jpg");
        var textureCanon = new THREE.TextureLoader().load("./views/ressources/p_venus.jpg");
        var texturePlane = new THREE.TextureLoader().load("./views/ressources/s_moon.jpg");

	// Create les textures pour les elements 3d
	var paddle1Material =
	  new THREE.MeshBasicMaterial(
		{
                  map: texturePaddle
		});
	var paddle2Material =
	  new THREE.MeshBasicMaterial(
		{
		  //color: 0xAF40F2
                  map: textureCanon
		});
	var planeMaterial =
	  new THREE.MeshLambertMaterial(
		{
                    map: texturePlane
		});
	var tableMaterial =
	  new THREE.MeshLambertMaterial(
		{
		  color: 0x111111
		});
	var lifeMaterial =
	  new THREE.MeshLambertMaterial(
		{
                  map: texturePlane
		});

	var sphereMaterial =
	  new THREE.MeshBasicMaterial(
		{
                  map: textureBall
		});

	//Cree les elements 3d avec les textures et les ajoute dans la scene

	var plane = new THREE.Mesh(
	  new THREE.PlaneGeometry(
		planeWidth * 0.95,
		planeHeight,
		planeQuality,
		planeQuality),
	  planeMaterial);
	scene.add(plane);

	var table = new THREE.Mesh(
	  new THREE.CubeGeometry(
		planeWidth * 1.05,
		planeHeight * 1.03,
		50,
		planeQuality,
		planeQuality,
		1),
	  tableMaterial);
	table.position.z = -50;
	scene.add(table);

	lifeHeight = 5;
	lifeDepth = 10;
	lifeQuality = 1;
	lifes = new THREE.Mesh(
	  new THREE.CubeGeometry(
		lifeHeight,
		fieldHeight,
		lifeDepth,
		lifeQuality,
		lifeQuality,
		lifeQuality),
	  lifeMaterial);
	scene.add(lifes);
	lifes.position.x = planeWidth/ 1.03/2 *-1;
	lifes.position.y = 0;
	lifes.position.z = 0,5;

	paddleWidth = 10;
	paddleHeight = 30;
	paddleDepth = 10;
	paddleQuality = 1;
	paddle1 = new THREE.Mesh(
	  new THREE.CubeGeometry(
		paddleWidth,
		paddleHeight,
		paddleDepth,
		paddleQuality,
		paddleQuality,
		paddleQuality),
	  paddle1Material);
	scene.add(paddle1);

	paddle2 = new THREE.Mesh(
	  new THREE.CubeGeometry(
		paddleWidth,
		paddleHeight,
		paddleDepth,
		paddleQuality,
		paddleQuality,
		paddleQuality),
	  paddle2Material);
	scene.add(paddle2);

	var radius = 5;
	var	segments = 6;
	var	rings = 6;

	ball = new THREE.Mesh(
	  new THREE.SphereGeometry(
		radius,
		segments,
		rings),
	  sphereMaterial);
	scene.add(ball);

	// Place la balle
	resetBall();
	ball.position.z = radius;

	// Place les deux palettes
	paddle1.position.x = -fieldWidth/2 + paddleWidth;
	paddle2.position.x = fieldWidth/2 - paddleWidth;
	paddle1.position.z = paddleDepth;
	paddle2.position.z = paddleDepth;

	// Lumiere
	pointLight = new THREE.PointLight(0xFFFFFF);

	pointLight.position.x = -1000;
	pointLight.position.y = 0;
	pointLight.position.z = 1000;
	pointLight.intensity = 1.0;
	pointLight.distance = 10000;

	scene.add(pointLight);
}

// Dessin
function draw()
{
	//Desine la scene
	renderer.render(scene, camera);

	// Boucle
	requestAnimationFrame(draw);
	ballPhysics();
	paddlePhysics();
	playerPaddleMovement();
}

// Collision de la balle
function ballPhysics()
{
	// Quand la balle arrive deriere les palettes
	if (ball.position.x <= -fieldWidth/2)
	{
		maxLife--;
		document.getElementById("scores").innerHTML = "Vie(s) : " + maxLife;
		resetBall();
		endCheck();
	}
	if (ball.position.x >= fieldWidth/2)
	{
		resetBall();
	}

	// Collision contre le bord gauche et droit
	if (ball.position.y <= -fieldHeight/2)
	{
		ballDirY = -ballDirY;
	}
	if (ball.position.y >= fieldHeight/2)
	{
		ballDirY = -ballDirY;
	}

	//Bouge la balle
	ball.position.x += ballDirX * ballSpeed;
	ball.position.y += ballDirY * ballSpeed;
}

// Mouvement de la palette Joueur
function playerPaddleMovement()
{
	if (Key.isDown(Key.left))
	{
		if (paddle1.position.y < fieldHeight * 0.45)
			paddle1DirY = paddleSpeed * 0.5;
		else
			paddle1DirY = 0;
	}
	else if (Key.isDown(Key.right))
	{
		if (paddle1.position.y > -fieldHeight * 0.45)
			paddle1DirY = -paddleSpeed * 0.5;
		else
			paddle1DirY = 0;
	}
	// Quand une touche est lache immobilise la palette
	else
		paddle1DirY = 0;

	paddle1.scale.y += (1 - paddle1.scale.y) * 0.2;
	paddle1.scale.z += (1 - paddle1.scale.z) * 0.2;
	paddle1.position.y += paddle1DirY;
}

// Initialisation Camera
function cameraPos()
{
	camera.position.x = paddle1.position.x - 100;
	camera.position.y += (paddle1.position.y - camera.position.y) * 0.05;
	camera.position.z = paddle1.position.z + 100 + 0.04 * (-ball.position.x + paddle1.position.x);

	camera.rotation.x = -0.01 * (ball.position.y) * Math.PI/180;
	camera.rotation.y = -60 * Math.PI/180;
	camera.rotation.z = -90 * Math.PI/180;
}

// Colision de la raquette
function paddlePhysics()
{
	if (ball.position.x <= paddle1.position.x + paddleWidth&&  ball.position.x >= paddle1.position.x)
	{
		if (ball.position.y <= paddle1.position.y + paddleHeight/2 &&  ball.position.y >= paddle1.position.y - paddleHeight/2)
		{
			if (ballDirX < 0)
			{
				ballDirY -= paddle1DirY * 0.6;
				ballDirX = -ballDirX;
			}
		}
	}


}

// Reinisialise la balle
function resetBall()
{
	ball.position.x = fieldWidth/2;
	ball.position.y = 0;

	// Change la Direction Y aleatoirement
	ballDirY = Math.random() > 0.5 ? 1 : -1;

	// Change la Direction x aleatoirement
	ballDirX = Math.random()*-1;
	while(ballDirX>(-0.5))
		ballDirX = Math.random()*-1;

	// A chaque nouvelle vie augmente la vitesse de la balle
	ballSpeed = ballSpeed+difficulty;

	// Ajoute un effet sur le canon
	if (maxLife >0){
		if(ballDirY>0)
			paddle2.rotation.z = ballDirY +ballDirX;
		else
			paddle2.rotation.z = ballDirY -ballDirX;
	}


}

//Detection Fin de Jeu
function endCheck()
{
	if (maxLife <= 0)
	{
		ballSpeed = 0;
		difficulty = 0;

		document.getElementById("winnerBoard").innerHTML = "Attendre les autres joueurs";
		chronoStop();

		var time = document.getElementById("chronotime").innerHTML;
		console.log(time);

		send(format(6, time));
	}
}
