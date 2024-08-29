<?php

// Logo configuration
$logo_path = ROOT_PATH . 'assets/images/logo.webp';
$logo_alt = 'Logo';
?>
<header class="header">
    <div class="container-fluid py-5 px-sm-5">
        <div class="header__wrapper d-flex align-items-center justify-content-start">
            <div class="nav-right">
                <a href="<?php echo ROOT_PATH ?>" class="header__logo d-flex gap-2 text-decoration-none">
                    <img src="<?php echo $logo_path; ?>" alt="<?php echo $logo_alt; ?>">
                </a>
            </div>
        </div>
    </div>
</header>