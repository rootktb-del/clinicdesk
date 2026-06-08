<?php

class Paginator
{
    private int $totalItems;
    private int $perPage;
    private int $currentPage;

    public function __construct(
        int $totalItems,
        int $currentPage
    )
    {
        $this->totalItems = $totalItems;
        $this->perPage = ITEMS_PER_PAGE;
        $this->currentPage = $currentPage;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
    

    public function offset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function totalPages(): int
    {
        return (int) ceil(
            $this->totalItems / $this->perPage
        );
    }

    public function hasPrev(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNext(): bool
    {
        return $this->currentPage < $this->totalPages();
    }
}


?>