<template>
  <div class="p-5 mx-auto">
    <h1 class="mb-10 font-bold text-5xl">Settings</h1>
    <div class="flex flex-row mx-10 items-center">
      <img
        class="rounded-full h-48 w-48"
        src="https://www.placecage.com/400/400"
      />
      <div class="px-10 text-left align-middle">
        <span class="block">{{ user.name }}</span>
        <span>{{ user.email }}</span>
      </div>
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
      .get("/user")
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
