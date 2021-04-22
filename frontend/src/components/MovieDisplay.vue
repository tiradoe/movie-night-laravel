<template>
  <div class="movie sm:m-10">
    <div class="text-left rounded sm:p-2 shadow bg-white">
      <div class="mb-10">
        <!-- MOVIE POSTER -->
        <img
          class="object-scale-down w-40 mx-auto my-10 sm:my-0 sm:align-top hidden sm:inline-block sm:w-2/5 lg:w-1/5 max-h-96"
          :src="movie.Poster"
        />
        <div class="inline-block mb-10 align-top sm:w-3/5 lg:w-4/5">
          <!-- MOVIE TITLE -->
          <span
            class="block pt-5 mb-5 text-3xl font-bold text-center sm:text-left sm:pt-0 sm:pl-4 sm:mb-0"
          >
            {{ movie.Title }}
          </span>

          <!-- MOVIE INFO -->
          <div class="p-4">
            <span class="block"
              ><span class="font-semibold">Director:</span>
              {{ movie.Director }}</span
            >
            <span class="block">
              <span class="font-semibold">Genre:</span>
              {{ movie.Genre }}
            </span>
            <span class="block">
              <span class="font-semibold">Rating:</span>
              {{ movie.Rated }}
            </span>
            <span class="block">
              <span class="font-semibold">Year:</span>
              {{ movie.Year }}
            </span>
            <span class="block">
              <span class="font-semibold">Rotten Tomatoes Score:</span>
              {{ movie.Ratings[1].Value }}
            </span>
            <span class="block mt-5">{{ movie.Plot }}</span>
          </div>
        </div>
      </div>

      <!-- ADD TO LIST -->
      <div v-if="!mainDisplay" class="mx-4">
        <span class="font-semibold">Currently on these lists:</span>
        <div class="flex justify-between">
          <ul class="">
            <li>List one</li>
            <li>List two</li>
          </ul>

          <div class="">
            <button
              v-if="movie"
              @click="addToList(movie)"
              class="p-2 text-white bg-blue-600 rounded"
            >
              Add to List
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";
import { Movie } from "@/types/index";

export default defineComponent({
  name: "MovieDisplay",
  methods: {
    addToList(movie: Movie): void {
      //The movie API has capitalized keys and the DB doesn't like that.  I don't like it either.
      const updatedMovie = Object.fromEntries(
        Object.entries(movie).map(([key, value]) => [key.toLowerCase(), value])
      );
      this.$http.post(`/lists/1`, updatedMovie);
    },
  },
  props: {
    movie: {
      type: Object as PropType<Movie>,
      required: true,
    },
    mainDisplay: {
      type: Boolean,
      required: false,
    },
  },
});
</script>
