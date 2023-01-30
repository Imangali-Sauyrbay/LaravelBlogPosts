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
    private bool $hasMore;
    private int $lastPage;
    private int $postsCount;
    private $data;
    private int $eachSide = 3;


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
            return redirect()->to($this->url(1));
        }

        $this->page = (int) Request::query('page', 1);

        $this->postsCount = Cache::tags($tags)->remember('count' . $suffix,
        now()->addSeconds(15), fn() => $query->count());
        $this->lastPage = ceil($this->postsCount / $this->perPage);


        $this->hasMore = $this->postsCount > $this->perPage;

        $offset = ($this->page - 1) * $this->perPage;

        if($offset > $this->postsCount) {
            return redirect()->to($this->url($this->lastPage));
        }

        if($offset < 0) {
            $this->offset = 0;
            return;
        }

        $this->offset = $offset;
    }


    private function getPaginatedRecords(Builder $query)
    {
        $this->data = $query->limit($this->perPage)->offset($this->offset)->get();
        return $this->data;
    }

    private function url(int $n) {
        return Request::fullUrlWithQuery(['page' => $n]);
    }

    private function getPaginatorLinks($shouldAddString=false)
    {
        return view('pagination.pagination', [
            'paginator' => $this,
            'elements' => $this->elements(),
            'shouldShowResultString' => $shouldAddString
        ]);
    }

    private function elements()
    {
        $window = $this->calcElements();

        return array_filter([
            $window['first'],
            is_array($window['slider']) ? '...' : null,
            $window['slider'],
            is_array($window['last']) ? '...' : null,
            $window['last'],
        ]);
    }

    private function calcElements()
    {
        if ($this->lastPage < ($this->eachSide * 2) + 8) {
            return $this->getSmallSlider();
        }

        return $this->getUrlSlider($this->eachSide);
    }

    protected function getSmallSlider()
    {
        return [
            'first' => $this->getUrlRange(1, $this->lastPage),
            'slider' => null,
            'last' => null,
        ];
    }

    protected function getUrlSlider($onEachSide)
    {
        $window = $onEachSide + 4;

        if (!$this->hasPages()) {
            return ['first' => null, 'slider' => null, 'last' => null];
        }


        if ($this->page <= $window) {
            return $this->getSliderTooCloseToBeginning($window, $onEachSide);
        }


        elseif ($this->page > ($this->lastPage - $window)) {
            return $this->getSliderTooCloseToEnding($window, $onEachSide);
        }

        return $this->getFullSlider($onEachSide);
    }

    protected function getSliderTooCloseToBeginning($window, $onEachSide)
    {
        return [
            'first' => $this->getUrlRange(1, $window + $onEachSide),
            'slider' => null,
            'last' => $this->getFinish(),
        ];
    }

    protected function getSliderTooCloseToEnding($window, $onEachSide)
    {
        $last = $this->getUrlRange(
            $this->lastPage - ($window + ($onEachSide - 1)),
            $this->lastPage
        );

        return [
            'first' => $this->getStart(),
            'slider' => null,
            'last' => $last,
        ];
    }

    protected function getFullSlider($onEachSide)
    {
        return [
            'first' => $this->getStart(),
            'slider' => $this->getAdjacentUrlRange($onEachSide),
            'last' => $this->getFinish(),
        ];
    }

    public function getAdjacentUrlRange($onEachSide)
    {
        return $this->getUrlRange(
            $this->page - $onEachSide,
            $this->page + $onEachSide
        );
    }

    private function getFinish()
    {
        return $this->getUrlRange(
            $this->lastPage - 1,
            $this->lastPage
        );
    }

    private function getStart()
    {
        return $this->getUrlRange(1, 2);
    }


    private function getUrlRange($start, $end)
    {
        return collect(range($start, $end))->mapWithKeys(function ($page) {
            return [$page => $this->url($page)];
        })->all();
    }

    public function hasMorePages()
    {
        return $this->hasMore;
    }

    public function onFirstPage()
    {
        return $this->page == 1;
    }

    public function previousPageUrl()
    {
        if($this->page > 1) {
            return Request::fullUrlWithQuery(['page' => $this->page - 1]);
        }
    }

    public function nextPageUrl()
    {
        if($this->page < $this->lastPage) {
            return Request::fullUrlWithQuery(['page' => $this->page + 1]);
        }
    }

    public function firstItem()
    {
        return $this->postsCount > 0 ? ($this->page - 1) * $this->perPage + 1 : null;
    }

    public function lastItem()
    {
        return $this->postsCount > 0 ? $this->firstItem() + $this->count() - 1 : null;
    }

    public function count() {
        return isset($this->data) ? $this->data->count() : 0;
    }

    public function total() {
        return $this->postsCount;
    }

    public function onEachSide(int $n) {
        if ($n <=0 )
            return $this->data;

        $this->eachSide = $n;

        return $this->data;
    }

    public function hasPages()
    {
        return $this->page != 1 || $this->hasMorePages();
    }

    public function currentPage() {
        return $this->page;
    }

}
