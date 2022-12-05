const canvas = document.querySelector("canvas");
const ctx = canvas.getContext("2d");
const tools = document.querySelectorAll(".tools");
const size = document.querySelector("#size");
const color = document.querySelector("#brushColor");

var steps = [];
var step = -1;

function saveStep() {
    if (step < steps.length - 1) {
        steps.length = step + 1;
    } else {
        steps.push(canvas.toDataURL());
        step++;
    }
}

function reset() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    steps = [];
    step = -1;
    localStorage.removeItem("canvas");
}

function load() {
    var dataURL = localStorage.getItem("canvas");
    if (dataURL) {
        var canvasPic = new Image();
        canvasPic.src = dataURL;
        canvasPic.onload = function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(canvasPic, 0, 0);
        };
    }
}

function save() {
    var dataURL = canvas.toDataURL();
    console.log(dataURL);
    localStorage.setItem("canvas", dataURL);
}

function undo() {
    if (step > 0) {
        step--;
        var canvasPic = new Image();
        canvasPic.src = steps[step];
        canvasPic.onload = function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(canvasPic, 0, 0);
        };
    }
}

function redo() {
    if (step < steps.length - 1) {
        step++;
        var canvasPic = new Image();
        canvasPic.src = steps[step];
        canvasPic.onload = function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(canvasPic, 0, 0);
        };
    }
}

let isDrawing = false;
let selectedTool = "brush";
let sizeTools = 10;
let selectedColor = "#000000";
fitToContainer(canvas);

function fitToContainer(canvas) {
    canvas.style.width = "100%";
    canvas.style.height = "100%";
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
}

const drawing = (e) => {
    if (!isDrawing) return;
    if (selectedTool === "brush") {
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
    } else if (selectedTool === "eraser") {
        ctx.clearRect(e.offsetX, e.offsetY, sizeTools, sizeTools);
        ctx.stroke();
    }
    saveStep();
};

tools.forEach((tool) => {
    tool.addEventListener("click", (e) => {
        document.querySelector(".actived").classList.remove("actived");
        tool.classList.add("actived");
        selectedTool = tool.id;
        canvas.style.cursor = `url(./drawing/${selectedTool}.svg) 0 10, auto`;
        console.log(tool.id);
    });
});

const startDrawing = (e) => {
    ctx.beginPath();
    isDrawing = true;
    ctx.lineWidth = sizeTools;
    ctx.strokeStyle = selectedColor;
    ctx.lineCap = "round";
    ctx.lineJoin = "round";
};

size.addEventListener("change", () => {
    console.log(size.value);
    sizeTools = size.value;
});

color.addEventListener("change", () => {
    console.log(color.value);
    selectedColor = color.value;
});

canvas.addEventListener("mousedown", startDrawing);
canvas.addEventListener("mousemove", drawing);
canvas.addEventListener("mouseup", () => (isDrawing = false));
