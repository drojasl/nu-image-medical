<template>
    <div class="w-full sm:w-8/12 md:w5/12 p-5 bg-white">
        <h1 class="font-bold py-4 text-gray-800">LIST OF CLIENTS</h1>
        <div v-if="!clients.length">-- No clients registered --</div>
        <div v-for="client of clients" :key="client.id">
            <div>
                > <span class="font-semibold">{{ client.name }}</span>
                ({{ client.email }})
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                clients: []
            }
        },
        methods: {
            loadClients() {
                // TODO: Put API urls as constant
                axios.get('/api/clients')
                    .then(response => {
                        this.clients = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        mounted() {
            this.loadClients();
        }
    }
</script>
