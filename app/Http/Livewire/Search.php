<?php

namespace App\Http\Livewire;

use App\Models\Tag;
use App\Models\Video;
use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\LaravelCrossEloquentSearch\Search as CrossSearch;

class Search extends Component
{
    use WithPagination;

    protected $listeners = ['filterByTag' => 'filterByTag'];

    public $search = '';
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
    
        $items = CrossSearch::new();
        $items->add(Article::with('tags'), ['title'], $this->sortByColumn());
        $items->add(Video::with('tags'), ['title'], $this->sortByColumn());        
        $items->includeModelType();

        $items = $this->sortDirection($items);
        
        $items = $items                
                    ->paginate($this->perPage)
                    ->beginWithWildcard()            
                    ->get($this->search); //empty search will return all items
              
        if ($this->filters) {
            $results = $this->applyTagFilter($items);            
        } else {
            $results = $items;
        }

        //Get tag count without being affect by pagination        
        $tags = $this->getTags();        
       
        return view('livewire.search')->with(compact('results', 'tags'));
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

    private function applyTagFilter($items)
    {

        $items = CrossSearch::new();  
        
        $selectedTags = $this->filters;
        
        $items->add(
            Article::with('tags')
                ->whereHas('tags', function ($query) use ($selectedTags) {
                    $query->whereIn('id', $selectedTags);
                }), 
            ['title'],
            $this->sortByColumn());

            $items->add(
                Video::with('tags')
                    ->whereHas('tags', function ($query) use ($selectedTags) {
                        $query->whereIn('id', $selectedTags);
                    }), 
                ['title'],
                $this->sortByColumn());
        

        $items->includeModelType();

        $items = $this->sortDirection($items);

        return $items                
            ->paginate($this->perPage)
            ->beginWithWildcard()
            ->get($this->search);
    }

    private function getTags()
    {
        return Tag::withCount(['articles', 'videos'])
            ->having('articles_count', '>=', 1)
            ->having('videos_count', '>=', 1)            
            ->orderBy(DB::raw("`articles_count` + `videos_count`"), 'DESC')
            ->take(15)         
            ->get();
    }

}
