<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        @if (Auth::user()->role == 'panitia')
            <li class="nav-heading">Laporan</li>

            <li class="nav-item">
                <a class="nav-link {{ (request()->routeIs('lpj.*') || request()->routeIs('lpj-attachments.*')) ? '' : 'collapsed' }}" href="{{ route('lpj.index') }}">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span>LPJ</span>
                </a>
            </li>
        @else
            {{-- 1. UTAMA --}}
            <li class="nav-heading">Utama</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>

        {{-- 2. PELAKSANAAN & KEGIATAN --}}
        @if (Auth::user()->role != 'timevaluasi')
            <li class="nav-heading">Pelaksanaan & Kegiatan</li>

            @if (in_array(Auth::user()->role, ['mahasiswa', 'admin', 'stafbaak']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('modulposttest.*') ? '' : 'collapsed' }}" href="{{ route('modulposttest.index') }}">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>Modul, Pretest & Posttest</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('absen*') ? '' : 'collapsed' }}" data-bs-target="#absensi-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-calendar-check-fill"></i><span>Absensi Kehadiran</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="absensi-nav" class="nav-content collapse {{ request()->routeIs('absen*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('absenpertama.index') }}" class="{{ request()->routeIs('absenpertama.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Absensi Hari I</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('absenkedua.index') }}" class="{{ request()->routeIs('absenkedua.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Absensi Hari II</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('absenketiga.index') }}" class="{{ request()->routeIs('absenketiga.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Absensi Hari III</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kedisiplinan*') ? '' : 'collapsed' }}" data-bs-target="#kedisiplinan-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-shield-check"></i><span>Kedisiplinan</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="kedisiplinan-nav" class="nav-content collapse {{ request()->routeIs('kedisiplinan*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('kedisiplinanpertama.index') }}" class="{{ request()->routeIs('kedisiplinanpertama.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Kedisiplinan Hari I</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kedisiplinankedua.index') }}" class="{{ request()->routeIs('kedisiplinankedua.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Kedisiplinan Hari II</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kedisiplinanketiga.index') }}" class="{{ request()->routeIs('kedisiplinanketiga.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Kedisiplinan Hari III</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kelompok.*') ? '' : 'collapsed' }}" href="{{ route('kelompok.index') }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Manajemen Kelompok</span>
                </a>
            </li>
        @endif

        {{-- 3. EVALUASI & OBSERVASI --}}
        @php
            $canSeeEvaluasi = !in_array(Auth::user()->role, ['kakakpendamping', 'dosenpendamping']);
            $canSeeObservasi = in_array(Auth::user()->role, ['admin', 'stafbaak', 'timevaluasi']);
        @endphp

        @if ($canSeeEvaluasi || $canSeeObservasi)
            <li class="nav-heading">Evaluasi & Observasi</li>

            @if ($canSeeEvaluasi)
                @php
                    $evaluasiMenus = \App\Models\EvaluasiMenu::available()->orderBy('nomor')->get();
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('evaluasi*') || request()->routeIs('perpustakaan*')) ? '' : 'collapsed' }}" data-bs-target="#evaluasi" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-clipboard2-check-fill"></i><span>Evaluasi Penyampaian Materi</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="evaluasi" class="nav-content collapse {{ (request()->routeIs('evaluasi*') || request()->routeIs('perpustakaan*')) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                        @foreach ($evaluasiMenus as $menu)
                            @if (Auth::user()->role != 'mahasiswa' || $menu->is_active)
                                <li>
                                    @if ($menu->is_active && $menu->route_name && Route::has($menu->route_name))
                                        <a href="{{ route($menu->route_name) }}" class="{{ request()->routeIs($menu->route_name . '*') ? 'active' : '' }}">
                                            <i class="bi bi-circle"></i><span>{{ $loop->iteration }}. {{ $menu->clean_nama }}</span>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" class="text-muted" style="{{ !$menu->is_active ? 'opacity: 0.6;' : '' }}">
                                            <i class="bi bi-circle"></i><span>{{ $loop->iteration }}. {{ $menu->clean_nama }}</span>
                                            @if (Auth::user()->role != 'mahasiswa' && !$menu->is_active)
                                                <span class="badge bg-secondary ms-1" style="font-size: 0.65rem;">Non-aktif</span>
                                            @endif
                                        </a>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @endif

            @if ($canSeeObservasi)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('observasiacara*') ? '' : 'collapsed' }}" data-bs-target="#observasi-acara-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-eye-fill"></i><span>Observasi Acara</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="observasi-acara-nav" class="nav-content collapse {{ request()->routeIs('observasiacara*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('observasiacara.index') }}" class="{{ request()->routeIs('observasiacara.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Observasi Acara 1</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('observasiacara2.index') }}" class="{{ request()->routeIs('observasiacara2.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Observasi Acara 2</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('observasiacarafeb.index') }}" class="{{ request()->routeIs('observasiacarafeb.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Observasi Acara Feb</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('observasiacarafst.index') }}" class="{{ request()->routeIs('observasiacarafst.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Observasi Acara Fst</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('observasiacarafikes.index') }}" class="{{ request()->routeIs('observasiacarafikes.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Observasi Acara Fikes</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        @endif

        {{-- 4. KELOLA MATERI & SOAL --}}
        @if (in_array(Auth::user()->role, ['admin', 'stafbaak']))
            <li class="nav-heading">Kelola Materi & Soal</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('materimodul.*') ? '' : 'collapsed' }}" href="{{ route('materimodul.index') }}">
                    <i class="bi bi-cloud-upload-fill"></i>
                    <span>Upload Materi Modul</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('soalpretest*') ? '' : 'collapsed' }}" data-bs-target="#soal-pretest" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-file-earmark-arrow-up-fill"></i><span>Upload Soal Pretest</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="soal-pretest" class="nav-content collapse {{ request()->routeIs('soalpretest*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('soalpretestpertama.index') }}" class="{{ request()->routeIs('soalpretestpertama.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Pretest 1</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('soalpretestkedua.index') }}" class="{{ request()->routeIs('soalpretestkedua.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Pretest 2</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('soalpretestketiga.index') }}" class="{{ request()->routeIs('soalpretestketiga.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Pretest 3</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('soalpretestkeempat.index') }}" class="{{ request()->routeIs('soalpretestkeempat.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Pretest 4</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('soalposttest*') ? '' : 'collapsed' }}" data-bs-target="#soal-postest" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-file-earmark-check-fill"></i><span>Upload Soal Posttest</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="soal-postest" class="nav-content collapse {{ request()->routeIs('soalposttest*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('soalposttestpertama.index') }}" class="{{ request()->routeIs('soalposttestpertama.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Posttest 1</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('soalposttestkedua.index') }}" class="{{ request()->routeIs('soalposttestkedua.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Posttest 2</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('soalposttestketiga.index') }}" class="{{ request()->routeIs('soalposttestketiga.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Posttest 3</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('soalposttestkeempat.index') }}" class="{{ request()->routeIs('soalposttestkeempat.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Posttest 4</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('soaltugaskelompok.*') ? '' : 'collapsed' }}" href="{{ route('soaltugaskelompok.index') }}">
                    <i class="bi bi-book-half"></i>
                    <span>Tugas Modul 5</span>
                </a>
            </li>
        @endif

        {{-- 5. REKAPITULASI & LAPORAN --}}
        @if (in_array(Auth::user()->role, ['admin', 'stafbaak', 'pimpinan', 'timevaluasi']))
            <li class="nav-heading">Rekapitulasi & Laporan</li>

            <li class="nav-item">
                <a class="nav-link {{ (request()->routeIs('rekap*') || request()->routeIs('rekapkeseluruhan*')) ? '' : 'collapsed' }}" data-bs-target="#rekap-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-pie-chart-fill"></i><span>Rekapitulasi Nilai & Presensi</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="rekap-nav" class="nav-content collapse {{ (request()->routeIs('rekap*') || request()->routeIs('rekapkeseluruhan*')) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('rekapkeseluruhan.index') }}" class="{{ request()->routeIs('rekapkeseluruhan.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Absensi, Kedisiplinan, Pretest & Posttest</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rekapevaluasi.index') }}" class="{{ request()->routeIs('rekapevaluasi.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Rekapitulasi Evaluasi TCR</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rekapobservasi.index') }}" class="{{ request()->routeIs('rekapobservasi.*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Rekapitulasi Observasi Acara</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('hasiltest.*') ? '' : 'collapsed' }}" href="{{ route('hasiltest.index') }}">
                    <i class="bi bi-clipboard-data-fill"></i>
                    <span>Hasil Test Mahasiswa</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dokumen.*') ? '' : 'collapsed' }}" href="{{ route('dokumen.index') }}">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span>Dokumen Pendukung</span>
                </a>
            </li>

            @if (in_array(Auth::user()->role, ['admin', 'timevaluasi']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('lpj.*') ? '' : 'collapsed' }}" href="{{ route('lpj.index') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>LPJ</span>
                    </a>
                </li>
            @endif
        @endif

        {{-- 6. LAYANAN & KOMUNIKASI --}}
        <li class="nav-heading">Layanan & Komunikasi</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('chat.*') ? '' : 'collapsed' }} d-flex align-items-center justify-content-between" href="{{ route('chat.index') }}">
                <div class="d-flex align-items-center">
                    <i class="bi bi-chat-square-text-fill text-success me-2"></i>
                    <span>Pesan / Live Chat</span>
                </div>
                <span class="badge bg-danger rounded-pill px-2 py-1 ms-auto" id="sidebarChatUnreadBadge" style="display: none; font-size: 0.68rem;">0</span>
            </a>
        </li>

        {{-- 7. PENGATURAN SYSTEM --}}
        @if (in_array(Auth::user()->role, ['admin', 'stafbaak', 'timevaluasi']))
            <li class="nav-heading">Pengaturan System</li>

            @if (in_array(Auth::user()->role, ['admin', 'stafbaak']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? '' : 'collapsed' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Data Pengguna</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('chatbot-faq.*') ? '' : 'collapsed' }}" href="{{ route('chatbot-faq.index') }}">
                        <i class="bi bi-chat-dots-fill text-primary"></i>
                        <span>Pengaturan Chatbot & WA</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('evaluasimenu.*') ? '' : 'collapsed' }}" href="{{ route('evaluasimenu.index') }}">
                    <i class="bi bi-pencil-square text-primary"></i>
                    <span>Pengaturan & Soal Evaluasi</span>
                </a>
            </li>

            @if (in_array(Auth::user()->role, ['admin', 'stafbaak']))
                <li class="nav-item">
                    <a class="nav-link {{ (request()->routeIs('sertifikatsetting.*') || request()->routeIs('kelulusan.*')) ? '' : 'collapsed' }}" data-bs-target="#sertifikat-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-patch-check-fill text-primary"></i><span>Sertifikat & Kelulusan</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="sertifikat-nav" class="nav-content collapse {{ (request()->routeIs('sertifikatsetting.*') || request()->routeIs('kelulusan.*')) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('sertifikatsetting.index') }}" class="{{ request()->routeIs('sertifikatsetting.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Pengaturan Template Sertifikat</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kelulusan.index') }}" class="{{ request()->routeIs('kelulusan.*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Status Kelulusan & Buka Sertifikat</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        @endif
        @endif

    </ul>

</aside>
