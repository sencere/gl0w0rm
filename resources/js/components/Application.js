import React from "react"; 
import ReactDOM from 'react-dom'; 
import Sketch from "react-p5";
import Button from './PostBar';

import Firefly from './Firefly';
import StartButton from "./StartButton";
import axios from 'axios';

class Application extends React.Component {
    canvas = document.getElementById('landgrass');
    amountOfFireflies = 80;
    particles = [];
    attractors = [];
    attractorState = true;
    p5 = Object();
    countdown = 60;
    attracorsAllowed = 10;
    readyButtonState = false;
    readyTimerState = false;
    readyAttractorState = false;
    finishState = false;
    startButtonConfiguration;
    options = {};
    listAngles = [0, 0, [3.141593, 6.283185], [1.570796, 3.926991, 5.4977871], [0.5235988, 2.617994, 3.665191, 5.759587], [0.5235988, 1.570796, 2.617994, 3.665191, 5.759587], [0.5235988, 1.570796, 2.617994, 3.665191, 4.712389, 5.759587]];
    timerShown = false;
    timer = 3;
    timerTextColor = [255, 255, 255];
    predictions = [];
    attractorCount = 0;
    completed = false;
    smCircleX = 0;
    smCircleY = 0;
    secondTimer = 10;

    // TODO
    // -code clean up

    async componentDidMount() {
        let responseData = axios.post('/posts/options/' + this.canvas.dataset.id, {})
            .then(response => this.setState({
                target: response.data.target,
                question: response.data.question,
                options: response.data.options,
                time: response.data.time
            }));

        let resultResponse = axios.post('/results/result/' + this.canvas.dataset.id, {'postId': this.canvas.dataset.id})
            .then(response => this.setState({
                result: response.data.result,
                confidence: response.data.confidence,
                option: response.data.option
            }));
    };

    getPredictions = () => {
        let responseData = axios.post('/posts/predictions/' + this.canvas.dataset.id, {})
            .then(response => this.assignPredictions(response.data));
    };

    getResult = (p5) => {
        let responseData = axios.post('/results/result/' + this.canvas.dataset.id, {postId: this.canvas.dataset.id})
            .then(response => this.assignResult(response.data, p5));
    };

    setPredictionCompleted = () => {
        this.readyButtonState = true;
        this.readyTimerState = true;
        this.readyAttractorState = false;
        this.finishState = true;
        this.completed = true;
        this.timer = '';
    };

    addAttractor = (mouseX, mouseY, p5, botClick=true) => {
        if (this.attractorCount < this.attracorsAllowed &&
            (mouseX > 0 && mouseX < this.canvas.clientWidth && mouseY > 0 && mouseY < this.canvas.clientHeight) &&
            this.readyAttractorState) {
            this.attractors.push(p5.createVector(mouseX, mouseY));

            mouseX = parseInt(mouseX.toFixed(0));
            mouseY = parseInt(mouseY.toFixed(0));
            let time = parseInt(this.timer);

            // Simple POST request with a JSON body using axios
            const data = {
                postId: parseInt(this.canvas.dataset.id),
                mouseX: mouseX,
                mouseY: mouseY,
                time: time
            };

            if (!botClick) {
                this.attractorCount++;
                axios.post('/predictions', data);
            }
        }
    };

    checkReadyState = (mouseX, mouseY, p5) => {
        if (!this.completed) {
            if (mouseX > this.startButtonConfiguration.buttonX && mouseX < this.startButtonConfiguration.buttonX + this.startButtonConfiguration.rectWidth) {
                if (mouseY > this.startButtonConfiguration.buttonY && mouseY < this.startButtonConfiguration.buttonY + this.startButtonConfiguration.rectHeight) {
                    this.readyTimerState = true;
                    this.readyButtonState = true;
                    this.startFirstTimer(p5);
                }
            }
        }
    };

    updateFirstTimer = (counter) => {
        this.timer = counter;
    };

    startFirstTimer = (p5) => {
        this.timerTextColor = [255, 0, 0];
        let timer = this.timer;
        let width = this.canvas.clientWidth;
        let height = this.canvas.clientHeight;
        let myVar = setInterval(() => {
            timer--;
            this.updateFirstTimer(timer);
            if (timer < 1) {
                clearInterval(myVar);
                // this.startSecondTimer();
                // this.readyTimerState = true;
                this.timerTextColor = [255, 0, 0];
                this.timer = 'GO!';
                this.secondTimer = this.state.time;
                this.getPredictions();
                setTimeout(() =>  {
                    this.startSecondTimer(p5);
                    this.readyAttractorState = true;
                }, 2000);
            }
        }, 1000);
    };

