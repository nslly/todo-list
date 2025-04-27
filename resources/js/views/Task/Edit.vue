<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
        <div v-if="task && !error" class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Edit Task</h1>
                <p class="text-gray-500 mt-2">Modify the task details below and save your changes.</p>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700">Task Title</label>
                    <input
                        v-model="task.todos"
                        type="text"
                        id="title"
                        placeholder="Enter task title"
                        class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <div v-if="errorMessage " class="text-red-500 mb-4 text-sm">
                    {{ errorMessage  }}
                </div>

                <div class="flex justify-end space-x-4">
                    <button
                        type="button"
                        @click="handleCancel"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
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

    const { showTask, task, updateTask } = useTask()
    const route = useRoute()
    const router = useRouter()

    const error = ref(null)
    const errorMessage = ref('')
    const errorDetails = ref('')

    const taskId = route.params.id

    onMounted(async () => {
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

        return
    })

    const handleSubmit = async () => {
        const taskId = route.params.id
        const todo = task.value.todos

        const result = await updateTask(taskId, todo)
        if(result.error)
        {
            return errorMessage.value = result.error

        } else {
            router.push(`/tasks/${taskId}`)
        }

    }

    const handleCancel = () => {
        router.push(`/`)
    }
</script>
