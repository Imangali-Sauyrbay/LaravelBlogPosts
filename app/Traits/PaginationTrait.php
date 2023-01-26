<?php

namespace App\Traits;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Builder;

trait PaginationTrait {
    private int $perPage = 10;
    private int $page = 1;
    private int $offset = 0;
    private int $postsCount;

    /**
     * Pagination using Illuminate\Pagination\Paginator;
     */
    private function paginator($data)
    {
        $currentPage = Paginator::resolveCurrentPage();
        return new Paginator($data->forPage($currentPage, $this->perPage), $data->count(), $this->perPage);
    }

    /**
     * Custom pagination method
     *
     * @param Builder $query query builder for getting count of records
     * @param array $tags tags for caching query
     * @param string $suffix suffix
     * @return void
     */
    private function paginate(Builder $query, array $tags=['blogpost', 'posts-count'], string $suffix="")
    {
        if(!Request::has('page') || Request::query('page') < 0) {
            return redirect()->to(Request::fullUrlWithQuery(['page' => 1]));
        }

        $this->page = (int) Request::query('page', 1);

        $this->postsCount = Cache::tags($tags)->remember('count' . $suffix,
        now()->addSeconds(15), fn() => $query->count());

        $offset = ($this->page - 1) * $this->perPage;

        if($offset > $this->postsCount) {
            $lastPage = ceil($this->postsCount / $this->perPage);
            return redirect()->to(Request::fullUrlWithQuery(['page' => $lastPage]));
        }

        if($offset < 0) {
            $this->offset = 0;
            return;
        }

        $this->offset = $offset;
    }


    private function getPaginatedRecords(Builder $query)
    {
        return $query->limit($this->perPage)->offset($this->offset)->get();
    }
}
