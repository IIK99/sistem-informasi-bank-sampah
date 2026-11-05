<!-- LINE CHART LAPORAN KELUAR MASUK SALDO -->
<div class="col-12">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                        style="width: 36px; height: 36px; background: rgba(25, 135, 84, 0.12); color: #198754;">
                        <i class="bi bi-graph-up-arrow fs-6"></i>
                    </span>
                    <h5 class="fw-bold mb-0">Laporan Keluar Masuk Saldo</h5>
                </div>
                <p class="text-muted small mb-0">
                    Pergerakan hasil jual ke pengepul (masuk) dan transaksi setoran sampah ke nasabah (keluar).
                </p>
            </div>

            <!-- Ringkasan Metrik -->
            <div class="d-flex flex-wrap gap-2">
                <div class="px-3 py-2 rounded-3 border bg-light d-flex align-items-center gap-2 shadow-none">
                    <span class="rounded-circle" style="width: 10px; height: 10px; background-color: #198754;"></span>
                    <div>
                        <div class="text-muted" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 600;">
                            Saldo Masuk (Jual Pengepul)</div>
                        <div class="fw-bold text-success" style="font-size: 0.95rem;">
                            Rp {{ number_format($totalSaldoMasukSemua ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="px-3 py-2 rounded-3 border bg-light d-flex align-items-center gap-2 shadow-none">
                    <span class="rounded-circle" style="width: 10px; height: 10px; background-color: #dc3545;"></span>
                    <div>
                        <div class="text-muted" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 600;">
                            Saldo Keluar (Setoran Nasabah)</div>
                        <div class="fw-bold text-danger" style="font-size: 0.95rem;">
                            Rp {{ number_format($totalSaldoKeluarSemua ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="px-3 py-2 rounded-3 border bg-light d-flex align-items-center gap-2 shadow-none">
                    <span class="rounded-circle" style="width: 10px; height: 10px; background-color: #0d6efd;"></span>
                    <div>
                        <div class="text-muted" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 600;">
                            Sisa Kas Saldo</div>
                        <div class="fw-bold text-primary" style="font-size: 0.95rem;">
                            Rp {{ number_format($saldoKasSekarang ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Canvas Chart -->
        <div class="position-relative" style="height: 330px; width: 100%;">
            <canvas id="chartKeluarMasukSaldo"></canvas>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('chartKeluarMasukSaldo');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const labels = @json($chartLabels ?? []);
            const dataMasuk = @json($chartMasuk ?? []);
            const dataKeluar = @json($chartKeluar ?? []);

            // Gradien halus untuk Saldo Masuk (Hijau)
            const gradMasuk = ctx.createLinearGradient(0, 0, 0, 300);
            gradMasuk.addColorStop(0, 'rgba(25, 135, 84, 0.28)');
            gradMasuk.addColorStop(1, 'rgba(25, 135, 84, 0.01)');

            // Gradien halus untuk Saldo Keluar (Merah)
            const gradKeluar = ctx.createLinearGradient(0, 0, 0, 300);
            gradKeluar.addColorStop(0, 'rgba(220, 53, 69, 0.25)');
            gradKeluar.addColorStop(1, 'rgba(220, 53, 69, 0.01)');

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Saldo Masuk (Jual ke Pengepul)',
                            data: dataMasuk,
                            borderColor: '#198754',
                            backgroundColor: gradMasuk,
                            fill: true,
                            tension: 0.38,
                            borderWidth: 2.5,
                            pointBackgroundColor: '#198754',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4.5,
                            pointHoverRadius: 7
                        },
                        {
                            label: 'Saldo Keluar (Setoran Nasabah)',
                            data: dataKeluar,
                            borderColor: '#dc3545',
                            backgroundColor: gradKeluar,
                            fill: true,
                            tension: 0.38,
                            borderWidth: 2.5,
                            pointBackgroundColor: '#dc3545',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4.5,
                            pointHoverRadius: 7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0c1813',
                            titleFont: {
                                size: 13,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    let val = context.parsed.y || 0;
                                    return ' ' + context.dataset.label + ': Rp ' + val.toLocaleString(
                                        'id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(val) {
                                    if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) +
                                        ' jt';
                                    if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + ' rb';
                                    return 'Rp ' + val;
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                borderDash: [5, 5]
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
