<?php

namespace Pages;

class Model_Report extends \Orm\Model
{
    
    protected static $_properties = array(
        'id'            => array('type' => 'int'),
        'page_id'       => array('type' => 'int'),
        'assigned_id'   => array('type' => 'int'),
        'report_link'   => array('type' => 'varchar', 'default' => ''),
    );
    
    protected static $_belongs_to = array(
        'page' => array(
            'key_from'          => 'page_id',
            'model_to'          => 'Pages\Model_Page',
            'key_to'            => 'id',
            'cascade_save'      => true,
            'cascade_delete'    => true,
        )
    );
    
    public static function set_form_fields($form, $instance = null)
    {
        $form->add('title', 'Title', 
                array('type' => 'text'),
                array(array('required'), array('min_length', 3), array('max_length', 150))
        );
        
        $form->add('content', 'Content', 
                array('type' => 'textarea'),
                array(array('required'), array('min_length', 3))
        );
        
        $form->add('report_link', 'Report Link', 
                array('type' => 'text'),
                array(array('min_length', 20), array('max_length', 150))
        );
        
        $form->add('category', 'Category', array(
                'type'      => 'select',
                'options'   => array('Broken Links', 'Layout Problem', 'Bugs')
        ));
        
        $form->add('assigned_id', 'Assigned', array(
                'type'      => 'select',
                'options'   => array('Reaveraz','Toshi','Jak')
        ));
        
        $form->add('submit', null, array('value' => 'Save', 'type' => 'submit')); 
    }
    
    public static function process_form(\Fieldset $form, Model_Page $page, $save = true)
    {
        $page = Model_Page::process_form($form, $page, false);
        
        $page->report               = ($page->report)?:self::factory();
        $page->report->assigned_id  = $form->validated('assigned_id');
        $page->report->report_link  = $form->validated('report_link');
        
        if ($save)
        {
            $page->save();
        }
        return $page;
    }

}

/* End of file report.php */