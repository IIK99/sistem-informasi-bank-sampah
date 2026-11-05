@extends('layouts.admin')

@section('title', 'Data Nasabah')
@section('page_title', 'Kelola Data Nasabah')
@section('page_subtitle', 'Manajemen akun pengguna nasabah')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Daftar Nasabah</h5>
        @canAction('Nasabah', 'create')
        <a href="{{ route('nasabah.create') }}" class="btn btn-primary rounded-20px px-3">
            Tambah Nasabah
        </a>
        @endcanAction
    </div>
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success rounded-3">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>No. HP / WA</th>
                        <th>Email</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nasabahs as $index => $nasabah)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-medium">{{ $nasabah->name }}</td>
                        <td><code>{{ $nasabah->username }}</code></td>
                        <td>
                            @if ($nasabah->no_hp)
                                @php
                                    $cleanWa = preg_replace('/[^0-9]/', '', $nasabah->no_hp);
                                    if (str_starts_with($cleanWa, '0')) {
                                        $cleanWa = '62' . substr($cleanWa, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="text-success text-decoration-none fw-semibold d-inline-flex align-items-center gap-1" title="Kirim Pesan WhatsApp">
                                    <i class="bi bi-whatsapp"></i>
                                    <span>{{ $nasabah->no_hp }}</span>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $nasabah->email ?? '-' }}</td>
                        <td class="text-end">
                            @canAction('Nasabah', 'update')
                            <a href="{{ route('nasabah.edit', $nasabah->id) }}" class="btn btn-sm btn-outline-primary rounded-20px">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcanAction

                            @canAction('Nasabah', 'delete')
                            <form action="{{ route('nasabah.destroy', $nasabah->id) }}" method="POST" class="d-inline form-delete" data-item-title="nasabah {{ $nasabah->name }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-20px">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcanAction
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data nasabah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
