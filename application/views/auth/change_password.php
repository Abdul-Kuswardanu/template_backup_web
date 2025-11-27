<?php
$additional_js = array(
    base_url('assets/js/password-validation.js')
);

$this->load->view('inc/header', array(
    'title' => $title,
    'user_id' => $user_id
));
?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-key"></i> Ubah Password</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo site_url('auth/process_change_password'); ?>" method="POST" id="changePasswordForm">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    <i class="bi bi-lock"></i> Password Saat Ini
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                        <i class="bi bi-eye" id="eye_current_password"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">
                                    <i class="bi bi-key"></i> Password Baru
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                        <i class="bi bi-eye" id="eye_new_password"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    Password harus 8-16 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter khusus
                                </div>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="passwordStrengthText" class="text-muted"></small>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Konfirmasi Password Baru
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                        <i class="bi bi-eye" id="eye_confirm_password"></i>
                                    </button>
                                </div>
                                <div id="passwordMatch" class="form-text"></div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Ubah Password
                                </button>
                                <a href="<?php echo site_url('home'); ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$custom_js = "
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById('eye_' + inputId);
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('bi-eye');
        eye.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.remove('bi-eye-slash');
        eye.classList.add('bi-eye');
    }
}
";

$this->load->view('inc/footer', array(
    'additional_js' => $additional_js,
    'custom_js' => $custom_js
));
?>

