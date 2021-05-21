<template>
  <div class="sm:p-4 bg-gray-50">
    <h1 class="m-10 text-3xl font-bold text-center">Login</h1>
    <p v-if="errorText" class="inline-block sm:p-3 text-gray-600 bg-red-100">
      {{ errorText }}
    </p>
    <form class="flex flex-col items-center">
      <div class="m-5">
        <label class="block pb-2 text-left" for="email">Email</label>
        <input
          v-model="formData.email"
          class="block p-2 bg-gray-200 rounded w-72 md:w-96"
          id="email"
          type="email"
        />
      </div>
      <div class="m-5">
        <label class="block pb-2 text-left" for="password">Password</label>
        <input
          v-model="formData.password"
          class="block p-2 bg-gray-200 rounded w-72 md:w-96"
          id="password"
          type="password"
        />
      </div>

      <button
        @click.prevent="authenticate"
        class="block p-5 mx-auto mt-10 text-white rounded bg-button"
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
import { AxiosError } from "axios";
import { defineComponent } from "vue";
import store from "@/store/index";

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
      this.$http.get("/sanctum/csrf-cookie").then(() => {
        this.$http
          .post(`/login`, this.formData, {
            headers: {
              Accept: "application/json",
            },
          })
          .then(() => {
            store.commit("updateLogin", true);
            this.$router.push("/");
          })
          .catch((error: AxiosError) => {
            this.errorText = error.response?.data.message;
          });
      });
    },
  },
});
</script>
