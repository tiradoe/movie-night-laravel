<template>
  <h1 class="text-left text-3xl sm:text-5xl p-5 sm:p-10 font-bold">Settings</h1>
  <hr class="shadow mb-10" />
  <div
    class="flex flex-row mx-10 items-center bg-gray-300 rounded shadow-lg p-5"
  >
    <img
      class="rounded-full h-24 w-24 sm:h-48 sm:w-48"
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
      .then((response: any) => {
        let userData: User = {
          id: response.data.id,
          name: response.data.name,
          email: response.data.email,
        };

        this.user = userData;
      })
      .catch((error: any) => {
        if (error.response && error.response.status === 401) {
          this.$router.push("/login");
        } else {
          console.error(error);
        }
      });
  },
});
</script>
