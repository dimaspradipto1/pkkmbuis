  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

          <li class="nav-heading">Utama</li>

          <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                  <i class="bi bi-grid-fill"></i>
                  <span>Dashboard</span>
              </a>
          </li>

          @if (Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')
              <li class="nav-heading">Layanan Pesan & Bantuan</li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('chat.*') ? '' : 'collapsed' }} d-flex align-items-center justify-content-between" href="{{ route('chat.index') }}">
                      <div class="d-flex align-items-center">
                          <i class="bi bi-chat-square-text-fill text-success me-2"></i>
                          <span>Pesan / Live Chat</span>
                      </div>
                      <span class="badge bg-danger rounded-pill px-2 py-1 ms-auto" id="sidebarChatUnreadBadge" style="display: none; font-size: 0.68rem;">0</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('chatbot-faq.*') ? '' : 'collapsed' }}" href="{{ route('chatbot-faq.index') }}">
                      <i class="bi bi-gear-fill text-primary"></i>
                      <span>Pengaturan Chatbot & WA</span>
                  </a>
              </li>
          @endif

          <li class="nav-heading">Pelaksanaan & Kegiatan</li>

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

          @if (Auth::user()->role != 'mahasiswa')
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
          @endif

          @if (Auth::user()->role == 'mahasiswa' || Auth::user()->role == 'admin')
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('modulposttest.*') ? '' : 'collapsed' }}" href="{{ route('modulposttest.index') }}">
                      <i class="bi bi-journal-bookmark-fill"></i>
                      <span>Modul, Pretest & Posttest</span>
                  </a>
              </li>
          @endif

          <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('kelompok.*') ? '' : 'collapsed' }}" href="{{ route('kelompok.index') }}">
                  <i class="bi bi-people-fill"></i>
                  <span>Manajemen Kelompok</span>
              </a>
          </li>

          <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('dokumen.*') ? '' : 'collapsed' }}" href="{{ route('dokumen.index') }}">
                  <i class="bi bi-file-earmark-text-fill"></i>
                  <span>Dokumen Pendukung</span>
              </a>
          </li>

          @if (Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')
              <li class="nav-heading">Kelola Modul & Test</li>

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

              <li class="nav-heading">Rekapitulasi & Pengguna</li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('rekap*') ? '' : 'collapsed' }}" data-bs-target="#rekap-nav" data-bs-toggle="collapse" href="#">
                      <i class="bi bi-pie-chart-fill"></i><span>Rekapitulasi</span><i class="bi bi-chevron-down ms-auto"></i>
                  </a>
                  <ul id="rekap-nav" class="nav-content collapse {{ request()->routeIs('rekap*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
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
                  </ul>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('hasiltest.*') ? '' : 'collapsed' }}" href="{{ route('hasiltest.index') }}">
                      <i class="bi bi-clipboard-data-fill"></i>
                      <span>Hasil Test Mahasiswa</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('users.*') ? '' : 'collapsed' }}" href="{{ route('users.index') }}">
                      <i class="bi bi-person-lines-fill"></i>
                      <span>Data Pengguna</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('chatbot-faq.*') ? '' : 'collapsed' }}" href="{{ route('chatbot-faq.index') }}">
                      <i class="bi bi-chat-dots-fill text-success"></i>
                      <span>Pengaturan Chatbot & WA</span>
                  </a>
              </li>
          @endif

          <li class="nav-heading">Evaluasi Penyampaian Materi</li>

          @php
              $evaluasiMenus = \App\Models\EvaluasiMenu::orderBy('nomor')->get();
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
                                      <i class="bi bi-circle"></i><span>{{ $menu->nama }}</span>
                                  </a>
                              @else
                                  <a href="javascript:void(0)" class="text-muted" style="{{ !$menu->is_active ? 'opacity: 0.6;' : '' }}">
                                      <i class="bi bi-circle"></i><span>{{ $menu->nama }}</span>
                                      @if (Auth::user()->role != 'mahasiswa' && !$menu->is_active)
                                          <span class="badge bg-secondary ms-1" style="font-size: 0.65rem;">Non-aktif</span>
                                      @endif
                                  </a>
                              @endif
                          </li>
                      @endif
                  @endforeach

                  @if (Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')
                      <li class="mt-2 pt-2 border-top">
                          <a href="{{ route('evaluasimenu.index') }}" class="{{ request()->routeIs('evaluasimenu.*') ? 'active' : '' }}">
                              <i class="bi bi-gear-fill me-1 text-primary"></i><span class="fw-bold">Pengaturan Status Menu</span>
                          </a>
                      </li>
                  @endif
              </ul>
          </li>

      </ul>

  </aside>
