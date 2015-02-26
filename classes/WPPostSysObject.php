<?php
require_once(sprintf("%s/../../../../wp-load.php", dirname(__FILE__)));

class WPPostSysObject
{
    public $post_ID;

	public function Update()
	{
        $data = array();
		$format = array();
        foreach($this->properties as $property => $type)
        {
            switch($type)
            {
                case 'int':
                    $format[] = '%d';
                    break;
                
                case 'float':
                    $format[] = '%f';
                    break;

                case 'string':
                case 'date':
                    $format[] = '%s';
                    break;
            }
            
            $data[$property] = $this->$property;
        }

        foreach($data as $key => $value)
        {
            if ($value != '')
                update_post_meta($this->post_ID, '_'.$key, $value);
            else
                delete_post_meta($this->post_ID, '_'.$key);
        }

        // delete values from database that doesn't exist in object
        $meta_keys = get_post_meta($post_ID);

        if ($meta_keys)
			foreach($meta_keys as $key => $value)
				if (!array_key_exists('_'.$key, $data))
					delete_post_meta($this->post_ID, '_'.$key);
	}

	public function LoadBy_array($values)
    {
        foreach($this->properties as $property => $type)
        {
            if (array_key_exists('_'.$property, $values))
            {
                switch(strtolower($type))
                {
                    case 'int':
                        $this->$property = intval($values['_'.$property]);
                        break;
                    
                    //ToDo: desenvolver tratamento de float
                    case 'float':
                        $this->$property = $values['_'.$property];
                        break;
                    
                    case 'string':
                    case 'date':
                        $this->$property = $values['_'.$property];
                        break;
                }
            }
        }

        $this->post_ID = $values['post_ID'];
    }

	public function Load($id)
	{
		foreach($this->properties as $property => $type)
			$this->$property = get_post_meta($id, '_'.$property, true);

		$this->post_ID = $id;
	}
}
?>