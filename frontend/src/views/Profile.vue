<template>
  <div class="p-5">
    <h1>Profile</h1>
    <span class="block">{{ user.name }}</span>
    <span>{{ user.email }}</span>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { User } from "@/types/index";

export default defineComponent({
  name: "Profile",
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
          console.log(error);
        }
      });
  },
});
</script>
