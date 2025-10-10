export default defineNuxtRouteMiddleware((to) => {
  const cookieToken = useCookie('auth_token').value
  const localToken = process.client ? localStorage.getItem('auth_token') : null
  const token = cookieToken || localToken

  if (['/dashboard/login', '/dashboard/register'].includes(to.path)) {
    if (token) return navigateTo('/')
    return
  }

  if (!token) return navigateTo('/dashboard/login')
})