<!DOCTYPE html>
<html>
<head>
    <title>Add Account</title>
</head>
<body>

<h2>Add Account</h2>

<form method="post" action="<?= site_url('test_accounts/create') ?>">

<input name="kry_kode" placeholder="Kode" required><br>
<input name="kry_nik" placeholder="NIK" required><br>
<input name="kry_nama" placeholder="Nama" required><br>
<input name="kry_tempat" placeholder="Tempat Lahir" required><br>

Tanggal Lahir:<br>
<input type="date" name="kry_tgl_lahir" required><br>

<textarea name="kry_alamat" placeholder="Alamat" required></textarea><br>
<input name="kry_tlp" placeholder="Telepon" required><br>

<input name="kry_username" placeholder="Username" required><br>
<input type="password" name="kry_pswd" placeholder="Password" required><br>

Level:<br>
<select name="kry_level" required>
    <option>Admin</option>
    <option>Customer Service</option>
    <option>HR</option>
    <option>Kasir</option>
    <option>Teknisi</option>
</select><br>

Tanggal Masuk:<br>
<input type="date" name="kry_tgl_masuk" required><br>

Tanggal Keluar:<br>
<input type="date" name="kry_tgl_keluar"><br><br>

<button type="submit">Create Account</button>

</form>

</body>
</html>
