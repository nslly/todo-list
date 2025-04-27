import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from '../config/axios'

export default function useAuth() {
    const router = useRouter()
    const user = ref(JSON.parse(localStorage.getItem('user')) || null)
    const token = ref(localStorage.getItem('token') || null)
    const isAuthenticated = ref(!!localStorage.getItem('token'))

    if (token.value) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
    }



    async function login(email, password) {
        try {
            const response = await axios.post('/login', {
                email,
                password
            })
    
            if (response.data.token) {
                token.value = response.data.token
                user.value = response.data.user
                
                localStorage.setItem('token', token.value)
                localStorage.setItem('user', JSON.stringify(user.value))

                
                axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`

                isAuthenticated.value = true
                router.push('/')

                return { success: response.data.message.success }

            }
            return { error: 'Unknown login error.' }

        } catch (error) {
            const errors = error.response?.data?.errors;
            const firstError = errors ? Object.values(errors)[0][0] : error.response?.data?.message || 'Login failed. Please try again.';
            
            return { error: firstError };
        }
    }

    async function logout() {
        try {
            await axios.post('/logout')
        } catch (error) {
            console.error('Logout error:', error)
        } finally {
            token.value = null
            user.value = null
            isAuthenticated.value = false
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            delete axios.defaults.headers.common['Authorization']
            
            router.push('/login')
        }
    }

    return {
        user,
        token,
        login,
        isAuthenticated,
        logout,
    }
}