<?php

namespace Pages;

class Model_Page extends \Orm\Model
{

    protected static $_observers = array(
        'Orm\\Observer_CreatedAt'   => array('before_insert'),
        'Orm\\Observer_UpdatedAt'   => array('before_save'),
    );
    
    protected static $_properties = array(
        'id'            => array('type' => 'int'),
        'user_id'       => array('type' => 'int'),
        'category'      => array('type' => 'int'),
        'section'       => array('type' => 'varchar'),
        'title'         => array('type' => 'varchar'),
        'slug'          => array('type' => 'varchar'),
        'content'       => array('type' => 'text'),
        'state'         => array('type' => 'int', 'default' => 1),
        'status'        => array('type' => 'int', 'default' => 1),
        'like'          => array('type' => 'int', 'default' => 0),
        'comment'       => array('type' => 'int', 'default' => 0),
        'created_at'    => array('type' => 'int'),
        'updated_at'    => array('type' => 'int')
    );
    
    protected static $_has_one = array(
        'report' => array(
            'key_from'          => 'id',
            'model_to'          => 'Pages\Model_Report',
            'key_to'            => 'page_id',
            'cascade_save'      => true,
            'cascade_delete'    => true,
        ),
        
        'anime' => array(
            'key_from'          => 'id',
            'model_to'          => 'Pages\Model_Anime',
            'key_to'            => 'page_id',
            'cascade_save'      => true,
            'cascade_delete'    => true,
        )
    );
    
    protected static $_has_many = array(
        'comments' => array(
            'key_from'          => 'id',
            'model_to'          => 'Comments\Model_Comment',
            'key_to'            => 'parent_id',
            'cascade_save'      => true,
            'cascade_delete'    => true,
        ),
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
        
        $form->add('category', 'Category', array(
                'type'      => 'select',
                'options'   => array('Web', 'Code')
        ));
        
        $form->add('submit', null, array('value' => 'Save', 'type' => 'submit'));
        
    }
    
    public static function process_form(\Fieldset $form, Model_Page $page, $save = true)
    {
        $page->slug         = \Inflector::friendly_title($form->validated('title'), '-', true);
        $page->category     = $form->validated('category');
        $page->title        = $form->validated('title');
        $page->content      = $form->validated('content');

        if ($save)
        {
            $page->save();
        }
        return $page;
    }
    
}

/* End of file page.php */