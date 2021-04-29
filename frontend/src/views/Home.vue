<template>
  <h1 class="text-left text-3xl sm:text-5xl p-5 sm:p-10 font-bold">TONIGHT</h1>
  <hr class="shadow mb-10" />
  <MovieDisplay
    v-if="movie"
    :movie="movie"
    :mainDisplay="true"
    :listId="listId"
  />
  <p v-else>No movie scheduled.</p>
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
      listId: 0,
    };
  },
  mounted() {
    this.$http.get("/movies/1").then((response: any) => {
      this.movie = response.data;
    });
  },
});
</script>
