<?php

require_once 'Menu.php';
session_start();

// untuk menyimpan file gambar yang diunggah
define('UPLOAD_DIR', __DIR__ . '/images/');
define('ALLOWED_EXT', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

if (!isset($_SESSION['menus'])) $_SESSION['menus'] = [];
if (!isset($_SESSION['idx']))   $_SESSION['idx']   = 1;
if (!isset($_SESSION['flash'])) $_SESSION['flash'] = null;

// helper upload gambar
function handleUpload($fileKey)
{
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Gagal mengunggah gambar.'];
        return null;
    }

    $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXT)) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Format gambar tidak didukung (gunakan jpg/jpeg/png/gif/webp).'];
        return null;
    }

    $filename = uniqid('menu_') . '.' . $ext;
    $target   = UPLOAD_DIR . $filename;

    if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $target)) {
        // Disimpan sebagai path file lokal (relatif terhadap index.php), bukan URL.
        return 'images/' . $filename;
    }
    return null;
}

// function add remove dan update menu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $nama  = trim($_POST['nama'] ?? '');
        $fnb   = $_POST['fnb'] ?? '';
        $rasa  = trim($_POST['rasa'] ?? '');
        $price = (int)($_POST['price'] ?? 0);

        if ($nama === '' || $fnb === '' || $rasa === '' || $price <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Format salah. Semua field (nama, tipe, rasa, harga) wajib diisi dengan benar.'];
        } else {
            $gambar = handleUpload('gambar') ?? '';
            $id     = $_SESSION['idx'];
            $_SESSION['menus'][] = new Menu($id, $nama, $fnb, $rasa, $price, $gambar);
            $_SESSION['flash']   = ['type' => 'success', 'msg' => "Menu \"$nama\" berhasil ditambahkan dengan id $id."];
            $_SESSION['idx']++;
        }
    } elseif ($action === 'update') {
        $id    = (int)($_POST['id'] ?? 0);
        $nama  = trim($_POST['nama'] ?? '');
        $fnb   = $_POST['fnb'] ?? '';
        $rasa  = trim($_POST['rasa'] ?? '');
        $price = (int)($_POST['price'] ?? 0);
        $found = false;

        if ($nama === '' || $fnb === '' || $rasa === '' || $price <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Format salah. Semua field (nama, tipe, rasa, harga) wajib diisi dengan benar.'];
        } else {
            foreach ($_SESSION['menus'] as $m) {
                if ($m->getId() === $id) {
                    $m->setNama($nama);
                    $m->setFnb($fnb);
                    $m->setRasa($rasa);
                    $m->setPrice($price);
                    $newGambar = handleUpload('gambar');
                    if ($newGambar !== null) {
                        $m->setGambar($newGambar);
                    }
                    $found = true;
                    break;
                }
            }
            $_SESSION['flash'] = $found
                ? ['type' => 'success', 'msg' => "Menu dengan id $id berhasil diperbarui."]
                : ['type' => 'error', 'msg' => "Menu dengan id $id tidak ditemukan."];
        }
    } elseif ($action === 'remove') {
        $id    = (int)($_POST['id'] ?? 0);
        $found = false;
        foreach ($_SESSION['menus'] as $key => $m) {
            if ($m->getId() === $id) {
                unset($_SESSION['menus'][$key]);
                $_SESSION['menus'] = array_values($_SESSION['menus']);
                $found = true;
                break;
            }
        }
        $_SESSION['flash'] = $found
            ? ['type' => 'success', 'msg' => "Menu dengan id $id berhasil dihapus."]
            : ['type' => 'error', 'msg' => "Menu dengan id $id tidak ditemukan."];
    }

    // Redirect supaya form tidak ter-submit ulang saat halaman di-refresh.
    header('Location: index.php');
    exit;
}

// data untuk ditampilkan di tabel
$keyword = trim($_GET['search'] ?? '');
$editId  = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editMenu = null;

if ($editId !== null) {
    foreach ($_SESSION['menus'] as $m) {
        if ($m->getId() === $editId) {
            $editMenu = $m;
            break;
        }
    }
}

$displayMenus = $_SESSION['menus'];
if ($keyword !== '') {
    $displayMenus = array_filter($displayMenus, function ($m) use ($keyword) {
        return stripos($m->getNama(), $keyword) !== false;
    });
}

