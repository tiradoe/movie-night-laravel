<template>
  <div class="p-5 sm:p-10">
    <h1 class="text-left text-3xl text-gray-700 sm:text-5xl font-bold">
      {{ currentList.name }}
    </h1>
  </div>
  <hr class="shadow" />

  <div class="text-center mx-5 sm:mx-10 xl:mx-auto">
    <search-form v-if="currentList.id" :listId="currentList.id" />
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
