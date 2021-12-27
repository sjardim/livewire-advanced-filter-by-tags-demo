<?php

namespace App\Http\Livewire;

use App\Models\Tag;
use App\Models\Video;
use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use ProtoneMedia\LaravelCrossEloquentSearch\Search as CrossSearch;

class Search extends Component
{
    use WithPagination;

    protected $listeners = ['filterByTag' => 'filterByTag'];

    public $search;
    public array $filters = [];
    public int $perPage = 10;
    public string $sort = 'updated_at|desc';
    
    /*
     * Reset pagination when doing a search
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // $articles = Article::with('tags');
        // $videos = Video::with('tags');

        // $results = $articles->union($videos);

        //Get tag count without being affect by pagination        
        $tags = $this->getTags();        
        // $tags = [];

        $results = $this->applySearchFilter();

        // if($this->search) {
        //     $results = $this->applySearchFilter($results);
        //     $results->orderBy($this->sortByColumn(), $this->sortDirection());
        // } else {
        //     $results = $results->orderBy($this->sortByColumn(), $this->sortDirection())
        // ->paginate($this->perPage);
        // }


        // $this->applyTagFilter($results);

        

        return view('livewire.search')->with(compact('results', 'tags'));
    }

    
    public function filterByTag($tag)
    {
        if (in_array($tag, $this->filters)) {
            $ix = array_search($tag, $this->filters);

            unset($this->filters[$ix]);
        } else {
            $this->filters[] = $tag;

            //Reset pagination, otherwise filter won't work
            $this->resetPage();
        }
    }

    public function sortByColumn()
    {
        $sort = explode("|", $this->sort);
        return $sort[0] ?? null;
    }

    public function sortDirection()
    {
        $sort = explode("|", $this->sort);

        return $sort[1] ?? 'asc';
    }

    private function applySearchFilter()
    {
        
        if($this->search) {        
            return CrossSearch::new()
            ->add(Article::class, 'title', 'updated_at')
            ->orderBy($this->sortByColumn())
            ->add(Video::class, 'title', 'updated_at')
            ->orderBy($this->sortByColumn())
            ->includeModelType()
            ->beginWithWildcard()
            // ->orderByRelevance()
            ->orderByDesc()
            ->paginate($this->perPage)
            ->get($this->search);
        }
    
        return CrossSearch::new()
            ->add(Article::class, 'title', 'updated_at')
            ->add(Video::class, 'title', 'updated_at')
            ->orderByDesc()
            ->includeModelType()
            ->paginate($this->perPage)
            ->get();


        // return CrossSearch::new()
        //     ->add(Article::with('tags'), 'title', 'updated_at')
        //     ->add(Video::with('tags'), 'title', 'updated_at')
        //     ->beginWithWildcard()
        //     ->orderByDesc()
        //     ->paginate($this->perPage)
        //     ->get($this->search);

            
    }

    private function applyTagFilter($results)
    {
        if ($this->filters) {
            foreach ($this->filters as $filter) {
                $results->whereHas('tags', function ($query) use ($filter) {
                    $query->where('id', $filter);
                });
            }
        }

        return null;
    }

    private function getTags()
    {
        $tags = Tag::withCount(['articles', 'videos'])
            ->orderBy('articles_count', 'DESC')            
            ->orderBy('videos_count', 'DESC')
            ->take(15)
            ->get();

        // Show only tags that are used
        return $tags->filter( function ($tag) {
            return $tag->articles_count > 0;
        });
    }

}
