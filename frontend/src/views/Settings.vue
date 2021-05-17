<template>
  <h1 class="p-5 text-3xl font-bold text-left sm:text-5xl sm:p-10">Settings</h1>
  <hr class="mb-10 shadow" />
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
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { User } from "@/types/index";
import { AxiosResponse, AxiosError } from "axios";
import store from "@/store/index";

export default defineComponent({
  name: "Settings",
  components: {},
  data: function () {
    return {
      user: { id: 0, name: "", email: "" },
    };
  },
  mounted() {
    this.$http
      .get("/api/user")
      .then((response: AxiosResponse) => {
        let userData: User = {
          id: response.data.id,
          name: response.data.name,
          email: response.data.email,
        };

        this.user = userData;
      })
      .catch((error: AxiosError) => {
        if (error.response?.status === 401) {
          store.commit("updateLogin", false);
          this.$router.push("/login");
        } else {
          console.error(error);
        }
      });
  },
});
</script>
