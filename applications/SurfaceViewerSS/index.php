<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Data Exploration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
       	background-color: black;
      }
    </style>
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/img/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/img/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/img/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/img/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="/img/favicon.png">

    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/themes/vader/jquery-ui.css" rel="stylesheet" type="text/css"/>

  </head>
  <body>

    <!-- http://mmil-dataportal.ucsd.edu:3000/applications/SurfaceViewerSS/index.php?subjid=Y0181,Y0368&visitid=Y0181,Y0368 -->

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Data Portal Surface Viewer <span class="project_name"></span></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

 <?php 
   session_start();

   include("../../code/php/AC.php");
   $user_name = check_logged(); /// function checks if visitor is logged.

   if (isset($_SESSION['project_name']))
      $project_name = $_SESSION['project_name'];

   echo('<script type="text/javascript"> user_name = "'.$user_name.'"; project_name = "'.$project_name.'"; </script>');

 ?>


 <div class="container">
 	<div style="z-index: 99; position: absolute; top: 50px; left:10px; color: white; font-weight: 200; line-height: 1.4; font-size: 21px; text-shadow: 0 1px 3px rgba(0.6,0.6,0.6,0.9); font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;">
 		<div class="btn-group" style="margin-top: 50px;">
           <button type="button" class="btn btn-default" id="back"><span class="glyphicon glyphicon-chevron-left" style="vertical-align:middle"></span>&nbsp;</button>
           <button type="button" class="btn btn-default" id="name">something</button>
           <button type="button" class="btn btn-default" id="forward"><span class="glyphicon glyphicon-chevron-right" style="vertical-align:middle"></span>&nbsp;</button>
        </div>
 	</div>

 </div>


 <script src="js/jquery.min.js"></script>

 <script src="js/three.min.js"></script>

 <!-- <script src="js/ColladaLoader.js"></script> -->

 <script src="js/Detector.js"></script>

 <script src="js/TrackballControls.js"></script>

 <script src="js/KeyboardState.js"></script>

 <script src="js/threex.webcamtexture.js"></script>

 <script src="js/THREEx.solidwireframe.js"></script>

<!-- <script src="js/VTKLoader.js"></script> -->

 <script src="js/jDataView.js"></script>

 <script>

 function getURLParameter(name) {
 	return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
 }

 if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

 var container, keyboard;

 var camera, scene, renderer, objects;
 var particleLight, pointLight;
 var dae, skin;
 var directionalLight;
 var rotSpeed = 0.02;
 var startAnimation = false;
 var updateFcts	= [];
 var useWebCam = false;
 var usewireframe = false;
 var webcamTexture;
 var scene;
 var lh, rh;
 var morphlh, morphrh;
 var morphNow;
 var subjects;
 var thissubject = 0;

 //var loader = new THREE.ColladaLoader();
 // var loader = new THREE.VTKLoader();

 subjid = getURLParameter('subjid');
 visitid = getURLParameter('visitid');
 brainname = subjid + ' - ' + visitid;

var TRI_MAGIC_NUMBER = (-2 & 0x00ffffff);

function readInt3(data, pos) {
  var val = ((( (data[pos+0].charCodeAt(0) & 0x0000FF) << 16)
            | ( (data[pos+1].charCodeAt(0) & 0x0000FF) << 8)
	    | ( (data[pos+2].charCodeAt(0) & 0x0000FF) )  ));
  return val;
}
function readUInt3(data, pos) {
  var val = ((( (data[pos+0].charCodeAt(0) & 0x0000FF) << 16)
            | ( (data[pos+1].charCodeAt(0) & 0x0000FF) << 8)
	    | ( (data[pos+2].charCodeAt(0) & 0x0000FF) )  )) >>> 0;
  return val;
}
function readInt4(data, pos) {
  var x0 = (data[pos+0].charCodeAt(0));
  var x1 = (data[pos+1].charCodeAt(0));
  var x2 = (data[pos+2].charCodeAt(0));
  var x3 = (data[pos+3].charCodeAt(0));
  var val = ( ( (data[pos+0].charCodeAt(0) & 0x000000FF) << 24)
            | ( (data[pos+1].charCodeAt(0) & 0x000000FF) << 16)
	    | ( (data[pos+2].charCodeAt(0) & 0x000000FF) << 8)
	    | ( (data[pos+3].charCodeAt(0) & 0x000000FF) ) );
  return val;
}
function getIntAt ( arr, offs ) {
  return (arr[offs+0] << 24) +
    (arr[offs+1] << 16) +
    (arr[offs+2] << 8) +
    arr[offs+3];
}
function getUIntAt ( arr, offs ) {
  return (arr[offs+0] << 24) +
    (arr[offs+1] << 16) +
    (arr[offs+2] << 8) +
    arr[offs+3] >>> 0;
}

