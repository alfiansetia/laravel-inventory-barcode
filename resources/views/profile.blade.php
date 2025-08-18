@extends('layouts.template', ['title' => 'Profile'])

@push('css')
@endpush

@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="alert alert-secondary alert-title d-flex align-items-center" role="alert">
                <i class="ti ti-edit fs-5 me-2"></i> Ubah Password
            </div>
            {{-- ✅ Alert Pesan --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Password Lama <span class="text-danger">*</span></label>
                            <input type="password" name="password_lama"
                                class="form-control @error('password_lama') is-invalid @enderror" autocomplete="off"
                                required autofocus>
                            @error('password_lama')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password_baru"
                                class="form-control @error('password_baru') is-invalid @enderror" autocomplete="off"
                                required>
                            @error('password_baru')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="konfirmasi_password"
                                class="form-control @error('konfirmasi_password') is-invalid @enderror" autocomplete="off"
                                required>
                            @error('konfirmasi_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-4 pb-1 mt-5 border-top">
                    <div class="d-grid gap-3 d-sm-flex justify-content-md-start pt-1">
                        <a href="{{ route('home') }}" class="btn btn-default px-4">
                            <i class="fas fa-arrow-left me-1"></i>Batal</a>
                        <button type="submit" class="btn btn-secondary px-4">
                            <i class="fas fa-save me-1"></i>Simpan</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
@push('js')
@endpush
