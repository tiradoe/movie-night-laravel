<template>
  <div class="p-4">
    <h1 class="m-5 md:m-10 text-3xl font-bold sm:text-left">TONIGHT</h1>
    <MovieDisplay :movies="movies" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import MovieDisplay from "@/components/MovieDisplay.vue"; // @ is an alias to /src
import { Movie } from "@/types/index";

const movies: Movie[] = [];

export default defineComponent({
  name: "Home",
  components: {
    MovieDisplay,
  },
  data: function () {
    return {
      movies: movies,
    };
  },
  mounted() {
    this.movies = [];
    this.$http.get("/movies").then((response: any) => {
      this.movies.push(response.data);
    });
  },
});
</script>
