<script lang="ts">
  import type { Snippet } from 'svelte';
  import { afterNavigate, goto, invalidateAll } from '$app/navigation';
  import { resolve } from '$app/paths';
  import { page } from '$app/state';
  import { Permission } from '$lib/auth/permissions';
  import Badge from '$lib/components/ui/Badge.svelte';
  import BrandMark from '$lib/components/ui/BrandMark.svelte';
  import Icon, { type IconName } from '$lib/components/ui/Icon.svelte';
  import { session } from '$lib/features/auth/session.svelte';
  import NotificationBell from '$lib/features/notifications/NotificationBell.svelte';
  import { formatDateTime } from '$lib/utils/format';

  let { children }: { children: Snippet } = $props();

  interface NavItem {
    path:
      | '/'
      | '/activities'
      | '/history'
      | '/contracts'
      | '/reports'
      | '/payments'
      | '/notifications'
      | '/statistics'
      | '/contractors'
      | '/departments'
      | '/users'
      | '/roles'
      | '/audit'
      | '/jobs';
    label: string;
    icon: IconName;
    visible: boolean;
  }

  const canSeeContracts = $derived(
    session.canAny(
      Permission.ContractsViewAll,
      Permission.ContractsViewAssigned,
      Permission.ContractsViewOwn
    )
  );

  /**
   * Un menú corto por rol (ADR-021): cada persona ve solo lo que usa.
   *
   * - Contratista: su panel y sus actividades.
   * - Supervisor: su panel y sus contratistas (allí están las actividades y los informes).
   * - Administración: panel, contratistas, historial, estadísticas y roles y permisos.
   *
   * Lo demás (contratos, pagos, dependencias, auditoría, tareas programadas) sigue disponible
   * desde las pantallas donde se necesita; no ocupa espacio en el menú.
   */
  const isContractor = $derived(
    session.can(Permission.ActivitiesExecute) &&
      !session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned)
  );
  const isSupervisor = $derived(
    session.can(Permission.ContractsViewAssigned) && !session.can(Permission.ContractsViewAll)
  );

  const mainNav = $derived.by((): NavItem[] => {
    if (isContractor) {
      return [
        { path: '/', label: 'Panel de control', icon: 'grid', visible: true },
        { path: '/activities', label: 'Mis actividades', icon: 'check-list', visible: true }
      ];
    }
    if (isSupervisor) {
      return [
        { path: '/', label: 'Panel de control', icon: 'grid', visible: true },
        { path: '/contractors', label: 'Contratistas', icon: 'users', visible: true }
      ];
    }
    return (
      [
        { path: '/', label: 'Panel de control', icon: 'grid', visible: true },
        {
          path: '/contractors',
          label: 'Contratistas',
          icon: 'users',
          visible: session.can(Permission.ContractorsView)
        },
        { path: '/history', label: 'Historial', icon: 'clock', visible: canSeeContracts },
        {
          path: '/statistics',
          label: 'Estadísticas',
          icon: 'chart',
          visible: session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned)
        },
        {
          path: '/roles',
          label: 'Roles y permisos',
          icon: 'shield',
          visible: session.can(Permission.RolesView)
        }
      ] satisfies NavItem[]
    ).filter((item) => item.visible);
  });

  function isActive(path: NavItem['path']): boolean {
    const href = resolve(path);
    return path === '/' ? page.url.pathname === href : page.url.pathname.startsWith(href);
  }

  // Título de la barra superior: el módulo activo del menú. Las pantallas que no están en el
  // menú, pero se abren desde otras (contratos, pagos, etc.), traen su título de esta lista.
  const OTHER_TITLES: [string, string][] = [
    ['/account/password', 'Cambiar contraseña'],
    ['/contracts', 'Contratos'],
    ['/reports', 'Informes'],
    ['/payments', 'Pagos'],
    ['/departments', 'Dependencias'],
    ['/users', 'Usuarios'],
    ['/audit', 'Auditoría'],
    ['/jobs', 'Tareas programadas'],
    ['/notifications', 'Notificaciones']
  ];

  const pageTitle = $derived.by(() => {
    const active = mainNav.find((item) => isActive(item.path));
    if (active) return active.label;
    const path = page.url.pathname;
    const other = OTHER_TITLES.find(([route]) => path.startsWith(resolve(route as '/')));
    return other?.[1] ?? 'SIGCON';
  });

  const today = new Intl.DateTimeFormat('es-CO', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    timeZone: 'America/Bogota'
  }).format(new Date());

  const initial = $derived(session.user?.full_name.trim().charAt(0).toUpperCase() ?? '?');

  // Menú lateral en celular, sobre el <dialog> nativo: foco atrapado, cierre con Escape y
  // fondo inerte. En pantalla grande el menú es fijo y se puede contraer a solo iconos.
  let drawer = $state<HTMLDialogElement>();
  let menuOpen = $state(false);
  let profileOpen = $state(false);
  let loggingOut = $state(false);
  let collapsed = $state(false);

  const STORAGE_KEY = 'sigcon:sidebar-collapsed';

  // La preferencia de menú contraído se recuerda en este navegador; si no se puede leer
  // (modo privado, almacenamiento bloqueado), simplemente se muestra expandido.
  $effect(() => {
    try {
      collapsed = localStorage.getItem(STORAGE_KEY) === '1';
    } catch {
      collapsed = false;
    }
  });

  function toggleCollapsed(): void {
    collapsed = !collapsed;
    profileOpen = false;
    try {
      localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
    } catch {
      // Sin almacenamiento la preferencia dura lo que dure la pantalla abierta.
    }
  }

  function openMenu(): void {
    drawer?.showModal();
    menuOpen = true;
  }

  function closeMenu(): void {
    drawer?.close();
  }

  // Un clic fuera del panel (sobre el fondo oscurecido) llega al propio <dialog>.
  function closeOnBackdrop(event: MouseEvent): void {
    if (event.target === drawer) closeMenu();
  }

  // El contenido entra con una animación corta al cambiar de pantalla, no al filtrar o
  // paginar (ahí solo cambia la consulta y el foco debe quedarse donde está).
  let navKey = $state(0);
  let lastPath = page.url.pathname;

  afterNavigate(() => {
    closeMenu();
    refreshedAt = Date.now();
    if (page.url.pathname !== lastPath) {
      lastPath = page.url.pathname;
      navKey++;
    }
  });

  /**
   * Los datos de la pantalla se vuelven a pedir solos: al volver a la pestaña después de un
   * rato y cada dos minutos mientras se está mirando. Así el tablero no muestra cifras viejas
   * cuando algo cambió en el servidor (otro usuario, el Cron o una limpieza de datos).
   */
  let refreshing = $state(false);
  let refreshedAt = $state(Date.now());

  async function refresh(): Promise<void> {
    if (refreshing) return;
    refreshing = true;
    try {
      await invalidateAll();
      refreshedAt = Date.now();
    } finally {
      refreshing = false;
    }
  }

  function autoRefresh(): () => void {
    const WHILE_VISIBLE_MS = 120_000; // cada 2 minutos con la pestaña a la vista
    const AWAY_MS = 30_000; // al volver, si estuvo fuera más de medio minuto
    let hiddenAt: number | null = null;

    const onVisibility = () => {
      if (document.visibilityState === 'hidden') {
        hiddenAt = Date.now();
        return;
      }
      if (hiddenAt !== null && Date.now() - hiddenAt > AWAY_MS) void refresh();
      hiddenAt = null;
    };
    const timer = setInterval(() => {
      if (document.visibilityState === 'visible' && Date.now() - refreshedAt > WHILE_VISIBLE_MS) {
        void refresh();
      }
    }, 30_000);
    document.addEventListener('visibilitychange', onVisibility);

    return () => {
      clearInterval(timer);
      document.removeEventListener('visibilitychange', onVisibility);
    };
  }

  async function logout(): Promise<void> {
    loggingOut = true;
    try {
      await session.logout();
    } finally {
      loggingOut = false;
      closeMenu();
      await goto(resolve('/login'));
    }
  }
