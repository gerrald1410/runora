@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="logo mb-3"><span class="text-danger">RUN</span><span>ORA</span></h2>
                        <p class="text-muted">Buat akun baru</p>
                    </div>
                    
                    <!-- Alert untuk notifikasi -->
                    <div id="alertMessage" class="alert d-none" role="alert"></div>
                    
                    <form id="registerForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control" id="regName" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="regEmail" placeholder="Masukkan email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" id="regPassword" placeholder="Buat password (minimal 6 karakter)" required>
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="regPasswordConfirmation" placeholder="Konfirmasi password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No Telepon</label>
                            <input type="text" class="form-control" id="regPhone" placeholder="Masukkan No Telepon (opsional)">
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100 py-2" id="registerBtn">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </button>
                    </form>
                    
                    <p class="text-center mt-4 mb-0">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-danger fw-semibold">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Ambil nilai form
        const name = document.getElementById('regName').value;
        const email = document.getElementById('regEmail').value;
        const password = document.getElementById('regPassword').value;
        const passwordConfirmation = document.getElementById('regPasswordConfirmation').value;
        const phone = document.getElementById('regPhone').value;
        
        // Validasi password
        if (password !== passwordConfirmation) {
            showAlert('Password dan konfirmasi password tidak cocok!', 'danger');
            return;
        }
        
        if (password.length < 6) {
            showAlert('Password minimal 6 karakter!', 'danger');
            return;
        }
        
        if (name.trim() === '') {
            showAlert('Nama harus diisi!', 'danger');
            return;
        }
        
        if (email.trim() === '') {
            showAlert('Email harus diisi!', 'danger');
            return;
        }
        
        // Disable button dan tampilkan loading
        const submitBtn = document.getElementById('registerBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        
        try {
            // Kirim data ke server
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('password_confirmation', passwordConfirmation);
            formData.append('phone', phone);
            
            const response = await fetch('{{ route("register") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Tampilkan notifikasi sukses
                showAlert(data.message || 'Registrasi berhasil!', 'success');
                
                // Redirect ke halaman login setelah 2 detik
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                // Tampilkan pesan error
                showAlert(data.message || 'Registrasi gagal! Silakan coba lagi.', 'danger');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan. Silakan coba lagi.', 'danger');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
    
    // Fungsi untuk menampilkan alert
    function showAlert(message, type) {
        const alertDiv = document.getElementById('alertMessage');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        alertDiv.classList.remove('d-none');
        
        // Auto hide setelah 5 detik
        setTimeout(() => {
            alertDiv.classList.add('d-none');
        }, 5000);
    }
    
    // Real-time password match indicator (opsional)
    const passwordInput = document.getElementById('regPassword');
    const confirmInput = document.getElementById('regPasswordConfirmation');
    
    function checkPasswordMatch() {
        if (confirmInput.value.length > 0) {
            if (passwordInput.value !== confirmInput.value) {
                confirmInput.classList.add('is-invalid');
                confirmInput.classList.remove('is-valid');
            } else {
                confirmInput.classList.remove('is-invalid');
                confirmInput.classList.add('is-valid');
            }
        } else {
            confirmInput.classList.remove('is-invalid');
            confirmInput.classList.remove('is-valid');
        }
    }
    
    passwordInput.addEventListener('keyup', checkPasswordMatch);
    confirmInput.addEventListener('keyup', checkPasswordMatch);
</script>
@endpush