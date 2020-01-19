<?php
class Pages extends Controller{
    public function __construct(){
		//Sample for using a model
        $this->postModel=$this->model('Post');
    }
    
    public function index(){
        $data = [
            'title'=>'Welcomeee'
            ];
        $this->view('pages/index', $data);
    }

    

    public function about(){
        $data = ['title'=>'About'];
        $this->view('pages/about', $data);
        
    }
}