    startSecondTimer = (p5) => {
        let timer = this.secondTimer;
        this.timerTextColor = [255, 255, 255];
        let width = this.canvas.clientWidth;
        let height = this.canvas.clientHeight;
        let myVar = setInterval(() => {
            timer--;
            this.updateFirstTimer(timer);
            if (typeof this.predictions[timer] !== 'undefined') {
                this.addAttractor(this.predictions[timer].mouseX, this.predictions[timer].mouseY, p5);
            }

            if (timer < 1) {
                clearInterval(myVar);
                // this.startSecondTimer();
                this.timer = '';
                this.finishState = true;
                this.readyAttractorState = false;
            }
        }, 1000);
    };

    sleep = (ms) => {
        return new Promise(resolve => setTimeout(resolve, ms));
    };

    startTimer = (p5) => {
        let seconds = this.seconds;
        let width = this.canvas.clientWidth;
        let height = this.canvas.clientHeight;
        let myVar = setInterval(() => {
            seconds -= 1;
            this.updateCounter(seconds);
            if (seconds <= 1) {
                clearInterval(myVar);
            }
        }, 1000);
    };

    assignResult = (predictions, p5) => {
        if (predictions.length) {
            this.displayOnlyResult(predictions.confidence, predictions.option, p5);
            this.setPredictionCompleted();
            p5.noLoop();
        }
    };

    assignPredictions = (predictions) => {
        let predictionsArr = [];

        Object.entries(predictions).forEach(([key, value]) => {
            predictionsArr[key] = value;
        })

        this.predictions = predictionsArr;
    };

    getSmallCircleCoordinates = () => {
        let count = this.particles.length;
    };


    displayOnlyResult = (confidenceScore, middleText, p5) => {
        // p5.loop();
        let width = this.canvas.clientWidth;
        let height = this.canvas.clientHeight;
        p5.noStroke();
        p5.fill(0, 129, 255);
        p5.textSize(30);
        p5.textAlign(p5.CENTER, p5.CENTER);
        p5.text(middleText, width/2, height/2);

        p5.textSize(20);
        p5.textAlign(p5.CENTER, p5.CENTER);
        p5.text('Confidence: ' + confidenceScore + '%', width/2, (height/2) - height/15);
        p5.fill(255);
        p5.textSize(30);
        p5.text(this.state.target + '\n' + this.state.question, width/2, height/3);



        // p5.noLoop();
    };

    displayCircleMiddleText = (p5, width, height, middleText, confidenceScore, optionKey) => {
        let confidence = parseFloat(confidenceScore.toFixed(2));
        let option = parseInt(optionKey, 10);
        p5.noStroke();
        p5.fill(0, 129, 255);
        p5.textSize(30);
        p5.textAlign(p5.CENTER, p5.CENTER);
        p5.text(middleText, width/2, height/2);

        p5.textSize(20);
        p5.textAlign(p5.CENTER, p5.CENTER);
        p5.text('Confidence: ' + confidenceScore.toFixed(2) + '%', width/2, (height/2) - height/15);

        // Simple POST request with a JSON body using axios
        const data = {
            postId: parseInt(this.canvas.dataset.id),
            confidence: confidence,
            option: option
        };
        axios.post('/results', data);
    };

    displayPredictionResults = (p5, width, height) => {
        let countOptions = Object.keys(this.state.options).length;
        let count = 0;
        let circleDiameter = (width/2) - (1/20 * width);
        let radius = circleDiameter/2;
        let resultArr = [];
        let minValue = Math.pow(10,5);
        let winner = '';
        let middleText = '';
        let distanceSum = 1;
        let percentagePerOption = 100 / countOptions;
        let angle = this.listAngles[countOptions][count];
        let optionKey = 0;
        angle = angle + p5.PI;

        if (this.predictions.length > 0) {
            Object.entries(this.state.options).forEach(([key, value]) => {
                let angle = this.listAngles[countOptions][count];
                angle = angle + p5.PI;

                let x = (width/2) + radius * p5.cos(angle);
                let y = (height/2) + radius * p5.sin(angle);

                let distance = p5.dist(x, y, this.smCircleX, this.smCircleY);
                distanceSum += distance;

                if (distance < minValue) {
                    winner = value;
                    minValue = distance;
                    optionKey = key;
                }

                this.minValue = distance;
                count++;
            });
            let confidenceScore = (radius - minValue) * 100 / radius;

            this.displayCircleMiddleText(p5, width, height, winner, confidenceScore, optionKey);
            p5.noLoop();
        }
    };


    setup = (p5, parentRef) => {
        p5.createCanvas(this.canvas.clientWidth, this.canvas.clientWidth).parent(parentRef);
        let mouseX = p5.mouseX;
        let mouseY = p5.mouseY;
        this.smCircleX = this.canvas.clientWidth / 2;
        this.smCircleY = this.canvas.clientHeight / 2;
        this.getResult(p5);

        // p5.frameRate(60);
        if (!this.completed) {
            p5.mousePressed = () => {
                this.addAttractor(p5.mouseX, p5.mouseY, p5, false);
                this.checkReadyState(p5.mouseX, p5.mouseY, p5);
            };
        }
    };

