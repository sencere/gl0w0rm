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