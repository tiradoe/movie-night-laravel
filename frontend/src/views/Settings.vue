<template>
  <h1 class="p-5 text-3xl font-bold text-left sm:text-5xl sm:p-10">Settings</h1>
  <hr class="mb-10 shadow" />

  <loader v-if="loading" item="account" />
  <div v-else>
    <div
      class="flex flex-row items-center p-5 mx-10 bg-gray-300 rounded shadow-lg"
    >
      <img
        class="w-24 h-24 rounded-full sm:h-48 sm:w-48"
        src="https://www.placecage.com/400/400"
      />
      <div class="p-5 text-left align-middle">
        <span class="block">{{ user.name }}</span>
        <span>{{ user.email }}</span>
      </div>
    </div>

    <div
      class="flex flex-col p-5 m-5 mx-10 text-left bg-gray-300 rounded shadow-lg"
    >
      <span class="text-xl font-extrabold">Reset Password </span>

      <form @submit.prevent="resetPassword">
        <div class="flex flex-col py-5">
          <span class="font-semibold">Current Password</span>
          <input
            class="p-2 sm:w-96"
            type="password"
            v-model="current_password"
          />
          <span v-show="current_password_error" class="mt-1 text-red-700">
            {{ this.current_password_error }}</span
          >
        </div>

        <div class="flex flex-col py-5">
          <span class="font-semibold">New Password</span>
          <input class="p-2 sm:w-96" type="password" v-model="password" />
          <span v-show="new_password_error" class="mt-1 text-red-700">
            {{ new_password_error }}</span
          >
        </div>

        <div class="flex flex-col py-5">
          <span class="font-semibold">Confirm New Password</span>
          <input
            class="p-2 sm:w-96"
            type="password"
            v-model="password_confirmation"
          />
        </div>
        <button class="p-2 text-white rounded bg-button">Submit</button>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { User } from "@/types/index";
import { AxiosResponse, AxiosError } from "axios";
import Loader from "@/components/Loader.vue";
import store from "@/store/index";

export default defineComponent({
  name: "Settings",
  components: { Loader },
  data: function () {
    return {
      user: { id: 0, name: "", email: "" },
      current_password: "",
      loading: true,
      password: "",
      password_confirmation: "",
      current_password_error: "",
      new_password_error: "",
    };
  },
  methods: {
    getUser(): void {
      this.$http
        .get("/api/user")
        .then((response: AxiosResponse) => {
          let userData: User = {
            id: response.data.id,
            name: response.data.name,
            email: response.data.email,
            uuid: response.data.uuid,
            username: response.data.username,
          };

          this.user = userData;
          this.loading = false;
        })
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            store.commit("updateLogin", false);
            this.$router.push("/login");
          }
        });
    },
    resetPassword(): void {
      this.$http
        .put("/user/password", {
          current_password: this.current_password,
          password: this.password,
          password_confirmation: this.password_confirmation,
        })
        .catch((error: AxiosError) => {
          this.resetErrors();
          if (error.response?.status === 422) {
            const password_errors = error.response.data.errors;

            if (password_errors.current_password) {
              this.current_password_error = password_errors.current_password[0];
            }
            if (password_errors.password) {
              this.new_password_error = password_errors.password[0];
            }
          }
        });
    },
    resetErrors(): void {
      this.current_password_error = "";
      this.new_password_error = "";
    },
  },
  mounted() {
    this.getUser();
  },
});
</script>
