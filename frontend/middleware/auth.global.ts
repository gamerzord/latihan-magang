export default defineNuxtRouteMiddleware((to) => {
  // Only run this check in the browser
  if (typeof window === 'undefined') return

  const token = localStorage.getItem('token')

  // Allow login/register routes
  if (to.path === '/dashboard/login' || to.path === '/dashboard/register') return

  // If no token, redirect to login
  if (!token) return navigateTo('/dashboard/login')
})