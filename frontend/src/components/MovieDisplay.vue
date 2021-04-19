<template>
  <div class="movie m-5 sm:m-10">
    <div class="text-left rounded" :key="movie.id" v-for="movie in movies">
      <div class="mb-10">
        <!-- MOVIE POSTER -->
        <img
          class="my-10 sm:my-0 sm:align-top sm:inline-block object-scale-down mx-auto w-40 sm:w-2/5 lg:w-1/5 sm:align-top max-h-96"
          :src="movie.Poster"
        />
        <div class="inline-block align-top sm:w-3/5 lg:w-4/5 mb-10">
          <!-- MOVIE TITLE -->
          <span
            class="block text-center sm:text-left font-bold text-3xl pt-5 sm:pt-0 sm:pl-4 mb-5 sm:mb-0"
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
      <div class="mx-4">
        <span class="font-semibold">Currently on these lists:</span>
        <div class="flex justify-between">
          <ul class="">
            <li>List one</li>
            <li>List two</li>
          </ul>

          <div class="">
            <span>Add to: </span>
            <select class="p-1">
              <option>One</option>
              <option>two</option>
              <option>three</option>
            </select>
            <button class="p-1 px-2 ml-1 bg-green-200 rounded">+</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "MovieDisplay",
  props: {
    movies: {
      type: Object,
      required: true,
    },
  },
});
</script>
