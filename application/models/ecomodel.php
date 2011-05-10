<?php

class Ecomodel extends FT_Model{
     
    /**
     * @ignore
     */
    function Ecomodel(){
        parent::__construct();
 		$this->arc_config['store_name'] = "eco";
    }
       
    public function getImpactCategoryMenu() {
        $categories = $this->arc_getAllImpactCategories();
        $menu_html = '<input name="impacts_field" type="hidden" />';
        $menus = array();
        $menus['main'] = "";
        foreach ($categories as $category){
            $menus['main'] .= '<option value="' . $category['uri'] . '">' . $category['label'] . '</option>';
            $menus[$category['label']] = "";
            $indicators = $this->arc_getImpactCategoryIndicators($category['uri']);
            foreach ($indicators as $indicator) {
                $menus[$category['label']] .= '<option value="' . $indicator['uri'] . '">' . $indicator['label'] . '</option>';
            }
        }
        foreach ($menus as $key=>$menu) {
            $show = "";
            if ($key == "main") {
                $show = " show";
            }
            $menu_html .= '<select class="hide' . $show . '" name="impacts_' . $key . '">' . $menu . '</select>';
            if ($key == "main") {
                $menu_html .= '<br />';
            }
        }
        return '<div class="dialog" id="impacts_dialog">' . $menu_html . '</div>';
         
    }
     
    private function arc_getAllImpactCategories() {
        $q = "select ?uri ?label where { " . 
            "?uri rdf:type eco:ImpactCategory . " . 
            "?uri rdfs:label ?label . " . 
            "}";
             
        $results = $this->executeQuery($q, "remote");
        if (count($results) != 0) {
            return $results;
        } else {
            return false;
        }
    }
 
    private function arc_getImpactCategoryIndicators($uri) {
        $q = "select ?uri ?label where { " . 
            "?uri rdf:type eco:ImpactAssessmentMethodCategoryDescription . " . 
            "?uri eco:hasImpactCategory '" . $uri . "' . " . 
            "?uri rdfs:label ?label . " . 
            "}";
 
        $results = $this->executeQuery($q, "remote");
        if (count($results) != 0) {
            return $results;
        } else {
            return false;
        }
    }

	public function makeToolTip($uri, $tooltips) {
		if (isset($tooltips[$uri]) != true) {
			if (strpos($uri,":") !== false) {
				$tooltips[$uri]['label'] = $this->getLabel($uri);
				$tooltips[$uri]['l'] = $tooltips[$uri]['label'];
			} 
		}
	}
     
}