export default defineNuxtRouteMiddleware((to) => {
  if (import.meta.server) {
  const userToken = useCookie('auth-token').value
  const adminToken = useCookie('admin-auth-token').value
  const token = userToken || adminToken

  const publicPages = ['/dashboard/login', '/dashboard/register', '/dashboard/admin']

  if (publicPages.includes(to.path)) {
    if (token) return navigateTo('/')
    return
  }

  if (!token) return navigateTo('/dashboard/login')
  }
})