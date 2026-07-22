  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

          <li class="nav-item">
              <a class="nav-link " href="{{ route('dashboard') }}">
                  <i class="bi bi-grid"></i>
                  <span>Dashboard</span>
              </a>
          </li>
        

          <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                  <i class="bi bi-menu-button-wide"></i><span>Absensi Kehadiran</span><i
                      class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                  <li>
                      <a href="{{ route('absenpertama.index') }}">
                          <i class="bi bi-circle"></i><span>Absensi Hari I</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('absenkedua.index') }}">
                          <i class="bi bi-circle"></i><span>Absesi Hari II</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('absenketiga.index') }}">
                          <i class="bi bi-circle"></i><span>Absensi Hari III</span>
                      </a>
                  </li>
              </ul>
          </li>

          @if (Auth::user()->role != 'mahasiswa')
              <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                      <i class="bi bi-journal-text"></i><span>Kedisiplinan</span><i
                          class="bi bi-chevron-down ms-auto"></i>
                  </a>
                  <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                      <li>
                          <a href="{{ route('kedisiplinanpertama.index') }}">
                              <i class="bi bi-circle"></i><span>Kedisiplinan Hari I</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('kedisiplinankedua.index') }}">
                              <i class="bi bi-circle"></i><span>Kedisiplinan Hari II</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('kedisiplinanketiga.index') }}">
                              <i class="bi bi-circle"></i><span>Kedisiplinan Hari III</span>
                          </a>
                      </li>
                  </ul>
              </li>
          @endif

          @if (Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')

              <li class="nav-item">
                  <a class="nav-link " href="{{ route('materimodul.index') }}">
                      <i class="bi bi-grid"></i>
                      <span>Upload Materi Modul</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#soal-pretest" data-bs-toggle="collapse" href="#">
                      <i class="bi bi-layout-text-window-reverse"></i><span>Upload Soal Pretest</span><i
                          class="bi bi-chevron-down ms-auto"></i>
                  </a>
                  <ul id="soal-pretest" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                      <li>
                          <a href="{{ route('soalpretestpertama.index') }}">
                              <i class="bi bi-circle"></i><span>Prestest 1</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('soalpretestkedua.index') }}">
                              <i class="bi bi-circle"></i><span>Prestest 2</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('soalpretestketiga.index') }}">
                              <i class="bi bi-circle"></i><span>Prestest 3</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('soalpretestkeempat.index') }}">
                              <i class="bi bi-circle"></i><span>Prestest 4</span>
                          </a>
                      </li>
                  </ul>
              </li>

              <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#soal-postest" data-bs-toggle="collapse" href="#">
                      <i class="bi bi-layout-text-window-reverse"></i><span>Upload Soal Postest</span><i
                          class="bi bi-chevron-down ms-auto"></i>
                  </a>
                  <ul id="soal-postest" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                      <li>
                          <a href="{{ route('soalposttestpertama.index') }}">
                              <i class="bi bi-circle"></i><span>Postest 1</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('soalposttestkedua.index') }}">
                              <i class="bi bi-circle"></i><span>Postest 2</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('soalposttestketiga.index') }}">
                              <i class="bi bi-circle"></i><span>Postest 3</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('soalposttestkeempat.index') }}">
                              <i class="bi bi-circle"></i><span>Postest 4</span>
                          </a>
                      </li>
                  </ul>
              </li><!-- End Post test Nav -->

              <li class="nav-item">
                  <a class="nav-link collapsed" href="{{ route('soaltugaskelompok.index') }}">
                      <i class="bi bi-book"></i>
                      <span>Tugas Modul 5</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#rekap-nav" data-bs-toggle="collapse" href="#">
                      <i class="bi bi-layout-text-window-reverse"></i><span>Rekapitulasi</span><i
                          class="bi bi-chevron-down ms-auto"></i>
                  </a>
                  <ul id="rekap-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                      <li>
                          <a href="{{ route('rekapkeseluruhan.index') }}">
                              <i class="bi bi-circle"></i><span>Absensi, Kedisiplinan, Pretest dan Postes</span>
                          </a>
                      </li>
                  </ul>
              </li>

              <li class="nav-item">
                  <a class="nav-link collapsed" href="{{ route('hasiltest.index') }}">
                      <i class="bi bi-clipboard-data"></i>
                      <span>Hasil Test Mahasiswa</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link collapsed" href="{{ route('users.index') }}">
                      <i class="bi bi-person-lines-fill"></i>
                      <span>Data Pengguna</span>
                  </a>
              </li>
          @endif

          @if (Auth::user()->role == 'mahasiswa' || Auth::user()->role == 'admin')
              <li class="nav-item">
                  <a class="nav-link collapsed" href="{{ route('modulposttest.index') }}">
                      <i class="bi bi-book"></i>
                      <span>Modul, Pritest dan Postes Mahasiswa</span>
                  </a>
              </li>
          @endif

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ route('dokumen.index') }}">
                  <i class="bi bi-file-earmark-text"></i>
                  <span>Dokumen Pendukung</span>
              </a>
          </li>

      </ul>

  </aside>
