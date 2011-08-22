<?php

namespace Pages;

class Controller_Pages extends \Controller_App
{
    /**
     * @var string  Holds the page model
     */
    protected $model    = null;
    
    /**
     * @var string  Holds the page section
     */
    protected $section  = null;
    
    /**
     * @var string  Holds the page title
     */
    protected $title   = null;
    
    /**
     * The index action.
     * 
     * @access  public
     * @return  void
     */
    public function action_index()
    {
        $pages = call_user_func('Pages\\'.$this->model.'::find_'.$this->section);

        $this->template->title   = $this->section;
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
        if ( ! $page = call_user_func('Pages\\'.$this->model.'::find_'.$this->section, $id))
        {
            \Request::show_404();
        }

        $this->template->title   = $page->title;
        $this->template->content = \View::factory('view')->set('page', $page);
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
        
        $form = \Fieldset::factory($this->section)->add_model('Pages\\'.$this->model);
        
        if ($this->model != 'Model_Page')
        {
            $form->populate($page->{$this->section}, true);
        }
        
        $form->populate($page, true);
        
        if ($form->validation()->run())
        {
            $page->user_id      = 1;
            $page->section      = $this->section;
            
            if ($page = call_user_func_array('Pages\\'.$this->model.'::process_form', array($form, $page)))
            {
                \Session::set_flash('success', 'Created '.$page->title);
                \Response::redirect($this->section.'/'.$page->id.'/'.$page->slug);
            }
            \Session::set_flash('error', 'Could not create '.$this->title);
        }
        
        $this->template->title   = 'Add '.$this->title;
        $this->template->content = \View::factory('add-edit')->set('form', $form, false)->set('title', 'Add '.$this->title);
    }
    
    /**
     * The edit action.
     * 
     * @access  public
     * @return  void
     */
    public function action_edit($id = false)
    {
        if ( ! $page = call_user_func('Pages\\'.$this->model.'::find_'.$this->section, $id))
        {
            \Request::show_404();
        }

        $form = \Fieldset::factory($this->section)->add_model('Pages\\'.$this->model);
        
        if ($this->model != 'Model_Page')
        {
            $form->populate($page->{$this->section}, true);
        }
        
        $form->populate($page, true);

        if ($form->validation()->run())
        {
            if ($page = call_user_func_array('Pages\\'.$this->model.'::process_form', array($form, $page)))
            {
                \Session::set_flash('success', 'Updated '.$page->title);
                \Response::redirect($this->section.'/'.$page->id.'/'.$page->slug);
            }
            \Session::set_flash('error', 'Could not update '.$this->title);
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
    public function action_delete($id = null)
    {

    }

}

/* End of file pages.php */