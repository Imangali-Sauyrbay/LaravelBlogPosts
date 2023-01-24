<?php

namespace App\Http\Requests;

use App\Models\Blogpost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;

class PaginatePostRequest extends FormRequest
{

    public $page = 0;
    public $offset = 0;
    public RedirectResponse $path;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function withPagination($perPage, Builder $query, $suffix = '')
    {
        if(!$this->has('page') || $this->query('page') < 0) {
            $this->path = redirect()->to($this->fullUrlWithQuery(['page' => 1]));
            return false;
        }

        $this->page = (int) $this->query('page', 1);

        $postsCount = Cache::tags(['blogpost', 'posts-count'])->remember('count' . $suffix,
        now()->addSeconds(15), fn() => $query->count());

        $offset = ($this->page - 1) * $perPage;

        if($offset > $postsCount) {
            $lastPage = ceil($postsCount / $perPage);
            $this->path = redirect()->to($this->fullUrlWithQuery(['page' => $lastPage]));
            return false;
        }

        if($offset < 0) {
            $this->offset = 0;
            return true;
        }

        $this->offset = $offset;
        return true;
    }
}