</script>

<!-- Navegación: la misma lista sirve al menú fijo y al del celular. `rail` = solo iconos. -->
{#snippet navList(rail: boolean)}
  <nav aria-label="Principal" class="flex-1 overflow-y-auto px-3 py-5">
    {#if !rail}
      <p class="label-eyebrow px-3 pb-2">Menú principal</p>
    {/if}
    <ul class="space-y-1">
      {#each mainNav as item (item.path)}
        {@const current = isActive(item.path)}
        <li>
          <a
            href={resolve(item.path)}
            aria-current={current ? 'page' : undefined}
            title={rail ? item.label : undefined}
            class="group relative flex items-center rounded-xl text-[15px] font-medium transition-colors
              {rail ? 'justify-center px-0 py-3' : 'gap-3 px-3.5 py-3'}
              {current ? 'bg-primary-soft text-primary' : 'text-ink hover:bg-white/5'}"
          >
            {#if current}
              <span
                class="absolute inset-y-2 left-0 w-[3px] rounded-full bg-primary"
                aria-hidden="true"
              ></span>
            {/if}
            <Icon name={item.icon} class="size-5 shrink-0" />
            {#if rail}
              <span class="sr-only">{item.label}</span>
            {:else}
              <span class="truncate">{item.label}</span>
            {/if}
          </a>
        </li>
      {/each}
    </ul>
  </nav>
{/snippet}

<!-- Perfil y cierre de sesión, al pie del menú. -->
{#snippet profileBlock(rail: boolean)}
  {#if session.user}
    <div class="border-t border-border px-3 py-3">
      <button
        type="button"
        class="flex w-full items-center rounded-xl text-left transition-colors hover:bg-white/5
          {rail ? 'justify-center px-0 py-2' : 'gap-3 px-2 py-2'}"
        aria-expanded={rail ? undefined : profileOpen}
        aria-controls={rail ? undefined : 'perfil-cuenta'}
        title={rail ? 'Mi cuenta' : undefined}
        onclick={() => (rail ? toggleCollapsed() : (profileOpen = !profileOpen))}
      >
        <span
          class="inline-flex size-10 shrink-0 items-center justify-center rounded-full bg-linear-135 from-[#3b82f6] to-[#2563eb] font-semibold text-white shadow-lg shadow-blue-900/40"
          aria-hidden="true">{initial}</span
        >
        {#if rail}
          <span class="sr-only">Mi cuenta</span>
        {:else}
          <span class="min-w-0 flex-1">
            <span class="block truncate font-semibold text-ink">{session.user.full_name}</span>
            <span class="block truncate text-xs text-primary">
              {session.user.roles.map((r) => r.name).join(', ') || 'Sin rol asignado'}
            </span>
          </span>
          <Icon
            name="chevron"
            class="size-4 shrink-0 text-muted transition-transform {profileOpen
              ? 'rotate-180'
              : ''}"
          />
          <span class="sr-only">Mi cuenta</span>
        {/if}
      </button>

      {#if profileOpen && !rail}
        <div
          id="perfil-cuenta"
          class="animate-fade mx-2 mt-2 rounded-xl border border-border bg-canvas p-3 text-sm"
        >
          <p class="label-eyebrow mb-2">Mi cuenta</p>
          <dl class="space-y-2">
            <div>
              <dt class="text-xs text-muted">Correo</dt>
              <dd class="break-all text-ink">{session.user.email}</dd>
            </div>
            <div>
              <dt class="text-xs text-muted">Roles</dt>
              <dd class="mt-0.5 flex flex-wrap gap-1">
                {#each session.user.roles as role (role.code)}
                  <Badge tone="info">{role.name}</Badge>
                {:else}
                  <span class="text-muted">Sin rol asignado</span>
                {/each}
              </dd>
            </div>
            <div>
              <dt class="text-xs text-muted">Último inicio de sesión registrado</dt>
              <dd class="text-ink">{formatDateTime(session.user.last_login_at, 'Nunca')}</dd>
            </div>
          </dl>
          <a
            href={resolve('/account/password')}
            class="mt-3 flex items-center gap-2 font-medium text-primary hover:underline"
          >
            <Icon name="key" class="size-4" />
            Cambiar contraseña
          </a>
        </div>
      {/if}

      <div class="mt-1">
        <button
          type="button"
          class="flex w-full items-center rounded-lg text-sm text-muted transition-colors hover:bg-white/5 hover:text-ink
            {rail ? 'justify-center px-0 py-2.5' : 'gap-2.5 px-2 py-2'}"
          title={rail ? 'Cerrar sesión' : undefined}
          onclick={logout}
          disabled={loggingOut}
        >
          <Icon name="logout" class="size-4 shrink-0" />
          {#if rail}<span class="sr-only">Cerrar sesión</span>{:else}Cerrar sesión{/if}
        </button>
      </div>
    </div>
  {/if}
{/snippet}

<a
  href="#contenido"
  class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:rounded-lg focus:bg-deep focus:px-3 focus:py-2"
>
  Saltar al contenido
</a>

<div class="flex min-h-dvh" {@attach autoRefresh}>
  <!-- Menú fijo (pantalla grande). En celular se abre como panel deslizante. -->
  <aside
    class="sticky top-0 hidden h-dvh shrink-0 flex-col border-r border-border bg-surface-strong transition-[width] duration-200 ease-out lg:flex
      {collapsed ? 'w-[5rem]' : 'w-[17.5rem]'}"
    aria-label="Menú lateral"
  >
    <div
      class="flex items-center gap-3 border-b border-border px-4 py-4 {collapsed
        ? 'justify-center'
        : ''}"
    >
      {#if !collapsed}
        <a href={resolve('/')} class="flex min-w-0 flex-1 items-center gap-3">
          <BrandMark size="md" />
          <span class="min-w-0">
            <span class="block truncate font-semibold text-ink">Alcaldía Jamundí</span>
            <span class="block text-xs text-muted" translate="no">SIGCON</span>
          </span>
        </a>
      {/if}
      <button
        type="button"
        class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg text-muted transition-colors hover:bg-white/5 hover:text-ink"
        aria-label={collapsed ? 'Expandir menú' : 'Contraer menú'}
        aria-expanded={!collapsed}
        title={collapsed ? 'Expandir menú' : 'Contraer menú'}
        onclick={toggleCollapsed}
      >
        <Icon name="menu" class="size-5" />
      </button>
    </div>

    {@render navList(collapsed)}
    {@render profileBlock(collapsed)}
  </aside>

  <div class="flex min-w-0 flex-1 flex-col">
    <header
      class="sticky top-0 z-30 border-b border-border bg-surface-strong"
      style:padding-top="env(safe-area-inset-top)"
    >
      <div class="flex items-center gap-3 px-4 py-3 sm:gap-4 sm:px-6">
        <button
          type="button"
          class="inline-flex size-10 items-center justify-center rounded-xl text-muted transition-colors hover:bg-primary-soft hover:text-ink lg:hidden"
          aria-label="Abrir menú"
          aria-haspopup="dialog"
          aria-expanded={menuOpen}
          aria-controls="menu-principal"
          onclick={openMenu}
        >
          <Icon name="menu" class="size-6" />
        </button>

        <a href={resolve('/')} class="flex items-center gap-2.5 lg:hidden">
          <BrandMark />
          <!-- En celular el texto se oculta a la vista, pero sigue dando nombre al enlace. -->
          <span class="sr-only font-semibold text-ink sm:not-sr-only">Alcaldía Jamundí</span>
        </a>

        <span class="hidden h-6 w-px bg-border sm:block lg:hidden" aria-hidden="true"></span>
        <p class="min-w-0 truncate text-base font-semibold text-ink sm:text-lg">{pageTitle}</p>

        <div class="ml-auto flex items-center gap-1 sm:gap-2">
          <span class="mr-2 hidden text-sm text-muted md:inline">{today}</span>
          <button
            type="button"
            class="inline-flex size-10 items-center justify-center rounded-xl text-muted transition-colors hover:bg-primary-soft hover:text-ink"
            aria-label="Actualizar los datos de la pantalla"
            title="Actualizar los datos de la pantalla"
            onclick={refresh}
            disabled={refreshing}
          >
            <Icon name="refresh" class="size-5 {refreshing ? 'animate-spin' : ''}" />
          </button>
          <NotificationBell />
        </div>
      </div>
    </header>

    <main id="contenido" class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 sm:py-8">
      {#key navKey}
        <div class="animate-rise">{@render children()}</div>
      {/key}
    </main>

    <footer class="border-t border-border">
      <div
        class="mx-auto max-w-7xl px-4 py-4 text-xs text-muted sm:px-6"
        style:padding-bottom="max(1rem, env(safe-area-inset-bottom))"
      >
        SIGCON · Alcaldía Municipal de Jamundí — Valle del Cauca, Colombia
      </div>
    </footer>
  </div>
</div>

<dialog
  bind:this={drawer}
  id="menu-principal"
  aria-label="Menú principal"
  class="drawer m-0 h-dvh max-h-dvh w-[19rem] max-w-[85vw] border-0 border-r border-border bg-surface-strong p-0 text-ink shadow-2xl shadow-black/50 backdrop:bg-backdrop backdrop:backdrop-blur-sm lg:hidden"
  onclick={closeOnBackdrop}
  onclose={() => {
    menuOpen = false;
    profileOpen = false;
  }}
>
  <div class="flex h-full flex-col">
    <div class="flex items-center gap-3 border-b border-border px-5 py-5">
      <BrandMark size="md" />
      <div class="min-w-0 flex-1">
        <p class="font-semibold text-ink">Alcaldía Jamundí</p>
        <p class="text-xs text-muted" translate="no">SIGCON</p>
      </div>
      <button
        type="button"
        class="inline-flex size-9 items-center justify-center rounded-lg text-muted transition-colors hover:bg-white/5 hover:text-ink"
        aria-label="Cerrar menú"
        onclick={closeMenu}
      >
        <Icon name="close" />
      </button>
    </div>

    {@render navList(false)}
    {@render profileBlock(false)}
  </div>
</dialog>
