window.onload = function () {
    function sketch(p) {
        var amountOfFireflies = 80;
        var particles = [];
        var attractors = [];
        var attractorState = true;
        var countdown = 60;
        var attracorsAllowed = 10;
        var readyButtonState = true;
        var readyTimerState = false;
        var readyAttractorState = false;
        var finishState = false;
        var startButtonConfiguration = null;
        var timer = 3;
        var predictions = [];
        var attractorCount = 0;
        var completed = false;
        var smCircleX = null;
        var smCircleY = null;
        var secondTimer = 10;
        var variance = 0;
        var mean = 0;
        var loading = false;
        var circleMiddlePoint = { x: 0, y: 0 };

        // Options request
        var target = "";
        var question = "";
        var options = {};
        var time = 0;
        var token = "";
        var postId = null;

        // Result request
        var result = null;
        var confidence = 0;
        var option = {};
        var circleX = 0;
        var circleY = 0;

        var listAngles = [];
        var timerTextColor = [255, 255, 255];

        // Predictions request
        var predictions = [];
        var mean = 0;
        var variance = 0;

        var url = "";

        var backgroundColor = [22, 22, 22];
        var circleColor = [20, 20, 20];

        assignPredictions = function (data) {
            var predictionsArr = [];

            Object.entries(data.predictions).forEach(([key, value]) => {
                predictionsArr[key] = value;
            });

            predictions = predictionsArr;
            mean = data.mean;
            variance = data.variance;
        };

        getPredictions = function (p5) {
            var data = {
                _token: token,
                width: p5.width,
                height: p5.height,
            };
            p5.httpPost(
                url + "/posts/predictions/" + postId,
                data,
                function (response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }

                    assignPredictions(response);
                },
                function (error) {
                    console.log(error);
                }
            );
        };

        setOptions = function (responseOptions) {
            target = responseOptions.target;
            time = responseOptions.time;
            question = responseOptions.question;
            options = responseOptions.options;
        };

        assignResult = (predictions, p5) => {
            predictions = JSON.parse(predictions);
            if (predictions.result) {
                result = predictions.result;
                confidence = predictions.confidence;
                option = predictions.option;
                circleX = predictions.circleX;
                circleY = predictions.circleY;

                displayOnlyResult(confidence, option, circleX, circleY, p5);
                setPredictionCompleted();
                p5.noLoop();
            }

            if (!result) {
                readyButtonState = false;
            }

            loading = true;
        };

        checkReadyState = function (mouseX, mouseY, p5) {
            if (!completed) {
                if (
                    mouseX > startButtonConfiguration.buttonX &&
                    mouseX <
                        startButtonConfiguration.buttonX +
                            startButtonConfiguration.rectWidth
                ) {
                    if (
                        mouseY > startButtonConfiguration.buttonY &&
                        mouseY <
                            startButtonConfiguration.buttonY +
                                startButtonConfiguration.rectHeight
                    ) {
                        readyTimerState = true;
                        readyButtonState = true;
                        startFirstTimer(p5);
                    }
                }
            }
        };

        updateTimer = function (counter) {
            timer = counter;
        };

        addAttractor = function (mouseX, mouseY, p5, botClick = true) {
            var attractorsAllowed =
                botClick || attractorCount < attracorsAllowed;
            var width = p5.width;
            var height = p5.height;
            var innerPostId = postId;
            var innerToken = token;

            if (
                attractorsAllowed &&
                mouseX > 0 &&
                mouseX < width &&
                mouseY > 0 &&
                mouseY < height &&
                readyAttractorState
            ) {
                attractors.push(p5.createVector(mouseX, mouseY));

                mouseX = parseInt(mouseX.toFixed(0));
                mouseY = parseInt(mouseY.toFixed(0));
                var time = typeof timer !== "string" ? parseInt(timer) : time;

                // sending attractor position
                var data = {
                    _token: innerToken,
                    postId: parseInt(innerPostId),
                    mouseX: mouseX,
                    mouseY: mouseY,
                    time: time,
                    width: width,
                    height: height,
                };

                if (!botClick) {
                    attractorCount++;
                    p5.httpPost(
                        url + "/predictions",
                        data,
                        function (response) {
                            console.log("attractor was added.");
                        },
                        function (error) {
                            console.log(error);
                        }
                    );
                }
            }
        };

        // prediction from player
        displayCircleMiddleText = function (
            p5,
            width,
            height,
            winner,
            confidenceScore,
            optionKey
        ) {
            var confidence = parseFloat(confidenceScore.toFixed(2));
            var option = parseInt(optionKey, 10);

            // Canvas Background
            p5.background(backgroundColor);

            // Big Circle
            var circleDiameter = p5.height / 2;
            p5.fill(circleColor);
            p5.noStroke();
            p5.circle(p5.width / 2, p5.height / 2, circleDiameter);
            p5.strokeWeight(0);

            p5.noStroke();
            p5.fill(0, 129, 255);
            p5.textSize(20);
            p5.textAlign(p5.CENTER, p5.CENTER);
            p5.text(
                "Confidence: " +
                    "\n" +
                    confidenceScore.toFixed(2) +
                    "%" +
                    "\n" +
                    "Your prediction: " +
                    "\n" +
                    winner,
                0,
                0,
                p5.width,
                p5.height
            );

            // Simple POST request with a JSON body using axios
            var data = {
                postId: parseInt(postId),
                confidence: confidenceScore.toFixed(2),
                option: optionKey,
                circleX: smCircleX,
                circleY: smCircleY,
                _token: token,
            };

            p5.httpPost(
                url + "/results",
                data,
                function (response) {
                    console.log("result added");
                },
                function (error) {
                    console.log(error);
                }
            );
            p5.noLoop();
        };

        displayOnlyResult = function (
            confidenceScore,
            middleText,
            circleX,
            circleY,
            p5
        ) {
            var width = p5.width;
            var height = p5.height;
            var count = 0;
            smCircleX = circleX;
            smCircleY = circleY;
            var countOptions = Object.keys(options).length;
            var circleDiameter = width / 2 - (1 / 20) * width;
            var radius = circleDiameter / 2;
            var minValue = Math.pow(10, 5);
            var winner = "";
            var optionKey = 0;
            Object.entries(options).forEach(([key, value]) => {
                var angle = listAngles[countOptions][count];
                var x = width / 2 + radius * p5.cos(-1 * angle);
                var y = height / 2 + radius * p5.sin(-1 * angle);
                var distance = p5.dist(x, y, smCircleX, smCircleY);

                if (distance < minValue) {
                    winner = value;
                    minValue = distance;
                    optionKey = key;
                }

                count++;
            });

            var crowdConfidenceScore = 100 - (minValue * 100) / circleDiameter;
            crowdConfidenceScore = Math.abs(confidenceScore);

            if (crowdConfidenceScore > 100) crowdConfidenceScore = "";

            // QUESTION
            p5.fill(255, 204, 0);
            p5.textSize(20);
            p5.textAlign(p5.CENTER, p5.CENTER);
            p5.text(
                target +
                    "\n" +
                    question +
                    "\n" +
                    "\n" +
                    "Confidence: " +
                    "\n" +
                    crowdConfidenceScore +
                    "%" +
                    "\n" +
                    "\n" +
                    "Crowd prediction:" +
                    "\n" +
                    winner,
                0,
                0,
                p5.width,
                p5.height
            );
            p5.noLoop();
        };

        startFirstTimer = function (p5) {
            this.timerTextColor = [255, 0, 0];
            var timerInside = timer;
            var width = p5.width;
            var height = p5.height;
            var myVar = setInterval(function () {
                timerInside--;
                updateTimer(timerInside);
                if (timerInside < 1) {
                    clearInterval(myVar);
                    updateTimer("GO!");
                    timerTextColor = [0, 129, 255];
                    secondTimer = time;
                    getPredictions(p5);
                    setTimeout(function () {
                        startSecondTimer(p5);
                        readyAttractorState = true;
                    }, 1000);
                }
            }, 1000);
        };

        startSecondTimer = function (p5) {
            var timer = secondTimer;
            timerTextColor = [255, 255, 255];
            var width = p5.width;
            var height = p5.height;
            var myVar = setInterval(() => {
                timer--;
                updateTimer(timer);

                if (typeof predictions[timer] !== "undefined") {
                    addAttractor(
                        predictions[timer].mouseX,
                        predictions[timer].mouseY,
                        p5
                    );
                }

                if (timer < 1) {
                    clearInterval(myVar);
                    updateTimer("");
                    finishState = true;
                    readyAttractorState = false;

                    predictions = attractors;
                }
            }, 1000);
        };

        setPredictionCompleted = function () {
            readyButtonState = true;
            readyTimerState = true;
            readyAttractorState = false;
            finishState = true;
            completed = true;
            timer = "";
        };

        calculatingListAngles = function (p5) {
            var angles = 6;
            // calculate listAngles
            for (var i = 0; i <= angles; i++) {
                var innerArray = [];

                if (i > 1) {
                    for (var j = 0; j < i; j++) {
                        var angle = 0;
                        angle = 2 * j * (180 / i) + 90;
                        angle = (angle * p5.PI) / 180;
                        innerArray.push(angle);
                    }

                    listAngles.push(innerArray);
                } else {
                    listAngles.push(innerArray.length);
                }
            }
        };

        sleep = function (milliSeconds, p5) {
            // interval for checking early looping
            var myInterval = setInterval(() => {
                if (loading) {
                    p5.loop();
                }
            }, 1000);

            // timeout for start the loop again (loading funciton)
            setTimeout(function () {
                clearInterval(myInterval);
            }, milliSeconds);
        };

        displayPredictionResults = (p5, width, height) => {
            var countOptions = Object.keys(options).length;
            var count = 0;
            var circleDiameter = width / 2 - (1 / 20) * width;
            var radius = circleDiameter / 2;
            var resultArr = [];
            var minValue = p5.pow(10, 5);
            var winner = "";
            var middleText = "";
            var percentagePerOption = 100 / countOptions;
            var optionKey = 0;

            if (predictions.length > 0) {
                Object.entries(options).forEach(([key, value]) => {
                    var angle = listAngles[countOptions][count];
                    var x = width / 2 + radius * p5.cos(-1 * angle);
                    var y = height / 2 + radius * p5.sin(-1 * angle);

                    var distance = p5.dist(x, y, smCircleX, smCircleY);

                    if (distance < minValue) {
                        winner = value;
                        minValue = distance;
                        optionKey = key;
                    }

                    count++;
                });

                var confidenceScore = 100 - (minValue * 100) / circleDiameter;
                confidenceScore = Math.abs(confidenceScore);

                displayCircleMiddleText(
                    p5,
                    width,
                    height,
                    winner,
                    confidenceScore,
                    optionKey
                );
                p5.noLoop();
            }
        };

        p.setup = function () {
            var self = this;

            // Font
            this.textFont("Nunito");

            // Canvas element
            var canvas = document.getElementById("landgrass");
            postId = canvas.getAttribute("data-id");
            url = canvas.getAttribute("data-url");
            var canvasWidth = canvas.clientWidth;
            var canvasHeight = this.height;

            smCircleX = canvasWidth / 2;
            smCircleY = canvasWidth / 2;

            p.createCanvas(canvasWidth, canvasWidth);

            // Option angles calculation
            calculatingListAngles(this);

            // Options
            this.httpGet(
                url + "/post/options/" + postId,
                "json",
                false,
                function (response) {
                    setOptions(response);
                }
            );

            // Result
            token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            this.httpPost(
                url + "/results/result/" + postId,
                { width: canvasWidth, height: canvasHeight, _token: token },
                function (response) {
                    assignResult(response, self);
                },
                function (error) {
                    console.log(error);
                }
            );

            // mouse click
            if (!completed) {
                this.mousePressed = function () {
                    addAttractor(this.mouseX, this.mouseY, this, false);
                    checkReadyState(this.mouseX, this.mouseY, this);
                };
            }

            this.fill(255);
            this.textSize(20);
            this.textAlign(this.CENTER, this.CENTER);
            this.text("Loading...", canvasWidth / 2, canvasHeight / 2);
            sleep(2000, this);
            this.noLoop();
        };

        p.draw = function () {
            var self = this;
            var count = 0;
            // var radiusSmallerCircle = 10;

            // Canvas Background
            this.background(backgroundColor);

            // Big Circle
            var circleDiameter = this.height / 2;
            var radius = circleDiameter / 2;
            var countOptions = Object.keys(options).length;
            var attractorRadius = circleDiameter / 30;

            this.fill(circleColor);
            this.noStroke();
            this.circle(this.width / 2, this.height / 2, circleDiameter);
            this.strokeWeight(0);

            if (result) {
                setPredictionCompleted();
            } else if (result === null) {
            }

            // DISPLAY OPTION LOGIC + DISPLAY SMALLER CIRCLE
            if (readyTimerState && !completed) {
                var smallerCircleColor = this.color(217, 255, 255);
                smallerCircleColor.setAlpha(
                    128 + 128 * (this.sin(this.millis() / 1000) + 0.7)
                );

                this.fill(smallerCircleColor);
                this.noStroke();
                this.circle(smCircleX, smCircleY, circleDiameter / 4);
                this.strokeWeight(0);

                // display of options
                Object.entries(options).forEach(([key, value]) => {
                    var angle = listAngles[countOptions][count];
                    var x = this.width / 2 + radius * this.cos(-1 * angle);
                    var y = this.height / 2 + radius * this.sin(-1 * angle);

                    this.fill(255);
                    this.textSize(20);
                    this.textAlign(this.CENTER, this.CENTER);
                    this.text(value, x, y);
                    // this.text(key, x, y + 20);
                    count++;
                });
            }

            // Question position in the middle
            this.noStroke();
            this.fill(timerTextColor);
            this.textSize(20);
            this.textAlign(this.CENTER, this.CENTER);
            if (!readyTimerState) {
                this.text(
                    target + "\n" + question,
                    0,
                    0,
                    this.width,
                    this.height
                );
            }

            // Firefly implementation
            if (readyTimerState && !finishState) {
                particles.push(
                    new Firefly(
                        this.random(this.width),
                        this.random(this.height),
                        this
                    )
                );

                if (particles.length > amountOfFireflies) {
                    particles.splice(0, 1);
                }

                // Attractors
                for (var i = 0; i < attractors.length; i++) {
                    this.fill(240, 10, 10, 150);
                    this.noStroke();
                    this.circle(
                        attractors[i].x,
                        attractors[i].y,
                        attractorRadius
                    );
                }

                // Firefly rendering
                var particleSumX = 0;
                var particleSumY = 0;
                var particleLength = particles.length;

                for (var i = 0; i < particleLength; i++) {
                    var particle = particles[i];
                    particleSumX += particles[0].pos.x;
                    particleSumY += particles[0].pos.y;
                    for (var j = 0; j < attractors.length; j++) {
                        particle.attracted(attractors[j], p5);
                    }
                    particle.update();
                    particle.show();
                }

                // smaller circle
                var concentrationX = particleSumX / particleLength;
                var concentrationY = particleSumY / particleLength;
                var movingFactor = 0.05;
                var rememberMovingFactor = 0;
                var allowedDistance = 2.5 * attractorRadius;
                var distanceConcentCircl = this.dist(
                    concentrationX,
                    concentrationY,
                    smCircleX,
                    smCircleY
                );

                if (mean === 0 && variance === 0) {
                    if (allowedDistance < distanceConcentCircl) {
                        rememberMovingFactor = movingFactor;
                        movingFactor = 0.8;
                    } else {
                        movingFactor = rememberMovingFactor;
                    }
                } else {
                    movingFactor =
                        4 *
                        Math.pow(
                            Math.exp(1),
                            (-1 * Math.pow(secondTimer - mean, 2)) / variance
                        );
                }

                if (readyAttractorState) {
                    if (smCircleX < concentrationX) {
                        smCircleXcheck = smCircleX + movingFactor;
                    } else {
                        smCircleXcheck = smCircleX - movingFactor;
                    }

                    if (smCircleY < concentrationY) {
                        smCircleYcheck = smCircleY + movingFactor;
                    } else {
                        smCircleYcheck = smCircleY - movingFactor;
                    }

                    var ropeDistance =
                        this.dist(
                            smCircleXcheck,
                            smCircleYcheck,
                            this.width / 2,
                            this.height / 2
                        ) <
                        radius + attractorRadius;

                    if (ropeDistance) {
                        smCircleX = smCircleXcheck;
                        smCircleY = smCircleYcheck;
                    }
                }
            }

            if (readyTimerState) {
                this.noStroke();
                this.fill(timerTextColor);
                this.textSize(30);
                this.textAlign(this.CENTER, this.CENTER);
                this.text(timer, this.width / 2, this.height / 2);
            }

            if (result === true) {
                displayOnlyResult(confidence, option, circleX, circleY, this);
                this.noLoop();
            }

            if (finishState) {
                displayPredictionResults(this, this.width, this.height);
            }

            // StartButton logic
            if (readyButtonState === false) {
                var startButton = new StartButton(this);
                startButtonConfiguration = startButton.getButtonConfiguration();
            }
        };
    }
    new p5(sketch, "landgrass");
    new PostBar();
};

