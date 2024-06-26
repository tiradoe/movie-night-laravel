<template>
  <div class="p-4 bg-gray-50">
    <h1 class="m-5 text-3xl font-bold text-center">Create Account</h1>

    <form class="flex flex-col items-center flex-grow w-full">
      <p v-if="errorText" class="inline-block p-3 text-gray-600 bg-red-100">
        {{ errorText }}
      </p>
      <div class="block m-5">
        <label class="block pb-2 text-left" for="name">Name</label>
        <input
          v-model="formData.name"
          class="block p-2 bg-gray-200 w-72 sm:w-96"
          id="name"
          type="text"
        />
      </div>

      <div class="block m-5">
        <label class="block pb-2 text-left" for="username">Username</label>
        <input
          v-model="formData.username"
          class="block p-2 bg-gray-200 w-72 sm:w-96"
          id="username"
          type="text"
        />
      </div>

      <div class="block m-5">
        <label class="block pb-2 text-left" for="email">Email</label>
        <input
          v-model="formData.email"
          class="block p-2 bg-gray-200 w-72 sm:w-96"
          id="email"
          type="email"
        />
      </div>

      <div class="block m-5">
        <label class="block pb-2 text-left" for="password">Password</label>
        <input
          v-model="formData.password"
          class="block p-2 bg-gray-200 w-72 sm:w-96"
          id="password"
          type="password"
        />
      </div>

      <div class="block m-5">
        <label class="block pb-2 text-left" for="confirm-password"
          >Confirm Password</label
        >
        <input
          v-model="formData.password_confirmation"
          class="block p-2 bg-gray-200 w-72 sm:w-96"
          id="confirm-password"
          type="password"
        />
      </div>

      <button
        @click.prevent="authenticate"
        class="block p-5 mx-auto text-white rounded bg-button"
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
import store from "@/store/index";
import axios, { AxiosError } from "axios";

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
        username: "",
      },
    };
  },
  methods: {
    authenticate() {
      this.$http.get("/sanctum/csrf-cookie").then(() => {
        this.$http
          .post(`/register`, this.formData, {
            headers: {
              Accept: "application/json",
            },
          })
          .then(() => {
            store.commit("updateLogin", true);
            this.$router.push("/");
          })
          .catch((error: AxiosError<any,any>) => {
            if (error.response?.status === 422) {
              if ("name" in error.response?.data.errors) {
                this.errorText = error.response?.data.errors.name[0];
              } else if ("email" in error.response?.data.errors)
                this.errorText = error.response?.data.errors.email[0];
            } else if ("password" in error.response?.data.errors) {
              this.errorText = error.response?.data.errors.password[0];
            } else if ("username" in error.response?.data.errors) {
              this.errorText = error.response?.data.errors.username[0];
            } else {
              this.errorText = error.response?.data.message;
            }
          });
      });
    },
  },
});
</script>
