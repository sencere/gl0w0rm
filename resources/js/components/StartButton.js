class StartButton {
    rectWidth = 0;
    rectHeight = 0;
    inside = false;
    buttonX = 5;
    buttonY = 5;
    buttonRadius = 10;

    constructor(p5, width, height) {
        let rectWidth = this.rectWidth = width -10;
        let rectHeight = this.rectHeight = height/10 -10;
        let mouseX = p5.mouseX;
        let mouseY = p5.mouseY;
        if(mouseX > this.buttonX && mouseX < this.buttonX + rectWidth){
            if(mouseY > this.buttonY && mouseY < this.buttonY + rectHeight){
                this.inside = true;
            }
        }

        p5.noStroke();
        if (this.inside){
            p5.fill(163, 4, 4);
        } else {
            p5.fill(4,86,163);
        }
        let rect = p5.rect(this.buttonX, this.buttonY, rectWidth, rectHeight, this.buttonRadius);
        p5.fill(255);
        p5.textSize(20);
        p5.textAlign(p5.CENTER, p5.CENTER);

        if (this.inside){
            p5.text('Yes (click me)', rectWidth/2, rectHeight/2 + 5);
        } else {
            p5.text('Ready?', rectWidth/2, rectHeight/2 + 5);
        }
    };

    getConfiguration = () => {
        return {
            'rectWidth':this.rectWidth,
            'rectHeight':this.rectHeight,
            'buttonX':this.buttonX,
            'buttonY':this.buttonY,
            'buttonRadius':this.buttonRadius
        }
    }
}

export default StartButton;
