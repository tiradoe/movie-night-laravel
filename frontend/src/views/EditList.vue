<template>
  <div class="sm:p-4 mt-10">
    <h1 class="font-bold">{{ currentList.name }}</h1>
    <div id="movie-search" class="flex sm:m-5">
      <search-form
        v-if="currentList.id"
        class="mx-auto"
        :listId="currentList.id"
      />
    </div>
    <movie-list v-if="currentList" :currentList="currentList" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import SearchForm from "@/components/SearchForm.vue";
import MovieList from "@/components/MovieList.vue";

export default defineComponent({
  name: "AddMovie",
  components: {
    SearchForm,
    MovieList,
  },
  data: function () {
    return {
      currentList: {},
    };
  },
  mounted() {
    this.currentList = this.$http
      .get(`/lists/${this.$route.params.id}`)
      .then((response: any) => {
        this.currentList = response.data.list;
      });
  },
});
</script>
