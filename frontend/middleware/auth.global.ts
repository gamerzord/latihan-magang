export default defineNuxtRouteMiddleware((to) => {
  const token = useCookie('auth-token')
  
  if (to.path === '/dashboard/login' || to.path === '/dashboard/register') {
    return
  }
  
  if (!token.value) {
    return navigateTo('/dashboard/login')
  }
})