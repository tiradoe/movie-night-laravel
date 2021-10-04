<template>
  <fieldset
    class="flex flex-col items-center p-5 mt-5 mb-5 text-left bg-gray-300 rounded shadow"
  >
    <h2 class="font-semibold underline cursor-pointer" @click="toggleAddMovie">
      Add Movie
    </h2>
    <div class="sm:w-1/2 grid grid-cols-2" v-show="addMovie">
      <div class="flex flex-col mb-5 col-span-2 sm:mx-2">
        <label class="font-semibold text-left" for="search"> Title </label>
        <!-- MOVIE TITLE / IMDB ID-->
        <input
          id="search"
          class="max-w-xl p-2 rounded shadow border-r-none focus:shadow-outline"
          @keyup.enter="findMovie()"
          type="text"
          v-model="query"
          aria-label="Enter Movie Title"
          placeholder="Enter Movie Title or IMDB ID"
          aria-placeholder="Enter Movie Title or IMDB ID"
        />
      </div>

      <!-- YEAR -->
      <div class="flex flex-col mb-5 col-span-2 sm:mx-2">
        <label class="font-semibold text-left" for="Year"> Year </label>
        <input
          class="p-2 rounded shadow sm:max-w-xl"
          id="year"
          maxlength="4"
          v-model="year"
        />
      </div>

      <!-- CONTENT TYPE -->
      <div class="flex flex-col sm:max-w-xl col-span-2 sm:mx-2">
        <label class="font-semibold text-left" for="content-type"> Type </label>
        <select
          class="p-2 mb-5 bg-white rounded shadow"
          id="content-type"
          v-model="contentType"
        >
          <option value="movie">Movie</option>
          <option value="series">Series</option>
          <option value="episode">Episode</option>
        </select>
      </div>
    </div>
    <!-- SEARCH BUTTON -->
    <button
      class="p-2 text-white rounded shadow cursor-pointer sm:max-w-xs sm:mx-2 col-span-3 sm:col-span-2 bg-button border-l-none"
      @click="findMovie()"
      aria-label="Search"
      v-show="addMovie"
    >
      Search
    </button>
  </fieldset>

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
      year: "",
      contentType: "movie",
      searchStatus: "waiting",
      movie: movie,
      addMovie: false,
    };
  },
  methods: {
    findMovie(): void {
      this.$http
        .get(
          `/api/movies/search?query=${this.query}&year=${this.year}&type=${this.contentType}`
        )
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
    toggleAddMovie(): void {
      this.addMovie = !this.addMovie;
    },
    resetMovie(): void {
      this.movie = null;
      this.query = "";
      this.year = "";
      this.contentType = "movie";
    },
  },
  props: {
    listId: {
      type: String,
      required: true,
    },
  },
});
</script>
