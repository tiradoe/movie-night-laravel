<template>
  <div class="mt-10">
    <h1 class="text-3xl sm:text-5xl mb-5 px-10 font-bold">
      {{ currentList.name }}
    </h1>
    <div id="movie-search" class="flex sm:m-10">
      <search-form
        v-if="currentList.id"
        class="mx-auto"
        :listId="currentList.id"
      />
    </div>
    <movie-list />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import SearchForm from "@/components/SearchForm.vue";
import MovieList from "@/components/MovieList.vue";
import store from "@/store/index";

export default defineComponent({
  name: "EditList",
  components: {
    SearchForm,
    MovieList,
  },
  computed: {
    currentList() {
      return store.state.currentList;
    },
  },
});
</script>
