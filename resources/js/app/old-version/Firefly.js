class Firefly {
    p5 = Object();
    pos;
    prev;
    vel;
    stroke;
    acc;

    constructor(x, y, p5) {
        this.p5 = p5;
        this.pos = p5.createVector(x, y);
        this.prev = p5.createVector(x, y);
        let angle = Math.random() * (2*Math.PI);
        let length = 1;
        let colorArray = [p5.color(136, 170, 0, p5.random(200, 255)), p5.color(255, 204, 0, p5.random(200, 255))];
        this.vel = p5.createVector(length * Math.cos(angle), length*Math.sin(angle)).setMag(p5.random(2,5));
        this.stroke = colorArray[Math.floor(Math.random() * colorArray.length)];
        this.acc = p5.createVector();
    }

    update = () => {
        this.vel.add(this.acc);
        let length = 1;
        let angle = Math.random() * (2*Math.PI);
        this.vel.add(this.acc);
        this.vel.limit(5);
        this.pos.add(this.vel);
        this.acc.mult(0);
    };

    show = () => {
        this.p5.stroke(this.stroke);
        this.p5.strokeWeight(7);
        this.p5.line(this.pos.x, this.pos.y, this.prev.x, this.prev.y);
        // this.p5.fill(255,204, 0, this.p5.random(200, 255));
        // this.p5.circle(this.pos.x, this.pos.y, 10);
        this.prev.x = this.pos.x;
        this.prev.y = this.pos.y;
    };

    attracted = (target) => {
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
}

export default Firefly;
