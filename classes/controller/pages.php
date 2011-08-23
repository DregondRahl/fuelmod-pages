<?php

namespace Pages;

class Controller_Pages extends \Controller_App
{

    /**
     * @var string  Holds the page section
     */
    protected $section  = null;
    
    /**
     * @var string  Holds the page title
     */
    protected $config   = array();
    
    /**
     * The router control.
     * 
     * @access  public
     * @return  void
     */
    public function router($method, $params)
    {
        $config = \Config::load('page');

        if ( ! empty($params) and array_key_exists($params[0], $config))
        {
            $this->section = $params[0];
            $this->config  = (object) $config[$params[0]];

            parent::router($method, $params);
        }
        else
        {
            \Request::show_404();
        }
    }


    /**
     * The index action.
     * 
     * @access  public
     * @return  void
     */
    public function action_index()
    {
        $pages = Model_Page::find()->where('section', $this->section);
        
        if ($this->config->related)
        {
            foreach($this->config->related as $related)
            {
                $pages->related($related);
            }
        }
        
        if ( ! $pages = $pages->get())
        {
            \Request::show_404();
        }
        
        $this->template->title   = ucwords($this->section);
        $this->template->content = \View::factory('index')->set('pages', $pages);
    }
    
    /**
     * The view action.
     * 
     * @access  public
     * @return  void
     */
    public function action_view($id = false)
    {
        $page = Model_Page::find()->where('id', $id)->where('section', $this->section);
        
        if ($this->config->related)
        {
            foreach($this->config->related as $related)
            {
                $page->related($related);
            }
        }
        
        if ($this->config->comments)
        {
            $page->related('comments');
        }
        
        if ( ! $page = $page->get_one())
        {
            \Request::show_404();
        }

        $this->template->title   = $page->title;
        $this->template->content = \View::factory('view')->set('page', $page)->set('comments', $this->config->comments);
    }
    
    /**
     * The add action.
     * 
     * @access  public
     * @return  void
     */
    public function action_add()
    {
        $page = Model_Page::factory();
        
        $form = \Fieldset::factory($this->section)->add_model('Pages\\'.$this->config->model);
        
        if ($this->config->related)
        {
            $form->populate($page->{$this->section}, true);
        }
        
        $form->populate($page, true);
        
        if ($form->validation()->run())
        {
            $page->user_id      = 1;
            $page->section      = $this->section;

            if ($page = call_user_func_array('Pages\\'.$this->config->model.'::process_form', array($form, $page)))
            {
                \Session::set_flash('success', 'Created '.$page->title);
                \Response::redirect($this->section.'/'.$page->id.'/'.$page->slug);
            }
            \Session::set_flash('error', 'Could not create '.$this->title);
        }
        
        $this->template->title   = 'Add '.$this->config->title;
        $this->template->content = \View::factory('add-edit')->set('form', $form, false)->set('title', 'Add '.$this->config->title);
    }
    
    /**
     * The edit action.
     * 
     * @access  public
     * @return  void
     */
    public function action_edit($section = false, $id = false)
    {

        $page = Model_Page::find()->where('id', $id)->where('section', $this->section);
        
        if ($this->config->related)
        {
            foreach($this->config->related as $related)
            {
                $page->related($related);
            }
        }
        
        if ($this->config->comments)
        {
            $page->related('comments');
        }
        
        if ( ! $page = $page->get_one())
        {
            \Request::show_404();
        }

        $form = \Fieldset::factory($this->section)->add_model('Pages\\'.$this->config->model);
        
        if ($this->config->related)
        {
            $form->populate($page->{$this->section}, true);
        }
        
        $form->populate($page, true);

        if ($form->validation()->run())
        {
            if ($page = call_user_func_array('Pages\\'.$this->config->model.'::process_form', array($form, $page)))
            {
                \Session::set_flash('success', 'Updated '.$page->title);
                \Response::redirect($this->section.'/'.$page->id.'/'.$page->slug);
            }
            \Session::set_flash('error', 'Could not update '.$this->config->title);
        }
        
        $this->template->title   = 'Edit: '. $page->title;
        $this->template->content = \View::factory('add-edit')->set('form', $form, false)->set('title', 'Edit: '. $page->title);
    }
    
    /**
     * The delete action.
     * 
     * @access  public
     * @return  void
     */
    public function action_delete($section = false, $id = null)
    {

    }

}

/* End of file pages.php */