import { ref } from 'vue'
import axios from '../config/axios'

export default function useTask() {

    const tasks = ref([])
    const task = ref(null)
    const error = ref(null)

    async function fetchTasks() {
        error.value = null

        try {
            const response = await axios.get('/tasks')
            tasks.value = response.data.tasks
        } catch (err) {
            console.error('Fetch tasks error:', err)
            error.value = err.response?.data?.message || 'Failed to fetch tasks.'
        } 
    }


    async function createTask(todo) {
        error.value = null;

        try {
            const response = await axios.post('/tasks', { todos: todo })
            task.value = response.data.task
            tasks.value.unshift(task.value) 
            return { success: response.data.message }
        } catch (err) {
            const errors = err.response?.data?.errors;
            const firstError = errors ? Object.values(errors)[0][0] : error.response?.data?.message || 'Failed to create task.';
            return { error: firstError };

        } 
    }

    async function showTask(id) {
        error.value = null
    
        try {
            const response = await axios.get(`/tasks/${id}`)
            task.value = response.data.task
            return { task: task.value, error: null }
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch task.'
            return { task: null, error: error.value }
        }
    }

    async function updateTask(id, todo) {
        error.value = null
    
        try {

            const response = await axios.put(`/tasks/${id}`, { todos: todo })
            task.value = response.data.task
    
            const index = tasks.value.findIndex(t => t.id === id)
            if (index !== -1) {
                tasks.value[index] = task.value
            }
    
            return { error: false } 
        } catch (err) {
            console.error('Update task error:', err)
            error.value = err.response?.data?.message || 'Failed to update task.'
            return { error: error.value }
        } 
    }

    async function deleteTask(id) {
        error.value = null
        try {
            await axios.delete(`/tasks/${id}`)
            return { success: 'Task deleted successfully' }
        } catch (err) {
            console.error('Delete task error:', err)
            return { error: err.response?.data?.message || 'Failed to delete task.' }
        }
    }


    return {
        tasks,
        task,
        error,
        fetchTasks,
        createTask,
        showTask,
        updateTask,
        deleteTask,
    }
}
