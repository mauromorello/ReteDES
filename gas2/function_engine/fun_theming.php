<?php
 
//THEME WINTER
function theme_winter_css(){
    global $RG_addr;
    return "<style> body {background-image:url('".$RG_addr["img_theme_winter"]."');background-repeat:no-repeat;}</style>";   
} 
function theme_winter_javascript(){
    global $RG_addr;
    return "
      <script type=\"text/javascript\" src=\"".$RG_addr["js_three"]."\"></script>
      <script type=\"text/javascript\" src=\"".$RG_addr["js_snow"]."\"></script>
     
        <script>

            var SCREEN_WIDTH = window.innerWidth;
            var SCREEN_HEIGHT = 360;

            var container;

            var particle;

            var camera;
            var scene;
            var renderer;

            var mouseX = 0;
            var mouseY = 0;

            var windowHalfX = window.innerWidth / 2;
            var windowHalfY = window.innerHeight / 2;
            
            var particles = []; 
            var particleImage = new Image();//THREE.ImageUtils.loadTexture( \"images/ParticleSmoke.png\" );
            particleImage.src = '".$RG_addr["img_particle_smoke"]."'; 

            init();
        
            function init() {

                container = document.createElement('div');
                document.body.appendChild(container);

                camera = new THREE.PerspectiveCamera( 75, SCREEN_WIDTH / SCREEN_HEIGHT, 1, 10000 );
                camera.position.z = 1000;

                scene = new THREE.Scene();
                scene.add(camera);
                    
                renderer = new THREE.CanvasRenderer();
                renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
                var material = new THREE.ParticleBasicMaterial( { map: new THREE.Texture(particleImage) } );
                    
                for (var i = 0; i < 100; i++) {

                    particle = new Particle3D( material);
                    particle.position.x = Math.random() * 2000 - 1000;
                    particle.position.y = Math.random() * 2000 - 1000;
                    particle.position.z = Math.random() * 2000 - 1000;
                    particle.scale.x = particle.scale.y =  1;
                    scene.add( particle );
                    
                    particles.push(particle); 
                }

                container.appendChild( renderer.domElement );
                renderer.domElement.style.position = 'absolute';
                renderer.domElement.style.left = '0 px';
                renderer.domElement.style.top = '0px';
                renderer.domElement.style.zIndex = '-10';
                renderer.domElement.style.display = 'block';

    
                document.addEventListener( 'mousemove', onDocumentMouseMove, false );
                //document.addEventListener( 'touchstart', onDocumentTouchStart, false );
                //document.addEventListener( 'touchmove', onDocumentTouchMove, false );
                
                setInterval( loop, 1000 / 60 );
                
            }
            
            function onDocumentMouseMove( event ) {

                mouseX = event.clientX - windowHalfX;
                mouseY = event.clientY - windowHalfY;
            }

            function onDocumentTouchStart( event ) {

                if ( event.touches.length == 1 ) {

                    event.preventDefault();

                    mouseX = event.touches[ 0 ].pageX - windowHalfX;
                    mouseY = event.touches[ 0 ].pageY - windowHalfY;
                }
            }

            function onDocumentTouchMove( event ) {

                if ( event.touches.length == 1 ) {

                    event.preventDefault();

                    mouseX = event.touches[ 0 ].pageX - windowHalfX;
                    mouseY = event.touches[ 0 ].pageY - windowHalfY;
                }
            }

            //

            function loop() {

            for(var i = 0; i<particles.length; i++)
                {

                    var particle = particles[i]; 
                    particle.updatePhysics(); 
    
                    with(particle.position)
                    {
                        if(y<-1000) y+=2000; 
                        if(x>1000) x-=2000; 
                        else if(x<-1000) x+=2000; 
                        if(z>1000) z-=2000; 
                        else if(z<-1000) z+=2000; 
                    }                
                }
            
                camera.position.x += ( mouseX - camera.position.x ) * 0.05;
                camera.position.y += ( - mouseY - camera.position.y ) * 0.05;
                camera.lookAt(scene.position); 

                renderer.render( scene, camera );

                
            }

        </script>";   
} 