function swap32(val) {
  return ((val & 0xFF) << 24)
    | ((val & 0xFF00) << 8)
    | ((val >> 8) & 0xFF00)
    | ((val >> 24) & 0xFF);
}

function swap16(val) {
  return ((val & 0xFF) << 8)
    | ((val >> 8) & 0xFF);
}

// convert freesurfer to Three.js
function parseAndLoadSurf(data) {
  var view = new jDataView(data);
  var pos = 0;

  // read the cookie
  //var cookie = swap32(readUInt3(data,pos)); 
  pos += 3;
  //cookie = (cookie >> 8) & 0xffffff;
  //if (cookie != TRI_MAGIC_NUMBER) {
  //  console.log("found magic number");
  //}

  // read the comment
  var i = pos;
  var comment = "";
  for (; i < pos+1024; i++) {
    if ( data[i] == '\0'.charCodeAt(0) ) {
      break;
    } else {
      comment = comment + String.fromCharCode(data[i]);
    }
  }
  pos=i; // comment length only

  // read the number of vertices
  var numVert = view._getInt32(pos,false); pos+=4;
  console.log('number of vertices is: ' + numVert);

  // read the number of faces
  var faceCount = view._getInt32(pos,false); pos+=4;
  console.log('number of faces is: ' + faceCount);

  // read in all the vertices
  var geometry = new THREE.Geometry();
  for ( var i = 0; i < numVert; i++) {
    var x = view.getFloat32(pos,false); pos+=4;
    var y = view.getFloat32(pos,false); pos+=4;
    var z = view.getFloat32(pos,false); pos+=4;
    geometry.vertices.push( new THREE.Vector3(x, y, z) );
  }

  for ( var i = 0; i < faceCount; i++) {
    var x = view.getUint32(pos,false); pos+=4;
    var y = view.getUint32(pos,false); pos+=4;
    var z = view.getUint32(pos,false); pos+=4;
    geometry.faces.push ( new THREE.Face3(x, y, z) );
  }

  geometry.computeFaceNormals();
  geometry.computeVertexNormals();

  var material;
  if (usewireframe) {
	material = new THREEx.SolidWireframeMaterial(geometry);
  } else {
	material = new THREE.MeshLambertMaterial({ 
		color: new THREE.Color(0.6,0.6,0.6), 
		ambient: new THREE.Color(0.1,0.1,0.1),
		emissive: new THREE.Color(0.1,0.1,0.1), 
		shading: THREE.SmoothShading 
	});
  }

  var o = new THREE.Mesh( geometry, material );
  o.receiveShadow = true;
  o.castShadow = true;
  o.doubleSided = true;
  o.overdraw = true;
  scene.add( o );
  return o;
}

