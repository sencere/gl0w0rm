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