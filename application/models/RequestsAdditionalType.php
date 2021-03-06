<?php

class RequestsAdditionalType extends Zend_Db_Table
{
	protected $_name = 'requests_additional_type';
    
        function DumpAllActive()
        {
            $rows = $this->fetchAll("type_status=1");
            
            return $rows->toArray();
        }
	
        function GetFormElements($requests_category_id, $just_input_box=NULL)
        {
            if($just_input_box)
            {
            	$cond_type = " and type_id=1";
            }else{
            	$cond_type = "";
            }
            
            if($requests_category_id)
            {
            	$cond_category = " and requests_category_id='".$requests_category_id."'";
            }else{
            	$cond_category = "";
            }
            
            $rows = $this->fetchAll("type_status=1".$cond_type.$cond_category);
            
            $result = array();
            
            foreach($rows as $row)
            {
                $result[$row['requests_additional_type_id']] = array($row['type_required'], $row['type_title']);
            }
            
            return $result;
        }
        
        function GetUnnecessaryElements($requests_category_id, $decolation=NULL)
        {
            $rows = $this->fetchAll("type_status=1 and requests_category_id!='".$requests_category_id."'");
            
            $result = array();
            
            foreach($rows as $row)
            {
                $element = $row['requests_additional_type_id'];
                
                if($decolation)
                {
                    $element = "additional".$element;
                }
                
                $result[] = $element;
            }
            
            return $result;
        }
}



