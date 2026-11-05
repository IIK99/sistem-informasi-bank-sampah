@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang kembali, ' . auth()->user()->name . '. Berikut ringkasan sistem Anda.')

@section('content')
    <div class="row g-4">
        <!-- Banner Admin -->
        <div class="col-12">
            <div class="card border-0 text-white p-4 rounded-4 shadow-sm position-relative overflow-hidden"
                style="background: linear-gradient(135deg, #0b2219 0%, #123326 100%); border-left: 5px solid #B4F105 !important;">
                <div class="position-relative z-1">
                    @php
                        $jam = \Carbon\Carbon::now()->format('H');
                        if ($jam >= 5 && $jam < 11) {
                            $salam = 'Selamat Pagi';
                        } elseif ($jam >= 11 && $jam < 15) {
                            $salam = 'Selamat Siang';
                        } elseif ($jam >= 15 && $jam < 18) {
                            $salam = 'Selamat Sore';
                        } else {
                            $salam = 'Selamat Malam';
                        }
                    @endphp

                    <h3 class="fw-bold mb-2 text-white">
                        {{ $salam }}, {{ auth()->user()->name }}!
                    </h3>
                    <p class="text-white-50 mb-0" style="max-width: 650px; font-size: 0.95rem;">
                        Anda masuk ke sistem sebagai <strong
                            class="text-white text-uppercase">{{ auth()->user()->role }}</strong>. Seluruh menu dan hak
                        akses pada panel navigasi telah disesuaikan secara otomatis.
                    </p>
                </div>
                <i class="bi bi-recycle position-absolute text-white d-none d-md-block"
                    style="font-size: 4.75rem; right: 2rem; top: 50%; transform: translateY(-50%); opacity: 0.12; pointer-events: none; z-index: 0;"></i>
            </div>
        </div>

        <!-- LINE CHART LAPORAN KELUAR MASUK SALDO -->
        @include('partials.chart_keluar_masuk')
    </div>
@endsection
