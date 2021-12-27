<?php

namespace App\Http\Livewire;

use App\Models\Tag;
use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    protected $listeners = ['filterByTag' => 'filterByTag'];

    public $search;
    public $filters = [];
    public $perPage = 10;
    public $sort = 'created_at|desc';
    
    /*
     * Reset pagination when doing a search
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // $articles = Article::with('tags')->paginate($this->perPage);

        $articles = Article::with('tags')->withCount('tags');
        
        //Get tag count without being affect by pagination        
        $uniqueTags = $this->getTags();        

        $this->applySearchFilter($articles);

        $this->applyTagFilter($articles);

        $articles = $articles->orderBy($this->sortByColumn(), $this->sortDirection())
        ->paginate($this->perPage);

        return view('livewire.search')->with(compact('articles', 'uniqueTags'));
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

        if(!$sort[0]) {
            return;
        }

        return $sort[0];
    }

    public function sortDirection()
    {
        $sort = explode("|", $this->sort);

        return $sort[1] ?? 'asc';
    }

    private function applySearchFilter($articles)
    {
        if ($this->search) {
            return $articles->whereRaw("title LIKE \"%$this->search%\"");
        }

        return null;
    }

    private function applyTagFilter($articles)
    {
        if ($this->filters) {
            foreach ($this->filters as $filter) {
                $articles->whereHas('tags', function ($query) use ($filter) {
                    $query->where('id', $filter);
                });
            }
        }

        return null;
    }

    private function getTags()
    {
        $tags = Tag::withCount('articles')
            ->orderBy('articles_count', 'DESC')
            ->take(15)
            ->get();

        // Show only tags with articles
        return $tags->filter( function ($tag) {
            return $tag->articles_count > 0;
        });
    }

}