$flash = $_SESSION['flash'];
$_SESSION['flash'] = null; // tampil sekali saja
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Menu Bioskop X</title>
<style>
    :root {
        --blue: #1d4ed8;
        --green: #16a34a;
        --red: #dc2626;
        --bg: #f3f4f6;
    }
    * { box-sizing: border-box; }
    body {
        font-family: Arial, Helvetica, sans-serif;
        background: var(--bg);
        margin: 0;
        padding: 0 0 40px;
        color: #1f2937;
    }
    header {
        background: var(--blue);
        color: #fff;
        padding: 20px 24px;
    }
    header h1 { margin: 0 0 4px; font-size: 22px; }
    header p { margin: 0; font-size: 14px; opacity: .9; }
    .container {
        max-width: 960px;
        margin: 24px auto;
        padding: 0 16px;
    }
    .card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,.1);
    }
    .card h2 { margin-top: 0; font-size: 18px; color: var(--blue); }
    .alert {
        padding: 10px 14px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .alert-success { background: #dcfce7; color: var(--green); border: 1px solid var(--green); }
    .alert-error   { background: #fee2e2; color: var(--red);   border: 1px solid var(--red); }
    form.inline { display: inline; }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 12px;
        align-items: end;
    }
    label { display: block; font-size: 13px; margin-bottom: 4px; color: #374151; }
    input[type=text], input[type=number], select, input[type=file] {
        width: 100%;
        padding: 8px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }
    .actions { margin-top: 14px; }
    button, .btn {
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        color: #fff;
    }
    .btn-primary { background: var(--blue); }
    .btn-danger  { background: var(--red); }
    .btn-secondary { background: #6b7280; }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    th, td {
        border: 1px solid #e5e7eb;
        padding: 8px 10px;
        text-align: left;
        vertical-align: middle;
    }
    th { background: #eef2ff; color: var(--blue); }
    img.thumb {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }
    .no-img {
        width: 56px; height: 56px;
        display: flex; align-items: center; justify-content: center;
        background: #f3f4f6; color: #9ca3af; font-size: 10px;
        border-radius: 6px; border: 1px dashed #d1d5db;
    }
    .search-row { display: flex; gap: 8px; }
    .search-row input { flex: 1; }
    .empty-note { color: #6b7280; font-style: italic; }
</style>
</head>
<body>

<header>
    <h1>🎬 Bioskop X &mdash; Katalog Menu</h1>
    <p>Kelola menu food &amp; beverage bioskop di sini.</p>
</header>

<div class="container">

    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($flash['msg']) ?>
        </div>
    <?php endif; ?>

    <!-- ================= FORM TAMBAH / EDIT ================= -->
    <div class="card">
        <h2><?= $editMenu ? 'Edit Menu (ID ' . $editMenu->getId() . ')' : 'Tambah Menu Baru' ?></h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?= $editMenu ? 'update' : 'add' ?>">
            <?php if ($editMenu): ?>
                <input type="hidden" name="id" value="<?= $editMenu->getId() ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div>
                    <label>Nama Menu</label>
                    <input type="text" name="nama" required
                           value="<?= htmlspecialchars($editMenu ? $editMenu->getNama() : '') ?>">
                </div>
                <div>
                    <label>Tipe</label>
                    <select name="fnb" required>
                        <option value="">-- Pilih --</option>
                        <?php foreach (['food' => 'Food', 'beverage' => 'Beverage'] as $val => $label): ?>
                            <option value="<?= $val ?>"
                                <?= ($editMenu && $editMenu->getFnb() === $val) ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Rasa</label>
                    <input type="text" name="rasa" required
                           value="<?= htmlspecialchars($editMenu ? $editMenu->getRasa() : '') ?>">
                </div>
                <div>
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" min="1" required
                           value="<?= htmlspecialchars($editMenu ? $editMenu->getPrice() : '') ?>">
                </div>
                <div>
                    <label>Gambar <?= $editMenu ? '(kosongkan jika tidak diganti)' : '' ?></label>
                    <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.gif,.webp">
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn-primary">
                    <?= $editMenu ? 'Simpan Perubahan' : 'Tambah Menu' ?>
                </button>
                <?php if ($editMenu): ?>
                    <a href="index.php" class="btn btn-secondary" style="text-decoration:none; display:inline-block;">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- ================= FORM SEARCH ================= -->
    <div class="card">
        <h2>Cari Menu</h2>
        <form method="get" class="search-row">
            <input type="text" name="search" placeholder="Cari berdasarkan nama menu..."
                   value="<?= htmlspecialchars($keyword) ?>">
            <button type="submit" class="btn-primary">Cari</button>
            <?php if ($keyword !== ''): ?>
                <a href="index.php" class="btn btn-secondary" style="text-decoration:none; padding:8px 16px; display:inline-block;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- ================= TABEL MENU ================= -->
    <div class="card">
        <h2>Daftar Menu <?= $keyword !== '' ? '(hasil pencarian "' . htmlspecialchars($keyword) . '")' : '' ?></h2>

        <?php if (empty($displayMenus)): ?>
            <p class="empty-note">
                <?= empty($_SESSION['menus']) ? 'Belum ada menu. Tambahkan menu di atas.' : 'Menu tidak ditemukan.' ?>
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Rasa</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($displayMenus as $m): ?>
                        <tr>
                            <td><?= $m->getId() ?></td>
                            <td>
                                <?php if ($m->getGambar() !== '' && file_exists($m->getGambar())): ?>
                                    <img class="thumb" src="<?= htmlspecialchars($m->getGambar()) ?>" alt="<?= htmlspecialchars($m->getNama()) ?>">
                                <?php else: ?>
                                    <div class="no-img">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($m->getNama()) ?></td>
                            <td><?= htmlspecialchars(ucfirst($m->getFnb())) ?></td>
                            <td><?= htmlspecialchars($m->getRasa()) ?></td>
                            <td>Rp<?= number_format($m->getPrice(), 0, ',', '.') ?></td>
                            <td>
                                <a class="btn btn-primary" style="text-decoration:none; display:inline-block; padding:6px 12px;"
                                   href="index.php?edit=<?= $m->getId() ?>">Edit</a>
                                <form class="inline" method="post"
                                      onsubmit="return confirm('Hapus menu \"<?= htmlspecialchars(addslashes($m->getNama())) ?>\"?');">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="id" value="<?= $m->getId() ?>">
                                    <button type="submit" class="btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>
</body>
</html>