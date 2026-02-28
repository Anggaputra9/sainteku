<aside {{-- Sidebar beralih berdasarkan variabel sidebarToggle --}}
  :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'" {{-- Class Statis: Hapus lg:translate-x-0 dari sini
  agar bisa disembunyikan di desktop --}} class="sidebar fixed left-0 top-0 z-[9999] flex h-screen w-[290px] flex-col overflow-y-hidden
         border-r border-gray-200 bg-white px-5 duration-300 ease-linear
         dark:border-gray-800 dark:bg-black lg:static" {{-- Menutup sidebar saat klik di luar (terutama di mobile) --}}
  @click.outside="sidebarToggle = false">
  <div :class="sidebarToggle ? 'justify-between' : 'justify-center'"
    class="sidebar-header flex items-center gap-2 pb-7 pt-8">
    <a href="{{ route('masterdata.index') }}">
      <span class="logo" :class="sidebarToggle ? '' : 'lg:hidden'">
        <img class="block dark:hidden" src="{{ asset('tailadmin/images/logo/logo.svg') }}" alt="Logo" />
        <img class="hidden dark:block" src="{{ asset('tailadmin/images/logo/logo-dark.svg') }}" alt="Logo" />
      </span>

      <img class="logo-icon" :class="sidebarToggle ? 'hidden' : 'lg:block'"
        src="{{ asset('tailadmin/images/logo/logo-icon.svg') }}" alt="Logo" />
    </a>
  </div>

  <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
    <nav x-data="{ selected: null }">
      <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
          <span class="menu-group-title" :class="!sidebarToggle ? 'lg:hidden' : ''">
            SISTEM UTAMA
          </span>
        </h3>

        <ul class="mb-6 flex flex-col gap-4">
          @foreach($menus as $menu)

            @php
              $isParentActive = false;

              if ($menu->children->count()) {
                foreach ($menu->children as $child) {
                  if (Route::is($child->menu_link) || Route::is($child->menu_link . '*')) {
                    $isParentActive = true;
                    break;
                  }
                }
              } else {
                if ($menu->menu_link && Route::is($menu->menu_link)) {
                  $isParentActive = true;
                }
              }
            @endphp

            <li x-data="{ open: {{ $isParentActive ? 'true' : 'false' }} }">

              @if($menu->children->count() > 0)

                      <a href="#" @click.prevent="open = !open" class="menu-item group
                 {{ $isParentActive ? 'menu-item-active' : 'menu-item-inactive' }}">

                        @if($menu->menu_icon)
                          <i class="{{ $menu->menu_icon }} mr-3"></i>
                        @endif

                        <span>{{ $menu->menu_name }}</span>

                        <i class="fa-solid fa-chevron-down ml-auto transition-transform duration-200"
                          :class="open ? 'rotate-180' : ''"></i>
                      </a>

                      <div x-show="open" x-transition class="overflow-hidden">

                        <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                          @foreach($menu->children as $child)

                            @php
                              $isChildActive = Route::is($child->menu_link)
                                || Route::is($child->menu_link . '*');
                            @endphp

                            <li>
                              <a href="{{ route($child->menu_link) }}" class="menu-dropdown-item group
                               {{ $isChildActive ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                {{ $child->menu_name }}
                              </a>
                            </li>

                          @endforeach
                        </ul>
                      </div>

              @else

                      <a href="{{ $menu->menu_link && $menu->menu_link !== '#' ? route($menu->menu_link) : '#' }}" class="menu-item group
                 {{ $isParentActive ? 'menu-item-active' : 'menu-item-inactive' }}">

                        @if($menu->menu_icon)
                          <i class="{{ $menu->menu_icon }} mr-3"></i>
                        @endif

                        {{ $menu->menu_name }}
                      </a>

              @endif

            </li>

          @endforeach
        </ul>
      </div>
    </nav>
  </div>
</aside>