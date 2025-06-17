<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Moonly A/B Test POC</title>

  <!-- External Styles & Scripts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>

  <style>
    body {
      font-family: 'Inter', sans-serif;
      font-size: 16px;
      font-weight: 400;
    }
  </style>
</head>

<body class="bg-white text-gray-900 flex flex-row min-h-screen">

  <!-- Sidebar -->
  <aside class="bg-slate-50 w-64 flex flex-col justify-between h-[100vh] border-r text-md sticky top-0">
    <div class="p-6 flex flex-col gap-8 h-full">
      <!-- Logo & Version -->
      <div class="flex items-center gap-2">
        <svg width="20" height="20" viewBox="0 0 22 23" fill="none" xmlns="http://www.w3.org/2000/svg">
            <mask id="mask0_1610_861" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="22" height="23">
            <path d="M11 22.5C17.0751 22.5 22 17.5751 22 11.5C22 5.42487 17.0751 0.5 11 0.5C4.92487 0.5 0 5.42487 0 11.5C0 17.5751 4.92487 22.5 11 22.5Z" fill="#D9D9D9"/>
            </mask>
            <g mask="url(#mask0_1610_861)">
            <path d="M11 22.5C17.0751 22.5 22 17.5751 22 11.5C22 5.42487 17.0751 0.5 11 0.5C4.92487 0.5 0 5.42487 0 11.5C0 17.5751 4.92487 22.5 11 22.5Z" fill="url(#paint0_radial_1610_861)"/>
            <path d="M11 22.5C17.0751 22.5 22 17.5751 22 11.5C22 5.42487 17.0751 0.5 11 0.5C4.92487 0.5 0 5.42487 0 11.5C0 17.5751 4.92487 22.5 11 22.5Z" fill="url(#paint1_radial_1610_861)"/>
            <path d="M11 22.5C17.0751 22.5 22 17.5751 22 11.5C22 5.42487 17.0751 0.5 11 0.5C4.92487 0.5 0 5.42487 0 11.5C0 17.5751 4.92487 22.5 11 22.5Z" fill="url(#paint2_radial_1610_861)"/>
            <g filter="url(#filter0_f_1610_861)">
            <path d="M10.098 22.456C16.161 22.456 21.076 17.541 21.076 11.478C21.076 5.41502 16.161 0.5 10.098 0.5C4.03502 0.5 -0.880001 5.41502 -0.880001 11.478C-0.880001 17.541 4.03502 22.456 10.098 22.456Z" fill="url(#paint3_linear_1610_861)" fill-opacity="0.6"/>
            </g>
            </g>
            <defs>
            <filter id="filter0_f_1610_861" x="-500.88" y="-499.5" width="1021.96" height="1021.96" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
            <feGaussianBlur stdDeviation="250" result="effect1_foregroundBlur_1610_861"/>
            </filter>
            <radialGradient id="paint0_radial_1610_861" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12.496 9.08) rotate(114.341) scale(11.1556)">
            <stop stop-color="#2DB1FB"/>
            <stop offset="1" stop-color="#FE6ABA"/>
            </radialGradient>
            <radialGradient id="paint1_radial_1610_861" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(5.434 6.506) rotate(56.3847) scale(14.0278)">
            <stop stop-color="#5709FA"/>
            <stop offset="1" stop-color="white" stop-opacity="0"/>
            </radialGradient>
            <radialGradient id="paint2_radial_1610_861" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(18.502 5.802) rotate(135.22) scale(8.08936)">
            <stop stop-color="white"/>
            <stop offset="1" stop-color="white" stop-opacity="0"/>
            </radialGradient>
            <linearGradient id="paint3_linear_1610_861" x1="13.046" y1="3.646" x2="7.04" y2="16.692" gradientUnits="userSpaceOnUse">
            <stop stop-color="white" stop-opacity="0.6"/>
            <stop offset="1" stop-color="white" stop-opacity="0"/>
            </linearGradient>
            </defs>
            </svg>
        <span class="font-semibold text-gray-900">AURORA</span>
        <span class="text-gray-400 text-sm">v1.7.3</span>
      </div>

      <!-- Main Navigation -->
      <nav class="flex flex-col h-full justify-between pb-14" aria-label="Main navigation">
        <a href="/distributions" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
           Overview
        </a>

        <div class="flex flex-col gap-3">
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-icon lucide-message-circle"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
            Chat
          </a>
          <a href="/chart" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database-icon lucide-database"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
            Models
          </a>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-vault-icon lucide-vault"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/><path d="m7.9 7.9 2.7 2.7"/><circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/><path d="m13.4 10.6 2.7-2.7"/><circle cx="7.5" cy="16.5" r=".5" fill="currentColor"/><path d="m7.9 16.1 2.7-2.7"/><circle cx="16.5" cy="16.5" r=".5" fill="currentColor"/><path d="m13.4 13.4 2.7 2.7"/><circle cx="12" cy="12" r="2"/></svg>
            Overview
          </a>
        </div>

        <div class="flex flex-col gap-3">
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap-icon lucide-graduation-cap"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>
            Academy
          </a>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
            Team
          </a>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Account
          </a>
        </div>

        <style>
            .pinned-link img {
              filter: grayscale(100%);
              transition: filter 0.3s ease;
            }
          
            .pinned-link:hover img {
              filter: grayscale(0%);
            }
          </style>

        <!-- Pinned Models -->
        <div>
            <h2 class="text-xs text-gray-500 font-semibold uppercase mb-3 tracking-wide">Pinned models</h2>
            <a href="#" class="pinned-link flex items-center gap-3 text-gray-700 hover:text-black pb-2 text-sm">
              <img src="https://www.gravatar.com/avatar/2c7d99fe281ecd3bcd65ab915bac6dd5?s=250" alt="Claude Sonnet" class="rounded-md w-6 h-6" />
              Claude Sonnet
            </a>
            <a href="#" class="pinned-link flex items-center gap-3 text-gray-700 hover:text-black text-sm">
              <img src="https://www.gravatar.com/avatar/2c7d99fe281ecd3bcd65ab915bac6dd5?s=250" alt="Montferland" class="rounded-md w-6 h-6" />
              Montferland
            </a>
          </div>

        <!-- Superuser Section -->
        <div>
          <h2 class="text-xs text-gray-500 font-semibold uppercase mb-3 tracking-wide">Superuser</h2>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database-icon lucide-database"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
            Models
          </a>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-split-icon lucide-split"><path d="M16 3h5v5"/><path d="M8 3H3v5"/><path d="M12 22v-8.3a4 4 0 0 0-1.172-2.872L3 3"/><path d="m15 9 6-6"/></svg>
            Distributions
          </a>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building-icon lucide-building"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>rganisations
          </a>
        </div>
      </nav>
    </div>

    <!-- Footer Controls -->
    <div class="border-t px-10 py-4 flex justify-between text-gray-500">
      <button aria-label="Settings"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-icon lucide-settings"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg></button>
      <button aria-label="Toggle Theme"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun-icon lucide-sun"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg></button>
      <button aria-label="Log out"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg></button>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1">
    <section>
      {{ $slot }}
    </section>
  </main>

</body>

</html>