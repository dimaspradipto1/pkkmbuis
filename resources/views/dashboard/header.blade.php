  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/img/logopkkmb.png') }}" alt="" style="max-height: 35px;">
        <span class="ms-1" style="font-size: 18px; font-weight: 700; color: #012970;">PKKMB UIS</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn ps-lg-3"></i>
    </div><!-- End Logo -->

    {{-- <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar --> --}}

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">



        @php
            $notifications = [];
            $user = Auth::user();
            
            if ($user) {
                if ($user->role == 'mahasiswa') {
                    $notifications[] = [
                        'id' => 'notif-welcome-' . $user->id,
                        'icon' => 'bi-info-circle text-primary',
                        'title' => 'Selamat Datang',
                        'text' => 'Selamat datang di portal PKKMB UIS, ' . $user->name,
                        'time' => 'Sistem'
                    ];

                    $absen1 = \App\Models\AbsenPertama::where('user_id', $user->id)->first();
                    if ($absen1) {
                        if ($absen1->hadir_pagi == 'Hadir') {
                            $notifications[] = [
                                'id' => 'notif-absen-1-pagi-' . $user->id,
                                'icon' => 'bi-check-circle text-success',
                                'title' => 'Absensi Pagi H-1',
                                'text' => 'Absensi Pagi Hari I Anda tercatat Hadir.',
                                'time' => 'Terverifikasi'
                            ];
                        }
                        if ($absen1->hadir_sore == 'Hadir') {
                            $notifications[] = [
                                'id' => 'notif-absen-1-sore-' . $user->id,
                                'icon' => 'bi-check-circle text-success',
                                'title' => 'Absensi Sore H-1',
                                'text' => 'Absensi Sore Hari I Anda tercatat Hadir.',
                                'time' => 'Terverifikasi'
                            ];
                        }
                    }

                    $absen2 = \App\Models\AbsenKedua::where('user_id', $user->id)->first();
                    if ($absen2) {
                        if ($absen2->hadir_pagi == 'Hadir') {
                            $notifications[] = [
                                'id' => 'notif-absen-2-pagi-' . $user->id,
                                'icon' => 'bi-check-circle text-success',
                                'title' => 'Absensi Pagi H-2',
                                'text' => 'Absensi Pagi Hari II Anda tercatat Hadir.',
                                'time' => 'Terverifikasi'
                            ];
                        }
                        if ($absen2->hadir_sore == 'Hadir') {
                            $notifications[] = [
                                'id' => 'notif-absen-2-sore-' . $user->id,
                                'icon' => 'bi-check-circle text-success',
                                'title' => 'Absensi Sore H-2',
                                'text' => 'Absensi Sore Hari II Anda tercatat Hadir.',
                                'time' => 'Terverifikasi'
                            ];
                        }
                    }

                    $absen3 = \App\Models\AbsenKetiga::where('user_id', $user->id)->first();
                    if ($absen3) {
                        if ($absen3->hadir_pagi == 'Hadir') {
                            $notifications[] = [
                                'id' => 'notif-absen-3-pagi-' . $user->id,
                                'icon' => 'bi-check-circle text-success',
                                'title' => 'Absensi Pagi H-3',
                                'text' => 'Absensi Pagi Hari III Anda tercatat Hadir.',
                                'time' => 'Terverifikasi'
                            ];
                        }
                        if ($absen3->hadir_sore == 'Hadir') {
                            $notifications[] = [
                                'id' => 'notif-absen-3-sore-' . $user->id,
                                'icon' => 'bi-check-circle text-success',
                                'title' => 'Absensi Sore H-3',
                                'text' => 'Absensi Sore Hari III Anda tercatat Hadir.',
                                'time' => 'Terverifikasi'
                            ];
                        }
                    }
                } else {
                    $totalMhs = \App\Models\User::where('role', 'mahasiswa')->count();
                    $notifications[] = [
                        'id' => 'notif-total-mhs-' . $user->id,
                        'icon' => 'bi-people text-success',
                        'title' => 'Total Mahasiswa',
                        'text' => 'Sebanyak ' . $totalMhs . ' mahasiswa terdaftar.',
                        'time' => 'Real-time'
                    ];

                    $notifications[] = [
                        'id' => 'notif-portal-absen-' . $user->id,
                        'icon' => 'bi-qr-code text-primary',
                        'title' => 'Portal Absensi',
                        'text' => 'Tampilkan QR Code Absensi pada proyektor.',
                        'time' => 'Info'
                    ];
                }
            }
            $notifCount = count($notifications);
        @endphp

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number" id="notif-badge" style="display: none;">{{ $notifCount }}</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" id="notif-dropdown-list" style="width: 280px; max-height: 400px; overflow-y: auto;">
            <li class="dropdown-header" id="notif-header-text">
              Anda memiliki {{ $notifCount }} notifikasi baru
            </li>
            
            @forelse($notifications as $notif)
              <li>
                <hr class="dropdown-divider">
              </li>
              <li class="notification-item d-flex align-items-start p-2 notif-item" data-notif-id="{{ $notif['id'] }}" style="cursor: pointer; transition: background-color 0.2s, opacity 0.2s;" onclick="dismissNotification('{{ $notif['id'] }}', this)">
                <i class="bi {{ $notif['icon'] }} fs-5 me-2 mt-1"></i>
                <div style="font-size: 0.8rem; text-align: left; flex: 1;">
                  <h6 class="fw-bold mb-1" style="font-size: 0.85rem; color: #012970;">{{ $notif['title'] }}</h6>
                  <p class="mb-0 text-muted">{{ $notif['text'] }}</p>
                  <p class="text-secondary small mb-0" style="font-size: 0.7rem;">{{ $notif['time'] }}</p>
                </div>
                <button type="button" class="btn-close ms-2 mt-1" style="font-size: 0.5rem;" aria-label="Close"></button>
              </li>
            @empty
              <li>
                <hr class="dropdown-divider">
              </li>
              <li class="dropdown-footer p-3 text-center text-muted small dropdown-footer-empty">
                Tidak ada notifikasi baru
              </li>
            @endforelse

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        {{-- <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav --> --}}

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0d6efd&color=fff&size=40&rounded=true" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{Auth::user()->name}}</h6>
              <span>{{Auth::user()->role}}</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.index') }}">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.password.edit') }}">
                <i class="bi bi-key"></i>
                <span>Update Password</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            {{-- <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li> --}}
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  <script>
      function dismissNotification(id, element) {
          let readNotifs = JSON.parse(localStorage.getItem('read_notifications') || '[]');
          if (!readNotifs.includes(id)) {
              readNotifs.push(id);
              localStorage.setItem('read_notifications', JSON.stringify(readNotifs));
          }

          element.style.opacity = '0';
          setTimeout(() => {
              let divider = element.previousElementSibling;
              if (divider && divider.tagName === 'LI') {
                  divider.remove();
              }
              element.remove();
              updateNotifCounts();
          }, 200);
      }

      function updateNotifCounts() {
          let readNotifs = JSON.parse(localStorage.getItem('read_notifications') || '[]');
          let items = document.querySelectorAll('.notif-item');
          let visibleCount = 0;

          items.forEach(item => {
              let id = item.getAttribute('data-notif-id');
              if (readNotifs.includes(id)) {
                  let divider = item.previousElementSibling;
                  if (divider && divider.tagName === 'LI') {
                      divider.remove();
                  }
                  item.remove();
              } else {
                  visibleCount++;
              }
          });

          let badge = document.getElementById('notif-badge');
          if (badge) {
              if (visibleCount > 0) {
                  badge.innerText = visibleCount;
                  badge.style.display = 'inline-block';
              } else {
                  badge.style.display = 'none';
              }
          }

          let headerText = document.getElementById('notif-header-text');
          if (headerText) {
              headerText.innerHTML = `Anda memiliki ${visibleCount} notifikasi baru`;
          }

          if (visibleCount === 0) {
              let list = document.getElementById('notif-dropdown-list');
              if (list) {
                  // Clean up remaining nodes inside dropdown
                  list.querySelectorAll('.notif-item, .dropdown-divider').forEach(el => el.remove());
                  if (!list.querySelector('.dropdown-footer-empty')) {
                      let liDivider = document.createElement('li');
                      liDivider.innerHTML = '<hr class="dropdown-divider">';
                      let liEmpty = document.createElement('li');
                      liEmpty.className = 'dropdown-footer p-3 text-center text-muted small dropdown-footer-empty';
                      liEmpty.innerText = 'Tidak ada notifikasi baru';
                      list.appendChild(liDivider);
                      list.appendChild(liEmpty);
                  }
              }
          }
      }

      document.addEventListener('DOMContentLoaded', () => {
          updateNotifCounts();
      });
  </script>
  </header><!-- End Header -->