//THEME WINTER
function theme_spring_css(){
    global $RG_addr;
    return "<style> body {background-image:url('".$RG_addr["img_theme_spring"]."');background-repeat:no-repeat;}</style>";   
} 
function theme_spring_javascript(){
    global $RG_addr;
    return "
        <script src=\"".$RG_addr["js_three"]."\"></script>
        <script src=\"".$RG_addr["js_birds"]."\"></script>
        <script src=\"".$RG_addr["js_request_anim_frame"]."\"></script>
        
        
        <script>

            

            var Boid = function() {

                var vector = new THREE.Vector3(),
                _acceleration, _width = 500, _height = 500, _depth = 200, _goal, _neighborhoodRadius = 100,
                _maxSpeed = 4, _maxSteerForce = 0.1, _avoidWalls = false;

                this.position = new THREE.Vector3();
                this.velocity = new THREE.Vector3();
                _acceleration = new THREE.Vector3();

                this.setGoal = function ( target ) {

                    _goal = target;

                }

                this.setAvoidWalls = function ( value ) {

                    _avoidWalls = value;

                }

                this.setWorldSize = function ( width, height, depth ) {

                    _width = width;
                    _height = height;vector
                    _depth = depth;

                }

                this.run = function ( boids ) {

                    if ( _avoidWalls ) {

                        vector.set( - _width, this.position.y, this.position.z );
                        vector = this.avoid( vector );
                        vector.multiplyScalar( 5 );
                        _acceleration.addSelf( vector );

                        vector.set( _width, this.position.y, this.position.z );
                        vector = this.avoid( vector );
                        vector.multiplyScalar( 5 );
                        _acceleration.addSelf( vector );

                        vector.set( this.position.x, - _height, this.position.z );
                        vector = this.avoid( vector );
                        vector.multiplyScalar( 5 );
                        _acceleration.addSelf( vector );

                        vector.set( this.position.x, _height, this.position.z );
                        vector = this.avoid( vector );
                        vector.multiplyScalar( 5 );
                        _acceleration.addSelf( vector );

                        vector.set( this.position.x, this.position.y, - _depth );
                        vector = this.avoid( vector );
                        vector.multiplyScalar( 5 );
                        _acceleration.addSelf( vector );

                        vector.set( this.position.x, this.position.y, _depth );
                        vector = this.avoid( vector );
                        vector.multiplyScalar( 5 );
                        _acceleration.addSelf( vector );

                    }/* else {

                        this.checkBounds();

                    }
                    */

                    if ( Math.random() > 0.5 ) {

                        this.flock( boids );

                    }

                    this.move();

                }

                this.flock = function ( boids ) {

                    if ( _goal ) {

                        _acceleration.addSelf( this.reach( _goal, 0.005 ) );

                    }

                    _acceleration.addSelf( this.alignment( boids ) );
                    _acceleration.addSelf( this.cohesion( boids ) );
                    _acceleration.addSelf( this.separation( boids ) );

                }

                this.move = function () {

                    this.velocity.addSelf( _acceleration );

                    var l = this.velocity.length();

                    if ( l > _maxSpeed ) {

                        this.velocity.divideScalar( l / _maxSpeed );

                    }

                    this.position.addSelf( this.velocity );
                    _acceleration.set( 0, 0, 0 );

                }

                this.checkBounds = function () {

                    if ( this.position.x >   _width ) this.position.x = - _width;
                    if ( this.position.x < - _width ) this.position.x =   _width;
                    if ( this.position.y >   _height ) this.position.y = - _height;
                    if ( this.position.y < - _height ) this.position.y =  _height;
                    if ( this.position.z >  _depth ) this.position.z = - _depth;
                    if ( this.position.z < - _depth ) this.position.z =  _depth;

                }

                //

                this.avoid = function ( target ) {

                    var steer = new THREE.Vector3();

                    steer.copy( this.position );
                    steer.subSelf( target );

                    steer.multiplyScalar( 1 / this.position.distanceToSquared( target ) );

                    return steer;

                }

                this.repulse = function ( target ) {

                    var distance = this.position.distanceTo( target );

                    if ( distance < 150 ) {

                        var steer = new THREE.Vector3();

                        steer.sub( this.position, target );
                        steer.multiplyScalar( 0.5 / distance );

                        _acceleration.addSelf( steer );

                    }

                }

                this.reach = function ( target, amount ) {

                    var steer = new THREE.Vector3();

                    steer.sub( target, this.position );
                    steer.multiplyScalar( amount );

                    return steer;

                }

                this.alignment = function ( boids ) {

                    var boid, velSum = new THREE.Vector3(),
                    count = 0;

                    for ( var i = 0, il = boids.length; i < il; i++ ) {

                        if ( Math.random() > 0.6 ) continue;

                        boid = boids[ i ];

                        distance = boid.position.distanceTo( this.position );

                        if ( distance > 0 && distance <= _neighborhoodRadius ) {

                            velSum.addSelf( boid.velocity );
                            count++;

                        }

                    }

                    if ( count > 0 ) {

                        velSum.divideScalar( count );

                        var l = velSum.length();

                        if ( l > _maxSteerForce ) {

                            velSum.divideScalar( l / _maxSteerForce );

                        }

                    }

                    return velSum;

                }

                this.cohesion = function ( boids ) {

                    var boid, distance,
                    posSum = new THREE.Vector3(),
                    steer = new THREE.Vector3(),
                    count = 0;

                    for ( var i = 0, il = boids.length; i < il; i ++ ) {

                        if ( Math.random() > 0.6 ) continue;

                        boid = boids[ i ];
                        distance = boid.position.distanceTo( this.position );

                        if ( distance > 0 && distance <= _neighborhoodRadius ) {

                            posSum.addSelf( boid.position );
                            count++;

                        }

                    }

                    if ( count > 0 ) {

                        posSum.divideScalar( count );

                    }

                    steer.sub( posSum, this.position );

                    var l = steer.length();

                    if ( l > _maxSteerForce ) {

                        steer.divideScalar( l / _maxSteerForce );

                    }

                    return steer;

                }

                this.separation = function ( boids ) {

                    var boid, distance,
                    posSum = new THREE.Vector3(),
                    repulse = new THREE.Vector3();

                    for ( var i = 0, il = boids.length; i < il; i ++ ) {

                        if ( Math.random() > 0.6 ) continue;

                        boid = boids[ i ];
                        distance = boid.position.distanceTo( this.position );

                        if ( distance > 0 && distance <= _neighborhoodRadius ) {

                            repulse.sub( this.position, boid.position );
                            repulse.normalize();
                            repulse.divideScalar( distance );
                            posSum.addSelf( repulse );

                        }

                    }

                    return posSum;

                }

            }

        </script>

        <script>

            var SCREEN_WIDTH = window.innerWidth,
            //SCREEN_HEIGHT = window.innerHeight,
            SCREEN_HEIGHT = 360,
            SCREEN_WIDTH_HALF = SCREEN_WIDTH  / 2,
            SCREEN_HEIGHT_HALF = SCREEN_HEIGHT / 2;

            var SCR_START = 0,
                SCR_END = 0,
                CAMERA_POS = 0;
            
            var camera, scene, renderer,
            birds, bird;

            var boid, boids;

            var stats;

            init();
            animate();

            function init() {

                camera = new THREE.PerspectiveCamera( 75, SCREEN_WIDTH / SCREEN_HEIGHT, 1, 10000 );
                camera.position.z = 450;

                scene = new THREE.Scene();

                birds = [];
                boids = [];

                for ( var i = 0; i < 40; i ++ ) {

                    boid = boids[ i ] = new Boid();
                    boid.position.x = Math.random() * 400 - 200;
                    boid.position.y = Math.random() * 400 - 200;
                    boid.position.z = Math.random() * 400 - 200;
                    boid.velocity.x = Math.random() * 2 - 1;
                    boid.velocity.y = Math.random() * 2 - 1;
                    boid.velocity.z = Math.random() * 2 - 1;
                    boid.setAvoidWalls( true );
                    boid.setWorldSize( 500, 500, 400 );

                    bird = birds[ i ] = new THREE.Mesh( new Bird(), new THREE.MeshBasicMaterial( { color:Math.random() * 0xffffff } ) );
                    bird.phase = Math.floor( Math.random() * 62.83 );
                    bird.position = boids[ i ].position;
                    bird.doubleSided = true;
                    // bird.scale.x = bird.scale.y = bird.scale.z = 10;
                    scene.add( bird );


                }

                renderer = new THREE.CanvasRenderer();
                // renderer.autoClear = false;
                //renderer.setSize( SCREEN_WIDTH - SCR_END, SCREEN_HEIGHT );
                renderer.setSize( SCREEN_WIDTH - SCR_END, SCREEN_HEIGHT );
                document.addEventListener( 'mousemove', onDocumentMouseMove, false );
                document.body.appendChild( renderer.domElement );

                
                renderer.domElement.style.position = 'absolute';
                renderer.domElement.style.left = '0 px';
                renderer.domElement.style.top = '0px';
                renderer.domElement.style.zIndex = '-10';
                renderer.domElement.style.display = 'block';
                

            }

            function onDocumentMouseMove( event ) {

                var vector = new THREE.Vector3( event.clientX - SCREEN_WIDTH_HALF, - event.clientY + SCREEN_HEIGHT_HALF, 0 );

                for ( var i = 0, il = boids.length; i < il; i++ ) {

                    boid = boids[ i ];

                    vector.z = boid.position.z;

                    boid.repulse( vector );

                }

            }

            //

            function animate() {

                requestAnimationFrame( animate );

                render();
                

            }

            function render() {

                for ( var i = 0, il = birds.length; i < il; i++ ) {

                    boid = boids[ i ];
                    boid.run( boids );

                    bird = birds[ i ];

                    color = bird.material.color;
                    color.r = color.g = color.b = ( 500 - bird.position.z ) / 1000;

                    bird.rotation.y = Math.atan2( - boid.velocity.z, boid.velocity.x );
                    bird.rotation.z = Math.asin( boid.velocity.y / boid.velocity.length() );

                    bird.phase = ( bird.phase + ( Math.max( 0, bird.rotation.z ) + 0.1 )  ) % 62.83;
                    bird.geometry.vertices[ 5 ].position.y = bird.geometry.vertices[ 4 ].position.y = Math.sin( bird.phase ) * 5;

                }

                
                renderer.render( scene, camera );

            }

        </script>";   
}

