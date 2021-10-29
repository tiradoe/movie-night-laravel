<template>
  <div class="flex text-lg">
    <div class="w-1/3">
      <img class="w-16 my-auto" :src="showing.movie.poster" />
    </div>
    <div class="flex flex-col w-1/3 my-auto font-semibold">
      <span class="">{{ showing.movie.title }}</span>
      <span class="">{{ prettyDate(showing.show_time) }}</span>
    </div>
    <div v-if="loggedIn" class="flex flex-col w-1/3 pr-10 my-auto text-right">
      <div @click="deleteShowing(showing.id)" class="pb-5">
        <font-awesome-icon
          class="cursor-pointer hover:text-red-700"
          icon="trash-alt"
        />
      </div>
      <span>
        <label class="pr-2" for="public">Public?</label>
        <input @change="updateShowing" type="checkbox" v-model="isPublic" />
      </span>
    </div>
  </div>
</template>

<script lang="ts">
import { Showing } from "@/types";
import { defineComponent, PropType } from "vue";
import store from "@/store/index";

export default defineComponent({
  name: "Showing",
  computed: {
    loggedIn(): boolean {
      return store.state.loggedIn;
    },
  },
  data: function () {
    return {
      isPublic: this.showing.isPublic,
    };
  },
  emits: ["update-schedule"],
  methods: {
    prettyDate(showTime: Date): string {
      const formatted = new Date(showTime).toLocaleString("en-US", {
        month: "2-digit",
        day: "2-digit",
        year: "2-digit",
      });

      return formatted;
    },
    deleteShowing(showingId: number): void {
      if (confirm("Delete showing?") === true) {
        this.$http
          .delete(`/api/showings/${showingId}`)
          .then(this.$emit("update-schedule"));
      }
    },
    updateShowing(): void {
      this.$http
        .put(`/api/showings/${this.showing.id}`, { isPublic: this.isPublic })
        .then(this.$emit("update-schedule"));
    },
  },
  props: {
    showing: {
      type: Object as PropType<Showing>,
      required: true,
    },
  },
});
</script>
