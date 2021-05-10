<template>
  <div class="p-4 bg-gray-50">
    <h1 class="m-5 font-bold text-center text-3xl">Create Account</h1>

    <form class="flex flex-col items-center flex-grow w-full">
      <p v-if="errorText" class="bg-red-100 inline-block p-3 text-gray-600">
        {{ errorText }}
      </p>
      <div class="block m-5">
        <label class="block text-left pb-2" for="name">Name</label>
        <input
          v-model="formData.name"
          class="block bg-gray-200 p-2 md:w-96"
          id="name"
          type="text"
        />
      </div>

      <div class="block m-5">
        <label class="block text-left pb-2" for="email">Email</label>
        <input
          v-model="formData.email"
          class="block bg-gray-200 p-2 md:w-96"
          id="email"
          type="email"
        />
      </div>

      <div class="block m-5">
        <label class="block text-left pb-2" for="password">Password</label>
        <input
          v-model="formData.password"
          class="block bg-gray-200 p-2 md:w-96"
          id="password"
          type="password"
        />
      </div>

      <div class="block m-5">
        <label class="block text-left pb-2" for="confirm-password"
          >Confirm Password</label
        >
        <input
          v-model="formData.password_confirmation"
          class="block bg-gray-200 p-2 md:w-96"
          id="confirm-password"
          type="password"
        />
      </div>

      <button
        @click.prevent="authenticate"
        class="block p-5 mx-auto bg-button rounded text-white"
        type="submit"
        value="submit"
      >
        Submit
      </button>
    </form>
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
  name: "Registration",
  components: {},
  data: function () {
    return {
      errorText: "",
      formData: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
    };
  },
  methods: {
    authenticate() {
      authClient.get("/sanctum/csrf-cookie").then(() => {
        authClient
          .post(`/register`, this.formData, {
            headers: {
              Accept: "application/json",
            },
          })
          .then(() => {
            localStorage.loggedIn = true;
            this.$router.push("/");
          })
          .catch((error) => {
            console.error(error.response.data.message);
          });
      });
    },
  },
});
</script>
