<?php

namespace App\Http\Resources\BookResources;
use App\Models\Volumn;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChaptersResource extends ResourceCollection
{
    /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    private $pagination;

    public function __construct($resource)
    {
        $this->pagination = [
            'total' => $resource->total(),
            'count' => $resource->count(),
            'per_page' => $resource->perPage(),
            'current_page' => $resource->currentPage(),
            'total_pages' => $resource->lastPage()
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }
    public function toArray($request)
    {
        $volumns = $this->collection->map(
            function ($chapter) {
                return $chapter->volumn;
            }
        );
        $included = $volumns->unique();
        return [
            'chapters' => ChapterInfoResource::collection($this->collection),
            'volumns' => $included->map( function ($include) {
                return new VolumnResource($include);
            }),
            'pagination' => $this->pagination,
        ];
    }
    public function with($request)
    {
        $volumns = $this->collection->flatMap(
            function ($chapter) {
                return $chapter->volumn;
            }
        );
        $included = $volumns->unique();
        return [
            'included' => 'data'
        ];
    }
}
