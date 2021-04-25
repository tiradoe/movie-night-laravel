<template>
  <div class="p-4">
    <h1 class="m-5 md:m-10 text-3xl font-bold sm:text-left">TONIGHT</h1>
    <MovieDisplay v-if="movie" :movie="movie" :mainDisplay="true" />
    <p v-else>No movie scheduled.</p>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import MovieDisplay from "@/components/MovieDisplay.vue"; // @ is an alias to /src
import { Movie } from "@/types/index";

const movie: Movie | null = null;

export default defineComponent({
  name: "Home",
  components: {
    MovieDisplay,
  },
  data: function () {
    return {
      movie: movie,
    };
  },
  mounted() {
    this.$http.get("/movies/1").then((response: any) => {
      this.movie = response.data;
    });
  },
});
</script>
