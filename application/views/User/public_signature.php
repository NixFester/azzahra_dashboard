<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Public Signature</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background:#f3f4f6; margin:0; padding:20px; }
        .card { max-width:720px; margin:40px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.08); }
        .center { text-align:center; }
        img { max-width:100%; height:auto; border:1px solid #ddd; border-radius:6px; }
        .btn { display:inline-block; margin:6px; padding:10px 14px; background:#1a3c6e; color:#fff; text-decoration:none; border-radius:6px; cursor:pointer; }
        .btn.warn { background:#f59e0b }
        .btn.danger { background:#e11d48 }
        canvas { border:2px dashed #1a3c6e; border-radius:8px; background:#fff; cursor:crosshair; width:100%; height:auto; }
    </style>
</head>
<body>
    <div class="card">
        <h3 class="center">Tanda Tangan</h3>
        <div class="center" id="existing">
            <?php if (!empty($signature_url)): ?>
                <img id="sig-img" src="<?= $signature_url ?>" alt="Tanda Tangan">
            <?php else: ?>
                <p>Tanda tangan belum tersedia.</p>
            <?php endif; ?>
        </div>

        <div id="area-ttd" style="margin-top:14px; <?php echo !empty($signature_url) ? 'display:none;' : '' ?>">
            <canvas id="canvas-ttd" width="600" height="240"></canvas>
            <div style="margin-top:10px; text-align:center;">
                <button class="btn" onclick="clearTTD()">Hapus</button>
                <button class="btn" onclick="simpanTTD()">Simpan</button>
            </div>
            <div id="ttd-pesan" style="text-align:center; margin-top:8px; font-size:13px;"></div>
        </div>

        <div style="text-align:center; margin-top:12px;">
            <button class="btn warn" onclick="gantiTTD()">Ganti Tanda Tangan</button>
            <button class="btn" onclick="kirimTTD()">Kirim TTD via WA</button>
            <button class="btn danger" onclick="hapusTTD()">Hapus TTD</button>
            <a class="btn" href="<?= site_url('Cetak/print_6/'.$kode) ?>" target="_blank">Print Surat Pernyataan</a>
        </div>
    </div>

<script>
var canvas  = document.getElementById('canvas-ttd');
var ctx     = canvas.getContext('2d');
var isDrawing = false;

var namaPelanggan = '';

function getPos(e) {
    var rect = canvas.getBoundingClientRect();
    var scaleX = canvas.width / rect.width;
    var scaleY = canvas.height / rect.height;
    if (e.touches) {
        return {
            x: (e.touches[0].clientX - rect.left) * scaleX,
            y: (e.touches[0].clientY - rect.top) * scaleY
        };
    }
    return {
        x: (e.clientX - rect.left) * scaleX,
        y: (e.clientY - rect.top) * scaleY
    };
}

canvas.addEventListener('mousedown', function(e) {
    isDrawing = true;
    var pos = getPos(e);
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
});
canvas.addEventListener('mousemove', function(e) {
    if (!isDrawing) return;
    var pos = getPos(e);
    ctx.lineTo(pos.x, pos.y);
    ctx.strokeStyle = '#000';
    ctx.lineWidth   = 2;
    ctx.lineCap     = 'round';
    ctx.stroke();
});
canvas.addEventListener('mouseup',    function() { isDrawing = false; });
canvas.addEventListener('mouseleave', function() { isDrawing = false; });
canvas.addEventListener('touchstart', function(e) { e.preventDefault(); isDrawing = true; var pos = getPos(e); ctx.beginPath(); ctx.moveTo(pos.x, pos.y); });
canvas.addEventListener('touchmove', function(e) { e.preventDefault(); if (!isDrawing) return; var pos = getPos(e); ctx.lineTo(pos.x, pos.y); ctx.strokeStyle = '#000'; ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.stroke(); });
canvas.addEventListener('touchend', function() { isDrawing = false; });

function clearTTD() { ctx.clearRect(0, 0, canvas.width, canvas.height); }
function gantiTTD() { document.getElementById('area-ttd').style.display = 'block'; }

function tambahInisial() {
    ctx.font         = 'italic 13px Arial';
    ctx.fillStyle    = '#aaaaaa';
    ctx.textAlign    = 'right';
    ctx.fillText('<?= isset($kode) ? $kode : '' ?>' + ' - ' + namaPelanggan, canvas.width - 10, canvas.height - 10);
}

function simpanTTD() {
    tambahInisial();
    var dataURL = canvas.toDataURL('image/png');
    var kode    = '<?= isset($kode) ? $kode : '' ?>';
    var pesan   = document.getElementById('ttd-pesan');

    pesan.innerHTML   = 'Menyimpan...';
    pesan.style.color = '#888';

    fetch('<?= base_url() ?>User/upload_signature_public', {
        method : 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body   : 'kode=' + encodeURIComponent(kode) + '&signature=' + encodeURIComponent(dataURL)
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            pesan.innerHTML   = 'Tanda tangan berhasil disimpan!';
            pesan.style.color = 'green';
            setTimeout(function() { location.reload(); }, 1000);
        } else {
            pesan.innerHTML   = 'Gagal menyimpan, coba lagi.';
            pesan.style.color = 'red';
        }
    });
}

function kirimTTD() {
    var hp = '<?= isset($cos_hp) ? $cos_hp : '' ?>' || '';
    if (!hp) { alert('Nomor HP customer tidak tersedia'); return; }
    if (hp.startsWith('0')) hp = '62' + hp.substring(1);
    hp = hp.replace(/\D/g, '');

    var link = '<?= site_url('User/public_signature/'.$kode) ?>';
    var message = 'Tanda tangan digital Anda:\n' + link;
    var wa = 'https://wa.me/' + hp + '?text=' + encodeURIComponent(message);
    window.open(wa, '_blank');
}

function hapusTTD() {
    if (!confirm('Hapus tanda tangan?')) return;
    var kode = '<?= isset($kode) ? $kode : '' ?>';
    fetch('<?= base_url() ?>User/delete_signature_public', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'kode=' + encodeURIComponent(kode)
    }).then(r => r.json()).then(d => { if (d.success) location.reload(); else alert('Gagal menghapus'); });
}
</script>
</body>
</html>