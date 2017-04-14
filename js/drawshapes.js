function drawShape(properties) {
    this.shape = document.createElement("span");
    switch (properties.shape) {
        case "triangle":
            this.shape.style.width = 0;
            this.shape.style.height = 0;
            this.shape.style.borderLeft = properties.height+"px solid transparent";
            this.shape.style.borderRight = 0+" solid transparent";
            this.shape.style.borderBottom = properties.width+"px solid "+properties.color;
            break;
        case "rectangle":
            this.shape.style.width = properties.width+"px";
            this.shape.style.height = properties.height+"px";
            this.shape.style.backgroundColor = properties.color;
            break;
        case "oval":
            this.shape.style.width = properties.width+"px";
            this.shape.style.height = properties.height+"px";
            this.shape.style.backgroundColor = properties.color;
            this.shape.style.borderRadius = properties.width+"px / "+properties.height+"px";
            break;
    }
    this.shape.style.display = "block";
    this.shape.style.position = "absolute";
    this.shape.style.top = properties.top+"px";
    this.shape.style.left = properties.left+"px";
    this.shape.style.transform = "rotate("+ properties.rotation +")";
    this.shape.style.transition = "all "+properties.hideSpeed+"ms";
    if(properties.isHidden)
        this.shape.className = "tHide";
}

Object.prototype.drawShapes = function(config){
    this.area = [this.offsetWidth,this.offsetHeight];
    function getRandomProperties(area,isHidden){
        var width = (Math.random() * (config.size.max - config.size.min) + config.size.min);
        var height = (Math.random() * (config.size.max - config.size.min) + config.size.min);
        return {
            shape: config.shape,
            width: width,
            height: height,
            color: config.colors[Math.floor(Math.random()*config.colors.length)],
            left: (Math.random() * ((area[0]-width) - 0) + 0),
            top: (Math.random() * ((area[1]-height) - 0) + 0),
            rotation: (Math.random() * (360 - 0) + 0)+"deg",
            isHidden: isHidden,
            hideSpeed: config.hideSpeed,
        };
    }

    var shapes = new Array(config.qtt);
    for (var i=0; i<shapes.length; i++){
        shapes[i] = new drawShape(getRandomProperties(this.area, false));
        this.appendChild(shapes[i].shape);
    }
    shapes[0].shape.className = "tHide";
    function replaceShape(){
        shapes[1].shape.className = "tHide";
        shapes[0].shape.parentNode.removeChild(shapes[0].shape);
        shapes[shapes.length-1].shape.style.transition = "all "+config.showSpeed+"ms";
        shapes[shapes.length-1].shape.className = "";
        shapes[shapes.length-2].shape.style.transition = "all "+config.hideSpeed+"ms";
        shapes.shift();
        var newShape = new drawShape(getRandomProperties(this.area, true));
        shapes.push(newShape);
        this.appendChild(newShape.shape);
    }
    setInterval(replaceShape.bind(this),config.speed);
};

