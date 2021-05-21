<template>
  <div class="flex justify-between p-5 text-white shadow-lg sm:p-10 bg-header">
    <div id="logo" class="inline-block">
      <span class="text-xl font-bold">Movie Night!</span>
    </div>
    <div v-show="loggedIn" id="links" class="hidden sm:inline-block">
      <router-link @click="closeMenu()" class="font-bold" to="/">
        Home
      </router-link>
      |
      <router-link @click="closeMenu" class="font-bold" to="/lists">
        Lists
      </router-link>
      |
      <router-link @click="closeMenu" class="font-bold" to="/schedule">
        Schedule
      </router-link>
      |
      <router-link @click="closeMenu" class="font-bold" to="/settings">
        <font-awesome-icon icon="cogs" />
      </router-link>
      |
      <span @click="logout" class="font-bold cursor-pointer">
        <font-awesome-icon icon="sign-out-alt" />
      </span>
    </div>

    <font-awesome-icon
      v-show="loggedIn"
      class="text-3xl inline-block sm:hidden cursor-pointer"
      icon="bars"
      @click="toggleMenu"
    />
    <side-menu
      class="inline-block"
      :isOpen="showMenu"
      v-on:closeMenu="toggleMenu"
      v-on:logout="logout"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import store from "@/store/index";
import SideMenu from "@/components/Menu.vue";

export default defineComponent({
  name: "Navbar",
  components: {
    SideMenu,
  },
  data: function () {
    return {
      showMenu: false,
    };
  },
  computed: {
    loggedIn(): boolean {
      return store.state.loggedIn;
    },
  },
  methods: {
    logout(): void {
      this.$http.post("/logout").then(() => {
        store.commit("updateLogin", false);
        this.showMenu = false;
        this.$router.push("/login");
      });
    },
    toggleMenu(): void {
      this.showMenu = !this.showMenu;
    },
    closeMenu(): void {
      this.showMenu = false;
    },
  },
});
</script>

<style scoped>
#nav a.router-link-exact-active {
  color: #f2da15;
}
</style>
