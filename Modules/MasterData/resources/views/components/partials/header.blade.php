<header class="topbar sticky top-0 z-50 border-b border-gray-200 bg-white py-4 dark:border-gray-800 dark:bg-black">
  <div class="mx-auto flex max-w-screen-2xl items-center justify-between px-4">
    <div class="flex items-center gap-4">
      <button @click="sidebarToggle = !sidebarToggle" class="btn-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
      <h2 class="text-lg font-semibold">Master Data</h2>
    </div>

    <div class="flex items-center gap-4">
      <div x-data="{open:false}" class="relative">
        <button @click="open = !open" class="flex items-center gap-2">
          <img src="{{ asset('tailadmin/images/avatar/avatar-1.jpg') }}" alt="avatar" class="h-8 w-8 rounded-full object-cover"/>
          <span class="hidden sm:inline">{{ auth()->user()->name ?? 'Guest' }}</span>
        </button>

        <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-48 rounded-md border bg-white shadow-lg dark:bg-gray-800">
          <a href="#" class="block px-4 py-2">Profile</a>
          <a href="#" class="block px-4 py-2">Settings</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>
