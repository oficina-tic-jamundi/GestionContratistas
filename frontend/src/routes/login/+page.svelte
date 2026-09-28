<script lang="ts">
  import { goto } from '$app/navigation';
  import { resolve } from '$app/paths';
  import { ApiError } from '$lib/api';
  import Alert from '$lib/components/ui/Alert.svelte';
  import BrandMark from '$lib/components/ui/BrandMark.svelte';
  import Button from '$lib/components/ui/Button.svelte';
  import Icon from '$lib/components/ui/Icon.svelte';
  import TextField from '$lib/components/ui/TextField.svelte';
  import { session } from '$lib/features/auth/session.svelte';
  import { fieldErrors } from '$lib/utils/forms';
  import type { PageProps } from './$types';

  let { data }: PageProps = $props();

  let email = $state('');
  let password = $state('');
  let submitting = $state(false);
  let error = $state<unknown>(null);

  async function submit(event: SubmitEvent): Promise<void> {
    event.preventDefault();
    submitting = true;
    error = null;
    try {
      const profile = await session.login(email, password);
      password = '';
      if (profile.user.must_change_password) {
        await goto(resolve('/account/password'), { replaceState: true });
      } else {
        // redirectTo es una ruta interna validada por safeRedirect (ya incluye la ruta base).
        // eslint-disable-next-line svelte/no-navigation-without-resolve
        await goto(data.redirectTo, { replaceState: true });
      }
    } catch (e) {
      error = e;
    } finally {
      submitting = false;
    }
  }

  const message = $derived(
    error instanceof ApiError
      ? error.kind === 'validation'
        ? null
        : error.message
      : error
        ? 'Ocurrió un error inesperado.'
        : null
  );

  /** Lo que el sistema hace, en palabras del usuario. Solo acompaña; no es publicidad. */
  const features = [
    { icon: 'check-list', text: 'Sus actividades y el avance de cada contrato, al día.' },
    { icon: 'file', text: 'Informes con acta y registro fotográfico, sin papeles.' },
    { icon: 'shield', text: 'Cada acción queda registrada, con su nombre y su fecha.' }
  ] as const;
</script>

<svelte:head>
  <title>Iniciar sesión · SIGCON</title>
</svelte:head>

<div class="grid min-h-dvh lg:grid-cols-[1.05fr_1fr]">
  <!-- Panel institucional: solo en pantalla grande; en celular manda el formulario. -->
  <aside
    class="relative hidden overflow-hidden border-r border-border lg:flex lg:flex-col lg:justify-between lg:p-12"
  >
    <div
      class="pointer-events-none absolute -top-32 -left-24 size-[32rem] rounded-full bg-primary/10 blur-3xl"
      aria-hidden="true"
    ></div>
    <div
      class="pointer-events-none absolute -right-32 -bottom-40 size-[34rem] rounded-full bg-[#1d4ed8]/15 blur-3xl"
      aria-hidden="true"
    ></div>

    <div class="relative flex items-center gap-3">
      <BrandMark size="md" />
      <div>
        <p class="font-semibold text-ink">Alcaldía Municipal de Jamundí</p>
        <p class="text-xs text-muted">Valle del Cauca, Colombia</p>
      </div>
    </div>

    <div class="relative max-w-lg">
      <p class="label-eyebrow" translate="no">SIGCON</p>
      <h1 class="mt-3 text-4xl font-bold tracking-tight text-ink">
        Sistema de Gestión de Contratistas
      </h1>
      <p class="mt-4 text-base text-muted">
        Contratos, actividades, informes y pagos de la Alcaldía en un solo lugar.
      </p>
      <ul class="mt-8 space-y-4">
        {#each features as feature (feature.text)}
          <li class="flex items-start gap-3 text-sm text-ink">
            <span
              class="grid size-9 shrink-0 place-items-center rounded-xl bg-primary-soft text-primary"
              aria-hidden="true"
            >
              <Icon name={feature.icon} class="size-5" />
            </span>
            <span class="pt-2">{feature.text}</span>
          </li>
        {/each}
      </ul>
    </div>

    <p class="relative text-xs text-muted">
      © {new Date().getFullYear()} Alcaldía Municipal de Jamundí — Valle del Cauca
    </p>
  </aside>

  <!-- Formulario -->
  <div class="flex flex-col items-center justify-center px-4 py-10 sm:px-8">
    <main class="w-full max-w-md">
      <div class="mb-8 flex flex-col items-center text-center lg:hidden">
        <BrandMark size="lg" />
        <h1 class="mt-6 text-2xl font-bold tracking-tight text-ink">Alcaldía de Jamundí</h1>
        <p class="mt-1 text-sm font-medium text-primary">Sistema de Gestión de Contratistas</p>
      </div>

      <div class="animate-rise rounded-3xl border border-border bg-surface p-6 shadow-pop sm:p-8">
        <h2 class="text-2xl font-bold tracking-tight text-ink">Iniciar sesión</h2>
        <p class="mt-1.5 text-sm text-muted">Ingrese sus credenciales institucionales.</p>

        <div class="mt-6 space-y-3">
          {#if data.expired && !error}
            <Alert variant="info">
              Su sesión finalizó. Inicie sesión nuevamente para continuar.
            </Alert>
          {/if}
          {#if message}
            <Alert variant="danger">{message}</Alert>
          {/if}
        </div>

        <form class="mt-6 space-y-5" onsubmit={submit} novalidate>
          <TextField
            label="Correo institucional"
            type="email"
            name="email"
            autocomplete="username"
            inputmode="email"
            spellcheck="false"
            placeholder="usuario@jamundi.gov.co"
            required
            bind:value={email}
            errors={fieldErrors(error, 'email')}
          />
          <TextField
            label="Contraseña"
            type="password"
            name="password"
            autocomplete="current-password"
            placeholder="••••••••"
            required
            bind:value={password}
            errors={fieldErrors(error, 'password')}
          />
          <Button type="submit" size="lg" loading={submitting} class="w-full">
            Ingresar al sistema
          </Button>
        </form>

        <p class="mt-6 text-center text-sm text-muted">
          ¿Olvidó su contraseña? Solicite al administrador del sistema que la restablezca.
        </p>
      </div>

      <p class="mt-8 text-center text-xs text-muted lg:hidden">
        © {new Date().getFullYear()} Alcaldía Municipal de Jamundí — Valle del Cauca
      </p>
    </main>
  </div>
</div>
