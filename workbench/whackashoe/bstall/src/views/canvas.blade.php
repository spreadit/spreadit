<canvas id="bstall_{{ $id }}" width="{{ $width }}" height="{{ $height }}" style="width:{{ $width }}px; height:{{ $height }};"></canvas>
<script>
(function() {
    var ctx = document.getElementById('{{ $id }}').getContext('2d');
    ctx.fillStyle = "rgb(255, 255, 255)";
    ctx.fillRect(0, 0, {{ $width }}, {{ $height }});
})();
</script>