    draw = (p5, parentRef) => {
        p5.print(this.state.result);
        if (this.state.result) {
            this.setPredictionCompleted();
        } else if (this.state.result === null) {
            return;
        }

        let backgroundColor = [22, 22, 22];
        let circleColor = [20, 20, 20];
        let particles = this.particles;
        let width = this.canvas.clientWidth;
        let height = this.canvas.clientHeight;
        let circleDiameter = (width/2) - (1/20 * width);
        let radius = circleDiameter/2;
        let countOptions = Object.keys(this.state.options).length;
        let count = 0;

        p5.background(backgroundColor);
        p5.fill(circleColor);
        p5.noStroke();
        p5.circle(width/2, height/2, circleDiameter);
        p5.strokeWeight(0);

        if (this.readyTimerState && !this.completed) {
            // let circleColor = p5.color(5, 8, 163);
            let circleColor = p5.color(217, 255, 255);
            circleColor.setAlpha(128 + 128 * (p5.sin(p5.millis() / 1000) + 0.7));

            p5.fill(circleColor);
            p5.noStroke();
            p5.circle(this.smCircleX, this.smCircleY, circleDiameter/4);
            p5.strokeWeight(0);

            Object.entries(this.state.options).forEach(([key, value]) => {
                let angle = this.listAngles[countOptions][count];
                angle = angle + p5.PI;

                let x = (width/2) + radius * p5.cos(angle);
                let y = (height/2) + radius * p5.sin(angle);

                p5.fill(255);
                p5.textSize(20);
                p5.textAlign(p5.CENTER, p5.CENTER);
                p5.text(value, x, y);
                count++;
            });
        }

        p5.noStroke();
        p5.fill(this.timerTextColor);
        p5.textSize(30);
        p5.textAlign(p5.CENTER, p5.CENTER);
        if (!this.readyTimerState) {
            p5.text(this.state.target + '\n' + this.state.question, width/2, height/2);
        }

        if (this.readyTimerState && !this.finishState) {
            // console.log(Object.keys(this.state.options).length);
            // if (particles.length < this.amountOfFireflies) {
            this.particles.push(new Firefly(p5.random(this.canvas.clientWidth), p5.random(this.canvas.clientHeight), p5));
            // }
            //
            if (particles.length > this.amountOfFireflies) {
                particles.splice(0, 1);
            }

            for (var i = 0; i < this.attractors.length; i++) {
                p5.fill(240,10,10,150);
                p5.noStroke();
                p5.circle(this.attractors[i].x, this.attractors[i].y, 10);
                // hide attraction point stroke(0, 255, 0);
                // p5.point(this.attractors[i].x, this.attractors[i].y);
            }
            let particleSumX = 0;
            let particleSumY = 0;
            let particleLength = this.particles.length;

            for (var i = 0; i < particleLength; i++) {
                var particle = this.particles[i];
                particleSumX += this.particles[0].pos.x;
                particleSumY += this.particles[0].pos.y;
                for (var j = 0; j < this.attractors.length; j++) {
                    particle.attracted(this.attractors[j]);
                }
                particle.update();
                particle.show();
            }

            // smaller circle
            let concentrationX = particleSumX / particleLength;
            let concentrationY = particleSumY / particleLength;
            let movingFactor = 0.1;

            if (this.smCircleX < concentrationX) {
                this.smCircleX = this.smCircleX + 0.2;
            } else {
                this.smCircleX = this.smCircleX - 0.2;
            }

            if (this.smCircleY < concentrationY) {
                this.smCircleY = this.smCircleY + 0.2;
            } else {
                this.smCircleY = this.smCircleY - 0.2;
            }

        }

        // my button
        if (!this.readyButtonState) {
            let startButton = new StartButton(p5, this.canvas.clientWidth, this.canvas.clientHeight);
            this.startButtonConfiguration = startButton.getConfiguration();
        }

        if (this.readyTimerState) {
            p5.noStroke();
            p5.fill(this.timerTextColor);
            p5.textSize(30);
            p5.textAlign(p5.CENTER, p5.CENTER);
            p5.text(this.timer, width/2, height/2);
        }

        if (this.finishState) {
            this.displayPredictionResults(p5, width, height);
        }

        if (this.state.result) {
            this.displayOnlyResult(this.state.confidence, this.state.option, p5);
            p5.noLoop();
        }
    }

    render() {
        return (
            <div>
                <Sketch setup={this.setup} draw={this.draw} />
                <Button />
            </div>
        );
    }
}

export default Application;
