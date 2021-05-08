<template>
  <h1 class="p-5 text-3xl font-bold text-left sm:text-5xl sm:p-10">TONIGHT</h1>
  <hr class="mb-10 shadow" />
  <MovieDisplay
    class="sm:mx-10"
    v-if="movie"
    :movie="movie"
    :mainDisplay="true"
    :listId="listId"
  />
  <p v-else class="p-10 bg-white rounded shadow sm:m-10">No movie scheduled.</p>
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
    this.$http
      .get("/api/showings?next=1")
      .then((response: any) => {
        this.movie = response.data.movie;
      })
      .catch((error: Error) => {
        console.error(error.message);
      });
  },
});
</script>
