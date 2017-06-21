<?php
namespace Models\Api\Entities;

class People {

	public $adults;
	public $children = [];

	const CHILDREN_SEPARATOR = ',';


	public function __construct($people = null)
	{
		if($people) {
			$this->adults = (int) $people->adults;

			if(is_array($people->children)) {
				$this->children = $people->children;
			}
		}
	}

	public function fromStored($people = null) {
	    if($people) {
	        $this->adults = $people->adults ? : $this->adults;
            $this->children = $people->children ? : $this->children;
        }
    }

    public function getChildrenString() {
	    if(count($this->children) === 0){
	        return 0;
        }
	    return implode(self::CHILDREN_SEPARATOR, $this->children);
    }
}