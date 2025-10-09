export default defineNuxtRouteMiddleware((to) => {
  const token = localStorage.getItem('token')

  if (to.path === '/dashboard/login' || to.path === '/dashboard/register') return

  if (!token) return navigateTo('/dashboard/login')
})