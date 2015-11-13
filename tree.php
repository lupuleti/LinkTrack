<?php

$searchedLink = "http://www.bctia.org/About-Us/BCTIA-Partners";
$start_url = "mlh.io";

class Tree {
    private $value;
    private $children;

    function __construct($value) {
        $this->children = array();
        $this->value = $value;
    }

    public function get_child($id) {
        if (array_key_exists($id, $this->children))
            return $this->children[$id];
        else
            return null;
    }

    public function set_child($id, $value) {
        if (array_key_exists($id, $this->children)) {
            $this->children[$id] = $value;
            return true;
        } else
            return false;
    }

    public function add_child($child) {
        array_push($this->children, $child);
    }

    public function is_empty() {
        return empty($this->children);
    }

    public function get_size() {
        return count($this->children);
    }

    public function get_value() {
        return $this->value;
    }

    public function print_tree($depth = 0) {
        for ($i = 0; $i < $depth; $i++)
            echo "  ";
        echo $this->value . "\n";
        foreach ($this->children as $child)
            $child->print_tree($depth + 1);
    }

    public function search_tree($value) {
        if ($this->value == $value)
            return true;
        foreach ($this->children as $child)
            return $this->search_tree($child);
        return false;
    }
}

$tree  = new Tree($start_url);
$queue = new SPLQueue();
$queue->enqueue($tree);
$count = 0;
while ($count < 53) {
    $currentTree = $queue->dequeue();
    $currentVal  = $currentTree->get_value();

    $jsonQ = file_get_contents("http://api.majestic.com/api/json?app_api_key=".
                               "96CA2AAC8EC2F73FA1365D69BED49B1D&cmd=GetBackL".
                               "inkData&item=$currentVal&Count=10&datasour".
                               "ce=fresh");
    $decodedJsonQ = json_decode($jsonQ);
    $arrayOfDataQ = $decodedJsonQ->DataTables->BackLinks->Data;

    foreach ($arrayOfDataQ as $item) {
        $newTree = new Tree($item->SourceURL);
        $queue->enqueue($newTree);
        $currentTree->add_child($newTree);
    }
    $count++;
}





