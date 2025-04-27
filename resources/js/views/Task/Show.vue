<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
        <div v-if="task && !error" class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-8">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">{{ task?.todos }}</h1>
                <div class="flex space-x-4 mt-4 sm:mt-0">
                    <router-link :to="{ name: 'tasks.edit', params: { id: task?.id } }" class="text-blue-500 hover:text-blue-600">
                        ✎ Edit
                    </router-link>
                    <button @click="handleDeleteTask(task?.id)" class="text-red-500 hover:text-red-600">
                        ✕ Delete
                    </button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center space-x-0 sm:space-x-4 text-sm text-gray-500 mb-6">
                <div class="flex items-center space-x-1 mb-2 sm:mb-0">
                    <span class="font-semibold">Created At:</span>
                    <span>{{ formatDate(task?.created_at) }}</span>
                </div>
                <div class="flex items-center space-x-1">
                    <span class="font-semibold">Updated At:</span>
                    <span>{{ formatDate(task?.updated_at) }}</span>
                </div>
            </div>

            <div class="mt-6 text-right">
                <router-link to="/" class="text-blue-500 hover:text-blue-600">Back to Tasks</router-link>
            </div>
        </div>

        <div v-if="error" class="w-full max-w-md bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="flex flex-col items-center">
                <svg class="w-12 h-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ errorMessage }}</h3>
                <p class="text-gray-500 mb-6">{{ errorDetails }}</p>
                <router-link to="/" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Return to Tasks
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import useTask from '@/composables/useTask'

    const { showTask, task, deleteTask } = useTask()
    const route = useRoute()
    const router = useRouter()

    const error = ref(false)
    const errorMessage = ref('')
    const errorDetails = ref('')

    onMounted(async () => {
        const taskId = route.params.id

        const result = await showTask(taskId)
        if (result.error) {
            error.value = true
            errorMessage.value = 'Task Not Found'
            errorDetails.value = "The task you're looking for doesn't exist or you don't have permission to view it."
            return
        } 

        if (!task.value) {
            error.value = true
            errorMessage.value = 'Unauthorized'
            errorDetails.value = "You are not authorized to view this task."
            return
        }
    })

    const handleDeleteTask = async (id) => {
        const result = await deleteTask(id)
        if (!result.error) {
            router.push('/')
        } else {
            console.error(result.error)
        }
    }

    const formatDate = (dateString) => {
        const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }
        return new Date(dateString).toLocaleDateString(undefined, options)
    }
</script>
