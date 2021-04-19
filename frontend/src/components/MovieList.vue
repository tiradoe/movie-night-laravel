<template>
  <ul>
    <li :key="movie.id" v-for="movie in movieList">{{ movie.title }}</li>
  </ul>
</template>

<script lang="ts">
import { defineComponent, PropType } from "@vue/runtime-core";
import { Movie } from "@/types/index";

export default defineComponent({
  name: "MovieList",
  components: {},
  data: function () {
    return {
      currentList: {},
      movieList: [
        { id: 1, title: "Movie Title", year: "2000" },
        { id: 2, title: "Movie Title 2", year: "2001" },
      ],
    };
  },
  props: {
    movies: {
      type: Object as PropType<Movie>,
      required: true,
    },
  },
});
</script>
