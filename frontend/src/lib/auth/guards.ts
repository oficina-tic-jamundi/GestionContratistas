import { error } from '@sveltejs/kit';
import { session } from '$lib/features/auth/session.svelte';
import type { Permission } from './permissions';

/**
 * Usar en el `load` de las páginas protegidas. Solo mejora la experiencia (evita mostrar
 * una pantalla que el backend igual rechazaría); no es un control de seguridad.
 */
export function requirePermission(...permissions: Permission[]): void {
  if (!session.can(...permissions)) {
    error(403, 'No tiene permiso para acceder a esta sección.');
  }
}

/** Igual que requirePermission, pero basta con tener uno de los permisos. */
export function requireAnyPermission(...permissions: Permission[]): void {
  if (!session.canAny(...permissions)) {
    error(403, 'No tiene permiso para acceder a esta sección.');
  }
}
