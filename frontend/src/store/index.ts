import { createStore } from "vuex";
import { List, Movie } from "@/types/index";

const movieList: List = {
  id: 0,
  isPublic: false,
  name: "",
  uuid: "",
  slug: "",
  movieCount: 0,
  movies: [],
  schedule_id: 0,
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
    updateListAccess(state, access): void {
      state.currentList.isPublic = access;
    },
  },
  actions: {},
  getters: {},
  modules: {},
});
