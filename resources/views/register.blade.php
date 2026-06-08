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
                    
                    <form id="registerForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" class="form-control" id="regName" placeholder="Masukkan nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="regEmail" placeholder="Masukkan email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" id="regPassword" placeholder="Buat password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No Telpon</label>
                            <input type="text" class="form-control" id="regPhone" placeholder="Masukkan No Telpon">
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2">
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
        
        const response = await fetch('{{ route("register") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                name: document.getElementById('regName').value,
                email: document.getElementById('regEmail').value,
                password: document.getElementById('regPassword').value,
                phone: document.getElementById('regPhone').value
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Registrasi berhasil!');
            window.location.href = data.redirect;
        } else {
            alert('Registrasi gagal! Email mungkin sudah terdaftar.');
        }
    });
</script>
@endpush