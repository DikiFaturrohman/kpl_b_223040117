<?php
// nav.php
// Pastikan session sudah dimulai di file yang meng-include nav.php jika $_SESSION diakses di sini.
// Misalnya, di index.php, detail.php, dll., panggil session_start() di awal.
$currentPage = basename($_SERVER['SCRIPT_NAME']); // SCRIPT_NAME lebih reliable
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$is_admin = $is_logged_in && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$username = $is_logged_in ? htmlspecialchars($_SESSION['username']) : '';

$keyword_search_nav = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8') : '';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top py-3">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <span>CAMPUS BLOG</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link me-3 <?php if ($currentPage == 'index.php') echo 'active'; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3 <?php if ($currentPage == 'about.php') echo 'active'; ?>" href="about.php">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3 <?php if ($currentPage == 'contact.php') echo 'active'; ?>" href="contact.php">Kontak</a>
                </li>
                <?php /* Link "Blog Saya" dan "Tulis Artikel" sekarang hanya di dropdown untuk user biasa */ ?>
            </ul>
            <form class="d-flex my-2 my-lg-0 me-lg-3" role="search" method="GET" action="index.php"> 
                <input class="form-control me-2" type="search" name="keyword" placeholder="Cari artikel..." aria-label="Search" value="<?= $keyword_search_nav; ?>">
                <button class="btn btn-outline-warning" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <div class="navbar-nav">
                <?php if ($is_logged_in): ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownUserLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> Halo, <?= $username; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUserLink">
                            <?php if ($is_admin): ?>
                                <li><a class="dropdown-item <?php if (strpos($currentPage, 'dasboard.php') !== false) echo 'active'; ?>" href="admin/dasboard.php"><i class="fas fa-tachometer-alt fa-fw me-2"></i>Admin Panel</a></li>
                            <?php else: // Pengguna Biasa ?>
                                <li><a class="dropdown-item <?php if ($currentPage == 'view.php') echo 'active'; ?>" href="view.php"><i class="fas fa-newspaper fa-fw me-2"></i>Blog Saya</a></li>
                                <li><a class="dropdown-item <?php if ($currentPage == 'write.php') echo 'active'; ?>" href="write.php"><i class="fas fa-edit fa-fw me-2"></i>Tulis Artikel</a></li>
                                <li><a class="dropdown-item <?php if ($currentPage == 'profile.php') echo 'active'; ?>" href="profile.php"><i class="fas fa-user-cog fa-fw me-2"></i>Profil Saya</a></li> 
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-warning me-2"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                    <a href="register.php" class="btn btn-outline-light"><i class="fas fa-user-plus me-1"></i>Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
