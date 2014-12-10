<canvas id="bstall_{{ $name }}" width="{{ $width }}" height="{{ $height }}" style="width:{{ $width }}px; height:{{ $height }}px;"></canvas>
<input type="color" id="bstall_{{ $name }}_color_picker">
<script>
(function() {
    var canvas = document.getElementById('bstall_{{ $name }}');
    var picker = document.getElementById('bstall_{{ $name }}_color_picker');
    
    var ctx = canvas.getContext('2d');
    ctx.fillStyle = "rgb(255, 255, 255)";
    ctx.fillRect(0, 0, {{ $width }}, {{ $height }});

    var send_buffer = [];
    var mouseDown = 0;

    var cbuf = {{ $canvas }};

    for(var y=0; y<cbuf.length; ++y) {
        for(var x=0; x<cbuf[y].length; ++x) {
            var r = cbuf[y][x] >> 0  & 0xFF;
            var g = cbuf[y][x] >> 8  & 0xFF;
            var b = cbuf[y][x] >> 16 & 0xFF;

            ctx.fillStyle = "rgb(" + r + "," + g + "," + b + ")";
            ctx.fillRect(x, y, 1, 1);
        }
    }

    function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    function getMousePos(canvas, evt) {
        var rect = canvas.getBoundingClientRect();
        return {
            x: evt.clientX - rect.left,
            y: evt.clientY - rect.top
        };
    }

    document.body.onmousedown = function() { 
        ++mouseDown;
    }

    document.body.onmouseup = function() {
        --mouseDown;
        
        if(send_buffer.length != 0) {
            var uniq = send_buffer.filter(function(i, p) {
                return send_buffer.indexOf(i) == p;
            });
            send_buffer = [];

            var req = new XMLHttpRequest();
            req.open("POST", "/bstall/draw/{{ $name }}", true);
            req.setRequestHeader("Content-type", "application/json");
            req.onreadystatechange = function() {
                console.log(req.responseText);
            };
            req.send(JSON.stringify({"pixels": uniq}));
        }
    }

    canvas.addEventListener('mousemove', function(e) {
        if(mouseDown) {
            var mp = getMousePos(canvas, e);
            var x = mp.x;
            var y = mp.y;

            var rgb = hexToRgb(picker.value);
            var c = ((rgb.r << 0) + (rgb.g << 8) + (rgb.b << 16));

            ctx.fillStyle = "rgb(" + rgb.r + "," + rgb.g + "," + rgb.b + ")";
            ctx.fillRect(x, y, 1, 1);

            add_to_buffer({
                x: x,
                y: y, 
                c: c
            });
        }
    }, false);

    function add_to_buffer(o)
    {
        for(var i=0; i<send_buffer.length; ++i) {
            if(send_buffer[i].x == o.x && send_buffer[i].y == o.y) {
                return;
            }
        }

        send_buffer.push({
            x: o.x,
            y: o.y,
            c: o.c
        });
    }
})();
</script>