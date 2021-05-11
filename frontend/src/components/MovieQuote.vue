<template>
  <div id="movie-quote" class="text-">
    <span id="quote" class="block mb-5 italic" v-html="quote" />
    <span id="quote-actor" class="inline-block font-semibold">
      {{ actor }}
    </span>
    -
    <span id="quote-movie-title" class="inline-block font-semibold"
      >&nbsp;{{ movie }}</span
    >
  </div>
</template>


<script lang="ts">
import { defineComponent } from "@vue/runtime-core";

export default defineComponent({
  name: "MovieQuote",
  props: {
    quote: {
      type: String,
      required: true,
    },
    actor: {
      type: String,
      required: true,
    },
    movie: {
      type: String,
      required: true,
    },
  },
});
</script>
