@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">

        {{-- Top Banner: Portal Absensi --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(45deg, #087C39, #FFF742) !important;">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 text-center text-md-start mb-3">
                            <div>
                                <h4 class="fw-bold mb-1 text-white fs-5 fs-md-4">
                                    <i class="bi {{ Auth::user()->role == 'mahasiswa' ? 'bi-qr-code-scan' : 'bi-qr-code' }} me-2"></i>
                                    {{ Auth::user()->role == 'mahasiswa' ? 'Scan Absensi Mandiri' : 'Portal QR Kehadiran' }}
                                </h4>
                                <p class="mb-0 text-white opacity-75 small d-none d-md-block">
                                    {{ Auth::user()->role == 'mahasiswa' ? 'Silahkan scan QR Panitia untuk mencatat kehadiran Anda.' : 'Pilih sesi berikut untuk menampilkan QR Code kepada mahasiswa.' }}
                                </p>
                            </div>
                        </div>

                        @if (Auth::user()->role != 'mahasiswa')
                            <div class="row g-2 g-md-3">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="p-2 p-md-3 bg-white bg-opacity-25 rounded-3 border border-white border-opacity-10 h-100 text-center">
                                        <p class="text-white fw-bold mb-2 small text-uppercase" style="font-size: 0.7rem;">Hari Pertama</p>
                                        <button class="btn btn-light btn-sm w-100 fw-bold shadow-sm extra-small py-2" style="color: #087C39;" onclick="showAttendanceQR(1)">
                                            <i class="bi bi-qr-code me-1"></i> QR Absensi
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="p-2 p-md-3 bg-white bg-opacity-25 rounded-3 border border-white border-opacity-10 h-100 text-center">
                                        <p class="text-white fw-bold mb-2 small text-uppercase" style="font-size: 0.7rem;">Hari Kedua</p>
                                        <button class="btn btn-light btn-sm w-100 fw-bold shadow-sm extra-small py-2" style="color: #087C39;" onclick="showAttendanceQR(2)">
                                            <i class="bi bi-qr-code me-1"></i> QR Absensi
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 offset-sm-3 offset-md-0">
                                    <div class="p-2 p-md-3 bg-white bg-opacity-25 rounded-3 border border-white border-opacity-10 h-100 text-center">
                                        <p class="text-white fw-bold mb-2 small text-uppercase" style="font-size: 0.7rem;">Hari Ketiga</p>
                                        <button class="btn btn-light btn-sm w-100 fw-bold shadow-sm extra-small py-2" style="color: #087C39;" onclick="showAttendanceQR(3)">
                                            <i class="bi bi-qr-code me-1"></i> QR Absensi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start mt-2">
                                <a href="{{ route('absen-scan.index') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm btn-sm" style="color: #087C39;">
                                    <i class="bi bi-qr-code-scan me-1"></i> Scan QR Kehadiran
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Banner: Modul Post Test --}}
        @if (Auth::user()->role == 'mahasiswa')
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm overflow-hidden" style="background: #fff; border-radius: 12px;">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-success bg-opacity-10 p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-book-half text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0">Modul, Pritest dan Postes Mahasiswa</h5>
                                        <p class="text-muted small mb-0 d-none d-md-block">Akses materi pembelajaran dan kerjakan evaluasi modul Anda di sini.</p>
                                    </div>
                                </div>
                                <a href="{{ route('modulposttest.index') }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Buka Modul & Ujian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <div class="row">
            {{-- Main Content Area --}}
            <div class="col-lg-8">
                <div class="row">
                    @if (Auth::user()->role != 'mahasiswa')
                        {{-- Main Attendance Hub: Multi-Day --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                                <div class="card-body p-3 p-xl-4">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                        <div>
                                            <h5 class="card-title mb-0 fs-5">Hub Absensi <span>| All Days</span></h5>
                                            <p class="text-muted extra-small mb-0 opacity-75">Monitoring kehadiran mahasiswa
                                                real-time.</p>
                                        </div>

                                        <div class="d-flex align-items-center flex-wrap gap-2 ms-xl-auto w-100 w-xl-auto">
                                            {{-- Detail Link --}}
                                            <div class="dropdown me-md-2 mb-2 mb-md-0">
                                                <button
                                                    class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 fw-bold extra-small dropdown-toggle shadow-none"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Manage
                                                </button>
                                                <ul
                                                    class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 extra-small">
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('absenpertama.index') }}"><i
                                                                class="bi bi-calendar-event me-2"></i> Hari Pertama</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('absenkedua.index') }}"><i
                                                                class="bi bi-calendar-event me-2"></i> Hari Kedua</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('absenketiga.index') }}"><i
                                                                class="bi bi-calendar-event me-2"></i> Hari Ketiga</a></li>
                                                </ul>
                                            </div>

                                            {{-- Search Input --}}
                                            <div class="position-relative w-100 w-md-auto mb-2 mb-md-0">
                                                <input type="text" id="attendanceSearch"
                                                    class="form-control form-control-sm rounded-pill px-4 ps-5 w-100 shadow-none border-light"
                                                    placeholder="Cari..."
                                                    style="background: #f8fafc; min-width: 200px; height: 38px;">
                                                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted opacity-50"
                                                    style="left: 0;"></i>
                                            </div>

                                            {{-- Tabs --}}
                                            <ul class="nav nav-pills nav-pills-custom gap-1 flex-nowrap overflow-auto pb-1"
                                                id="attendanceTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="day1-tab" data-bs-toggle="tab" data-bs-target="#day1"
                                                        type="button" role="tab">H-1</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="day2-tab" data-bs-toggle="tab" data-bs-target="#day2"
                                                        type="button" role="tab">H-2</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="day3-tab" data-bs-toggle="tab" data-bs-target="#day3"
                                                        type="button" role="tab">H-3</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content pt-2" id="attendanceTabContent">
                                        {{-- Day 1 Pane --}}
                                        <div class="tab-pane fade show active" id="day1" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-sm table-hover align-middle mb-0 attendance-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2">NO</th>
                                                            <th>NAMA</th>
                                                            <th>PAGI</th>
                                                            <th class="text-end pe-3">SORE</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allAbsen1 as $index => $abs)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $index + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $abs->user->name }}</span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $statusPagi = $abs->hadir_pagi;
                                                                        $initialPagi = substr($statusPagi, 0, 1);
                                                                        $colorPagi = match ($statusPagi) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorPagi }}">{{ $initialPagi }}</span>
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    @php
                                                                        $statusSore = $abs->hadir_sore;
                                                                        $initialSore = substr($statusSore, 0, 1);
                                                                        $colorSore = match ($statusSore) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorSore }}">{{ $initialSore }}</span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center py-3 text-muted">No
                                                                    data</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- Day 2 Pane --}}
                                        <div class="tab-pane fade" id="day2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-sm table-hover align-middle mb-0 attendance-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2">NO</th>
                                                            <th>NAMA</th>
                                                            <th>PAGI</th>
                                                            <th class="text-end pe-3">SORE</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allAbsen2 as $index => $abs)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $index + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $abs->user->name }}</span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $statusPagi = $abs->hadir_pagi;
                                                                        $initialPagi = substr($statusPagi, 0, 1);
                                                                        $colorPagi = match ($statusPagi) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorPagi }}">{{ $initialPagi }}</span>
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    @php
                                                                        $statusSore = $abs->hadir_sore;
                                                                        $initialSore = substr($statusSore, 0, 1);
                                                                        $colorSore = match ($statusSore) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorSore }}">{{ $initialSore }}</span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center py-3 text-muted">No
                                                                    data</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- Day 3 Pane --}}
                                        <div class="tab-pane fade" id="day3" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-sm table-hover align-middle mb-0 attendance-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2">NO</th>
                                                            <th>NAMA</th>
                                                            <th>PAGI</th>
                                                            <th class="text-end pe-3">SORE</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allAbsen3 as $index => $abs)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $index + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $abs->user->name }}</span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $statusPagi = $abs->hadir_pagi;
                                                                        $initialPagi = substr($statusPagi, 0, 1);
                                                                        $colorPagi = match ($statusPagi) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorPagi }}">{{ $initialPagi }}</span>
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    @php
                                                                        $statusSore = $abs->hadir_sore;
                                                                        $initialSore = substr($statusSore, 0, 1);
                                                                        $colorSore = match ($statusSore) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorSore }}">{{ $initialSore }}</span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center py-3 text-muted">No
                                                                    data</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="mt-3 pt-3 border-top border-light d-flex flex-wrap justify-content-center gap-2">
                                        <div
                                            class="extra-small text-muted fw-semibold me-2 opacity-75 d-flex align-items-center text-uppercase">
                                            Absensi Legend:</div>
                                        <div class="d-flex align-items-center gap-1 extra-small">
                                            <span class="badge bg-success py-1 px-2">H: Hadir</span>
                                            <span class="badge bg-warning py-1 px-2">I: Izin</span>
                                            <span class="badge bg-info text-white py-1 px-2">S: Sakit</span>
                                            <span class="badge bg-danger py-1 px-2">A: Alpa</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Main Discipline Hub: Multi-Day --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                                <div class="card-body p-3 p-xl-4">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                        <div>
                                            <h5 class="card-title mb-0 fs-5">Hub Kedisiplinan <span>| All Days</span></h5>
                                            <p class="text-muted extra-small mb-0 opacity-75">Monitoring kedisiplinan
                                                (Atribut, Waktu, Perilaku) mahasiswa.</p>
                                        </div>

                                        <div class="d-flex align-items-center flex-wrap gap-2 ms-xl-auto w-100 w-xl-auto">
                                            {{-- Detail Link --}}
                                            <div class="dropdown me-md-2 mb-2 mb-md-0">
                                                <button
                                                    class="btn btn-outline-info btn-sm rounded-pill px-3 py-2 fw-bold extra-small dropdown-toggle shadow-none"
                                                    type="button" data-bs-toggle="dropdown"
                                                    style="border-color: rgba(13, 202, 240, 0.3);">
                                                    <i class="bi bi-shield-check me-1"></i> Manage
                                                </button>
                                                <ul
                                                    class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 extra-small">
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('kedisiplinanpertama.index') }}"><i
                                                                class="bi bi-calendar-check me-2"></i> Hari Pertama</a>
                                                    </li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('kedisiplinankedua.index') }}"><i
                                                                class="bi bi-calendar-check me-2"></i> Hari Kedua</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('kedisiplinanketiga.index') }}"><i
                                                                class="bi bi-calendar-check me-2"></i> Hari Ketiga</a></li>
                                                </ul>
                                            </div>

                                            {{-- Search Input --}}
                                            <div class="position-relative w-100 w-md-auto mb-2 mb-md-0">
                                                <input type="text" id="disciplineSearch"
                                                    class="form-control form-control-sm rounded-pill px-4 ps-5 w-100 shadow-none border-light"
                                                    placeholder="Cari..."
                                                    style="background: #f8fafc; min-width: 200px; height: 38px;">
                                                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted opacity-50"
                                                    style="left: 0;"></i>
                                            </div>

                                            {{-- Tabs --}}
                                            <ul class="nav nav-pills nav-pills-custom gap-1 flex-nowrap overflow-auto pb-1"
                                                id="disciplineTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="dis1-tab" data-bs-toggle="tab" data-bs-target="#dis1"
                                                        type="button" role="tab">H-1</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="dis2-tab" data-bs-toggle="tab" data-bs-target="#dis2"
                                                        type="button" role="tab">H-2</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="dis3-tab" data-bs-toggle="tab" data-bs-target="#dis3"
                                                        type="button" role="tab">H-3</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content pt-2" id="disciplineTabContent">
                                        @foreach ([['id' => 'dis1', 'data' => $allDis1], ['id' => 'dis2', 'data' => $allDis2], ['id' => 'dis3', 'data' => $allDis3]] as $pane)
                                            <div class="tab-pane fade {{ $pane['id'] == 'dis1' ? 'show active' : '' }}"
                                                id="{{ $pane['id'] }}" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-sm table-hover align-middle mb-0 discipline-table">
                                                        <thead>
                                                            <tr class="text-uppercase extra-small fw-bold"
                                                                style="background: #f8fafc; color: #012970;">
                                                                <th class="ps-3 py-2">NO</th>
                                                                <th>NAMA</th>
                                                                <th>ATRIBUT</th>
                                                                <th>WAKTU</th>
                                                                <th class="text-end pe-3">PERILAKU</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($pane['data'] as $index => $dis)
                                                                <tr class="extra-small border-bottom border-light">
                                                                    <td class="ps-3 py-2 text-muted">{{ $index + 1 }}
                                                                    </td>
                                                                    <td class="text-nowrap"><span
                                                                            class="fw-bold text-dark search-target">{{ $dis->user->name }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $stA = strtolower(
                                                                                $dis->kelengkapan_atribut ?? '',
                                                                            );
                                                                            $colA =
                                                                                $stA === 'lengkap'
                                                                                    ? 'bg-success'
                                                                                    : ($stA === 'tidak lengkap'
                                                                                        ? 'bg-danger'
                                                                                        : 'bg-light text-muted border');
                                                                            $iconA =
                                                                                $stA === 'lengkap'
                                                                                    ? 'A'
                                                                                    : ($stA === 'tidak lengkap'
                                                                                        ? 'TL'
                                                                                        : '-');
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $colA }}">{{ $iconA }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $stW = strtolower(
                                                                                $dis->ketepatan_waktu ?? '',
                                                                            );
                                                                            $colW =
                                                                                $stW === 'tepat waktu'
                                                                                    ? 'bg-success'
                                                                                    : ($stW === 'terlambat'
                                                                                        ? 'bg-danger'
                                                                                        : 'bg-light text-muted border');
                                                                            $iconW =
                                                                                $stW === 'tepat waktu'
                                                                                    ? 'W'
                                                                                    : ($stW === 'terlambat'
                                                                                        ? 'TL'
                                                                                        : '-');
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $colW }}">{{ $iconW }}</span>
                                                                    </td>
                                                                    <td class="text-end pe-3">
                                                                        @php
                                                                            $stP = strtolower($dis->perilaku ?? '');
                                                                            $colP = match ($stP) {
                                                                                'sangat baik' => 'bg-success',
                                                                                'baik' => 'bg-primary',
                                                                                'cukup' => 'bg-warning',
                                                                                'kurang' => 'bg-danger',
                                                                                default
                                                                                    => 'bg-light text-muted border',
                                                                            };
                                                                            $iconP = match ($stP) {
                                                                                'sangat baik' => 'SB',
                                                                                'baik' => 'B',
                                                                                'cukup' => 'C',
                                                                                'kurang' => 'K',
                                                                                default => '-',
                                                                            };
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $colP }}">{{ $iconP }}</span>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5"
                                                                        class="text-center py-3 text-muted">No data</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div
                                        class="mt-3 pt-3 border-top border-light d-flex flex-wrap justify-content-center gap-2">
                                        <div
                                            class="extra-small text-muted fw-semibold me-2 opacity-75 d-flex align-items-center">
                                            KEDISIPLINAN:</div>
                                        <div
                                            class="d-flex align-items-center gap-1 extra-small flex-wrap justify-content-center">
                                            <span class="badge bg-success py-1 px-2">A: Lengkap</span>
                                            <span class="badge bg-danger py-1 px-2">TL: Tidak Lengkap/Telat</span>
                                            <span class="badge bg-success py-1 px-2">W: Tepat</span>
                                            <span class="badge bg-success py-1 px-2">SB: Sangat Baik</span>
                                            <span class="badge bg-primary py-1 px-2">B: Baik</span>
                                            <span class="badge bg-warning py-1 px-2">C: Cukup</span>
                                            <span class="badge bg-danger py-1 px-2">K: Kurang</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Main Test Hub: Multi-Module --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                                <div class="card-body p-3 p-xl-4">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                        <div>
                                            <h5 class="card-title mb-0 fs-5">Hub Hasil Test <span>| Pre & Post</span></h5>
                                            <p class="text-muted extra-small mb-0 opacity-75">Monitoring hasil pretest,
                                                posttest dan tugas kelompok mahasiswa.</p>
                                        </div>

                                        <div class="d-flex align-items-center flex-wrap gap-2 ms-xl-auto w-100 w-xl-auto">
                                            {{-- Detail Link --}}
                                            <div class="dropdown me-md-2 mb-2 mb-md-0">
                                                <button
                                                    class="btn btn-outline-success btn-sm rounded-pill px-3 py-2 fw-bold extra-small dropdown-toggle shadow-none"
                                                    type="button" data-bs-toggle="dropdown"
                                                    style="border-color: rgba(34, 197, 94, 0.3);">
                                                    <i class="bi bi-journal-check me-1"></i> Manage
                                                </button>
                                                <ul
                                                    class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 extra-small">
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('hasiltest.index') }}"><i
                                                                class="bi bi-list-check me-2"></i> Kelola Hasil Test</a>
                                                    </li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('soaltugaskelompok.index') }}"><i
                                                                class="bi bi-people me-2"></i> Tugas Kelompok</a></li>
                                                </ul>
                                            </div>

                                            {{-- Search Input --}}
                                            <div class="position-relative w-100 w-md-auto mb-2 mb-md-0">
                                                <input type="text" id="testSearch"
                                                    class="form-control form-control-sm rounded-pill px-4 ps-5 w-100 shadow-none border-light"
                                                    placeholder="Cari..."
                                                    style="background: #f8fafc; min-width: 200px; height: 38px;">
                                                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted opacity-50"
                                                    style="left: 0;"></i>
                                            </div>

                                            {{-- Tabs --}}
                                            <ul class="nav nav-pills nav-pills-custom gap-1 flex-nowrap overflow-auto pb-1"
                                                id="testTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="m1-tab" data-bs-toggle="tab" data-bs-target="#m1"
                                                        type="button" role="tab">M-1</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="m2-tab" data-bs-toggle="tab" data-bs-target="#m2"
                                                        type="button" role="tab">M-2</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="m3-tab" data-bs-toggle="tab" data-bs-target="#m3"
                                                        type="button" role="tab">M-3</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="m4-tab" data-bs-toggle="tab" data-bs-target="#m4"
                                                        type="button" role="tab">M-4</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="m5-tab" data-bs-toggle="tab" data-bs-target="#m5"
                                                        type="button" role="tab">M-5</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content pt-2" id="testTabContent">
                                        {{-- Modul Panes --}}
                                        @foreach ([['id' => 'm1', 'data' => $allM1], ['id' => 'm2', 'data' => $allM2], ['id' => 'm3', 'data' => $allM3], ['id' => 'm4', 'data' => $allM4]] as $index => $pane)
                                            <div class="tab-pane fade {{ $pane['id'] == 'm1' ? 'show active' : '' }}"
                                                id="{{ $pane['id'] }}" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover align-middle mb-0 test-table">
                                                        <thead>
                                                            <tr class="text-uppercase extra-small fw-bold"
                                                                style="background: #f8fafc; color: #012970;">
                                                                <th class="ps-3 py-2">NO</th>
                                                                <th>NAMA</th>
                                                                <th>PRE-TEST</th>
                                                                <th class="text-end pe-3">POST-TEST</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($pane['data'] as $idx => $user)
                                                                <tr class="extra-small border-bottom border-light">
                                                                    <td class="ps-3 py-2 text-muted">{{ $idx + 1 }}</td>
                                                                    <td class="text-nowrap"><span
                                                                            class="fw-bold text-dark search-target">{{ $user->name }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $pre = $user->hasilTests
                                                                                ->where('type', 'pretest')
                                                                                ->first();
                                                                            $skorPre = $pre ? $pre->skor : '-';
                                                                            $colPre = $pre ? ($pre->skor >= 65 ? 'text-success' : 'text-danger') : 'text-muted opacity-50';
                                                                        @endphp
                                                                        <span
                                                                            class="fw-bold {{ $colPre }}">{{ $skorPre }}</span>
                                                                    </td>
                                                                    <td class="text-end pe-3">
                                                                        @php
                                                                            $post = $user->hasilTests
                                                                                ->where('type', 'posttest')
                                                                                ->first();
                                                                            $skorPost = $post ? $post->skor : '-';
                                                                            $colPost = $post ? ($post->skor >= 65 ? 'text-success' : 'text-danger') : 'text-muted opacity-50';
                                                                        @endphp
                                                                        <span
                                                                            class="fw-bold {{ $colPost }}">{{ $skorPost }}</span>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center py-3 text-muted">
                                                                        No data</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- Group Task Pane (M5) --}}
                                        <div class="tab-pane fade" id="m5" role="tabpanel">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover align-middle mb-0 test-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2">NO</th>
                                                            <th>NAMA</th>
                                                            <th class="text-end pe-3">NILAI TUGAS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allTugas as $idx => $tug)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $idx + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $tug->user->name }}</span>
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    <span class="fw-bold text-success">100</span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3" class="text-center py-3 text-muted">No
                                                                    data</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-3 pt-3 border-top border-light d-flex flex-wrap justify-content-center gap-2">
                                        <div
                                            class="extra-small text-muted fw-semibold me-2 opacity-75 d-flex align-items-center">
                                            KETERANGAN:</div>
                                        <div
                                            class="d-flex align-items-center gap-1 extra-small flex-wrap justify-content-center">
                                            <span class="badge bg-light text-success border border-success border-opacity-10 py-1 px-2">Nilai >= 65: Tuntas</span>
                                            <span class="badge bg-light text-danger border border-danger border-opacity-10 py-1 px-2">Nilai < 65: Belum Tuntas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Academic Bar Chart Hub --}}
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow-sm overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <h5 class="card-title mb-0 fs-5">Progress Penuntasan Akademik <span>| Global
                                                    Admin</span></h5>
                                            <p class="text-muted extra-small mb-0 opacity-75">Statistik partisipasi
                                                mahasiswa dalam rangkaian tes dan penugasan.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('rekapkeseluruhan.index') }}"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 fw-bold extra-small shadow-none">
                                                <i class="bi bi-person-lines-fill me-1"></i> Detail
                                            </a>
                                            <div
                                                class="bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold extra-small">
                                                Total: {{ $totalMahasiswa }}
                                            </div>
                                        </div>
                                    </div>
                                    <div id="academicBarChart" style="min-height: 300px;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Academic Snapshot row --}}
                        <div class="col-12 col-md-6 col-xl-4 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0">Pre-Test</h5>
                                        <div class="d-flex gap-1">
                                            <input type="text"
                                                class="form-control form-control-sm rounded-pill search-input"
                                                data-target="pretest-table" placeholder="Cari..."
                                                style="width: 70px; font-size: 0.65rem;">
                                            <a href="{{ route('hasiltest.index') }}"
                                                class="btn btn-outline-info btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center"
                                                style="width: 24px; height: 24px;"><i
                                                    class="bi bi-eye-fill scale-75"></i></a>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0 pretest-table">
                                            <thead>
                                                <tr class="text-uppercase extra-small fw-bold"
                                                    style="background: #f8fafc; color: #012970;">
                                                    <th class="ps-3 py-2">NO</th>
                                                    <th>NAMA</th>
                                                    <th class="text-end pe-3">SKOR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($allPretest as $index => $pre)
                                                    <tr class="extra-small border-bottom border-light">
                                                        <td class="ps-3 py-2">{{ $index + 1 }}</td>
                                                        <td class="text-nowrap"><span
                                                                class="fw-bold text-dark search-target">{{ $pre->user->name }}</span>
                                                        </td>
                                                        <td class="text-end pe-3"><span
                                                                class="badge bg-info text-white">{{ $pre->skor }}</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center py-2 text-muted small">No
                                                            data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-4 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0">Post-Test</h5>
                                        <div class="d-flex gap-1">
                                            <input type="text"
                                                class="form-control form-control-sm rounded-pill search-input"
                                                data-target="posttest-table" placeholder="Cari..."
                                                style="width: 70px; font-size: 0.65rem;">
                                            <a href="{{ route('hasiltest.index') }}"
                                                class="btn btn-outline-primary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center"
                                                style="width: 24px; height: 24px;"><i
                                                    class="bi bi-eye-fill scale-75"></i></a>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0 posttest-table">
                                            <thead>
                                                <tr class="text-uppercase extra-small fw-bold"
                                                    style="background: #f8fafc; color: #012970;">
                                                    <th class="ps-3 py-2">NO</th>
                                                    <th>NAMA</th>
                                                    <th class="text-end pe-3">SKOR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($allPosttest as $index => $post)
                                                    <tr class="extra-small border-bottom border-light">
                                                        <td class="ps-3 py-2">{{ $index + 1 }}</td>
                                                        <td class="text-nowrap"><span
                                                                class="fw-bold text-dark search-target">{{ $post->user->name }}</span>
                                                        </td>
                                                        <td class="text-end pe-3"><span
                                                                class="badge bg-primary">{{ $post->skor }}</span></td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center py-2 text-muted small">No
                                                            data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 col-xl-4 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0">Kelompok</h5>
                                        <div class="d-flex gap-1">
                                            <input type="text"
                                                class="form-control form-control-sm rounded-pill search-input"
                                                data-target="tugas-table" placeholder="Cari..."
                                                style="width: 70px; font-size: 0.65rem;">
                                            <a href="{{ route('soaltugaskelompok.index') }}"
                                                class="btn btn-outline-success btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center"
                                                style="width: 24px; height: 24px;"><i
                                                    class="bi bi-eye-fill scale-75"></i></a>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0 tugas-table">
                                            <thead>
                                                <tr class="text-uppercase extra-small fw-bold"
                                                    style="background: #f8fafc; color: #012970;">
                                                    <th class="ps-3 py-2">NO</th>
                                                    <th>NAMA</th>
                                                    <th class="text-end pe-3">LINK</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($allTugas as $index => $tug)
                                                    <tr class="extra-small border-bottom border-light">
                                                        <td class="ps-3 py-2">{{ $index + 1 }}</td>
                                                        <td class="text-nowrap"><span
                                                                class="fw-bold text-dark search-target">{{ $tug->user->name }}</span>
                                                        </td>
                                                        <td class="text-end pe-3">
                                                            <a href="{{ $tug->link_tugas }}" target="_blank"
                                                                class="btn btn-link btn-sm p-0 extra-small"><i
                                                                    class="bi bi-link-45deg"></i></a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center py-2 text-muted small">No
                                                            data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- MAHASISWA VIEW: JOURNEY --}}
                        <div class="col-12">
                            <div class="card shadow-sm border-0 mb-4 h-auto">
                                <div class="card-body">
                                    <h5 class="card-title">Panduan Peserta <span>| PKKMB</span></h5>
                                    <div class="alert alert-info py-2 small border-0 bg-opacity-10 mb-2"
                                        style="background: rgba(0, 242, 255, 0.05);">
                                        <i class="bi bi-info-circle me-2"></i> Gunakan menu <strong>Modul</strong>
                                        untuk mengakses materi dan mengerjakan pretest/posttest.
                                    </div>
                                    <div class="alert alert-success py-2 small border-0 bg-opacity-10"
                                        style="background: rgba(0, 255, 65, 0.05);">
                                        <i class="bi bi-check-circle me-2"></i> Pastikan anda sudah melakukan scan
                                        absensi setiap pagi dan sore.
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Timeline Kehadiran <span>| My Attendance</span></h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless align-middle mb-0 text-center">
                                            <thead>
                                                <tr class="extra-small text-uppercase fw-bold text-muted border-bottom">
                                                    <th class="text-start py-2">HARI</th>
                                                    <th>PAGI</th>
                                                    <th>SORE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $abs1 = \App\Models\AbsenPertama::where(
                                                        'user_id',
                                                        Auth::id(),
                                                    )->first();
                                                    $abs2 = \App\Models\AbsenKedua::where(
                                                        'user_id',
                                                        Auth::id(),
                                                    )->first();
                                                    $abs3 = \App\Models\AbsenKetiga::where(
                                                        'user_id',
                                                        Auth::id(),
                                                    )->first();

                                                    $days = [
                                                        ['label' => 'Hari I', 'data' => $abs1],
                                                        ['label' => 'Hari II', 'data' => $abs2],
                                                        ['label' => 'Hari III', 'data' => $abs3],
                                                    ];
                                                @endphp

                                                @foreach ($days as $day)
                                                    <tr class="border-bottom border-light">
                                                        <td class="text-start fw-bold py-3 fs-6">{{ $day['label'] }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $stPagi = $day['data']->hadir_pagi ?? 'Belum';
                                                                $clPagi = match ($stPagi) {
                                                                    'Hadir' => 'bg-success',
                                                                    'Izin' => 'bg-warning',
                                                                    'Alpa', 'Tidak Hadir' => 'bg-danger',
                                                                    default => 'bg-light text-muted border',
                                                                };
                                                                $displayPagi = match ($stPagi) {
                                                                    'Hadir' => 'H',
                                                                    'Izin' => 'I',
                                                                    'Alpa', 'Tidak Hadir' => 'TH',
                                                                    default => '-',
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $clPagi }} fs-6 px-3 py-2"
                                                                style="min-width: 45px;">{{ $displayPagi }}</span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $stSore = $day['data']->hadir_sore ?? 'Belum';
                                                                $clSore = match ($stSore) {
                                                                    'Hadir' => 'bg-success',
                                                                    'Izin' => 'bg-warning',
                                                                    'Alpa', 'Tidak Hadir' => 'bg-danger',
                                                                    default => 'bg-light text-muted border',
                                                                };
                                                                $displaySore = match ($stSore) {
                                                                    'Hadir' => 'H',
                                                                    'Izin' => 'I',
                                                                    'Alpa', 'Tidak Hadir' => 'TH',
                                                                    default => '-',
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $clSore }} fs-6 px-3 py-2"
                                                                style="min-width: 45px;">{{ $displaySore }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div
                                        class="mt-3 pt-3 border-top border-light d-flex flex-wrap justify-content-center gap-3">
                                        <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                            <span class="badge bg-success py-1 px-2" style="min-width: 25px;">H</span>
                                            Hadir
                                        </div>
                                        <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                            <span class="badge bg-danger py-1 px-2" style="min-width: 25px;">TH</span>
                                            Tidak Hadir
                                        </div>
                                        <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                            <span class="badge bg-warning py-1 px-2" style="min-width: 25px;">I</span>
                                            Izin
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5 class="card-title mb-0">Rapor & Progress Materi <span>| My Academic
                                                Ledger</span></h5>
                                        @php
                                            $myPretests = \App\Models\HasilTest::where('user_id', Auth::id())
                                                ->where('type', 'pretest')
                                                ->get();
                                            $myPosttests = \App\Models\HasilTest::where('user_id', Auth::id())
                                                ->where('type', 'posttest')
                                                ->get();
                                            $myTugas = \App\Models\SoalTugasKelompok::where(
                                                'user_id',
                                                Auth::id(),
                                            )->get();
                                            $isComplete =
                                                $myPretests->count() >= 4 &&
                                                $myPosttests->count() >= 4 &&
                                                $myTugas->count() >= 1;
                                            $statusText = $isComplete ? 'Selesai' : 'Dalam Proses';
                                            $statusBadge = $isComplete ? 'bg-success' : 'bg-warning';
                                        @endphp
                                        <div class="text-end">
                                            <span class="badge {{ $statusBadge }} rounded-pill px-3 py-2 fs-6">
                                                <i
                                                    class="bi {{ $isComplete ? 'bi-check-all' : 'bi-hourglass-split' }} me-1"></i>
                                                {{ $statusText }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 bg-light rounded-4 border-light h-100">
                                                <h6
                                                    class="fw-bold mb-3 extra-small text-uppercase tracking-wider text-muted">
                                                    <i class="bi bi-pencil-square me-2 text-info"></i> Pre-Test Scores
                                                </h6>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @for ($i = 1; $i <= 4; $i++)
                                                        @php $pRec = $myPretests->skip($i-1)->first(); @endphp
                                                        <div
                                                            class="flex-fill bg-white p-2 rounded-3 text-center border shadow-sm">
                                                            <div class="extra-small opacity-50 mb-1">Tes
                                                                {{ $i }}</div>
                                                            <div
                                                                class="fw-bold fs-5 {{ $pRec ? 'text-info' : 'text-muted' }}">
                                                                {{ $pRec ? $pRec->skor : '-' }}</div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 bg-light rounded-4 border-light h-100">
                                                <h6
                                                    class="fw-bold mb-3 extra-small text-uppercase tracking-wider text-muted">
                                                    <i class="bi bi-file-earmark-check me-2 text-primary"></i> Post-Test
                                                    Scores
                                                </h6>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @for ($i = 1; $i <= 4; $i++)
                                                        @php $poRec = $myPosttests->skip($i-1)->first(); @endphp
                                                        <div
                                                            class="flex-fill bg-white p-2 rounded-3 text-center border shadow-sm">
                                                            <div class="extra-small opacity-50 mb-1">Tes
                                                                {{ $i }}</div>
                                                            <div
                                                                class="fw-bold fs-5 {{ $poRec ? 'text-primary' : 'text-muted' }}">
                                                                {{ $poRec ? $poRec->skor : '-' }}</div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 bg-light rounded-4 border-light h-100">
                                                <h6
                                                    class="fw-bold mb-3 extra-small text-uppercase tracking-wider text-muted">
                                                    <i class="bi bi-person-check me-2 text-success"></i> Tugas & Penuntasan
                                                </h6>
                                                <div class="d-grid gap-2">
                                                    <div
                                                        class="bg-white p-3 rounded-3 d-flex justify-content-between align-items-center border shadow-sm">
                                                        <span class="small fw-semibold">Tugas Kelompok</span>
                                                        @if ($myTugas->count() > 0)
                                                            <span class="badge bg-success rounded-circle p-2"><i
                                                                    class="bi bi-check-lg"></i></span>
                                                        @else
                                                            <span class="badge bg-warning rounded-circle p-2"><i
                                                                    class="bi bi-dash-lg"></i></span>
                                                        @endif
                                                    </div>
                                                    <div class="text-center mt-2">
                                                        <p class="extra-small text-muted italic mb-0">Terima kasih atas
                                                            partisipasi aktif Anda.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar Area --}}
            <div class="col-lg-4">
                @if (Auth::user()->role != 'mahasiswa')
                    {{-- ADMIN SIDEBAR --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Penuntasan Test <span>| Radial Metrics</span></h5>
                            <div id="testStatusRadial"></div>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new ApexCharts(document.querySelector("#testStatusRadial"), {
                                        series: [
                                            Math.round(({{ $pretestCount }} / ({{ $totalMahasiswa ?: 1 }})) * 100),
                                            Math.round(({{ $posttestCount }} / ({{ $totalMahasiswa ?: 1 }})) * 100),
                                            Math.round(({{ $tugasCount }} / ({{ $totalMahasiswa ?: 1 }})) * 100)
                                        ],
                                        chart: {
                                            height: 350,
                                            type: 'radialBar'
                                        },
                                        plotOptions: {
                                            radialBar: {
                                                dataLabels: {
                                                    total: {
                                                        show: true,
                                                        label: 'Completion'
                                                    }
                                                }
                                            }
                                        },
                                        labels: ['Pre-Test %', 'Post-Test %', 'Tugas %'],
                                        colors: ['#00A551', '#f9b115', '#087C39']
                                    }).render();
                                });
                            </script>
                        </div>
                    </div>

                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')
                        <div class="card overflow-auto shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center my-3">
                                    <h5 class="card-title p-0 m-0">Pendaftaran Terbaru <span>| Feed</span></h5>
                                    <a href="{{ route('users.index') }}"
                                        class="btn btn-outline-primary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center shadow-none"
                                        style="width: 24px; height: 24px;" title="View All Users">
                                        <i class="bi bi-eye-fill small"></i>
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tbody>
                                            @forelse($recentUsers as $user)
                                                <tr class="align-middle border-bottom border-light">
                                                    <td class="py-3">
                                                        <div class="fw-bold" style="color: #012970;">{{ $user->name }}
                                                        </div>
                                                        <span class="text-muted extra-small">{{ $user->email }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <span
                                                            class="badge bg-light text-success border border-success border-opacity-10">{{ $user->nomor_registrasi }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center py-4">No data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title">Quick Actions <span>| Admin</span></h5>
                                <div class="d-grid gap-2 mt-2">
                                    <a href="{{ route('users.index') }}"
                                        class="btn btn-outline-success btn-sm rounded-pill text-start ps-3 py-2 border-opacity-25"
                                        style="border-color: rgba(34, 197, 94, 0.3);">
                                        <i class="bi bi-people-fill me-2"></i> User Directory
                                    </a>
                                    <a href="{{ route('rekapkeseluruhan.index') }}"
                                        class="btn btn-outline-info btn-sm rounded-pill text-start ps-3 py-2 border-opacity-25"
                                        style="border-color: rgba(56, 189, 248, 0.3);">
                                        <i class="bi bi-file-earmark-bar-graph me-2"></i> Export Full Recap
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    {{-- MAHASISWA SIDEBAR: STATUS KELULUSAN --}}
                    <div class="alert bg-dark text-white border-0 extra-small rounded-4 shadow-sm mb-4"
                        style="background: #0f172a !important;">
                        <i class="bi bi-info-circle text-warning me-2"></i> Nilai akhir bersifat final dan merupakan hasil
                        audit sistem otomatis.
                    </div>
                    <div class="card shadow-sm border-0 mb-4 text-center">
                        <div class="card-body px-4 py-5">
                            <div class="mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-patch-check-fill text-primary fs-1"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">Status Kelulusan</h5>
                                <p class="text-muted extra-small">Hasil Akumulasi Nilai PKKMB</p>
                            </div>

                            @php
                                // 1. Tests (20%)
                                $allTests = \App\Models\HasilTest::where('user_id', Auth::id())->get();
                                $hasTugas = \App\Models\SoalTugasKelompok::where('user_id', Auth::id())->exists();
                                $sumTests = $allTests->sum('skor') + ($hasTugas ? 100 : 0);
                                $scoreTestsRaw = $sumTests / 9;

                                // 2. Absensi (50%) - Strict Presence Only
                                $absPoints = 0;
                                $myAbsRecs = [
                                    \App\Models\AbsenPertama::where('user_id', Auth::id())->first(),
                                    \App\Models\AbsenKedua::where('user_id', Auth::id())->first(),
                                    \App\Models\AbsenKetiga::where('user_id', Auth::id())->first(),
                                ];
                                foreach ($myAbsRecs as $myAb) {
                                    if ($myAb) {
                                        $p = strtolower($myAb->hadir_pagi ?? '');
                                        if ($p !== '' && str_contains($p, 'hadir') && !str_contains($p, 'tidak')) {
                                            $absPoints++;
                                        }
                                        $s = strtolower($myAb->hadir_sore ?? '');
                                        if ($s !== '' && str_contains($s, 'hadir') && !str_contains($s, 'tidak')) {
                                            $absPoints++;
                                        }
                                    }
                                }
                                $scoreAbsRaw = ($absPoints / 6) * 100;

                                // 3. Disiplin (30%) - Strict Honors Only
                                $disPoints = 0;
                                $myDisRecs = [
                                    \App\Models\KedisiplinanPertama::where('user_id', Auth::id())->first(),
                                    \App\Models\KedisiplinanKedua::where('user_id', Auth::id())->first(),
                                    \App\Models\KedisiplinanKetiga::where('user_id', Auth::id())->first(),
                                ];
                                foreach ($myDisRecs as $myDi) {
                                    if ($myDi) {
                                        if (strtolower($myDi->kelengkapan_atribut ?? '') === 'lengkap') {
                                            $disPoints++;
                                        }
                                        if (strtolower($myDi->ketepatan_waktu ?? '') === 'tepat waktu') {
                                            $disPoints++;
                                        }
                                        if (strtolower($myDi->perilaku ?? '') === 'sangat baik') {
                                            $disPoints++;
                                        }
                                    }
                                }
                                $scoreDisRaw = ($disPoints / 9) * 100;

                                $finalScore = $scoreTestsRaw * 0.2 + $scoreAbsRaw * 0.5 + $scoreDisRaw * 0.3;
                                $isPassed = $finalScore >= 65;
                            @endphp

                            <div class="display-4 fw-bold text-dark mb-1">{{ (float) number_format($finalScore, 2) }}
                            </div>
                            <div class="text-uppercase tracking-wider extra-small fw-bold text-muted mb-4">Total Nilai
                                Terbobot</div>

                            <div
                                class="p-3 rounded-4 {{ $isPassed ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }} mb-4">
                                <i
                                    class="bi {{ $isPassed ? 'bi-trophy-fill' : 'bi-shield-lock-fill' }} fs-4 d-block mb-1"></i>
                                <span
                                    class="fw-bold text-uppercase fs-5">{{ $isPassed ? 'LULUS' : 'DALAM PROSES' }}</span>
                            </div>

                            <div class="text-start border-top pt-4">
                                <h6 class="extra-small fw-bold text-uppercase text-muted mb-3">Rincian Komponen Nilai</h6>
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-secondary"><i class="bi bi-journals me-2 text-info"></i> Test &
                                        Tugas
                                    </span>
                                    <span class="fw-bold">{{ (float) number_format($scoreTestsRaw, 1) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-secondary"><i class="bi bi-person-check me-2 text-success"></i>
                                        Kehadiran </span>
                                    <span class="fw-bold">{{ (float) number_format($scoreAbsRaw, 1) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-0 small">
                                    <span class="text-secondary"><i class="bi bi-shield-check me-2 text-primary"></i>
                                        Kedisiplinan</span>
                                    <span class="fw-bold">{{ (float) number_format($scoreDisRaw, 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>



    </section>

    @push('scripts')
        <script>


            // Academic Bar Chart: Comparative
            document.addEventListener("DOMContentLoaded", () => {
                new ApexCharts(document.querySelector("#academicBarChart"), {
                    series: [{
                        name: 'Sudah Penuntasan',
                        data: [{{ $pretestCount }}, {{ $posttestCount }}, {{ $tugasCount }}]
                    }, {
                        name: 'Belum Penuntasan',
                        data: [{{ $totalMahasiswa - $pretestCount }},
                            {{ $totalMahasiswa - $posttestCount }},
                            {{ $totalMahasiswa - $tugasCount }}
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'Plus Jakarta Sans, sans-serif',
                        stacked: false // Better comparison side-by-side
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            columnWidth: '45%',
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            colors: ["#304758"]
                        },
                        offsetY: -20
                    },
                    colors: ['#00A551', '#FFF742'], // Emerald Green for Success, UIS Yellow for Pending
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        fontSize: '11px',
                        markers: {
                            radius: 12
                        }
                    },
                    xaxis: {
                        categories: ['Pre-Test', 'Post-Test', 'Tugas Kelompok'],
                    },
                    yaxis: {
                        labels: {
                            show: true
                        }
                    },
                    grid: {
                        borderColor: '#f1f1f1',
                        strokeDashArray: 4
                    },
                    tooltip: {
                        theme: 'light'
                    }
                }).render();
            });

            // Dashboard Live Search Filtering
            if (document.getElementById('attendanceSearch')) {
                document.getElementById('attendanceSearch').addEventListener('keyup', function() {
                    let filter = this.value.toLowerCase();
                    let activePane = document.querySelector('.tab-content .tab-pane.active');
                    let rows = activePane.querySelectorAll('.attendance-table tbody tr');

                    rows.forEach(row => {
                        let nameCell = row.querySelector('.search-target');
                        if (nameCell) {
                            let text = nameCell.textContent.toLowerCase();
                            row.style.display = text.includes(filter) ? '' : 'none';
                        }
                    });
                });
            }

            // Dashboard Live Search Filtering: Discipline
            if (document.getElementById('disciplineSearch')) {
                document.getElementById('disciplineSearch').addEventListener('keyup', function() {
                    let filter = this.value.toLowerCase();
                    let activePane = document.getElementById('disciplineTabContent').querySelector('.tab-pane.active');
                    let rows = activePane.querySelectorAll('.discipline-table tbody tr');

                    rows.forEach(row => {
                        let nameCell = row.querySelector('.search-target');
                        if (nameCell) {
                            let text = nameCell.textContent.toLowerCase();
                            row.style.display = text.includes(filter) ? '' : 'none';
                        }
                    });
                });
            }

            // Dashboard Live Search Filtering: Test Results
            if (document.getElementById('testSearch')) {
                document.getElementById('testSearch').addEventListener('keyup', function() {
                    let filter = this.value.toLowerCase();
                    let activePane = document.getElementById('testTabContent').querySelector('.tab-pane.active');
                    let rows = activePane.querySelectorAll('.test-table tbody tr');

                    rows.forEach(row => {
                        let nameCell = row.querySelector('.search-target');
                        if (nameCell) {
                            let text = nameCell.textContent.toLowerCase();
                            row.style.display = text.includes(filter) ? '' : 'none';
                        }
                    });
                });
            }

            // Small Local Searches
            document.querySelectorAll('.search-input').forEach(input => {
                input.addEventListener('keyup', function() {
                    let filter = this.value.toLowerCase();
                    let targetTable = this.getAttribute('data-target');
                    let rows = document.querySelectorAll(`.${targetTable} tbody tr`);

                    rows.forEach(row => {
                        let nameCell = row.querySelector('.search-target');
                        if (nameCell) {
                            let text = nameCell.textContent.toLowerCase();
                            row.style.display = text.includes(filter) ? '' : 'none';
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
