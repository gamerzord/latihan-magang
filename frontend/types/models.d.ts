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

export interface LoginResponse {
  token: string
  user: user
}

export interface Employee {
  id: number
  employee_code: string
  name: string
  email: string
  phone: string
  division_id: number | null
  position_id:number | null
  join_date: Date | string
  status: 'active' | 'inactive' | 'resigned'
  address: string
  created_at: Date | string
  updated_at: Date | string
  division?
  position?
}

export interface Division {
  id: number
  name: string
  code: string
  description: string
  is_active: boolean
  employees_count?: number
}

export interface Position {
  id: number
  name: string
  code: string
  level: 'staff' | 'supervisor' | 'manager' | 'director' | 'executive'
  description: string
  is_active: boolean
  employees_count?: number
}