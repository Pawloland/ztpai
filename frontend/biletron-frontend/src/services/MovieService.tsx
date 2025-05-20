import {Movie, MoviesResponse} from "../types/Movie.ts";

export const fetchMovies = async (): Promise<Movie[]> => {
    try {
        const response = await fetch('/api/movies?order[title]=asc')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        let data: MoviesResponse = await response.json()
        return data.member
    } catch (err) {
        //console.error('Error fetching movies:', err)
        return []
    }
};

export const fetchMoviesWithScreeningsInFuture = async (): Promise<Movie[]> => {
    try {
        const response = await fetch(`/api/movies?order[title]=asc&screenings.exists=true&screenings.start_time[strictly_after]=${new Date().toISOString()}`)
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        let data: MoviesResponse = await response.json()
        return data.member
    } catch (err) {
        //console.error('Error fetching movies:', err)
        return []
    }
}

export const deleteMovieById = async (id: number): Promise<boolean> => {
    try {
        const response = await fetch(`/api/movies/${id}`, {
            method: 'DELETE'
        })
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        return true
    } catch (err) {
        //console.error('Error deleting movie:', err)
        return false
    }
}
