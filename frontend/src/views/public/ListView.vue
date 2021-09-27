<template>
  <movie-list class="h-full" />
</template>
<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import MovieList from "@/components/MovieList.vue";
export default defineComponent({
  name: "ListView",
  components: {
    MovieList,
  },
});
</script>
