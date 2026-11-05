@extends($layout ?? 'layouts.admin')

@section('title', 'Profil Akun')
@section('page_title', 'Profil Pengguna')
@section('page_subtitle', 'Kelola informasi pribadi akun Anda')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8 col-xl-7">
        
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 border-0 shadow-sm rounded-4 mb-4 py-3 px-4">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <div class="fw-semibold">{{ session('success') }}</div>
            </div>
        @endif

        {{-- KARTU PROFIL MODERN GAYA SOSIAL MEDIA --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            {{-- BANNER HEADER (Cover Banner) --}}
            <div class="position-relative" style="height: 80px; background: linear-gradient(135deg, #198754, #0d5c36);">
            </div>

            <div class="card-body px-4 px-md-5 pb-5 pt-0">
                {{-- AVATAR OVERLAPPING BANNER & USER HEADER --}}
                <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-end justify-content-between gap-3 mb-4" style="margin-top: -55px;">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-end gap-3 text-center text-sm-start">
                        {{-- Circular Avatar --}}
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-lg position-relative"
                            style="width: 105px; height: 105px; background: linear-gradient(135deg, #198754, #0d5c36); color: white; border: 4px solid var(--bs-card-bg, #ffffff); font-size: 2.75rem; font-weight: 700;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        {{-- Nama & Handle --}}
                        <div class="pt-2 pt-sm-0">
                            <h4 class="fw-bold mb-0 d-inline-flex align-items-center gap-2">
                                <span>{{ $user->name }}</span>
                                <i class="bi bi-patch-check-fill text-success" title="Akun Terverifikasi" style="font-size: 1.2rem;"></i>
                            </h4>
                            <div class="text-muted small fw-medium mt-1">
                                <span>@</span><span>{{ $user->username }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Role Pill Badge --}}
                    <div class="mb-1">
                        @if($user->role === 'nasabah')
                            
                        @elseif($user->role === 'petugas')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-semibold">
                                <i class="bi bi-shield-check me-1"></i> Petugas
                            </span>
                        @else
                            <span class="badge bg-dark-subtle text-dark border border-dark-subtle rounded-pill px-3 py-2 fw-semibold">
                                <i class="bi bi-person-gear me-1"></i> Administrator
                            </span>
                        @endif
                    </div>
                </div>

                <hr class="my-4 opacity-25">

                {{-- FORM EDIT PROFIL --}}
                <form action="{{ route('profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary text-uppercase small mb-3" style="font-size: 0.78rem; letter-spacing: 0.5px;">
                            <i class="bi bi-person-lines-fill text-success me-1"></i> Informasi Pribadi
                        </h6>

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0 text-secondary">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <input type="text" name="name" class="form-control border-start-0 rounded-end-3 @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $user->name) }}" required placeholder="Masukkan nama lengkap">
                            </div>
                            @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        {{-- Username (Read-only) --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0 text-secondary">
                                    <i class="bi bi-at"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 rounded-end-3 bg-body-tertiary text-muted" 
                                    value="{{ $user->username }}" readonly disabled>
                            </div>
                            <div class="form-text small text-muted"><i class="bi bi-lock-fill me-1"></i> Username unik sistem dan tidak dapat diubah.</div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0 text-secondary">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input type="email" name="email" class="form-control border-start-0 rounded-end-3 @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $user->email) }}" placeholder="nama@email.com">
                            </div>
                            @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        {{-- Nomor WhatsApp / Handphone --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nomor WhatsApp / Handphone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0 text-success">
                                    <i class="bi bi-whatsapp"></i>
                                </span>
                                <input type="text" name="no_hp" class="form-control border-start-0 rounded-end-3 @error('no_hp') is-invalid @enderror" 
                                    value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 081234567890">
                            </div>
                            <div class="form-text small text-muted"><i class="bi bi-info-circle me-1"></i> Digunakan untuk menerima bukti setoran sampah dan notifikasi penarikan saldo via WhatsApp.</div>
                            @error('no_hp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4 pt-2">
                        <h6 class="fw-bold text-secondary text-uppercase small mb-3" style="font-size: 0.78rem; letter-spacing: 0.5px;">
                            <i class="bi bi-shield-lock-fill text-success me-1"></i> Keamanan Akun
                        </h6>

                        {{-- Password --}}
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-secondary">
                                Ganti Password <span class="text-muted fw-normal">(Kosongkan jika tidak ingin mengubah)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0 text-secondary">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <input type="password" name="password" class="form-control border-start-0 rounded-end-3 @error('password') is-invalid @enderror" 
                                    placeholder="Ketik password baru (min. 6 karakter)">
                            </div>
                            @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2 pt-3">
                        <button type="submit" class="btn btn-success fw-bold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-2 flex-grow-1">
                            <i class="bi bi-check2-circle fs-5"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill d-inline-flex align-items-center justify-content-center">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
