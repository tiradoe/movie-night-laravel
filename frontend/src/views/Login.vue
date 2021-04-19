<template>
  <div class="p-4 bg-gray-50">
    <h1 class="m-10 text-3xl font-bold text-center">Login</h1>
    <p v-if="errorText" class="bg-red-100 inline-block p-3 text-gray-600">
      {{ errorText }}
    </p>
    <form class="flex flex-col items-center">
      <div class="m-5">
        <label class="block text-left pb-2" for="email">Email</label>
        <input
          v-model="formData.email"
          class="block p-2 bg-gray-200 rounded md:w-96"
          id="email"
          type="email"
        />
      </div>
      <div class="m-5">
        <label class="block text-left pb-2" for="password">Password</label>
        <input
          v-model="formData.password"
          class="block p-2 bg-gray-200 rounded md:w-96"
          id="password"
          type="password"
        />
      </div>

      <button
        @click.prevent="authenticate"
        class="block p-2 mt-10 mx-auto bg-green-200 rounded"
        type="submit"
        value="submit"
      >
        Submit
      </button>
    </form>

    <div class="m-10">
      Don't have an account?
      <router-link class="text-blue-400" to="register"
        >Sign up here!</router-link
      >
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import axios from "axios";

export const authClient = axios.create({
  baseURL: process.env.VUE_APP_ROOT_API,
  withCredentials: true,
});

export default defineComponent({
  name: "Login",
  components: {},
  data: function () {
    return {
      errorText: "",
      formData: {
        email: "",
        password: "",
      },
    };
  },
  methods: {
    authenticate() {
      authClient.get("/sanctum/csrf-cookie").then(() => {
        authClient
          .post(`/login`, this.formData, {
            headers: {
              Accept: "application/json",
            },
          })
          .then(() => {
            localStorage.loggedIn = true;
            this.$router.push("/");
          })
          .catch((error) => {
            this.errorText = error.response.data.message;
          });
      });
    },
  },
});
</script>
