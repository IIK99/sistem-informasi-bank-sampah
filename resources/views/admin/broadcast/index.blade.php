@extends('layouts.admin')

@section('title', 'Broadcast Pengumuman')
@section('page_title', 'Broadcast Pesan Email')
@section('page_subtitle', 'Kirim pengumuman jadwal penimbangan sampah atau info penting ke seluruh nasabah.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-lite.css') }}">
    <style>
        .note-editor.note-frame {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            overflow: hidden;
        }

        .note-toolbar {
            background-color: #f8fafc !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="bi bi-megaphone me-2 text-success"></i>Formulir Siaran Email
                            </h5>
                            <p class="text-muted small mb-0">Pesan diformat dengan editor visual dan dikirim langsung ke
                                Gmail warga.</p>
                        </div>
                        <span>
                            Total Penerima: <strong>{{ $totalNasabah }} Nasabah</strong>
                        </span>
                    </div>

                    <form action="{{ route('admin.broadcast.send') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Judul Pengumuman / Subjek Email</label>
                            <input type="text" name="judul" class="form-control"
                                value="Jadwal Penimbangan Sampah Hari Ini" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Isi Pesan Pengumuman</label>
                            {{-- Wajib memiliki id="summernote" --}}
                            <textarea id="summernote" name="pesan" required>{!! $defaultTemplate !!}</textarea>
                            <div class="form-text small text-muted mt-2">
                                <i class="bi bi-info-circle me-1"></i> Gunakan toolbar di atas untuk mengatur teks tebal,
                                miring, list poin, atau penataan paragraf.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-semibold">
                                <i class="bi bi-send-fill me-2"></i>Kirim Broadcast Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/summernote/summernote-lite.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Tuliskan pengumuman lengkap di sini...',
                tabsize: 2,
                height: 280,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['codeview', 'help']]
                ]
            });
        });
    </script>
@endpush
