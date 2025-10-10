export interface Product {
  id: number
  name: string
  qty: number
  price: number
  description: string
}

export interface User {
  id: number
  name: string
  email: string
  password: string
  role: string
}

interface LoginResponse {
  token: string
  user: user
}