function DrawShape(properties) {
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
    function getDimensions(obj){
         return [obj.offsetWidth,obj.offsetHeight];
    }
    this.dimensions = getDimensions(this);
    function getRandomProperties(dimensions,isHidden){
        var width = (Math.random() * (config.size.max - config.size.min) + config.size.min);
        var height = (Math.random() * (config.size.max - config.size.min) + config.size.min);
        return {
            shape: config.shape,
            width: width,
            height: height,
            color: config.colors[Math.floor(Math.random()*config.colors.length)],
            left: (Math.random() * ((dimensions[0] - width))),
            top: (Math.random() * ((dimensions[1]-height))),
            rotation: (Math.random() * 360)+"deg",
            isHidden: isHidden,
            hideSpeed: config.hideSpeed
        };
    }
    function initShapes(obj,dimensions){
        var area = dimensions[0]*dimensions[1];
        var density = Math.round(config.density*(area/100000));
        var shapes = new Array(density);
        obj.innerHTML = '';
        for (var i=0; i<shapes.length; i++){
            shapes[i] = new DrawShape(getRandomProperties(dimensions, false));
            obj.appendChild(shapes[i].shape);
        }
        shapes[0].shape.className = "tHide";
        return shapes;
    }
    var shapes = initShapes(this,this.dimensions);
    function replaceShape(){
        shapes[1].shape.className = "tHide";
        shapes[0].shape.parentNode.removeChild(shapes[0].shape);
        shapes[shapes.length-1].shape.style.transition = "all "+config.showSpeed+"ms";
        shapes[shapes.length-1].shape.className = "";
        shapes[shapes.length-2].shape.style.transition = "all "+config.hideSpeed+"ms";
        shapes.shift();
        var newShape = new DrawShape(getRandomProperties(this.dimensions, true));
        shapes.push(newShape);
        this.appendChild(newShape.shape);
    }
    setInterval(replaceShape.bind(this),config.speed);
    this.afterResizeWidth = this.dimensions[0];
    window.addEventListener('resize',function(e){
        if(this.afterResizeWidth != getDimensions(this)[0]){
            this.dimensions = getDimensions(this);
            this.afterResizeWidth = this.dimensions[0];
            shapes = initShapes(this,this.dimensions);
        }
    }.bind(this),false);
};

