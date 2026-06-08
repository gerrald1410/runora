@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="logo mb-3"><span class="text-danger">RUN</span><span>ORA</span></h2>
                        <p class="text-muted">Masuk ke akun Anda</p>
                    </div>
                    
                    <form id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="loginEmail" placeholder="Masukkan email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" id="loginPassword" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </button>
                    </form>
                    
                    <p class="text-center mt-4 mb-0">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-danger fw-semibold">Daftar di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const response = await fetch('{{ route("login") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                email: document.getElementById('loginEmail').value,
                password: document.getElementById('loginPassword').value
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert('Login gagal! Periksa email dan password Anda.');
        }
    });
</script>
@endpush