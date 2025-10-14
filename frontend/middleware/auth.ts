export default defineNuxtRouteMiddleware((to) => {
  if (process.client) {
    const navigationEntry = performance.getEntriesByType('navigation')[0] as PerformanceNavigationTiming
    
    if (navigationEntry && (navigationEntry.type === 'reload' || navigationEntry.type === 'navigate')) {
      if (performance.navigation?.type === 1) { // TYPE_RELOAD
        localStorage.clear()
        useCookie('auth_token').value = null
        console.log('Local storage cleared on page reload!')
      }
    }
  }

  const cookieToken = useCookie('auth_token').value
  const localToken = process.client ? localStorage.getItem('auth_token') : null
  const token = cookieToken || localToken

  if (['/dashboard/login', '/dashboard/register'].includes(to.path)) {
    if (token) return navigateTo('/')
    return
  }

  if (!token) return navigateTo('/dashboard/login')
})