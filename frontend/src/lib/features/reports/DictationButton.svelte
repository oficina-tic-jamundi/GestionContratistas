<script lang="ts">
  import Icon from '$lib/components/ui/Icon.svelte';

  /**
   * Dictado por voz con el reconocimiento del navegador (Edge y Chrome). El audio lo transcribe
   * el navegador en sus propios servidores: por eso se avisa siempre y nunca se activa solo.
   * Si el navegador no lo admite, el botón no aparece y queda la escritura normal.
   *
   * `ontext` recibe SOLO lo nuevo de cada frase reconocida, para agregarlo al texto que ya hay.
   */
  let {
    ontext,
    label = 'Dictar con el micrófono',
    notice = true
  }: {
    ontext: (text: string) => void;
    label?: string;
    /** Aviso de privacidad junto al botón; si hay varios botones, basta con mostrarlo una vez. */
    notice?: boolean;
  } = $props();

  interface Recognizer {
    lang: string;
    continuous: boolean;
    interimResults: boolean;
    start: () => void;
    stop: () => void;
    onresult:
      | ((event: {
          resultIndex: number;
          results: ArrayLike<ArrayLike<{ transcript: string }> & { isFinal: boolean }>;
        }) => void)
      | null;
    onerror: ((event: { error: string }) => void) | null;
    onend: (() => void) | null;
  }

  type SpeechApi = new () => Recognizer;

  const api = $derived.by((): SpeechApi | null => {
    if (typeof window === 'undefined') return null;
    const w = window as unknown as {
      SpeechRecognition?: SpeechApi;
      webkitSpeechRecognition?: SpeechApi;
    };
    return w.SpeechRecognition ?? w.webkitSpeechRecognition ?? null;
  });

  let listening = $state(false);
  let error = $state<string | null>(null);
  let recognition: Recognizer | null = null;

  function toggle(): void {
    if (listening) {
      recognition?.stop();
      return;
    }
    const Recognition = api;
    if (!Recognition) return;

    error = null;
    recognition = new Recognition();
    recognition.lang = 'es-CO';
    recognition.continuous = true;
    recognition.interimResults = false;
    recognition.onresult = (event) => {
      // `results` acumula toda la sesión: solo se toma lo que llegó en este evento.
      let text = '';
      for (let i = event.resultIndex; i < event.results.length; i++) {
        const result = event.results[i];
        if (result?.isFinal) text += result[0]?.transcript ?? '';
      }
      if (text.trim()) ontext(text.trim());
    };
    recognition.onerror = (event) => {
      error =
        event.error === 'not-allowed'
          ? 'No se pudo usar el micrófono: permita el acceso en el navegador.'
          : 'No fue posible dictar. Intente de nuevo o escriba el texto.';
      listening = false;
    };
    recognition.onend = () => {
      listening = false;
    };
    recognition.start();
    listening = true;
  }
</script>

{#if api}
  <div class="space-y-2">
    <button
      type="button"
      class="inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm font-medium transition {listening
        ? 'border-danger/40 bg-danger-soft text-danger'
        : 'border-border bg-field text-ink hover:border-primary/50'}"
      aria-pressed={listening}
      onclick={toggle}
    >
      <Icon name="mic" class="size-4" />
      {listening ? 'Detener dictado' : label}
    </button>
    {#if listening}
      <p class="text-xs text-warning" role="status">
        Escuchando… hable con claridad y pulse "Detener dictado" al terminar.
      </p>
    {/if}
    {#if error}
      <p class="text-xs text-danger" role="alert">{error}</p>
    {/if}
    {#if notice}
      <p class="text-xs text-muted">
        El dictado lo transcribe su navegador (Edge o Chrome), que envía el audio a su propio
        servicio. No dicte información reservada; siempre puede escribir el texto.
      </p>
    {/if}
  </div>
{/if}