function loadSubjectFromSurf(s) {
  var filename = '/data/'+project_name+'/'+project_name+'_webcache/'+subjects[s].subjid+'/'+subjects[s].visitid+'/lh.pial';
  var oReq = new XMLHttpRequest();
  oReq.open("GET", filename, true);
  oReq.responseType = "arraybuffer";

  oReq.onload = function (oEvent) {
    var arrayBuffer = oReq.response; // Note: not oReq.responseText
    if (arrayBuffer) {
      var byteArray = new Uint8Array(arrayBuffer);
      var o = parseAndLoadSurf(byteArray);
      lh = o.geometry;
    }
  };

  oReq.send(null);

  var filename = '/data/'+project_name+'/'+project_name+'_webcache/'+subjects[s].subjid+'/'+subjects[s].visitid+'/rh.pial';
  var oReq2 = new XMLHttpRequest();
  oReq2.open("GET", filename, true);
  oReq2.responseType = "arraybuffer";

  oReq2.onload = function (oEvent) {
    var arrayBuffer2 = oReq2.response; // Note: not oReq2.responseText
    if (arrayBuffer2) {
      var byteArray2 = new Uint8Array(arrayBuffer2);
      var o = parseAndLoadSurf(byteArray2);
      rh = o;
    }
  };

  oReq2.send(null);
}

 function loadSubjectVertices(s) {
    jQuery.ajax({
    	dataType: "json",
    	url: 'getBrain.php',
    	type: "POST",
    	data: subjects[s],
    	success: function(data) {
           // alert('got something back!' + data);
           // now replace the geometry coordinates with the new coordinates
           lha = data.lh;
           rha = data.rh;
           for (var i = 0; i < lha.length/3; i++) {
              morphlh.vertices[i].x = lha[i];
              morphlh.vertices[i].y = lha[1*lha.length/3+i];
              morphlh.vertices[i].z = lha[2*lha.length/3+i];

              morphrh.vertices[i].x = rha[i];
              morphrh.vertices[i].y = rha[1*rha.length/3+i];
              morphrh.vertices[i].z = rha[2*rha.length/3+i];
           }
      	   //lh.computeVertexNormals();
      	   //rh.computeVertexNormals();

           // lh.verticesNeedUpdate = true;
           // rh.verticesNeedUpdate = true;
           morphNow = true;

           jQuery('#name').hide().text( subjects[s].subjid ).fadeIn(1000);
           document.title = subjects[s].subjid;
        }
    }).fail(function() {
       	alert("Loading of subject specific surface failed, check project setup.");
    });
 }

 jQuery(document).ready(function() {
 	jQuery('.project_name').text(project_name);
 	// load the
 	subjects = []; //  { "subjid": subjid, "visitid": visitid, "project_name": project_name } ];
 	subj = subjid.split(',');
 	visi = visitid.split(',');
 	for (var i = 0; i < subj.length; i++) {
	  if ( i < subj.length && i < visi.length ){
	    subjects.push( {"subjid": subj[i], "visitid": visi[i], "project_name": project_name } );
	  }
 	}
	
	init();
	animate();

    jQuery('#back').click(function() {
    	// go back one entry
    	nextsubject = thissubject - 1;
    	if (nextsubject < 0)
    		nextsubject = subjects.length-1;
    	if (nextsubject != thissubject) {
    		thissubject = nextsubject;
		//loadSubjectVertices(thissubject);
		loadSubjectFromSurf(thissubject);
      	}
    });
    jQuery('#forward').click(function() {
    	// go forward one entry
    	nextsubject = thissubject + 1;
    	if (nextsubject >= subjects.length)
    		nextsubject = 0;
    	if (nextsubject != thissubject) {
    		thissubject = nextsubject;
		//loadSubjectVertices(thissubject);
		loadSubjectFromSurf(thissubject);
     	}
    });
 });


