<template>
  <h1 class="text-left text-3xl sm:text-5xl p-5 sm:p-10 font-bold">TONIGHT</h1>
  <hr class="shadow mb-10" />
  <MovieDisplay
    class="sm:mx-10"
    v-if="movie"
    :movie="movie"
    :mainDisplay="true"
    :listId="listId"
  />
  <p v-else class="bg-white rounded shadow p-10 sm:m-10">No movie scheduled.</p>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import MovieDisplay from "@/components/MovieDisplay.vue";
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
    this.$http.get("/movies/").then((response: any) => {
      this.movie = response.data.movies.pop();
    });
  },
});
</script>