//THEME GONG
function theme_gong_javascript(){
global $RG_addr;
$h="
<script>           
init()

function init() {

                
                var canvas = document.createElement('canvas');
                canvas.style.position = 'absolute';
                canvas.style.left = '0 px';
                canvas.style.top = '0px';
                canvas.style.width = '100%';
                canvas.style.height = '100%';
                canvas.style.zIndex = '-10';
              
                document.getElementsByTagName('body')[0].appendChild(canvas);

            }





            /**
             * Hypnos by Hakim El Hattab (@hakimel, http://hakim.se)
             *
             * Inspired by http://patakk.tumblr.com/post/33304597365
             */
            (function() {

                var canvas = document.querySelector( 'canvas' ),
                    context = canvas.getContext( '2d' ),

                    width = window.innerWidth * 0.7,
                    height = window.innerHeight * 0.7,

                    radius = Math.min( width, height ) * 0.5,

                    // Number of layers
                    quality = 180,

                    // Layer instances
                    layers = [],

                    // Width/height of layers
                    layerSize = radius * 0.25,

                    // Layers that overlap to create the infinity illusion
                    layerOverlap = Math.round( quality * 0.1 );

                function initialize() {

                    for( var i = 0; i < quality; i++ ) {
                        layers.push({
                            x: width/2 + Math.sin( i / quality * 2 * Math.PI ) * ( radius - layerSize ),
                            y: height/2 + Math.cos( i / quality * 2 * Math.PI ) * ( radius - layerSize ),
                            r: ( i / quality ) * Math.PI
                        });
                    }

                    resize();
                    update();

                }

                function resize() {

                    canvas.width = width;
                    canvas.height = height;

                }

                function update() {

                    requestAnimationFrame( update );

                    step();
                    clear();
                    paint();

                }

                // Takes a step in the simulation
                function step () {

                    for( var i = 0, len = layers.length; i < len; i++ ) {

                        layers[i].r += 0.01;

                    }

                }

                // Clears the painting
                function clear() {

                    context.clearRect( 0, 0, canvas.width, canvas.height );

                }

                // Paints the current state
                function paint() {

                    // Number of layers in total
                    var layersLength = layers.length;

                    // Draw the overlap layers
                    for( var i = layersLength - layerOverlap, len = layersLength; i < len; i++ ) {

                        context.save();
                        context.globalCompositeOperation = 'destination-over';
                        paintLayer( layers[i] );
                        context.restore();

                    }

                    // Cut out the overflow layers using the first layer as a mask
                    context.save();
                    context.globalCompositeOperation = 'destination-in';
                    paintLayer( layers[0], true );
                    context.restore();

                    // // Draw the normal layers underneath the overlap
                    for( var i = 0, len = layersLength; i < len; i++ ) {

                        context.save();
                        context.globalCompositeOperation = 'destination-over';
                        paintLayer( layers[i] );
                        context.restore();

                    }

                }

                // Pains one layer
                function paintLayer( layer, mask ) {
                    size = layerSize + ( mask ? 10 : 0 );
                    size2 = size / 2;

                    context.translate( layer.x, layer.y );
                    context.rotate( layer.r );

                    // No stroke if this is a mask
                    if( !mask ) {
                        context.strokeStyle = '#000';
                        context.lineWidth = 1;
                        context.strokeRect( -size2, -size2, size, size );
                    }

                    context.fillStyle = '#fff';
                    context.fillRect( -size2, -size2, size, size );

                }

                /**
                 * rAF polyfill.
                 */
                (function() {
                    var lastTime = 0;
                    var vendors = ['ms', 'moz', 'webkit', 'o'];
                    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
                        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
                        window.cancelAnimationFrame = 
                          window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
                    }

                    if (!window.requestAnimationFrame)
                        window.requestAnimationFrame = function(callback, element) {
                            var currTime = new Date().getTime();
                            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
                            var id = window.setTimeout(function() { callback(currTime + timeToCall); }, 
                              timeToCall);
                            lastTime = currTime + timeToCall;
                            return id;
                        };

                    if (!window.cancelAnimationFrame)
                        window.cancelAnimationFrame = function(id) {
                            clearTimeout(id);
                        };
                }());

                initialize();

            })();


        
