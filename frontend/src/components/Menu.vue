<template>
  <div
    v-if="isOpen"
    class="z-50 p-0 m-0 top-0 right-0 w-screen sm:w-1/2 lg:w-2/5 h-screen absolute bg-header border-l-4 border-yellow-600 shadow-2xl"
  >
    <font-awesome-icon
      @click="$emit('closeMenu')"
      class="text-3xl block ml-auto m-5 right cursor-pointer"
      icon="times-circle"
    />

    <div id="links" class="flex flex-col">
      <router-link class="font-bold text-3xl pt-5" to="/">Home</router-link>
      <router-link class="font-bold text-3xl pt-5" to="/lists"
        >Lists</router-link
      >
      <router-link class="font-bold text-3xl pt-5" to="/schedule"
        >Schedule</router-link
      >
      <router-link class="font-bold text-3xl pt-5" to="/settings">
        Settings
      </router-link>
      <span
        @click="$emit('logout')"
        class="font-bold cursor-pointer text-3xl pt-5"
      >
        Log Out
      </span>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "side-menu",
  props: {
    isOpen: {
      type: Boolean,
      required: true,
    },
  },
  emits: ["closeMenu", "logout"],
});
</script>
