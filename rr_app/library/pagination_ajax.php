<?php
class Pagination {
	public $total = 0;
	public $page = 1;
	public $limit = 20;
	public $num_links = 10;
	public $url = '';
	public $text = 'Showing {start} to {end} of {total} ({pages} Pages)';
	public $text_first = '&#171; First';	
	public $text_last = 'Last &#187;';
	public $text_next = 'Next &#155;';
	public $text_prev = '&#139; Prev';
	public $style_links = 'pagination';
	public $style_results = 'results';
	 
	public function render() {
		$total = $this->total;		
		
		if ($this->page < 1) {
			$page = 1;
		} else {
			$page = $this->page;
		}
		
		if (!(int)$this->limit) {
			$limit = 10;
		} else {
			$limit = $this->limit;
		}
		
		$num_links = $this->num_links;
		$num_pages = ceil($total / $limit);
		
		$output = '';
		
		if ($page > 1) {
			$output .= ' <a href="javascript:void(0);" onclick="' . str_replace('{page}', 1, $this->url) . '">' . $this->text_first . '</a> <a href="javascript:void(0);" onclick="' . str_replace('{page}', $page - 1, $this->url) . '">' . $this->text_prev . '</a> ';
    	}

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);
			
				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}
						
				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}

			if ($start > 1) {
				$output .= '<span>....</span>';
			}

			for ($i = $start; $i <= $end; $i++) {
				if ($page == $i) {
					#$output .= ' <b>' . $i . '</b> ';
					$output .= ' <span class="active">' . $i . '</span> ';
					
				} else {
					$output .= ' <a href="javascript:void(0);" onclick="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a> ';
				}	
			}
							
			if ($end < $num_pages) {
				$output .= '<span>....</span>';
			}
		}
		
   		if ($page < $num_pages) {
			$output .= ' <a href="javascript:void(0);" onclick="' . str_replace('{page}', $page + 1, $this->url) . '">' . $this->text_next . '</a> <a href="javascript:void(0);" onclick="' . str_replace('{page}', $num_pages, $this->url) . '">' . $this->text_last . '</a> ';
		}
		
		$find = array(
			'{start}',
			'{end}',
			'{total}',
			'{pages}'
		);
		
		$replace = array(
			($total) ? (($page - 1) * $limit) + 1 : 0,
			((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit),
			$total, 
			$num_pages
		);		
		
		return ($output ? '<div class="' . $this->style_links . '">' . $output . '<span class="' . $this->style_results . '">' . str_replace($find, $replace, $this->text) . '</span></div>' : ' <span class="' . $this->style_results . '">' . str_replace($find, $replace, $this->text) . '</span> ') ;
	}
}
?>