function Firefly(x, y, p5) {
    this.pos = p5.createVector(x, y);
    this.prev = p5.createVector(x, y);
    this.angle = Math.random() * (2*Math.PI);
    this.length = 1;
    this.colorArray = [p5.color(136, 170, 0, p5.random(200, 255)), p5.color(255, 204, 0, p5.random(200, 255))];
    this.vel = p5.createVector(length * Math.cos(this.angle), length*Math.sin(this.angle)).setMag(p5.random(2,5));
    this.stroke = this.colorArray[Math.floor(Math.random() * this.colorArray.length)];
    this.acc = p5.createVector();

    this.update = function() {
        this.vel.add(this.acc);
        var length = 1;
        var angle = Math.random() * (2*Math.PI);
        this.vel.add(this.acc);
        this.vel.limit(5);
        this.pos.add(this.vel);
        this.acc.mult(0);
    };

    this.show = function() {
        p5.stroke(this.stroke);
        p5.strokeWeight(7);
        p5.line(this.pos.x, this.pos.y, this.prev.x, this.prev.y);
        // p5.fill(255,204, 0, p5.random(200, 255));
        // p5.circle(this.pos.x, this.pos.y, 10);
        this.prev.x = this.pos.x;
        this.prev.y = this.pos.y;
    };

    this.attracted = function(target, p5) {
        // var dir = target - this.pos
        var force = p5.Vector.sub(target, this.pos);
        var d = force.mag();
        d = 20;
        var G = 200;
        var strength = G / (d * d);
        force.setMag(strength);
        if (d < 20) {
            force.mult(-10);
        }
        this.acc.add(force);
    };

    return this;
}
function StartButton(p5) {
    rectWidth = 0;
    rectHeight = 0;
    inside = false;
    buttonX = 5;
    buttonY = 5;
    buttonRadius = 10;

    var rectWidth = rectWidth = p5.width - 10;
    var rectHeight = rectHeight = ((p5.height) / 10);
    var mouseX = p5.mouseX;
    var mouseY = p5.mouseY;

    if (mouseX > buttonX && mouseX < buttonX + rectWidth) {
        if (mouseY > buttonY && mouseY < buttonY + rectHeight) {
            inside = true;
        }
    }

    p5.noStroke();
    if (inside){
        p5.fill(163, 4, 4);
    } else {
        p5.fill(58, 79, 153);
    }

    var rect = p5.rect(buttonX, buttonY, rectWidth, rectHeight, buttonRadius);
    p5.fill(255);
    p5.textSize(20);
    p5.textAlign(p5.CENTER, p5.CENTER);

    if (inside){
        p5.text('Yes (click me)', rectWidth/2, rectHeight/2 + 5);
    } else {
        p5.text('Ready?', rectWidth/2, rectHeight/2 + 5);
    }

    this.getButtonConfiguration = function () {
        return {
            'rectWidth': rectWidth,
            'rectHeight': rectHeight,
            'buttonX': buttonX,
            'buttonY': buttonY,
            'buttonRadius': buttonRadius
        };
    }
}
function PostBar() {
    $votes = $('#votes');
    $upFill = $('#thumbsup-fill');
    $upEmpty = $('#thumbsup-empty');
    $downFill = $('#thumbsdown-fill');
    $downEmpty = $('#thumbsdown-empty');
    $upCounter = $('#up-counter');
    $downCounter = $('#down-counter');
    $subscribe = $('#subscribe');
    slug = $subscribe.data('slug');
    token = $('meta[name="csrf-token"]').attr('content');
    postId = $('#landgrass').data('id');
    urlVote = '/posts/' + postId + '/votes';
    urlSubscribe = '/subscription/' + slug;

    function requestAdd(type) {
        $.ajax({
            type: "POST",
            url: urlVote,
            data: {
                '_token':token,
                'type':type
            },
            success: function() {},
            dataType: 'json'
        });
    }

    function requestRemove(type) {
        $.ajax({
            type: "DELETE",
            url: urlVote,
            data: {
                '_token':token,
                'type':type
            },
            success: function() {},
            dataType: 'json'
        });
    }

    function requestSubscribe() {
        $.ajax({
            type: "POST",
            url: urlSubscribe,
            data: {
                '_token':token
            },
            success: function() {},
            dataType: 'json'
        });
    }

    function requestUnsubscribe() {
        $.ajax({
            type: "DELETE",
            url: urlSubscribe,
            data: {
                '_token':token
            },
            success: function() {},
            dataType: 'json'
        });
    }

    function requestAddUp() {
        requestAdd('up');
    }

    function requestAddDown() {
        requestAdd('down');
    }

    function requestRemoveUp() {
        requestRemove('up');
    }

    function requestRemoveDown() {
        requestRemove('down');
    }

    function changeCounter(state) {
        var upperCount = parseInt($upCounter.html());
        var downCount = parseInt($downCounter.html());

        if ($votes.hasClass('up')) {
            $upCounter.html(++upperCount);
            if (state === 'down') {
                $downCounter.html(--downCount);
            }
            requestAddUp();
        } else if ($votes.hasClass('down')) {
            $downCounter.html(++downCount);
            if (state === 'up') {
                $upCounter.html(--upperCount);
            }
            requestAddDown();
        } else {
            if (state === 'up') {
                $upCounter.html(--upperCount);
                requestRemoveUp();
            } else if (state === 'down') {
                $upCounter.html(--upperCount);
                requestRemoveDown();
            }
        }
    }

    function displayUp() {
        displayEmpty();
        $upFill.removeClass('d-none');
        $downEmpty.removeClass('d-none');

        if ($votes.hasClass('up')) {
            $votes.removeClass('up');
        } else {
            $votes.addClass('up');
        }
    }

    function displayDown() {
        displayEmpty();
        $downFill.removeClass('d-none');
        $upEmpty.removeClass('d-none');

        if ($votes.hasClass('down')) {
            $votes.removeClass('down');
        } else {
            $votes.addClass('down');
        }
    }

    function displayEmpty() {
        $upFill.addClass('d-none');
        $upEmpty.addClass('d-none');
        $downFill.addClass('d-none');
        $downEmpty.addClass('d-none');
        changeStatus('');
    }

    function init() {
        $upEmpty.removeClass('d-none');
        $downEmpty.removeClass('d-none');
        changeStatus('');
    }

    function changeStatus(changeStatusTo) {
        $votes.removeClass('up');
        $votes.removeClass('down');
        $votes.addClass(changeStatusTo);
    }


    function addSubscribedView() {
        $subscribe.removeClass('btn-purple');
        $subscribe.addClass('btn-danger');
    }

    function removeSubscribedView() {
        $subscribe.removeClass('btn-danger');
        $subscribe.addClass('btn-purple');

    }


    function btnFocus () {
        if ($subscribe.hasClass('subscribed')) {
            $subscribe.html('Unsubscribe');
            removeSubscribedView();
        }
    }

    function btnUnFocus () {
        if ($subscribe.hasClass('subscribed')) {
            $subscribe.html('Subscribed');
            addSubscribedView();
        }
    }

    function subscribe () {
        requestSubscribe();
        addSubscribedView();
        $subscribe.html('Subscribed');
        $subscribe.addClass('subscribed');
    }

    function unsubscribe () {
        requestUnsubscribe();
        removeSubscribedView();
        $subscribe.html('Subscribe');
        $subscribe.removeClass('subscribed');
    }

    if ($votes.hasClass('up') || $votes.hasClass('down')) {
        if ($votes.hasClass('up')) {
            displayUp();
        }

        if ($votes.hasClass('down')) {
            displayDown();
        }
    } else {
        displayEmpty();
        init();
    }

    $upFill.on('click', function() {
        displayEmpty();
        init();
        changeCounter('up');
    });

    $upEmpty.on('click', function() {
        buttonState = $votes.hasClass('down') ? 'down' : 'up';
        displayUp();
        changeCounter(buttonState);
    });

    $downFill.on('click', function() {
        displayEmpty();
        init();
        changeCounter('down');
    });

    $downEmpty.on('click', function() {
        buttonState = $votes.hasClass('down') ? 'down' : 'up';
        displayDown();
        changeCounter(buttonState);
    });

    $subscribe.on('click', function() {
        if ($subscribe.hasClass('subscribed')) {
            unsubscribe();

        } else {
            subscribe();
        }
    });

    $subscribe.mouseenter(function() {
        btnFocus();
    });

    $subscribe.mouseleave(function() {
        btnUnFocus();
    });
}
