<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Todo List</h2>

            <div class="flex-col sm:flex-row flex mb-6">
                <input
                    v-model="newTask"
                    type="text"
                    placeholder="What needs to be done?"
                    class="flex-1 border border-gray-300 rounded-l-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    @keyup.enter="handleAddTask"
                />

                <button
                    @click="handleAddTask"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold p-2 mt-2 sm:mt-0 sm:rounded-r-lg"
                >
                    Add
                </button>
            </div>
            
            <div v-if="error" class="text-red-500 mb-6 text-sm">
                {{ error }}
            </div>
            <div v-if="success" class="text-green-500 mb-6 text-sm">
                {{ success }}
            </div>

            <ul class="space-y-6">
                <li v-for="task in tasks" :key="task.id" class="flex items-center justify-between bg-gray-50 p-4 rounded-lg hover:shadow-md transition">
                    <div class="flex items-center space-x-3">
                        <router-link
                            :to="{ name: 'tasks.show', params: { id: task.id } }"
                            class="text-blue-500 hover:underline"
                        >
                            {{ task.todos }}
                        </router-link>
                    </div>
                    <div class="flex items-center space-x-2">
                        <router-link
                            :to="{ name: 'tasks.edit', params: { id: task.id } }"
                            class="text-green-500 hover:text-green-600"
                        >
                            Edit
                        </router-link>
                        <button
                            @click="handleDeleteTask(task.id)"
                            class="text-red-500 hover:text-red-600"
                        >
                            ✕
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed, onMounted } from 'vue'
    import useTask from '@/composables/useTask'
    import { useRouter } from 'vue-router'

    const { createTask, deleteTask, updateTask, fetchTasks, tasks } = useTask()

    const newTask = ref('')
    const error = ref(null)
    const success = ref(null)
    const router = useRouter()

    onMounted(() => {
        fetchTasks()
    })


    const handleAddTask = async () => {
        const result = await createTask(newTask.value)

        if (result.error) {
            error.value = result.error
            setTimeout(() => {
                error.value = null
            }, 2000)
        }

        if (result.success) {
            success.value = result.success
            setTimeout(() => {
                success.value = null
            }, 2000)
            newTask.value = '' 
        }
    }


    const handleDeleteTask = async (id) => {
        const result = await deleteTask(id)

        if (!result.error) {
            const index = tasks.value.findIndex(task => task.id === id)
            if (index !== -1) {
                tasks.value.splice(index, 1)
            }

            success.value = 'Task deleted successfully!'

            setTimeout(() => {
                success.value = null
            }, 3000)
        } else {
            error.value = result.error

            setTimeout(() => {
                error.value = null
            }, 3000)

            console.error(result.error)
        }
    }


</script>