function loadAverage() {

  /*loader.load("../SurfaceViewer/data/left_hemisphere.vtk", function(geom) {
		geom.computeVertexNormals();
		lh = geom;
		morphlh = geom.clone();
		var material;

		if (usewireframe) {
			material = new THREEx.SolidWireframeMaterial(geom);
		} else {
			material = new THREE.MeshLambertMaterial({ 
				color: new THREE.Color(0.6,0.6,0.6), 
				ambient: new THREE.Color(0.1,0.1,0.1), 
				emissive: new THREE.Color(0.1,0.1,0.1), 
				shading: THREE.SmoothShading 
			});
		}

		var o = new THREE.Mesh( geom, material );
		o.receiveShadow = true;
		o.castShadow = true;
		scene.add( o );
	});

	loader.load("../SurfaceViewer/data/right_hemisphere.vtk", function(geom) {
		geom.computeVertexNormals();
		rh = geom;
		morphrh = geom.clone();
		var material;

		if (usewireframe) {
			material = new THREEx.SolidWireframeMaterial(geom);
		} else {
			material = new THREE.MeshLambertMaterial({ 
				color: new THREE.Color(0.6,0.6,0.6), 
				ambient: new THREE.Color(0.1,0.1,0.1), 
				emissive: new THREE.Color(0.1,0.1,0.1), 
				shading: THREE.SmoothShading 
			});
		}
		o = new THREE.Mesh( geom, material );
		o.receiveShadow = true;
		o.castShadow = true;
		scene.add( o );
		}); */

}



function init() {

 	container = document.createElement( 'div' );
 	container.style.cssText = 'position: absolute;';
 	document.body.appendChild( container );
 	//jQuery('.container').append( container );

 	renderer = new THREE.WebGLRenderer();
 	renderer.setSize( window.innerWidth, window.innerHeight );
 	renderer.shadowMapEnabled = true;
        renderer.shadowMapSoft = true;
        renderer.shadowCameraNear = 3;
 	container.appendChild( renderer.domElement );

 	keyboard = new THREEx.KeyboardState();

 	camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 2000 );
 	camera.position.set( 217, 0, 150 );
 	camera.up = new THREE.Vector3(0,0,1);

 	scene = new THREE.Scene();

 	loadAverage();

 	var size = 14, step = 1;

 	var geometry = new THREE.Geometry();
 	var material = new THREE.LineBasicMaterial( { color: 0x404040 } );

 	for ( var i = - size; i <= size; i += step ) {

 		geometry.vertices.push( new THREE.Vector3( - size*10, - 60.14, i*10 ) );
 		geometry.vertices.push( new THREE.Vector3(   size*10, - 60.14, i*10 ) );

 		geometry.vertices.push( new THREE.Vector3( i*10, - 60.14, - size*10 ) );
 		geometry.vertices.push( new THREE.Vector3( i*10, - 60.14,   size*10 ) );

 	}

    var line = new THREE.Line( geometry, material, THREE.LinePieces );
    line.rotateOnAxis(new THREE.Vector3(1,0,0), 1.5);
    scene.add( line );

    //scene.add( dae );
    
    controls = new THREE.TrackballControls( camera );
    controls.target.z = 0;
    
    directionalLight = new THREE.DirectionalLight( 0xffffff, 0.50475 );
    directionalLight.position.set( 0, 100, 0 );
    directionalLight.castShadow = true;
    directionalLight.shadowMapWidth = 2048;
    directionalLight.shadowMapHeight = 2048;
    
    var d = 300; 
    
    directionalLight.shadowCameraLeft = -d/2;
    directionalLight.shadowCameraRight = d/2;
    directionalLight.shadowCameraTop = d/2;
    directionalLight.shadowCameraBottom = -d/2;
    // directionalLight.shadowCameraFar = 350;
    
    scene.add( directionalLight );
    
    var hemiLight = new THREE.HemisphereLight( 0xffffff, 0xffffff, 1.5 );
    hemiLight.color.setRGB( 0.75, 0.75, 0.75 );
    hemiLight.groundColor.setRGB( 0.5, 0.5, 0.5 );
    hemiLight.position.y = 5;
    scene.add( hemiLight );
    
    particleLight = new THREE.Mesh( new THREE.SphereGeometry( 4, 8, 8 ), 
				    new THREE.MeshBasicMaterial( { color: 0xffffff } ) );
    
    window.addEventListener( 'resize', onWindowResize, false );
    loadSubjectFromSurf(0);
}
function onWindowResize() {

 	camera.aspect = window.innerWidth / window.innerHeight;
 	camera.updateProjectionMatrix();

 	renderer.setSize( window.innerWidth, window.innerHeight );

 }

 var t = 0;
 var clock = new THREE.Clock();

 function animate() {

 	var delta = clock.getDelta();

 	requestAnimationFrame( animate );

 	if ( t > 1 ) t = 0;

 	if ( skin ) {

 		for ( var i = 0; i < skin.morphTargetInfluences.length; i++ ) {

			skin.morphTargetInfluences[ i ] = 0;

		}

		skin.morphTargetInfluences[ Math.floor( t * 30 ) ] = 1;

		t += delta;

	}

	render();
}

