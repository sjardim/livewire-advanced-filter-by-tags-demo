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
        //Get tag count without being affect by pagination        
        // $tags = $this->getTags();        
        $tags = [];

        $results = $this->applySearchFilter();
        
        $results = $this->sortDirection($results);

        $results = $results                
                ->paginate($this->perPage)
                ->get();


        $this->applyTagFilter($results);
       

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
        
        return $sort[0];
    }

    public function sortDirection($results)
    {
        $sort = explode("|", $this->sort);

        if($sort[1] === 'desc') {
            return $results->orderByDesc();
        }

        return $results;

    }

    private function applySearchFilter()
    {
        
        if($this->search) {        
            return CrossSearch::new()
            ->add(Article::with('tags'), 'title')
            ->orderBy($this->sortByColumn())
            ->add(Video::with('tags'), 'title')
            ->orderBy($this->sortByColumn())
            ->includeModelType()
            ->beginWithWildcard()
            // ->orderByRelevance()
            ->orderByDesc()
            ->paginate($this->perPage)
            ->get($this->search);
        }
        
        //https://github.com/protonemedia/laravel-cross-eloquent-search#sorting
        $x = CrossSearch::new();
        $x->add(Article::with('tags'), 'title', $this->sortByColumn());
        $x->add(Video::with('tags'), 'title', $this->sortByColumn());        
        $x->includeModelType();

        return $x;


        // return CrossSearch::new()
        //     ->add(Article::with('tags'), 'title')
        //     ->add(Video::with('tags'), 'title')            
        //     ->includeModelType()
        //     ->orderByDesc()
        //     ->paginate(100)
        //     ->get();

            
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
