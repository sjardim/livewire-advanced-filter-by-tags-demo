<div class="mx-auto">

    <div class="flex items-center">
        <label class="relative block w-full">
            <span class="sr-only">Search</span>
            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                <svg class="h-5 w-5 fill-gray-300" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
            </span>
            <input 
                wire:model.debounce.250ms="search"
                wire:ref="search-box"
                type="text"
                name="search"
                value=""
                placeholder="Type something..."
                class="placeholder:italic placeholder:text-gray-400 block bg-white w-full border border-gray-300 rounded-md py-2 pl-9 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm" 
                placeholder="Search for anything..." type="text" name="search"/>
        </label>

        <div
                wire:loading
                wire:target="search-box"
                class="ml-2 text-gray-500"
        >🔎 searching...</div>
    </div>

    @if($uniqueTags)
        <div class="flex flex-wrap mt-4 -m-1 gap-2">
            @foreach($uniqueTags as $tag)           
                <button
                        wire:click="$emit('filterByTag', {{ $tag->id }})"
                        wire:ref="search-box"
                        type="button"
                        class="flex items-center text-sm px-2 rounded-full 
                        text-[{{$tag->color}}]
                        border border-[{{$tag->color}}]
                        hover:bg-[{{$tag->color}}] hover:text-white                        
                        {{ in_array($tag->id, $filters) ? 'bg-['.$tag->color .'] text-white' : '' }}"
                >
                    {{ ucfirst($tag->name) }} <small class="ml-1 font-thin">({{ $tag->articles_count }})</small>
                </button>
            @endforeach
        </div>
    @endif

    <div class="">
        @if($articles->count())
            <div class="my-6 border-b pb-2 text-sm flex justify-between">
                <div class="flex">
                    Per page:
                    <select wire:model="perPage">
                        <option>10</option>
                        <option>15</option>
                        <option>25</option>
                    </select>
                </div>
                <div class="flex">
                    Sort by:
                    <select wire:model="sort">
                        <option value="title|asc" title="Title ascending">Title A-Z &uparrow;</option>
                        <option value="title|desc" title="Title descending">Title Z-A &downarrow;</option>
                        <option value="created_at|desc" title="Date descending">Newest &downarrow;</option>
                        <option value="created_at|asc" title="Date ascending">Oldest &uparrow;</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col space-y-6 border-b mb-4 pb-4">
            @foreach($articles as $article)
                <article>
                    <header class="flex flex-col flex-col-reverse md:flex-row">
                        <h2 class="text-xl text-gray-600 tracking-wide flex-1 font-serif">{{$article->title}}</h2>
                        <span class="text-sm text-gray-500">{{$article->created_at->diffForHumans()}}</span>
                    </header>

                    @if($tags = $article->tags)
                        <div class="mt-1 -mx-1">
                            @foreach($tags as $tag)
                                <small
                                        class="inline-flex items-center
                                        before:block before:rounded-full before:w-2 before:h-2 before:mr-1 before:bg-[{{$tag->color}}] 
                                        text-xs rounded-full px-2                                     
                                        text-[{{$tag->color}}]                                        
                                        {{ in_array($tag->id, $filters) ? 'before:hidden bg-['.$tag->color .'] text-white' : '' }}"
                                >
                                    {{ ucfirst($tag->name) }}
                                </small>
                            @endforeach
                        </div>
                    @endif
                </article>
            @endforeach
            </div>


            {{ $articles->links() }}

        @else
            <h2 class="text-xl text-gray-600 tracking-wide flex-1 font-serif py-8">No results found.</h2>
        @endif
    </div>
</div>
