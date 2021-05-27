<template>
  <div class="flex my-10 max-h-10">
    <!-- TEXT FIELD -->
    <label class="font-semibold text-left py-2 sm:p-2 w-1/3" for="search"
      >Add Movie</label
    >
    <input
      id="search"
      class="p-2 w-2/3 rounded rounded-r-none shadow border-r-none focus:shadow-outline"
      @keyup.enter="findMovie()"
      type="text"
      v-model="query"
      aria-label="Enter Movie Title"
      placeholder="Enter Movie Title or IMDB ID"
      aria-placeholder="Enter Movie Title or IMDB ID"
    />

    <!-- SEARCH BUTTON -->
    <button
      class="p-2 text-white rounded rounded-l-none shadow cursor-pointer bg-button border-l-none"
      @click="findMovie()"
      aria-label="Search"
    >
      Search
    </button>
  </div>

  <!-- RESULTS -->
  <MovieDisplay
    v-if="movie"
    class="mb-5"
    :movie="movie"
    :listId="listId"
    v-on:resetMovie="resetMovie"
  />
</template>

<script lang="ts">
import { defineComponent } from "vue";
import MovieDisplay from "@/components/MovieDisplay.vue";
import { Movie } from "@/types/index";
import { AxiosError, AxiosResponse } from "axios";
import store from "@/store/index";

let movie: Movie | null = null;

export default defineComponent({
  name: "SearchForm",
  components: {
    MovieDisplay,
  },
  computed: {
    showNotFound(): boolean {
      let hideMessage;

      if (this.searchStatus === "Found" || this.searchStatus === "waiting") {
        hideMessage = true;
      } else {
        return false;
      }

      return hideMessage;
    },
  },
  data: function () {
    return {
      query: "",
      searchStatus: "waiting",
      movie: movie,
    };
  },
  methods: {
    findMovie(): void {
      this.$http
        .get(`/api/movies/search?query=${this.query}`)
        .then((response: AxiosResponse) => {
          this.searchStatus = "Found";
          this.movie = response.data;
        })
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            store.commit("updateLogin", false);
            this.$router.push("/login");
          }
          this.searchStatus = "Not Found";
        });
    },
    resetMovie(): void {
      this.movie = null;
      this.query = "";
    },
  },
  props: {
    listId: {
      type: Number,
      required: true,
    },
  },
});
</script>
