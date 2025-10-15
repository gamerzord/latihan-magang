export default defineNuxtRouteMiddleware((to) => {
  const cookieToken = useCookie('auth_token').value
  const localToken = import.meta.client ? localStorage.getItem('auth_token') : null
  const localTokenA = import.meta.client ? localStorage.getItem('admin_auth_token') : null
  const token = cookieToken || localToken || localTokenA

  if (['/dashboard/login', '/dashboard/register', '/dashboard/admin'].includes(to.path)) {
    if (token) return navigateTo('/')
    return
  }

  if (!token) return navigateTo('/dashboard/login')
})