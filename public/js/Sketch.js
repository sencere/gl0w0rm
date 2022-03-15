import Firefly from 'Firefly';

window.onload = function() {
    function sketch_idnameofdiv(p) {
        var amountOfFireflies = 80;
        var particles = [];
        var attractors = [];
        var attractorState = true;
        //p5 = Object();
        var countdown = 60;
        var attracorsAllowed = 10;
        var readyButtonState = false;
        var readyTimerState = false;
        var readyAttractorState = false;
        var finishState = false;
        var startButtonConfiguration;
        var timer = 3;
        var predictions = [];
        var attractorCount = 0;
        var completed = false;
        var smCircleX = 0;
        var smCircleY = 0;
        var secondTimer = 10;
        var variance = 0;
        var mean = 0;
        var loading = false;
        var circleMiddlePoint = {x: 0, y: 0};

        // Options request
        var target = '';
        var question = '';
        var options = {};
        var time = 0;


        // Result request
        var result = '';
        var confidence = 0;
        var option = '';
        var circleX = 0;
        var circleY = 0; 


        var listAngles = [];
        var timerTextColor = [255, 255, 255];

        setOptions = function(responseOptions) {
            target = responseOptions.target;
            time = responseOptions.time;            
            question = responseOptions.question;
            options = responseOptions.options;
        }

        setResult = function(responseResult) {
            result = responseResult.result,
            confidence = responseResult.confidence,
            option = responseResult.option,
            circleX = responseResult.circleX,
            circleY = responseResult.circleY
        }




        checkReadyState = function(mouseX, mouseY, p5) {
            if (!completed) {
                if (mouseX > startButtonConfiguration.buttonX && mouseX < startButtonConfiguration.buttonX + startButtonConfiguration.rectWidth) {
                    if (mouseY > startButtonConfiguration.buttonY && mouseY < startButtonConfiguration.buttonY + startButtonConfiguration.rectHeight) {
                        readyTimerState = true;
                        readyButtonState = true;
                        startFirstTimer(p5);
                    }
                }
            }
        }

        updateTimer = function(counter) {
            timer = counter;
        };

        displayOnlyResult = function(confidenceScore, middleText, circleX, circleY, p5) {
            /*var width = p5.width;
            var height = p5.height;
            var count = 0;
            smCircleX = circleX;
            smCircleY = circleY;
            var countOptions = Object.keys(options).length;
            var circleDiameter = (width/2) - (1/20 * width);
            var radius = circleDiameter/2;
            var minValue = Math.pow(10,5);
            var winner = '';
            var distanceSum = 1;
            var optionKey = 0;
            Object.entries(options).forEach(([key, value]) => {
                var angle = listAngles[countOptions][count];
                var x = (width/2) + radius * p5.cos(-1 * angle);
                var y = (height/2) + radius * p5.sin(-1 * angle);

                var distance = p5.dist(x, y, smCircleX, smCircleY);
                distanceSum += distance;

                if (distance < minValue) {
                    winner = value;
                    minValue = distance;
                    optionKey = key;
                }
                this.minValue = distance;
                count++;
            });

            var crowdConfidenceScore = (radius - minValue) * 100 / radius;
            crowdConfidenceScore = parseFloat(crowdConfidenceScore.toFixed(2));

            // QUESTION
            p5.textSize(30);
            p5.text(target + '\n' + question, width/2, height/4);

            // CROWD PREDICTION
            p5.fill(255, 204, 0);
            p5.textSize(20);
            p5.textAlign(p5.CENTER, p5.CENTER);
            p5.text('Confidence: ' + crowdConfidenceScore + '%', width/2, (height/2) - height/15);

            p5.noStroke();
            p5.textSize(30);
            p5.textAlign(p5.CENTER, p5.CENTER);
            p5.text('Crowd prediction: \n' + winner, width/2, height/2);
            p5.noLoop();*/
        };

        startFirstTimer = function(p5) {
            this.timerTextColor = [255, 0, 0];
            var timerInside = timer;
            var width = p5.width;
            var height = p5.height;
            var myVar = setInterval(function() {
                timerInside--;
                updateTimer(timerInside);
                if (timerInside < 1) {
                    clearInterval(myVar);
                    updateTimer('GO!');
                    timerTextColor = [0, 255, 0];
                    secondTimer = time;
                    //getPredictions();
                    setTimeout(function()  {
                        startSecondTimer(p5);
                        readyAttractorState = true;
                    }, 1000);
                }
            }, 1000);
        }

        startSecondTimer = function(p5) {            
            var timer = secondTimer;
            timerTextColor = [255, 255, 255];
            var width = p5.width;
            var height = p5.height;
            var myVar = setInterval(() => {
                timer--;
                updateTimer(timer);

                if (timer < 1) {
                    clearInterval(myVar);
                    updateTimer('');
                    finishState = true;
                    readyAttractorState = false;

                    predictions = attractors;
                }
            }, 1000);
        };



        p.setPredictionCompleted = function() {
            this.readyButtonState = true;
            this.readyTimerState = true;
            this.readyAttractorState = false;
            this.finishState = true;
            this.completed = true;
            this.timer = '';
        }

        p.calculatingListAngles = function() {
            var angles = 6;
            // calculate listAngles
            for (var i = 0; i <= angles; i++) {
                var innerArray = [];

                if (i > 1) {
                    for (var j = 0;j < i; j++) {
                        var angle = 0;
                        angle = (2 * (j) * (180 / i)) + 90;
                        angle = angle * this.PI / 180;
                        innerArray.push(angle);
                    }

                    listAngles.push(innerArray);
                } else {
                    listAngles.push(innerArray.length);
                }
            }
        }

        sleep = function(milliSeconds, p5) {
            // interval for checking early looping
            var myInterval = setInterval(() => {
                if(loading) {
                    p5.loop();
                }
            }, 1000);

            // timeout for start the loop again (loading funciton)
            setTimeout(function() {
                clearInterval(myInterval);
                p5.loop()
            }, milliSeconds);
        };

        p.setup = function () {

            var self = this;

            // Font
            this.textFont('Nunito');

            // Canvas element
            var canvas = document.getElementById('landgrass');
            var postId = canvas.getAttribute('data-id');
            var canvasWidth = canvas.clientWidth;
            var canvasHeight = this.height;
            p.createCanvas(canvasWidth,canvasWidth);

            // Option angles calculation
            this.calculatingListAngles();

            // Options
            this.httpGet('/post/options/' + postId, 'json', false, function(response) {
                setOptions(response);
            });

            // Result
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            this.httpPost(
                '/results/result/' + postId, 
                {width: canvasWidth, height: canvasHeight, _token: token},
                function(response) {
                    setResult(response);
                },
                function(error) {
                    console.log(error);
                }
            );

            // mouse click 
            if (!completed) {
                this.mousePressed = function() {
                    //this.addAttractor(p5.mouseX, p5.mouseY, p5, false);
                    checkReadyState(this.mouseX, this.mouseY, this);
                };
            }

            this.fill(16);
            this.textSize(20);
            this.textAlign(this.CENTER, this.CENTER);
            this.text('Loading...', this.width/2, this.width/2);
            //sleep(1000, this);
            //this.noLoop();
        }

        p.draw = function () {
            var self = this;
            var count = 0;
            var radiusSmallerCircle = 10;

            // Canvas Background
            var backgroundColor = [22, 22, 22];
            this.background(backgroundColor);

            // Big Circle
            var circleColor = [20, 20, 20];
            var circleDiameter = (this.height/2);
            var radius = circleDiameter/2;
            var countOptions = Object.keys(options).length;

            this.fill(circleColor);
            this.noStroke();
            this.circle(this.width/2, this.height/2, circleDiameter);
            this.strokeWeight(0);

            if (result) {
                this.setPredictionCompleted();
            } else if (result === null) {
                return;
            }



            // DISPLAY OPTION LOGIC + DISPLAY SMALLER CIRCLE
            if (readyTimerState && !completed) {
                // let circleColor = p5.color(5, 8, 163);
                var circleColor = this.color(217, 255, 255);
                circleColor.setAlpha(128 + 128 * (this.sin(this.millis() / 1000) + 0.7));

                this.fill(circleColor);
                this.noStroke();
                this.circle(smCircleX, smCircleY + 25, circleDiameter/4);
                this.strokeWeight(0);

                // display of options
                Object.entries(options).forEach(([key, value]) => {
                    var angle = listAngles[countOptions][count];
                    var x = (this.width/2) + radius * this.cos(-1 * angle);
                    var y = (this.height/2) + radius * this.sin(-1 * angle);

                    this.fill(255);
                    this.textSize(20);
                    this.textAlign(this.CENTER, this.CENTER);
                    this.text(value, x, y);
                    count++;
                });
            }

            // Question position in the middle
            this.noStroke();
            this.fill(timerTextColor);
            this.textSize(30);
            this.textAlign(this.CENTER, this.CENTER);
            if (!readyTimerState) {
                this.text(target + '\n' + question, this.width/2, this.height/2);
            }


            // Firefly implementation
            if (readyTimerState && !finishState) {
                particles.push(new Firefly(this.random(this.width), this.random(this.height), this));

                if (particles.length > amountOfFireflies) {
                    particles.splice(0, 1);
                }

                // Attractors
                for (var i = 0; i < attractors.length; i++) {
                    p5.fill(240,10,10,150);
                    p5.noStroke();
                    p5.circle(attractors[i].x, attractors[i].y, radiusSmallerCircle);
                }

                // Firefly rendering
                var particleSumX = this.widht/2;
                var particleSumY = this.height/2;
                var particleLength = particles.length;

                for (var i = 0; i < particleLength; i++) {
                    var particle = particles[i];
                    particleSumX += particles[0].pos.x;
                    particleSumY += particles[0].pos.y;
                    for (var j = 0; j < attractors.length; j++) {
                        particle.attracted(attractors[j]);
                    }
                    particle.update();
                    particle.show();
                }
            }

            // StartButton logic
            if (readyButtonState === false) {
                var startButton = new StartButton(this);
                startButtonConfiguration = startButton.getButtonConfiguration();
            }

            if (readyTimerState) {
                this.noStroke();
                this.fill(timerTextColor);
                this.textSize(30);
                this.textAlign(this.CENTER, this.CENTER);
                this.text(timer, this.width/2, this.height/2);
            }

            //if (this.finishState) {
            //    displayPredictionResults(p5, width, height);
            //}

            if (result) {
                displayOnlyResult(confidence, option, circleX, circleY, this);
                //this.noLoop();
            }
        }
    }
    new p5(sketch_idnameofdiv, 'landgrass');
};