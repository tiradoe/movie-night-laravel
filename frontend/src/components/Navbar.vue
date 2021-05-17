<template>
  <div class="flex justify-between p-5 text-white shadow-lg sm:p-10 bg-header">
    <div id="logo" class="inline-block">
      <span class="text-xl font-bold">Movie Night!</span>
    </div>
    <div v-if="loggedIn" id="links" class="inline-block">
      <router-link class="font-bold" to="/">Home</router-link> |
      <router-link class="font-bold" to="/lists">Lists</router-link> |
      <router-link class="font-bold" to="/schedule">Schedule</router-link> |
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
import store from "@/store/index";

export default defineComponent({
  name: "Navbar",
  computed: {
    loggedIn(): boolean {
      return store.state.loggedIn;
    },
  },
  methods: {
    logout() {
      this.$http.post("/logout").then(() => {
        store.commit("updateLogin", false);
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
