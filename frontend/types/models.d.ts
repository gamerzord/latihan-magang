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
}

interface LoginResponse {
  user: {
    id: number
    name: string
    email: string
  }
  token: string
}