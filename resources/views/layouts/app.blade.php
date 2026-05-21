{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
 
    <title>@yield('title', 'Dashboard') - Simahaswa</title>
 
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
          rel="stylesheet">
 
    {{-- Font Awesome & SB Admin 2 --}}
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
 
    {{-- CSS tambahan dari halaman child --}}
    @stack('styles')
</head>
<body id="page-top">
 
<div id="wrapper">
 
    {{-- ===== SIDEBAR ===== --}}
    @include('partials.sidebar')
 
    {{-- ===== CONTENT WRAPPER ===== --}}
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
 
            {{-- ===== NAVBAR ===== --}}
            @include('partials.navbar')
 
            {{-- ===== PAGE CONTENT ===== --}}
            <div class="container-fluid">
 
                {{-- Page Heading --}}
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">
                        @yield('page-title', 'Dashboard')
                    </h1>
                    {{-- Tombol aksi (misal: Tambah Data) dari halaman child --}}
                    @yield('page-action')
                </div>
 
                {{-- Flash Message: Sukses --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif
 
                {{-- Flash Message: Error --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif
 
                {{-- Konten dari halaman child --}}
                @yield('content')
 
            </div>
            {{-- ===== END PAGE CONTENT ===== --}}
 
        </div>
 
        {{-- ===== FOOTER ===== --}}
        @include('partials.footer')
 
    </div>
</div>
 
{{-- Scroll to Top --}}
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
 
{{-- Scripts --}}
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
 
{{-- JS tambahan dari halaman child --}}
@stack('scripts')
 
</body>
</html>