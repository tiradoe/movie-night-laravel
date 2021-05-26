import { createStore } from "vuex";
import { List, Movie } from "@/types/index";

const movieList: List = {
  id: 0,
  name: "",
  movieCount: 0,
  movies: [],
};

export default createStore({
  state: {
    currentList: movieList,
    loggedIn: localStorage.getItem("loggedIn") == "true",
  },
  mutations: {
    updateList(state, newList: List): void {
      state.currentList = newList;
    },
    updateMovies(state, newMovies: Movie[]): void {
      state.currentList.movies = newMovies;
    },
    updateLogin(state, loggedIn: boolean): void {
      localStorage.setItem("loggedIn", loggedIn.toString());
      state.loggedIn = loggedIn;
    },
  },
  actions: {},
  modules: {},
});
