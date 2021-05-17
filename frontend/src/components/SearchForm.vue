<template>
  <div class="flex flex-col">
    <!-- SEARCH INPUT -->
    <div>
      <!-- TEXT FIELD -->
      <input
        id="search"
        class="p-2 rounded rounded-r-none shadow border-r-none focus:shadow-outline"
        @keyup.enter="findMovie()"
        type="text"
        v-model="query"
        aria-label="Enter Movie Title"
        placeholder="Enter Movie Title or IMDB ID"
        aria-placeholder="Enter Movie Title or IMDB ID"
      />

      <!-- SEARCH BUTTON -->
      <button
        class="mb-10 inline-block p-2 mt-5 text-white rounded rounded-l-none shadow cursor-pointer bg-button border-l-none"
        @click="findMovie()"
        aria-label="Search"
      >
        Search
      </button>
    </div>

    <!-- RESULTS -->
    <MovieDisplay
      v-if="movie"
      :movie="movie"
      :listId="listId"
      v-on:resetMovie="resetMovie"
    />
    <div v-show="showNotFound === false">No movie found.</div>

    <!-- ADD MOVIE -->
    <div v-if="searchStatus === 'Not Found'" id="movie-form" class="m-5">
      <h1 class="font-bold">Add Movie</h1>
      <div class="p-2">
        <!-- TITLE -->
        <label for="title" class="mr-2 font-semibold">Title</label>
        <input id="title" class="bg-gray-200" type="text" />
      </div>

      <!-- DIRECTOR -->
      <div class="p-2">
        <label for="title" class="mr-2 font-semibold">Director</label>
        <input id="title" class="bg-gray-200" type="text" />
      </div>

      <!-- RATING -->
      <div class="p-2">
        <label for="rating" class="mr-2 font-semibold">Rating</label>
        <select class="p-2" id="rating">
          <option>G</option>
          <option>PG</option>
          <option>PG-13</option>
          <option>R</option>
          <option>NC-17</option>
          <option>Not Rated</option>
        </select>
      </div>

      <!-- YEAR -->
      <div class="p-2">
        <label for="year" class="mr-2 font-semibold">Year</label>
        <input id="year" class="bg-gray-200" type="number" />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import MovieDisplay from "@/components/MovieDisplay.vue";
import { Movie } from "@/types/index";
import { AxiosResponse } from "axios";

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
        .catch(() => {
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
