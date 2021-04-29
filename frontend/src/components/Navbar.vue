<template>
  <div class="flex p-5 sm:p-10 justify-between shadow-lg text-white bg-header">
    <div id="logo" class="inline-block">
      <span class="text-xl font-bold">Movie Night!</span>
    </div>
    <div id="links" class="inline-block">
      <router-link class="font-bold" to="/">Home</router-link> |
      <router-link class="font-bold" to="/lists">Lists</router-link> |
      <router-link class="font-bold" to="/settings">
        <font-awesome-icon icon="cogs" />
      </router-link>
      |
      <span @click="logout" class="font-bold cursor-pointer">
        <font-awesome-icon icon="sign-out-alt" />
      </span>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "Navbar",
  data: function () {
    return {
      loggedIn: false,
    };
  },
  methods: {
    logout() {
      this.$http.post("/logout").then(() => {
        localStorage.removeItem("loggedIn");
        this.loggedIn = false;
        this.$router.push("/login");
      });
    },
  },
});
</script>

<style scoped>
#nav a.router-link-exact-active {
  color: #f2da15;
}
</style>
