<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Http\Resources\Resource as MovieResource;
use App\Http\Requests\MovieRequest;
use App\Movies\MoviesRepository;

class MovieController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'search']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/movies",
     *     summary="Get movies",
     *     description="Returns list of movies",
     *     operationId="getMovies",
     *     tags={"Movie"},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Method Not Allowed",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            // returns all movies
            $movies = Movie::where('startYear', '2023')->paginate($request->per_page ?? 25);
            return MovieResource::collection($movies)->additional(['status' => true, 'message' => 'List Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/movies/{movieId}",
     *     summary="Find movie by ID",
     *     description="Returns a single movie",
     *     operationId="getMovieById",
     *     tags={"Movie"},
     *     @OA\Parameter(
     *         description="ID of movie to return",
     *         in="path",
     *         name="movieId",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Movie not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Method Not Allowed",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
     */
    public function show($id)
    {
        try {
            // show one movie
            $movie = Movie::where('tconst', $id)->with('alias')->first();
            if ($movie) {
                return (new MovieResource($movie))->additional(['status' => true, 'message' => 'Retrieved Successfully'])->response()->setStatusCode(200);
            } else {
                return response()->json(['status' => false, 'message' => 'resource could not be found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/search-movies",
     *     summary="Search for movies.",
     *     tags={"Movie"},
     *      description="Returns list of related movies",
     *     operationId="searchMovies",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Search query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Method Not Allowed",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
     */
    public function search(Request $request, MoviesRepository $movie)
    {
        try {
            // returns search results movies
            $movies = $request->has('q')
                ? $movie->search($request->q, $request->per_page ?? 25)
                : Movie::where('startYear', '2023')->paginate($request->per_page ?? 25);
            return MovieResource::collection($movies)->additional(['status' => true, 'message' => 'Search Completed'])->response()->setStatusCode(200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
