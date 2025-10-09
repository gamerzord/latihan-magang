export default defineEventHandler((event) => {
  const token = getCookie(event, 'auth-token')
})