<template>
  <ul v-show="movies.length">
    <li :key="movie.id" v-for="movie in movies">{{ movie.title }}</li>
  </ul>
  <p v-show="!movies.length">No movies in list</p>
</template>

<script lang="ts">
import { defineComponent, PropType } from "@vue/runtime-core";
import { List, Movie } from "@/types/index";

let movies: Movie[] = [];
let list: List | null = null;

export default defineComponent({
  name: "MovieList",
  components: {},
  data: function () {
    return {
      list: list,
      movies: movies,
    };
  },
  methods: {
    addToList(): void {
      console.log("adding");
    },
  },
  mounted() {
    this.$http.get(`/lists/${this.$route.params.id}`).then((response: any) => {
      if (response.data.status === 404) {
        console.log("No Movies Found");
      } else {
        this.list = response.data.list;
        this.movies = response.data.movies;
      }
    });
  },
  props: {
    currentList: {
      type: Object as PropType<List>,
      required: true,
    },
  },
});
</script>
