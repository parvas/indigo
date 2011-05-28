<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Projects extends Controller {

    public function index($id)
    {
        $this->data = $this->model->get($id);
        
        $this->template
             ->title($this->data['title'])
             ->render('project_show', $this->data);
    }
    
    public function add()
    {
        if ($this->_validate())
        {
            $id = $this->model->add($this->module->post());
            URL::redirect("/projects/{$id}");
        }
        
        $this->data['action'] = 'Create';
        
        $this->template
             ->title('New Project')
             ->render('project_form');
    }

    public function edit($id)
    {
        $this->data = $this->model->get($id);
        $this->_check_if_null($this->data);
        
        if ($this->_validate())
        {
            $this->model->update($id, $this->module->post());
            URL::redirect('/projects/' . $id);
        }
        
        $this->data['action'] = 'Update';
        
        $this->template
             ->title('Edit Project')
             ->render('project_form', $this->data);
    }
    
    public function all()
    {
        $this->data['projects'] = $this->model->get();
        
        $this->template
             ->title(_SHOW_ALL_)
             ->render('projects_all', $this->data);
    }
    
    public function delete($id)
    {
        $this->model->delete($id);
        URL::redirect('/projects/all');
    }

    private function _validate()
    {
        return Validation::factory()
                ->label('title', 'Title')
                ->label('description', 'Description')
                ->rule('title', 'required')
                ->rule('title', 'max_length', 100)
                ->rule('description', 'required')
                ->rule('description', 'max_length', 1000)
                ->validate();
    }
    
    private function _check_if_null($resource)
    {
        if ($resource === null)
        {
            Exceptions::error_404(URL::fetch_full());
        }
    }
}