</script>";
return $h;    
    
} 

//THEME NIGHT
function theme_night_css(){
    global $RG_addr;
    return "<style type=\"text/css\"> 
                 html {-webkit-filter: hue-rotate(180deg) invert(1);}
            </style>";   
} 

//THEME PAPER_1
function theme_paper_1_css(){
    global $RG_addr;
    $border = "border:1px solid rgba(50,50,50,.8);"; 
    return "<style> 
                    body {background-image: url(".$RG_addr["img_theme_paper_1"]."); background-repeat: no-repeat; background-attachment: fixed; }                                                 
                    .rg_widget {".$border.";
                                box-shadow: 3px 3px 3px rgba(0,0,0,.5);}
                    .ui-accordion-content-active  {border: 0; background: transparent !important}
                    .ui-accordion-header  {border: 0; background:transparent !important}
                    #navigation {background:rgba(255,255,255,.6); ".$border."}
           </style>";   
} 


//THEME RAIN
function theme_rain_css(){
    global $RG_addr;
    $border = "border:1px solid rgba(50,50,50,.8);"; 
    return "<style> 
                    body {background-image: url(".$RG_addr["img_theme_rain"]."); background-repeat: no-repeat; background-attachment: fixed; }                                                 
                    .rg_widget {background:rgba(205,205,255,.8);
                                ".$border.";
                                box-shadow: 3px 3px 3px rgba(0,0,0,.5);}
                    .ui-accordion-content-active  {border: 0; background: transparent !important}
                    .ui-accordion-header  {border: 0; background:transparent !important}
                    #navigation {background:rgba(205,205,255,.8); ".$border."}
           </style>";   
} 


Function theme_sepia_css(){
    global $RG_addr;

return "<style type=\"text/css\">
            html {-webkit-filter: sepia();}
        </style>";  
}

Function theme_drunken_css($amount){
    global $RG_addr;

return "<style type=\"text/css\">
            html {-webkit-filter: blur(".$amount."px);}
        </style>";  
}
  
?>