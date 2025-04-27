<template>
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <router-link to="/" class="text-2xl font-extrabold text-blue-600">
                TODO LIST
            </router-link>

            <ul class="hidden md:flex space-x-8 font-medium text-gray-600">
                <li v-if="!isAuthenticated">
                    <router-link 
                        to="/login" 
                        class="hover:text-blue-600 transition"
                        :class="{ 'text-blue-600 font-semibold': $route.path === '/login' }"
                    >
                        Login
                    </router-link>
                </li>
                <li v-else class="flex items-center space-x-2" >
                    <span class="text-slate-400">Hi, {{ user.name }},</span>
                    <button @click="logout" class="text-white transition px-8 py-2.5 bg-blue-500 hover:bg-blue-600 rounded-md font-semibold">
                        Logout
                    </button>
                </li>
                
            </ul>

            <div class="md:hidden">
                <button @click="toggleMenu" class="focus:outline-none" aria-label="Toggle menu">
                    <svg v-if="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <div v-if="isOpen" class="md:hidden bg-white shadow-md transition-all duration-300 ease-in-out">
            <ul class="flex flex-col space-y-2 px-6 py-4 font-medium text-gray-700">
                <li v-if="!isAuthenticated">
                        <router-link 
                            to="/login" 
                            class="block py-2 hover:text-blue-600 transition"
                            :class="{ 'text-blue-600 font-semibold': $route.path === '/login' }"
                            @click="isOpen = false"
                        >
                            Login
                        </router-link>
                </li>
                <li v-else class="flex items-center space-x-2">
                    <span class="text-slate-400">Hi, {{ user.name }},</span>
                    <button 
                        @click="handleLogout" 
                        class="text-white transition px-8 py-2.5 bg-blue-500 hover:bg-blue-600 rounded-md font-semibold"
                    >
                    
                        Logout
                    </button>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
    import { ref, onMounted, watch, computed } from 'vue'
    import { useRouter } from 'vue-router'
    import useAuth from '@/composables/useAuth'

    const router = useRouter()
    const { logout, isAuthenticated, user } = useAuth()
    const isOpen = ref(false)

    router.afterEach(() => {
        isOpen.value = false
    })

    function toggleMenu() {
        isOpen.value = !isOpen.value
    }

    async function handleLogout() {
        await logout()
        isOpen.value = false
        router.push('/login')
    }


</script>