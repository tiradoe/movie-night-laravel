<template>
  <div class="flex flex-col">
    <datepicker
      id="datepicker"
      class="w-full mx-auto text-center border border-gray-400"
      placeholder="Choose Showing"
      aria-placeholder="Choose showing date"
      v-model="picked"
      v-on:update:modelValue="addShowing"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import Datepicker from "vue3-datepicker";
import { AxiosError } from "axios";
import store from "@/store";

export default defineComponent({
  name: "AddShowing",
  components: {
    Datepicker,
  },
  data: function () {
    return {
      picked: null,
    };
  },
  methods: {
    addShowing(event: Date): void {
      const confirmAdd = confirm(
        `Add Showing for ${
          event.getMonth() + 1
        }/${event.getDate()}/${event.getFullYear()}?`
      );

      if (confirmAdd === true) {
        this.$http
          .post("/api/showings", {
            movie_id: this.movieId,
            show_time: event.toUTCString(),
          })
          .then(() => this.$emit("updated-list"))
          .catch((error: AxiosError) => {
            if (error.response?.status === 401) {
              store.commit("updateLogin", false);
              this.$router.push("/login");
            }
          });
      }
    },
  },
  emits: ["updated-list"],
  props: {
    movieId: {
      type: Number,
      required: true,
    },
  },
});
</script>
