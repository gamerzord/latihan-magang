export const useAuth = () => {
  const token = useCookie('auth-token', {
    maxAge: 60 * 60 * 24 * 7,
    secure: true,
    httpOnly: true,
    sameSite: 'strict'
  })
  const login = async (email: string, password: string) => {
    try {
      const response = await $fetch('/api/login', {
        method: 'POST',
        body: { email, password },
        credentials: 'include'
      })
     
      return response
    } catch (error) {
      throw error
    }
  }
  const logout = async () => {
    await $fetch('/api/logout', { 
      method: 'POST',
      credentials: 'include'
    })
    await navigateTo('/dashboard/login')
  }
  const user = useState('auth.user', () => null)
  return {
    token: readonly(token),
    user: readonly(user),
    login,
    logout
  }
}