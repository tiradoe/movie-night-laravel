<template>
  <h1 class="font-bold text-5xl m-5">{{ currentList }}</h1>
  <movie-list class="h-full" />
</template>
<script lang="ts">
import { defineComponent } from "vue";
import MovieList from "@/components/MovieList.vue";
import store from "@/store/index";

export default defineComponent({
  name: "ListView",
  components: {
    MovieList,
  },
  computed: {
    currentList(): string {
      return store.state.currentList.name;
    },
  },
});
</script>