function render() {

	var timer = Date.now() * 0.0005;

	var delta = clock.getDelta();
	controls.update( delta );

	directionalLight.position.copy( camera.position );

	if (startAnimation) {
		camera.position.x = Math.cos( timer ) * 4;
		camera.position.y = 2;
		camera.position.z = Math.sin( timer ) * 4;

		camera.lookAt( scene.position );
	}

	if (morphNow)
		morph();

	checkRotation();

	renderer.render( scene, camera );

}

var morphTime = 0;
var startLH;
var startRH;
function morph() {
   if (morphTime == 0) {
   	 // make a copy of the start
   	 startLH = lh;
   	 startRH = rh;
   }

   morphTime += 0.002;
   for(var i = 0; i < lh.vertices.length; i++) {
   	   lh.vertices[i].x = (1.0-morphTime)*startLH.vertices[i].x + morphTime*morphlh.vertices[i].x;
	   lh.vertices[i].y = (1.0-morphTime)*startLH.vertices[i].y + morphTime*morphlh.vertices[i].y;
	   lh.vertices[i].z = (1.0-morphTime)*startLH.vertices[i].z + morphTime*morphlh.vertices[i].z;
   	   rh.vertices[i].x = (1.0-morphTime)*startRH.vertices[i].x + morphTime*morphrh.vertices[i].x;
	   rh.vertices[i].y = (1.0-morphTime)*startRH.vertices[i].y + morphTime*morphrh.vertices[i].y;
	   rh.vertices[i].z = (1.0-morphTime)*startRH.vertices[i].z + morphTime*morphrh.vertices[i].z;
   }	

   lh.verticesNeedUpdate = true;
   rh.verticesNeedUpdate = true;

   if (morphTime >= 1) {
   	  morphNow = false;
   	  morphTime = 0;
   }
}


function checkRotation(){

	var x = camera.position.x,
	y = camera.position.y,
	z = camera.position.z;

	if (keyboard.pressed("left")){ 
		camera.position.x = x * Math.cos(rotSpeed) + z * Math.sin(rotSpeed);
		camera.position.z = z * Math.cos(rotSpeed) - x * Math.sin(rotSpeed);
		startAnimation = false;
	} else if (keyboard.pressed("right")){
		camera.position.x = x * Math.cos(rotSpeed) - z * Math.sin(rotSpeed);
		camera.position.z = z * Math.cos(rotSpeed) + x * Math.sin(rotSpeed);
		startAnimation = false;
	} else if (keyboard.pressed("p")){
		startAnimation = !startAnimation;
	} else if (keyboard.pressed("up")) {
		camera.position.x = (x * Math.cos(0) - z * Math.sin(0))*0.95;
		camera.position.z = (z * Math.cos(0) + x * Math.sin(0))*0.95;					
	} else if (keyboard.pressed("down")) {
		camera.position.x = (x * Math.cos(0) - z * Math.sin(0))*1.05;
		camera.position.z = (z * Math.cos(0) + x * Math.sin(0))*1.05;					
	} else if (keyboard.pressed("w")) {
		usewireframe = !usewireframe;
		loadAverage();
	}

	camera.lookAt(scene.position);

} 




		</script>
	</body>
</html>
