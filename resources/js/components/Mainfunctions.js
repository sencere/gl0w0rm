function MainFunctions(x, y, r) {
    this.x = x;
    this.y = y;
    this.r = r;

    this.contains = function(mx, my) {
        return dist(mx, my, this.x, this.y) < this.r;
    }

    this.display = function(mx, my) {
        let corX = this.x;
        let corY = this.y;

        if (this.contains(mx, my)) {
            fill(100, 100, 100);
            noStroke();
        } else {
            fill(buttonColor);
            noStroke();
        }

        if (attractorsState === true) {
            corX = this.r;
            fill(backgroundColor);
        }

        rect(200, 20, 200, 40, 5, 5, 5, 5);
    }
}
