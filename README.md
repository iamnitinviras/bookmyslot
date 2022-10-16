# bookmyslot

$this->db->where('title', $title);
$this->db->from('app_content');
$check_title=$this->db->count_all_results();