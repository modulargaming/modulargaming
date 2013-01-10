<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pagination {

	public $pagination;

	public function items()
	{
		$items = array();

		// First.
		$first['title'] = 'first';
		$first['name'] = __('first');
		$first['url'] = ($this->pagination->first_page !== FALSE) ? $this->pagination->url($this->pagination->first_page) : FALSE;
		$items[] = $first;

		// Prev.
		$prev['title'] = 'prev';
		$prev['name'] = __('previous');
		$prev['url'] = ($this->pagination->previous_page !== FALSE) ? $this->pagination->url($this->pagination->previous_page) : FALSE;
		$items[] = $prev;

		// Numbers.
		for ($i=1; $i<=$this->pagination->total_pages; $i++)
		{
			$item = array();

			$item['current'] = ($i === $this->pagination->current_page);
			$item['name'] = $i;
			$item['url'] = ($i != $this->pagination->current_page) ? $this->pagination->url($i) : FALSE;

			$items[] = $item;
		}

		// Next.
		$next['title'] = 'next';
		$next['name'] = __('next');
		$next['url'] = ($this->pagination->next_page !== FALSE) ? $this->pagination->url($this->pagination->next_page) : FALSE;
		$items[] = $next;

		// Last.
		$last['title'] = 'last';
		$last['name'] = __('last');
		$last['url'] = ($this->pagination->last_page !== FALSE) ? $this->pagination->url($this->pagination->last_page) : FALSE;
		$items[] = $last;

		return $items;
	}

}