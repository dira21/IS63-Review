{{-- resources/views/auth/login.blade.php --}}
{{-- Halaman STANDALONE — tidak extend layout master --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Simahaswa</title>
 
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
          rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">
 
                        {{-- Header --}}
                        <div class="text-center mb-4">
                            <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                            <h1 class="h4 text-gray-900 mb-1 font-weight-bold">Simahaswa</h1>
                            <p class="text-muted small">
                                Sistem Informasi Manajemen Mahasiswa
                            </p>
                        </div>
 
                        {{-- Flash message sukses (misal: setelah logout) --}}
                        @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                        </div>
                        @endif
 
                        {{-- Error validasi — konsisten dengan pola Bab 6A --}}
                        @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</div>
                            @endforeach
                        </div>
                        @endif
 
                        {{-- Form Login --}}
                        <form method="POST" action="{{ route('login.attempt') }}" class="user">
                            @csrf
 
                            <div class="form-group">
                                <input type="email" name="email"
                                       value="{{ old('email') }}"
                                       class="form-control form-control-user
                                              {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       placeholder="Alamat Email" autofocus>
                            </div>
 
                            <div class="form-group">
                                <input type="password" name="password"
                                       class="form-control form-control-user
                                              {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                       placeholder="Password">
                            </div>
 
                            <div class="form-group">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="remember" id="remember"
                                           class="custom-control-input"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">
                                        Ingat saya
                                    </label>
                                </div>
                            </div>
 
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login
                            </button>
                        </form>
 
                        <hr>
                        <div class="text-center">
                            <small class="text-muted">
                                Demo: admin@simahaswa.com / password123
                            </small>
                        </div>
 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>
</html>