export interface List {
  id: number;
  isPublic: boolean;
  movieCount: number;
  movies: Movie[];
  name: string;
  schedule_id: number;
}

export interface Movie {
  actors: string;
  director: string;
  genre: string;
  id: number;
  plot: string;
  poster: string;
  rating: string;
  reviews: Review[];
  showings: Showing[];
  title: string;
  year: number;
}

export interface Review {
  source: string;
  value: string;
}

export interface Schedule {
  id: number;
  isPublic: boolean;
  name: string;
  owner: number;
  showings: Showing[];
  slug: string;
  uuid: string;
}

export interface Showing {
  id: number;
  movie_id: number;
  owner: number;
  show_time: string;
}

export interface User {
  id: number;
  email: string;
  name: string;
  uuid: string;
  username: string;
}
