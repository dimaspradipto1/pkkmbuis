<!-- Evaluasi Stat Cards -->
<div class="row g-3 mb-3">
    <!-- Total Mahasiswa -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #3b82f6 !important; transition: all 0.2s ease;">
            <div class="card-body d-flex align-items-center p-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">TOTAL MAHASISWA</div>
                    <div class="fs-4 fw-bold text-dark lh-1 mt-1">{{ number_format($totalMahasiswa ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sudah Mengisi Evaluasi -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #10b981 !important; transition: all 0.2s ease;">
            <div class="card-body d-flex align-items-center p-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">SUDAH MENGISI EVALUASI</div>
                    <div class="fs-4 fw-bold text-success lh-1 mt-1">{{ number_format($sudahMengisi ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Belum Mengisi Evaluasi -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #ef4444 !important; transition: all 0.2s ease;">
            <div class="card-body d-flex align-items-center p-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="bi bi-x-circle-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">BELUM MENGISI EVALUASI</div>
                    <div class="fs-4 fw-bold text-danger lh-1 mt-1">{{ number_format($belumMengisi ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (!empty($facultyStats) && empty($isFacultySpecific))
    <!-- Cards Progres Per Fakultas -->
    <div class="row g-3 mb-4">
        @foreach ($facultyStats as $fak)
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid {{ $fak['border_color'] }} !important; background: #ffffff;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                                     style="width: 40px; height: 40px; min-width: 40px; background: {{ $fak['bg_gradient'] }};">
                                    <i class="bi {{ $fak['icon'] }} fs-6"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem; line-height: 1.2;">{{ $fak['code'] }}</div>
                                    <div class="text-muted extra-small text-truncate" style="max-width: 170px;" title="{{ $fak['fullName'] }}">{{ $fak['name'] }}</div>
                                </div>
                            </div>
                            <span class="badge {{ $fak['badge_bg'] }} border rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.72rem;">
                                {{ $fak['persen'] }}%
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-baseline mt-2 mb-1">
                            <div>
                                <span class="fs-4 fw-bold text-dark lh-1">{{ number_format($fak['sudah']) }}</span>
                                <span class="text-muted extra-small">/ {{ number_format($fak['total']) }} Mahasiswa</span>
                            </div>
                            <div class="text-end">
                                <span class="text-muted extra-small">Belum: <strong class="text-danger">{{ number_format($fak['belum']) }}</strong></span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress rounded-pill bg-light mt-2" style="height: 6px;">
                            <div class="progress-bar rounded-pill" role="progressbar" 
                                 style="width: {{ $fak['persen'] }}%; background: {{ $fak['bg_gradient'] }};" 
                                 aria-valuenow="{{ $fak['persen'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
