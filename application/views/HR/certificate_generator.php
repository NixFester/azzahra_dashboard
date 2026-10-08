<?php $this->load->view('Template/header'); ?>

<!-- =================================================================================
     VIEW: certificate_generator.php
     FITUR: SCALE 100% (FULL HD) + SCROLLBAR OTOMATIS
================================================================================== -->

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    /* --- LAYOUT UTAMA --- */
    #cert-module-wrapper {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f4f6f9;
        display: flex;
        flex-wrap: nowrap;
        gap: 0; /* Hilangkan gap agar scrollbar rapi */
        color: #333;
        align-items: flex-start;
        margin-top: 15px;
        height: calc(100vh - 100px); /* Full Height */
        border: 1px solid #ddd;
    }

    /* --- PANEL KIRI (CONTROLS) --- */
    .cert-controls {
        flex: 0 0 320px; /* Lebar Tetap */
        background: white;
        padding: 20px;
        height: 100%;
        overflow-y: auto; /* Scroll Vertikal untuk menu */
        border-right: 1px solid #ccc;
        box-sizing: border-box;
        z-index: 2;
    }

    .cert-group { margin-bottom: 15px; border-bottom: 1px solid #f0f0f0; padding-bottom: 15px; }
    .cert-label { display: block; font-weight: 700; margin-bottom: 5px; font-size: 12px; color: #555; }
    .cert-input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px; box-sizing: border-box; }
    textarea.cert-input { font-family: sans-serif; line-height: 1.4; min-height: 80px; }
    
    .btn-cert-add { width: 100%; background: #28a745; color: white; padding: 6px; border: none; border-radius: 4px; cursor: pointer; font-size: 11px; margin-top: 5px;}
    .btn-cert-del { background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 10px; margin-left: 5px;}
    
    .btn-cert-gen { 
        width: 100%; padding: 12px; background: #0056b3; color: white; border: none; 
        border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 14px; margin-top: 10px; 
    }
    .btn-cert-gen:hover { background: #004494; }

    /* --- PANEL KANAN (PREVIEW AREA) --- */
    .cert-preview-container {
        flex: 1;
        background: #525659; /* Warna Background App PDF */
        height: 100%;
        /* KUNCI SCROLLBAR ADA DISINI: */
        overflow: auto; /* Aktifkan Scroll Horizontal & Vertikal */
        display: flex;
        align-items: flex-start; /* Mulai dari pojok kiri atas */
        justify-content: flex-start;
        padding: 40px; /* Memberi ruang napas */
        box-sizing: border-box;
        position: relative;
    }

    /* --- CANVAS SERTIFIKAT (UKURAN ASLI 100%) --- */
    #certificate-canvas {
        /* Ukuran Pasti A4 Landscape */
        width: 1123px; 
        height: 794px;
        
        position: relative;
        background-color: white;
        background-image: url('<?= base_url("assets/image/bg-sertifikat.png") ?>'); 
        background-size: 100% 100%;
        box-shadow: 0 0 20px rgba(0,0,0,0.5); /* Shadow efek kertas */
        text-align: left; 
        
        /* PENTING: Jangan di-shrink atau di-scale */
        flex-shrink: 0; 
        margin: auto; /* Agar posisi tengah jika layar sangat besar */
    }

    /* --- POSISI ELEMENT (Layout Aman) --- */
    
    /* Header */
    .c-header { position: absolute; top: 35px; left: 0; width: 100%; text-align: center; }
    .c-logo-main { height: 70px; display: inline-block; margin-bottom: 5px; object-fit: contain; }
    .c-brands-box { width: 100%; text-align: center; line-height: 0; margin-top: 2px; }
    .c-brand-img { height: 26px; margin: 0 10px; display: inline-block; vertical-align: middle; object-fit: contain; }

    /* Titles */
    .c-title { 
        position: absolute; top: 175px; 
        left: 0; width: 100%; text-align: center; 
        font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 52px; 
        color: #1a237e; letter-spacing: 8px; text-transform: uppercase; line-height: 1; 
    }
    
    .c-badge-wrap { position: absolute; top: 250px; left: 0; width: 100%; text-align: center; }
    .c-badge {
        background-color: #ff9800;
        width: 400px; height: 40px;
        border-radius: 50px;
        display: inline-block;
    }
    .c-badge-text {
        position: absolute; top: 0; left: 50%; transform: translateX(-50%);
        width: 400px; height: 40px; line-height: 40px;
        text-align: center; margin: 0;
        font-family: 'Poppins', sans-serif; font-size: 26px; letter-spacing: 3px; color: white;
    }

    .c-intro { 
        position: absolute; top: 320px; 
        width: 100%; text-align: center; 
        font-family: 'Poppins', sans-serif; font-size: 18px; color: #333; 
    }

    /* Nama Peserta */
    .c-name-wrap { position: absolute; top: 355px; width: 100%; text-align: center; }
    .c-name { 
        font-family: 'Arial', sans-serif; font-weight: bold; font-size: 56px; 
        color: #0088cc; display: inline-block; border-bottom: 3px solid #ff9800; 
        padding-bottom: 5px; min-width: 500px; 
    }

    /* Body Text */
    .c-body-wrap { 
        position: absolute; 
        top: 445px; 
        left: 50%; transform: translateX(-50%); 
        width: 850px; text-align: center; 
    }
    .c-body-text { 
        font-family: 'Poppins', sans-serif; font-size: 16px; 
        line-height: 1.6; color: #1a237e; margin: 0; 
    }
    .var-branch { font-weight: 800; text-transform: uppercase; }
    .var-grade { font-weight: 800; text-transform: uppercase; text-decoration: underline; color: #1a237e; font-size: 18px; }

    /* Footer */
    .c-footer { 
        position: absolute; 
        bottom: 35px; 
        width: 100%; text-align: center; 
    }
    .c-date { font-family: 'Times New Roman', serif; font-size: 16px; color: #333; margin-bottom: 2px; }
    
    .c-sig-box { height: 90px; width: 100%; display: flex; justify-content: center; align-items: flex-end; }
    .c-sig-img { height: 90px; width: auto; max-width: 250px; object-fit: contain; }
    .c-sig-line { width: 150px; border-bottom: 1px solid #333; height: 1px; margin-bottom: 10px; }
    
    .c-signer-role { font-family: 'Poppins', sans-serif; font-weight: 400; font-size: 14px; color: #1a237e; margin-top:2px; }
    .c-signer-name { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 18px; color: #1a237e; }

</style>


<div class="content-area">
    <div class="page-header">
        <h4 class="page-title">Generator Sertifikat Digital</h4>
    </div>

    <div id="cert-module-wrapper">
        
        <!-- KOLOM INPUT -->
        <div class="cert-controls">
            
            <div class="cert-group">
                <label class="cert-label">Nama Peserta</label>
                <input type="text" id="inpName" class="cert-input" value="MUHAMMAD AKBAR F" onkeyup="syncText()">
            </div>

            <div class="cert-group">
                <label class="cert-label">Logo</label>
                <div style="margin-bottom:8px;">
                    <small>Logo Utama:</small>
                    <input type="file" class="cert-input" accept="image/*" onchange="previewImage(this, 'imgLogoMain')">
                </div>
                <div>
                    <small>Logo Brand:</small>
                    <div id="brandList"></div>
                    <button class="btn-cert-add" onclick="addNewBrandInput()">+ Tambah Logo</button>
                </div>
            </div>

            <div class="cert-group" style="background: #eef2f5; padding: 10px; border-radius: 4px;">
                <label class="cert-label">Variabel:</label>
                <input type="text" id="inpBranch" class="cert-input" style="margin-bottom:5px;" value="AUTHORIZED SERVICE CENTER AZZAHRA COMPUTER TEGAL" onkeyup="syncText()">
                <input type="text" id="inpGrade" class="cert-input" value="SANGAT BAIK" onkeyup="syncText()">
            </div>

            <div class="cert-group">
                <label class="cert-label">Isi Teks Deskripsi</label>
                <textarea id="inpBody" class="cert-input" onkeyup="syncText()">Telah melaksanakan Praktek Kerja Lapangan di&#10;[CABANG]&#10;dengan kompetensi dan hasil akhir pencapaian&#10;[NILAI]</textarea>
            </div>

            <div class="cert-group">
                <label class="cert-label">Tanggal & TTD</label>
                <input type="text" id="inpDate" class="cert-input" value="Tegal, 1 Februari 2025" onkeyup="syncText()" style="margin-bottom:5px;">
                <input type="text" id="inpSigner" class="cert-input" value="Ferry Juanda, ST." onkeyup="syncText()" style="margin-bottom:5px;" placeholder="Nama Penandatangan">
                <input type="file" class="cert-input" accept="image/*" onchange="previewImage(this, 'imgSig')">
            </div>

            <button class="btn-cert-gen" onclick="generatePDF()">DOWNLOAD PDF</button>
        </div>

        <!-- KOLOM PREVIEW (SCROLLABLE) -->
        <div class="cert-preview-container">
            <div id="certificate-canvas">
                
                <div class="c-header">
                    <img src="<?= base_url("assets/image/Logo Tegal.png") ?>" id="imgLogoMain" class="c-logo-main" style="display:inline-block">
                    <div class="c-brands-box" id="brandContainer">
                        <img src="<?= base_url("assets/image/Asus.png") ?>" class="c-brand-img">
                        <img src="<?= base_url("assets/image/Infinix.png") ?>" class="c-brand-img">
                        <img src="<?= base_url("assets/image/Lenovo.png") ?>" class="c-brand-img">
                        <img src="<?= base_url("assets/image/Zyrex.png") ?>" class="c-brand-img">
                    </div>
                </div>

                <div class="c-title">SERTIFIKAT</div>
                <div class="c-badge-wrap">
                    <div class="c-badge"></div>
                    <p class="c-badge-text" style="margin-top: -20px">PENGHARGAAN</p>
                </div>
                <div class="c-intro">diberikan kepada :</div>

                <div class="c-name-wrap">
                    <span class="c-name" id="outName">MUHAMMAD AKBAR F</span>
                </div>

                <div class="c-body-wrap">
                    <p class="c-body-text" id="outBody"></p>
                </div>

                <div class="c-footer">
                    <div class="c-date" id="outDate">Tegal, 1 Februari 2025</div>
                    <div class="c-sig-box">
                        <img src="<?= base_url("assets/image/TTD_PakFerry.png") ?>" id="imgSig" class="c-sig-img" style="display:inline-block;">
                        <div id="placeholderSig" class="c-sig-line" style="display:none"></div>
                    </div>
                    <div class="c-signer-role">Branch Manager</div>
                    <div class="c-signer-name" id="outSigner">Ferry Juanda, ST.</div>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    // --- 1. SINKRONISASI TEXT (TETAP SAMA) ---
    function syncText() {
        document.getElementById('outName').innerText = document.getElementById('inpName').value;
        document.getElementById('outDate').innerText = document.getElementById('inpDate').value;
        document.getElementById('outSigner').innerText = document.getElementById('inpSigner').value;

        const rawBody = document.getElementById('inpBody').value;
        const valBranch = document.getElementById('inpBranch').value;
        const valGrade = document.getElementById('inpGrade').value;

        let formattedBody = rawBody
            .replace(/\n/g, '<br>')
            .replace(/\[CABANG\]/g, `<span class="var-branch">${valBranch}</span>`)
            .replace(/\[NILAI\]/g, `<span class="var-grade">${valGrade}</span>`);

        document.getElementById('outBody').innerHTML = formattedBody;
    }

    // --- 2. PREVIEW IMAGE (TETAP SAMA) ---
    function previewImage(input, targetId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(targetId);
                img.src = e.target.result;
                img.style.display = 'inline-block';
                if(targetId === 'imgSig') document.getElementById('placeholderSig').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // --- 3. BRAND LOGOS (TETAP SAMA) ---
    window.onload = function() {
        addNewBrandInput();
        syncText();
    };

    function addNewBrandInput() {
        const list = document.getElementById('brandList');
        const div = document.createElement('div');
        div.className = 'brand-row';
        div.innerHTML = `
            <input type="file" class="cert-input" accept="image/*" onchange="renderBrands()">
            <button class="btn-cert-del" onclick="this.parentElement.remove(); renderBrands();">Hapus</button>
        `;
        list.appendChild(div);
    }

    function renderBrands() {
        const container = document.getElementById('brandContainer');
        container.innerHTML = '';
        const inputs = document.querySelectorAll('#brandList input[type="file"]');
        inputs.forEach(inp => {
            if (inp.files && inp.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'c-brand-img';
                    container.appendChild(img);
                }
                reader.readAsDataURL(inp.files[0]);
            }
        });
    }

    // --- 4. GENERATE PDF (VERSI FIX BEST PRACTICE JS) ---
    window.jsPDF = window.jspdf.jsPDF;

    function generatePDF() {
        const element = document.getElementById('certificate-canvas');
        const btn = document.querySelector('.btn-cert-gen');
        const originalText = btn.innerHTML;

        btn.innerHTML = "Processing...";
        btn.disabled = true;

        // TEKNIK PENTING: Scroll ke 0,0 agar koordinat canvas akurat
        const previewContainer = document.querySelector('.cert-preview-container');
        previewContainer.scrollTop = 0;
        previewContainer.scrollLeft = 0;

        // TEKNIK PENTING: Tunggu sampai Font Google siap 100%
        document.fonts.ready.then(function () {

            // Tambahkan delay kecil (500ms) untuk memastikan rendering browser selesai
            setTimeout(() => {

                html2canvas(element, {
                    scale: 2, // Resolusi Tinggi
                    useCORS: true, // Izinkan gambar external
                    allowTaint: true,
                    scrollY: 0,
                    scrollX: 0,
                    backgroundColor: '#ffffff', // Pastikan background putih

                    // Memaksa html2canvas merender font dengan lebih akurat
                    onclone: (clonedDoc) => {
                        // Trik: Kadang elemen tersembunyi/scroll mempengaruhi render
                        const clonedElement = clonedDoc.getElementById('certificate-canvas');
                        clonedElement.style.margin = '0';
                    }
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');

                    // Setup PDF A4 Landscape (mm)
                    const pdf = new jsPDF('l', 'mm', 'a4');
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = pdf.internal.pageSize.getHeight();

                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

                    const name = document.getElementById('inpName').value.replace(/[^a-zA-Z0-9]/g, '_') || 'Sertifikat';
                    pdf.save(`Sertifikat_${name}.pdf`);

                    btn.innerHTML = originalText;
                    btn.disabled = false;

                }).catch(err => {
                    console.error("PDF Gen Error:", err);
                    alert("Gagal membuat PDF. Cek Console.");
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });

            }, 500); // Akhir Timeout
        });
    }
</script>

<?php $this->load->view('Template/footer'); ?>