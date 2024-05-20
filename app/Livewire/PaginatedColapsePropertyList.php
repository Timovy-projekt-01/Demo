<?php

namespace App\Livewire;

use Livewire\Component;

class PaginatedColapsePropertyList extends Component
{
    public $label;
    public $list;
    public $currentList;
    public $previousPage;
    public $currentPage;
    public $nextPage;
    public $lastPage;
    public $limit;

    public function mount($list, $label)
    {
        $this->limit = 5;
        $this->previousPage = 1;
        $this->currentPage = 1;
        $this->nextPage = null;
        $this->lastPage = 1;

        $this->list = $list;
        $this->currentList = $list;
        $this->label = $label;

        $this->setNewPage();
    }

    public function setNewPage($page = 1)
    {
        //if ($page) $this->currentPage = $page;
        $this->currentPage = $page;

        $offset = ($this->currentPage - 1) * $this->limit;
        $this->currentList = array_slice($this->list, $offset, $this->limit);

        $this->previousPage = ($this->currentPage === 1) ? $this->previousPage : $this->currentPage - 1;
        $this->lastPage = intval(ceil(count($this->list) / $this->limit));
        $this->nextPage = ($this->currentPage === $this->lastPage) ? $this->lastPage : $this->currentPage + 1;
    }

    public function goToPrevPage()
    {
        $this->setNewPage($this->previousPage);
    }

    public function goToNextPage()
    {
        $this->setNewPage($this->nextPage);
    }

    public function goToFirstPage()
    {
        $this->setNewPage();
    }

    public function goToLastPage()
    {
        $this->setNewPage($this->lastPage);
    }

    public function render()
    {
        return view('livewire.paginated-colapse-property-list', [
            'items' => $this->list,
            'label' => $this->label,
        ]);
    }
}
