<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="<?php echo base_url(); ?>assets/template/beck/dist/images/logo.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test - Buat Akun Baru - Azzahra Computer</title>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/beck/dist/css/app.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/file/alert/animet.css">

    <style>
        body.login {
            min-height: 100vh;
            background: linear-gradient(135deg, #4338ca, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 900px;
            background: #ffffff;
            border-radius: 26px;
            overflow: hidden;
            box-shadow: 0 35px 80px rgba(0,0,0,0.25);
            animation: fadeSlide 0.9s ease-out;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-left {
            background: linear-gradient(135deg, #4338ca, #2563eb);
            color: #fff;
            padding: 60px;
            position: relative;
            text-align: center;
        }

        .login-left h1 {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.4;
            margin-top: 50px;
        }

        .login-left p {
            font-size: 14px;
            margin-top: 15px;
            line-height: 1.6;
        }

        .login-left-form {
            background: linear-gradient(135deg, #4338ca, #2563eb);
            padding: 60px;
            color: #fff;
        }

        .login-left-form h2 {
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 10px;
        }

        .login-left-form p {
            font-size: 14px;
            text-align: center;
            margin-bottom: 35px;
            opacity: 0.9;
        }

        .login-right {
            padding: 60px;
        }

        .login-right h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #2d3748;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2d3748;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="date"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #4338ca;
            box-shadow: 0 0 0 4px rgba(67, 56, 202, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4338ca, #2563eb);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(67, 56, 202, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background: #e2e8f0;
            color: #2d3748;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background: #cbd5e0;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-danger {
            background: #fed7d7;
            color: #742a2a;
            border-left: 4px solid #f56565;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border-left: 4px solid #48bb78;
        }

        .alert-info {
            background: #bee3f8;
            color: #2c5282;
            border-left: 4px solid #4299e1;
        }

        .error-text {
            color: #e53e3e;
            font-size: 13px;
            margin-top: 6px;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                max-width: 100%;
            }

            .login-left,
            .login-right {
                padding: 40px 30px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-row.full {
                grid-template-columns: 1fr;
            }
        }

        .form-column-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-column-2.full {
            grid-template-columns: 1fr;
        }
        .login::before {
            background-image: none !important;
        }
    </style>
</head>
<body class="login">
    <div class="login-wrapper">
        <div class="login-left-form">
            <h2>📝 Buat Akun Baru</h2>
            <p>Formulir Test Pembuatan Akun Karyawan</p>
            <p style="font-size: 12px; margin-top: 30px;">Silahkan isi semua data dengan lengkap dan benar</p>
        </div>

        <div class="login-right">
            <a href="<?php echo base_url('Auth'); ?>" class="btn-back">← Kembali ke Login</a>

            <?php if (isset($sukses)): ?>
                <div class="alert alert-success">
                    ✓ <?php echo $sukses; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    ✗ <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger">
                    <?php echo validation_errors(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo base_url('Auth/tes_buat_akun'); ?>" enctype="multipart/form-data">

                <!-- Data Pribadi -->
                <h3 style="color: #2d3748; margin-top: 30px; margin-bottom: 20px; font-size: 16px;">Data Pribadi</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kry_kode">Kode Karyawan *</label>
                        <input type="text" id="kry_kode" name="kry_kode" required value="<?php echo set_value('kry_kode'); ?>" placeholder="cth: KRY001">
                    </div>
                    <div class="form-group">
                        <label for="kry_nik">NIK *</label>
                        <input type="text" id="kry_nik" name="kry_nik" required value="<?php echo set_value('kry_nik'); ?>" placeholder="cth: 3273101234567890">
                    </div>
                </div>

                <div class="form-group">
                    <label for="kry_nama">Nama Lengkap *</label>
                    <input type="text" id="kry_nama" name="kry_nama" required value="<?php echo set_value('kry_nama'); ?>" placeholder="cth: Budi Santoso">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kry_tempat">Tempat Lahir *</label>
                        <input type="text" id="kry_tempat" name="kry_tempat" required value="<?php echo set_value('kry_tempat'); ?>" placeholder="cth: Jakarta">
                    </div>
                    <div class="form-group">
                        <label for="kry_tgl_lahir">Tanggal Lahir *</label>
                        <input type="date" id="kry_tgl_lahir" name="kry_tgl_lahir" required value="<?php echo set_value('kry_tgl_lahir'); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="kry_alamat">Alamat Lengkap *</label>
                    <textarea id="kry_alamat" name="kry_alamat" required placeholder="cth: Jl. Merdeka No. 123, Jakarta Pusat"><?php echo set_value('kry_alamat'); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="kry_tlp">Nomor Telepon *</label>
                    <input type="text" id="kry_tlp" name="kry_tlp" required value="<?php echo set_value('kry_tlp'); ?>" placeholder="cth: 08123456789">
                </div>

                <!-- Data Akun -->
                <h3 style="color: #2d3748; margin-top: 30px; margin-bottom: 20px; font-size: 16px;">Data Akun</h3>

                <div class="form-group">
                    <label for="kry_username">Username *</label>
                    <input type="text" id="kry_username" name="kry_username" required value="<?php echo set_value('kry_username'); ?>" placeholder="cth: budi.santoso">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kry_pswd">Password *</label>
                        <input type="password" id="kry_pswd" name="kry_pswd" required placeholder="Min. 6 karakter">
                    </div>
                    <div class="form-group">
                        <label for="con_pswd">Konfirmasi Password *</label>
                        <input type="password" id="con_pswd" name="con_pswd" required placeholder="Ulangi password">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kry_level">Level Akun *</label>
                        <select id="kry_level" name="kry_level" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="Admin" <?php echo set_select('kry_level', 'Admin'); ?>>Admin</option>
                            <option value="Kasir" <?php echo set_select('kry_level', 'Kasir'); ?>>Kasir</option>
                            <option value="Customer Service" <?php echo set_select('kry_level', 'Customer Service'); ?>>Customer Service</option>
                            <option value="Teknisi" <?php echo set_select('kry_level', 'Teknisi'); ?>>Teknisi</option>
                            <option value="HR" <?php echo set_select('kry_level', 'HR'); ?>>HR</option>
                        </select>
                    </div>
                </div>

                <!-- Data Kepegawaian -->
                <h3 style="color: #2d3748; margin-top: 30px; margin-bottom: 20px; font-size: 16px;">Data Kepegawaian</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kry_tgl_masuk">Tanggal Masuk *</label>
                        <input type="date" id="kry_tgl_masuk" name="kry_tgl_masuk" required value="<?php echo set_value('kry_tgl_masuk'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="kry_tgl_keluar">Tanggal Keluar (Opsional)</label>
                        <input type="date" id="kry_tgl_keluar" name="kry_tgl_keluar" value="<?php echo set_value('kry_tgl_keluar'); ?>">
                    </div>
                </div>

                <button type="submit" class="btn-submit">✓ Buat Akun</button>

            </form>
        </div>
    </div>

</body>
</html>
