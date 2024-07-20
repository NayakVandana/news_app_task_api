<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsService;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            // Validation
            $validator = Validator::make($request->all(), [
                'per_page' => ['nullable', 'integer'],
                'current_page' => ['nullable', 'integer'],
                'search' => ['nullable', 'string'],               
            ]);

            if ($validator->fails()) {
                return $this->sendJsonResponse(false, $validator->errors()->first(), $validator->errors()->getMessages(), 200);
            }

           
            $perPage = $request->input('per_page', 10);
            $currentPage = $request->input('current_page', 1);
            $searchKeyword = $request->input('search');
            


            //api news call
            $newsService = app(NewsService::class);
            $data =  $newsService->getArticles();

            // Convert object to an array
            $dataArray = json_decode(json_encode($data), true);

           
            $articles = $dataArray['articles'] ?? [];

          
            $articlesCollection = collect($articles);

            // search keyword
            if ($searchKeyword) {
                $articlesCollection = $articlesCollection->filter(function ($article) use ($searchKeyword) {
                    
                    $title = is_array($article['title']) ? implode(' ', $article['title']) : $article['title'];
                    $source = is_array($article['source']) ? implode(' ', $article['source']) : $article['source'];
                    $publishedAt = is_array($article['publishedAt']) ? implode(' ', $article['publishedAt']) : $article['publishedAt'];
                    $author = is_array($article['author']) ? implode(' ', $article['author']) : $article['author'];

                   
                    $titleMatches = stripos($title, $searchKeyword) !== false;
                    $sourceMatches = stripos($source, $searchKeyword) !== false;
                    $publishedAtMatches = stripos($publishedAt, $searchKeyword) !== false;
                    $authorMatches = stripos($author, $searchKeyword) !== false;

                    
                    return $titleMatches || $sourceMatches || $publishedAtMatches || $authorMatches;
                });
            }

            // Pagination
            $paginatedArticles = new LengthAwarePaginator(
                $articlesCollection->forPage($currentPage, $perPage)->values(),
                $articlesCollection->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );
          
            return $this->sendJsonResponse(true, 'Articles fetched successfully', $paginatedArticles, 200);
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
