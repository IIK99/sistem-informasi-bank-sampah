@extends(Auth::user()->role === 'nasabah' ? 'layouts.nasabah' : 'layouts.admin')

@section('title', 'Saldo Saya')
@section('page_title', 'Saldo Anda')
@section('page_subtitle', 'Ringkasan keuangan dari setoran sampah')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
            <div class="card-body p-5 text-center">
                <i class="bi bi-wallet2 display-3 opacity-50 mb-3"></i>
                <h6 class="text-uppercase fw-bold text-white-50">Total Saldo Aktif</h6>
                <h2 class="display-5 fw-bold mb-0">Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}</h2>
                <hr class="border-light opacity-25 my-4">
                <a href="{{ route('nasabah.penarikan.index') }}" class="btn btn-light rounded-20px px-4 text-success fw-bold">
                    Tarik Saldo Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
