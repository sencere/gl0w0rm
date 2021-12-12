import React from 'react';
import ReactDOM from 'react-dom';
import Application from './Application'; 

class Application extends React.Component {
    example = document.getElementById('example');

    setup = (p5, parentRef) => {
        p5.createCanvas(this.example.clientWidth, this.example.clientWidth).parent(parentRef);
    };

    getRandomWord(p5) {
        let words = [1,2,3,4,5,6,7,8,9,10,11,12,'Burger House', 'Swagat'];
        const word = p5.random(words)
        p5.background(240);
        p5.textSize(32);
        p5.text(word, 10, 30);
        p5.fill(0, 102, 153);
    }

    draw = (p5) => {
        p5.fill('#ED225D');
        p5.textSize(36);
        this.getRandomWord(p5)
        p5.noLoop();
    }

    render() {
        return (
            <Sketch setup={this.setup} draw={this.draw} />
        );
    }
}

export default Application;

