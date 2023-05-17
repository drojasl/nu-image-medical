<template>
    <div class="w-full sm:w-8/12 md:w5/12 p-5 bg-white">
        <h1 class="font-bold py-4 text-gray-800">CREATE CLIENT</h1>

        <form method="POST" v-on:submit.prevent="createClientHandler()">
            <div class="d-block px-2 py-4">
                <input
                class="text-xl p-2 w-10/12 border-b-2 border-green-500"
                type="text"
                placeholder="Name"
                name="name"
                v-model="name"
                required
                >
                <span class="text-red-500">*</span>
                <br>
                <input
                class="text-xl p-2 mt-10 w-10/12 border-b-2 border-green-500"
                type="text"
                placeholder="Email"
                name="email"
                v-model="email"
                required
                >
                <span class="text-red-500">*</span>
                <br>
                <input
                class="text-xl p-2 mt-10 w-10/12 border-b-2 border-green-500"
                type="text"
                placeholder="Phone"
                name="phone"
                v-model="phone"
                required
                >
                <span class="text-red-500">*</span>
                <textarea
                    class="text-xl p-2 mt-10 w-10/12 border-b-2 border-green-500"
                    placeholder="Questions or Comments"
                    maxlength="100"
                    name="comments"
                    v-model="comments"
                ></textarea>

                <input type="submit" value="Create" class="bg-green-500 mt-10 w-5/12 px-7 py-2 text-white cursor-pointer">
                <br>
                <div class="bg-red-100 text-red-500 mt-4 py-2 px-5 text-center" v-if="serverError">{{ serverError }}</div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                name: "",
                email: "",
                phone: "",
                comments: "",
                serverError: ""
            }
        },
        methods: {
            createClientHandler() {
                this.serverError = "";
                if(this.valideForm()) {
                    // TODO: Put API urls as a constant
                    axios.post('/api/client/create', {
                        name: this.name,
                        email: this.email,
                        phone: this.phone,
                        comments: this.comments
                    })
                    .then(response => {
                        if(response?.data?.message === 'Client saved succesfully') {
                            this.name = "";
                            this.email = "";
                            this.phone = "";
                            this.comments = "";
                            // Redirect to home
                            this.$router.push('/');
                        }
                    })
                    .catch(error => {
                        const serverMessage = error?.response?.data?.message;
                        switch (serverMessage) {
                            case "Error validating the input data":
                                this.serverError = error?.response?.data?.error;
                                break;
                            case "Error saving in database":
                                this.serverError = serverMessage;
                                if (error?.response?.data?.error.includes("UNIQUE constraint failed: clients.email")) {
                                    this.serverError = "The email is already registered";
                                }
                                break;
                            case "Error sending the email":
                                this.serverError = "The client was created. But there was an issue trying to notify to the support email";
                                break;
                            default:
                                this.serverError = serverMessage;
                                console.log(error);
                                break;
                        }
                    });
                }
            },
            valideForm() {
                // name, email, phone required
                if (!this.name || !this.email || !this.phone) {
                    return true;
                }
                // well formed email
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!regex.test(this.email)) {
                    return false;
                }
                return true;
            }
        }
    }
</script>
