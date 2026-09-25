<nav
    data-proposal-navigation
    x-data="{
      mobileMenuOpen: false,
      activeMobileMenu: '',
      navigationMenuOpen: false,
      navigationMenu: '',
      navigationMenuTrigger: null,
      navigationMenuShow(menu, trigger, focusFirst = false) {
          this.navigationMenuClearCloseTimeout();
          if (!focusFirst && this.$refs.navigationDropdown.contains(document.activeElement)) return;
          this.navigationMenuTrigger = trigger;
          this.navigationMenu = menu;
          this.navigationMenuOpen = true;
          this.navigationMenuReposition(trigger);
          if (focusFirst) this.$nextTick(() => {
              document.getElementById(trigger.getAttribute('aria-controls')).querySelector('a').focus();
          });
      },
      navigationMenuTab(event) {
          const links = [...document.getElementById(this.navigationMenuTrigger.getAttribute('aria-controls')).querySelectorAll('a')];
          if (event.shiftKey && event.target === links[0]) {
              event.preventDefault();
              this.navigationMenuClose(true);
          } else if (!event.shiftKey && event.target === links[links.length - 1]) {
              event.preventDefault();
              const next = this.navigationMenuTrigger.closest('li').nextElementSibling.querySelector('button, a');
              this.navigationMenuClose();
              next.focus();
          }
      },
      navigationMenuCloseDelay: 200,
      navigationMenuCloseTimeout: null,
      navigationMenuLeave() {
          this.navigationMenuClearCloseTimeout();
          let that = this;
          this.navigationMenuCloseTimeout = setTimeout(() => {
              if (!that.$refs.navigationDropdown.contains(document.activeElement)
                  && document.activeElement !== that.navigationMenuTrigger) that.navigationMenuClose();
          }, this.navigationMenuCloseDelay);
      },
      navigationMenuReposition(navElement) {
          this.navigationMenuClearCloseTimeout();
          if (this.$refs.navigationDropdown) {
            this.$refs.navigationDropdown.style.left = navElement.offsetLeft + 'px';
            this.$refs.navigationDropdown.style.marginLeft = (navElement.offsetWidth/2) + 'px';
          }
      },
      navigationMenuClearCloseTimeout(){
          clearTimeout(this.navigationMenuCloseTimeout);
      },
      navigationMenuClose(restoreFocus = false){
          this.navigationMenuClearCloseTimeout();
          if (restoreFocus && this.navigationMenuTrigger) this.navigationMenuTrigger.focus();
          this.navigationMenuOpen = false;
          this.navigationMenu = '';
      }
  }"
    @keydown.escape.window="
      mobileMenuOpen = false;
      if (navigationMenuOpen) {
          $event.preventDefault();
          navigationMenuClose($refs.navigationDropdown.contains(document.activeElement));
      }
  "
    @focusout="$nextTick(() => {
        if (navigationMenuOpen && !$refs.navigationDropdown.contains(document.activeElement)
            && document.activeElement !== navigationMenuTrigger) navigationMenuClose();
    })"
    class="relative z-30 w-full bg-white dark:bg-gray-800"
    aria-label="Primary navigation"
>
    <!-- Skip link (WCAG 2.4.1) -->
    <a
        href="#proposal-list"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50
           focus:rounded focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:font-semibold
           focus:text-gray-900 focus:shadow dark:focus:bg-gray-800 dark:focus:text-white"
    >
        Skip to main content
    </a>

    <!-- Optional SR-only status region -->
    <div class="sr-only" aria-live="polite" aria-atomic="true">
        <span x-text="mobileMenuOpen ? 'Mobile menu expanded.' : 'Mobile menu collapsed.'"></span>
    </div>

    <!-- Header with Logo, Desktop Nav, and Mobile Toggle -->
    <div class="flex items-center justify-between bg-dsv border-b border-susecondary px-4 py-2 dark:bg-gray-800">
        <!-- Logo (always left) -->
        @include('navbar.partials.logo')

        <!-- Desktop Navigation -->
        @include('navbar.partials.desktopmenu')
        {{-- @include('navbar.partials.banner') --}}

<!-- Right controls -->
<div class="ml-auto flex items-center gap-2">
    @include('navbar.partials.dark_toggle')

    <!-- Mobile Menu Toggle Button -->
    @include('navbar.partials.mobile_menu_toggle')
</div>
</div>

<!-- Mobile Navigation Menu -->
@include('navbar.partials.mobilemenu')

<!-- Desktop Dropdown Menu -->
@include('navbar.partials.desktop_dropdown')
</nav>

