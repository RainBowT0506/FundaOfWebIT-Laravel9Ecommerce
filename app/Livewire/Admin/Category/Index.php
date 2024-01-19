<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $category_id;

    public function setDeleteCategoryID($category_id)
    {

        Log::info($category_id);
        $this->category_id = $category_id;
    }

    public function destroyCategory()
    {
        if (!$this->category_id) {
            session()->flash('error', 'Invalid category ID');
            return;
        }

        $category = Category::find($this->category_id);

        if (!$category) {
            session()->flash('error', 'Category not found');
            return;
        }

        $path = 'uploads/category/' . $category->image_id;
        if (File::exists($path)) {
            File::delete($path);
        }

        $category->delete();
        session()->flash('message', 'Category Deleted');

        $this->dispatch('close-modal');
    }

    public function render()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('livewire.admin.category.index', ['categories' => $categories]);
    }
}
