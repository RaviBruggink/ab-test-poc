<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Moonly A/B Test POC</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

  @include('partials.scripts')

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
  <aside class="bg-slate-50 w-64 flex flex-col justify-between h-screen border-r sticky top-0 text-md">
    <div class="p-6 flex flex-col gap-8 h-full">
      <!-- Logo & Version -->
      <div class="flex items-center gap-2">
        <x-logo class="inline-block"/>
        <span class="font-semibold text-gray-900">AURORA</span>
        <span class="text-gray-400 text-sm">v1.7.3</span>
      </div>

      <!-- Main Navigation -->
      <nav class="flex flex-col h-full justify-between pb-14" aria-label="Main navigation">
        <a href="/distributions" class="flex items-center gap-3 text-gray-700 hover:text-black">
          <x-icons.house-icon class="inline-block"/>
          Overview
        </a>

        <div class="flex flex-col gap-3">
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <x-icons.message-circle-icon class="inline-block"/>
            Chat
          </a>
          <a href="/chart" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <x-icons.database-icon class="inline-block"/>
            Models
          </a>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <x-icons.vault-icon class="inline-block"/>
            Overview
          </a>
        </div>

        <div class="flex flex-col gap-3">
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <x-icons.graduation-cap-icon class="inline-block"/>
            Academy
          </a>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <x-icons.users-icon class="inline-block"/>
            Team
          </a>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <x-icons.user-icon class="inline-block"/>
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
            <x-icons.database-icon class="inline-block"/>
            Models
          </a>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <x-icons.split-icon class="inline-block"/>
            Distributions
          </a>
          <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black">
            <x-icons.building-icon class="inline-block"/>
            Organisations
          </a>
        </div>
      </nav>
    </div>

    <!-- Footer Controls -->
    <div class="border-t px-10 py-4 flex justify-between text-gray-500">
      <button aria-label="Settings">
        <x-icons.settings-icon class="inline-block"/>
      </button>
      <button aria-label="Toggle Theme">
        <x-icons.sun-icon class="inline-block"/>
      </button>
      <button aria-label="Log out">
        <x-icons.log-out-icon class="inline-block"/>
      </button>
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
