import React from "react"; import ReactDOM from 'react-dom'; import Sketch from "react-p5";
import Firefly from './Firefly';
import StartButton from "./StartButton";
import axios from 'axios';

class Application extends React.Component {
    landgrass = document.getElementById('landgrass');
    amountOfFireflies = 80;
    particles = [];
    attractors = [];
    attractorState = true;
    p5 = Object();
    countdown = 60;
    attracorsAllowed = 10;
    readyState = false;
    startButtonConfiguration;
    options = {};
    listAngles = [0, 0, [3.141593, 6.283185], [1.570796, 3.926991, 5.4977871], [0.5235988, 2.617994, 3.665191, 5.759587], [0.5235988, 1.570796, 2.617994, 3.665191, 5.759587]];
    timerShown = false;
    seconds = '';

    // TODO
    // -Ready Button (done)
    // -request options and display (done)
    // -send request (done)
    // -limit time + add countdown
    // -interact with others predictions, which means,
    //  that you have to pull the data first
    // -clean up
    async componentDidMount() {
        let responseData = axios.post('/posts/options/' + this.landgrass.dataset.id, {})
            .then(response => this.setState({ options: response.data }))
        // .catch(function (error) {
        // console.log(error);
        // });

        // const article = { title: 'React POST Request Example' };
        // const response = await axios.post('https://reqres.in/api/articles', article);
        // this.setState({ 'options': responseData });
    };

    attractorSwitch = () => {
        if (this.attractorState) {
            this.attractorState = false;
            return;
        }
        this.attractorState = true;
    };

    addAttractor = (mouseX, mouseY, p5) => {
        // if (this.attractorState) {
        // }
        // this.attractorSwitch();
        if (this.attractors.length < this.attracorsAllowed &&
            (mouseX > 0 && mouseX < this.landgrass.clientWidth && mouseY > 0 && mouseY < this.landgrass.clientHeight) &&
            this.readyState) {
            this.attractors.push(p5.createVector(mouseX, mouseY));
            console.log(mouseX, mouseY);

            mouseX = mouseX.toString().indexOf('.') > 0 ? parseInt(mouseX.toString().split('.')[0]) : parseInt(mouseX.toString());
            mouseY = mouseY.toString().indexOf('.') > 0 ? parseInt(mouseY.toString().split('.')[0]) : parseInt(mouseY.toString());

            // Simple POST request with a JSON body using axios
            const data = {
                postId: parseInt(this.landgrass.dataset.id),
                mouseX: mouseX,
                mouseY: mouseY
            };
            axios.post('/predictions', data);
        }

        // if (this.attractors.length > 0 && this.attractors.length < 2) {
        // var secondsBetweenActions = this.countdown;
        // setInterval( function() {
        // secondsBetweenActions--;
        // console.log(secondsBetweenActions);
        // }, 1000 );
        // }
    };

    checkReadyState = (mouseX, mouseY, p5) => {
        if (mouseX > this.startButtonConfiguration.buttonX && mouseX < this.startButtonConfiguration.buttonX + this.startButtonConfiguration.rectWidth) {
            if (mouseY > this.startButtonConfiguration.buttonY && mouseY < this.startButtonConfiguration.buttonY + this.startButtonConfiguration.rectHeight) {
                this.readyState = true;
                this.startFirstTimer(p5);
                this.seconds = 3;
            }
        }

    };


    updateCounter = (counter) => {
        this.seconds = counter;
    };

    startFirstTimer = (p5) => {
        let seconds = this.seconds;
        let width = this.landgrass.clientWidth;
        let height = this.landgrass.clientHeight;
        let myVar = setInterval(() => {
            seconds -= 1;
            if (seconds == 0)
                seconds = 'GO!';
            this.updateCounter(seconds);
            if (seconds < 1) {
                clearInterval(myVar);
            }
        }, 1000);
    };

    startTimer = (p5) => {
        let seconds = this.seconds;
        let width = this.landgrass.clientWidth;
        let height = this.landgrass.clientHeight;
        let myVar = setInterval(() => {
            seconds -= 1;
            this.updateCounter(seconds);
            if (seconds < 1) {
                clearInterval(myVar);
            }
        }, 1000);
    };

    contains = (mx, my) => {
        return this.p5.dist(mx, my, this.x, this.y) < this.r;
    };

    setup = (p5, parentRef) => {
        p5.createCanvas(landgrass.clientWidth, landgrass.clientWidth).parent(parentRef);
        let mouseX = p5.mouseX;
        let mouseY = p5.mouseY;
        // console.log(this.state);
        p5.frameRate(60); 
        p5.mousePressed = () => {
            // if (this.attractorsState) {
            // }
            this.addAttractor(p5.mouseX, p5.mouseY, p5);
            this.checkReadyState(p5.mouseX, p5.mouseY, p5);
            // if (startButton.contains(mouseX, mouseY) && attractorsState === false) {
            // attractorsSwitch();
            // }
            //
        }
    };

    draw = (p5, parentRef) => {
        let backgroundColor = [22, 22, 22];
        let circleColor = [20, 20, 20];
        let particles = this.particles;
        let width = landgrass.clientWidth;
        let height = landgrass.clientHeight;
        let circleDiameter = (width/2) - (1/20 * width);
        let radius = circleDiameter/2;
        let countOptions = Object.keys(this.state.options).length;
        let count = 0;

        p5.background(backgroundColor);
        p5.fill(circleColor);
        p5.noStroke();
        p5.circle(width/2, height/2, circleDiameter);
        // p5.stroke(255);
        p5.strokeWeight(0);

        Object.entries(this.state.options).forEach(([key, value]) => {
            // let angle = (countOptions - 2) * 180;
            let angle = this.listAngles[countOptions][count];
            angle = angle + p5.PI;

            // if (countOptions === 2) {
                // angle = 360;
            // }
            // angle = (angle / countOptions) * count;

            let x = (width/2) + radius * p5.cos(angle);
            let y = (height/2) + radius * p5.sin(angle);
            p5.fill(255);
            p5.textSize(20);
            p5.textAlign(p5.CENTER, p5.CENTER);
            p5.text(value, x, y);
            count++;
        })


        // console.log(Object.keys(this.state.options).length);
        // if (particles.length < this.amountOfFireflies) {
        this.particles.push(new Firefly(p5.random(landgrass.clientWidth), p5.random(landgrass.clientHeight), p5));
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

        for (var i = 0; i < this.particles.length; i++) {
            var particle = this.particles[i];
            for (var j = 0; j < this.attractors.length; j++) {
                particle.attracted(this.attractors[j]);
            }
            particle.update();
            particle.show();
        }


        // my button
        if (!this.readyState) {
            let startButton = new StartButton(p5, this.landgrass.clientWidth, this.landgrass.clientHeight);
            this.startButtonConfiguration = startButton.getConfiguration();
        }
        // startButton.display(mouseX, mouseY);
        // p5.noLoop();
        p5.noStroke();
        p5.fill(255);
        p5.textSize(30);
        p5.textAlign(p5.CENTER, p5.CENTER);
        p5.text(this.seconds, width/2, height/2);
    }

    render() {
        return (
            <Sketch setup={this.setup} draw={this.draw} />
        );
    }
}

export default Application;
