<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'core/MY_Crud_Controller.php');

class User extends MY_Crud_Controller {
	protected static $PAGE_GROUP = 'user';

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();

		$isLoggedIn = $this->session->userdata('is_logged_in');
		if(!isset($isLoggedIn) || $isLoggedIn != TRUE) {
			redirect(site_url() .'auth');
		}
    }

    public function index($params = array())
	{
        //TODO: Dashboard/
        redirect(site_url() .$this->router->class .'/profile');
    }

	protected function get_navigation() {
        $this->load->model(array('crud/Mnavigation'));
        $arr = $this->Mnavigation->get_navigation($this->session->userdata('role_id'), static::$PAGE_GROUP);

        $this->load->model(array('lms/Muser'));

        $is_teacher = $this->Muser->is_teacher();
        if ($is_teacher) {
            $arr1 = $this->Mnavigation->get_navigation($this->session->userdata('role_id'), 'teacher');
            if ($arr1 != null && count($arr1) > 0)
                $arr = array_merge($arr, $arr1);
        }

        //add link to admin panel
        $is_admin = $this->Muser->is_admin();
        if ($is_admin) {
            $arr1 = $this->Mnavigation->get_navigation($this->session->userdata('role_id'), 'link_admin');
            if ($arr1 != null && count($arr1) > 0)
                $arr = array_merge($arr, $arr1);
        }

        if (!$is_admin) {
            //add link to managed school
            $is_school_admin = $this->Muser->is_school_admin();
            if ($is_school_admin) {
                $managed_schools = $this->Muser->get_managed_schools();
                if (!empty($managed_schools) && count($managed_schools)) {
                    $arr1 = $this->Mnavigation->get_navigation($this->session->userdata('role_id'), 'link_school');
                    if ($arr1 != null && count($arr1) > 0) {
                        $nav = array(
                            'label'     => 'Pengelolaan Sekolah',
                            'nav_type'  => 'title',
                            'order_no'  => 100
                        );
                        $arr[] = $nav;
                        foreach($managed_schools as $school) {
                            $school_name = $school['group_name'];
                            $school_title = $school['group_title'];
                            //create copy
                            $arr2 = $arr1;
                            foreach($arr2 as $key=>$nav) {
                                $url = str_replace('$school_name', $school_name, $nav['url']);
                                $arr2[$key]['url'] = $url;
                                $label = str_replace('$school_title', $school_title, $nav['label']);
                                $arr2[$key]['label'] = $label;
                            }
                            $arr = array_merge($arr, $arr2);
                        }
                    }
                }
            }
    
            //add menu for classes
            $managed_classes = $this->Muser->get_managed_classes();
            if (!empty($managed_classes) && count($managed_classes)) {
                $arr1 = $this->Mnavigation->get_navigation($this->session->userdata('role_id'), 'clazz');
                if ($arr1 != null && count($arr1) > 0) {
                    $nav = array(
                        'label'     => 'Pengelolaan Kelas',
                        'nav_type'  => 'title',
                        'order_no'  => 100
                    );
                    $arr[] = $nav;
                    foreach($managed_classes as $clazz) {
                        $clazz_id = $clazz['group_id'];
                        $clazz_name = $clazz['group_name'];
                        $clazz_title = $clazz['group_title'];
                        //create copy
                        $arr2 = $arr1;
                        foreach($arr2 as $key=>$nav) {
                            $url = $nav['url'];
                            $url = str_replace('$clazz_id', $clazz_id, $url);
                            $url = str_replace('$clazz_name', $clazz_name, $url);
                            $arr2[$key]['url'] = $url;
                            $label = str_replace('$clazz_title', $clazz_title, $nav['label']);
                            $arr2[$key]['label'] = $label;
                            if (!empty($nav['page_name'])) {
                                $arr2[$key]['page_name'] .= '_'. $clazz_id;
                            }
                            //subitem
                            foreach($nav['subitems'] as $key2=>$subitem) {
                                $url = $subitem['url'];
                                $url = str_replace('$clazz_id', $clazz_id, $url);
                                $url = str_replace('$clazz_name', $clazz_name, $url);
                                $arr2[$key]['subitems'][$key2]['url'] = $url;
                                $label = str_replace('$clazz_title', $clazz_title, $subitem['label']);
                                $arr2[$key]['subitems'][$key2]['label'] = $label;
                                if (!empty($subitem['page_name'])) {
                                    $arr2[$key]['subitems'][$key2]['page_name'] .= '_'. $clazz_id;
                                }
                            }
                        }
                        $arr = array_merge($arr, $arr2);
                    }
                }
            }    
        }

        //add menu for children
        $children = $this->Muser->get_children();
        if (!empty($children) && count($children)) {
           $arr1 = $this->Mnavigation->get_navigation($this->session->userdata('role_id'), 'child');
            if ($arr1 != null && count($arr1) > 0) {
                $nav = array(
                    'label'     => 'Pengelolaan Anak',
                    'nav_type'  => 'title',
                    'order_no'  => 100
                );
                $arr[] = $nav;

                foreach($children as $child) {
                    $user_id = $child['user_id'];
                    $user_name = $child['user_name'];
                    $user_fullname = $child['lms_fullname'];

                    //create copy
                    $arr2 = $arr1;
                    foreach($arr2 as $key=>$nav) {
                        $url = $nav['url'];
                        $url = str_replace('$user_id', $user_id, $url);
                        $url = str_replace('$user_name', $user_name, $url);
                        $arr2[$key]['url'] = $url;
                        $label = str_replace('$user_fullname', $user_fullname, $nav['label']);
                        $arr2[$key]['label'] = $label;
                        if (!empty($nav['page_name'])) {
                            $arr2[$key]['page_name'] .= '_'. $user_id;
                        }
                        //subitem
                        foreach($nav['subitems'] as $key2=>$subitem) {
                            $url = $subitem['url'];
                            $url = str_replace('$user_id', $user_id, $url);
                            $url = str_replace('$user_name', $user_name, $url);
                            $arr2[$key]['subitems'][$key2]['url'] = $url;
                            $label = str_replace('$user_fullname', $user_fullname, $subitem['label']);
                            $arr2[$key]['subitems'][$key2]['label'] = $label;
                            if (!empty($subitem['page_name'])) {
                                $arr2[$key]['subitems'][$key2]['page_name'] .= '_'. $user_id;
                            }
                        }
                    }
                    $arr = array_merge($arr, $arr2);
                }
            }       
        }

		return $arr;
	}

    public function schedules() {
        $is_teacher = $this->session->userdata('is_teacher');
        $is_student = $this->session->userdata('is_student');
        $is_guardian = $this->session->userdata('is_guardian');

        if ($is_teacher) {
            $this->teacher_schedules();
        }
        else if ($is_student) {
            $this->student_schedules();
        }
        else if ($is_guardian) {
            $this->guardian_schedules();
        }
        else {
            $navigation = $this->get_navigation();
            theme_404_with_navigation($navigation);
        }
    }

    public function teacher_schedules() {
		$this->load->model(array('crud/Mpages', 'crud/Mpermission', 'crud/Mnavigation', "lms/Muser"));

		$page = $this->Mpages->get_page('my_teacher_schedules', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'teacher_schedule',
                'page_title'    => 'Jadwal Guru',
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];

		$page_data['page_role']           	 = $this->session->userdata('page_role');

        $page_data['teacher_id']  = $this->session->userdata('user_id');
        $page_data['teacher_name'] = $this->session->userdata('nama');
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        
		$template = '/lms/schedule/teacher_schedules.tpl';
		$this->smarty->render_theme($template, $page_data);

    }

    public function student_schedules() {
		$this->load->model(array('crud/Mpages', 'crud/Mnavigation'));

		$page = $this->Mpages->get_page('my_student_schedules', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'student_schedule',
                'page_title'    => 'Jadwal Siswa',
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        $name = $this->session->userdata('nama');
        $user_id = $this->session->userdata('user_id');

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];

		$page_data['page_role']           	 = $this->session->userdata('page_role');

        $page_data['student_id']  = $user_id;
        $page_data['student_name'] = $name;
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        
		$template = '/lms/schedule/student_schedules.tpl';
		$this->smarty->render_theme($template, $page_data);

    }

    public function guardian_schedules() {
		$this->load->model(array('crud/Mpages', 'crud/Mnavigation', 'lms/Muser'));

		$page = $this->Mpages->get_page('guardian_schedules', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'student_schedule',
                'page_title'    => 'Jadwal Siswa',
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];

		$page_data['page_role']           	 = $this->session->userdata('page_role');

        $page_data['student_id']  = null;
        $page_data['students'] = $this->Muser->get_children_lookup($this->session->userdata('user_id'));
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        
		$template = '/lms/schedule/student_schedules.tpl';
		$this->smarty->render_theme($template, $page_data);

    }

    public function child_schedules($user_id = 0) {
        if ($user_id == 0) {
            return $this->guardian_schedules();
        }

		$this->load->model(array('crud/Mpages', 'crud/Mnavigation', 'lms/Muser'));

		$page = $this->Mpages->get_page('child_schedules', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'student_schedule',
                'page_title'    => 'Jadwal Siswa',
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        $user = $this->Muser->get_user_profile($user_id);
        $name = $user['lms_fullname'];

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'] .'_'. $user_id;
		$page_data['page_title']             = $page['page_title'] .' - '. $name;
		$page_data['page_icon']              = $page['page_icon'];

        //var_dump($page_data);

		$page_data['page_role']           	 = $this->session->userdata('page_role');

        $page_data['student_id']  = $user_id;
        $page_data['student_name'] = $name;
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        
		$template = '/lms/schedule/student_schedules.tpl';
		$this->smarty->render_theme($template, $page_data);

    }

    public function clazz_schedules($class_id = 0) {

		$this->load->model(array('crud/Mpages', 'crud/Mnavigation', 'lms/Muser'));

		$page = $this->Mpages->get_page('class_schedules', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'class_schedule',
                'page_title'    => 'Jadwal Kelas',
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        $clazz = $this->Muser->get_class_profile($class_id);
        $name = $clazz['group_title'];

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'] .'_'. $class_id;
		$page_data['page_title']             = $page['page_title'] .' - '. $name;
		$page_data['page_icon']              = $page['page_icon'];

        $page_data['class_id']  = $class_id;
        $page_data['class_name'] = $name;

		$page_data['page_role']           	 = $this->session->userdata('page_role');
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        
		$template = '/lms/schedule/class_schedules.tpl';
		$this->smarty->render_theme($template, $page_data);

    }

    function review_submission($submission_id) {
        if (!$this->Mpermission->is_teacher()) {
            theme_403_with_navigation($this->get_navigation());		//not-authorized
			return;
        }

        if ($submission_id == 0) {
            //redirect
            redirect(site_url('user/my_teacher_assignments'));
            return;
        }

		$this->load->model(array('crud/Mpages', 'lms/assignment/Msubmissions', 'lms/assignment/Msubmissions_answers'));

        $user_id = $this->session->userdata('user_id');
        $submission = $this->Msubmissions->detail($submission_id);
        if ($submission == null || $submission['teacher_id'] !== $user_id) {
            theme_403_with_navigation($this->get_navigation());			//not-authorized
			return;
        }

        $answers = $this->Msubmissions_answers->submission_answers($submission_id);
        foreach($answers as $idx => $ans) {
            if (empty($ans['options']))     continue;

            $options = json_decode($ans['options'], true);

            if (($ans['type'] == 'option' || $ans['type'] == 'checkbox')) {
                if (empty($ans['answers'])) {
                    $arr = array();
                } else {
                    $arr = explode(',', $ans['answers']);
                }
                
                for($i=0; $i < count($options); $i++) {
                    $opt = $options[$i];

                    //append label (A/B/C/D)
                    if (empty($opt['label'])) {
                        $opt['label'] = chr(65+$i);
                    }

                    if (str_starts_with($opt['text'], '<p>')) {
                        $text = str_replace_first('<p>', '', $opt['text']);
                        $options[$i]['text'] = "<p>" .$opt['label'] .". " .$text;
                    }
                    else {
                        $options[$i]['text'] = $opt['label'] .". " .$opt['text'];
                    }
    
                    //mark answer
                    if(empty($ans['answers'])) {
                        $options[$i]['answered'] = 0;
                    }
                    else if (in_array($opt['option_id'], $arr)) {
                        $options[$i]['answered'] = 1;
                    }
                    else {
                        $options[$i]['answered'] = 0;
                    }

                    $options[$i]['value'] = $opt['option_id'];
                }
            }

            $answers[$idx]['options'] = $options;
            $answers[$idx]['options_json'] = json_encode($options, JSON_INVALID_UTF8_IGNORE);
        }

        $page = $this->Mpages->get_page('review_submissions', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'review_submission',
                'page_title'    => 'Review Tugas',
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        // $user = $this->Muser->get_user_profile($user_id);
        // $name = $user['lms_fullname'];

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];

		$page_data['page_role']           	 = $this->session->userdata('page_role');

        // $page_data['student_id']  = $user_id;
        // $page_data['student_name'] = $name;
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        $page_data['submission']     = $submission;

        $page_data['answers']        = $answers;
        $page_data['ajax']        = site_url('json/user/review_submission/' .$submission_id);   

		$template = '/lms/assignment/review.tpl';
		$this->smarty->render_theme($template, $page_data);
    }

    function answer_assignment($submission_id) {
        if (!$this->Mpermission->is_student()) {
            theme_403_with_navigation($this->get_navigation());		//not-authorized
			return;
        }

        if ($submission_id == 0) {
            //redirect
            redirect(site_url('user/student_assignments'));
            return;
        }

		$this->load->model(array('crud/Mpages', 'lms/assignment/Msubmissions', 'lms/assignment/Msubmissions_answers'));

        $user_id = $this->session->userdata('user_id');
        $submission = $this->Msubmissions->detail($submission_id);
        if ($submission == null || $submission['student_id'] !== $user_id) {
            theme_403_with_navigation($this->get_navigation());		//not-authorized
			return;
        }

        $answers = $this->Msubmissions_answers->submission_answers($submission_id);
        foreach($answers as $idx => $ans) {
            if (empty($ans['options']))     continue;

            $options = json_decode($ans['options'], true);

            if (($ans['type'] == 'option' || $ans['type'] == 'checkbox')) {
                if (empty($ans['answers'])) {
                    $arr = array();
                } else {
                    $arr = explode(',', $ans['answers']);
                }
                
                for($i=0; $i < count($options); $i++) {
                    $opt = $options[$i];

                    //append label (A/B/C/D)
                    if (empty($opt['label'])) {
                        $opt['label'] = chr(65+$i);
                    }

                    if (str_starts_with($opt['text'], '<p>')) {
                        $text = str_replace_first('<p>', '', $opt['text']);
                        $options[$i]['text'] = "<p>" .$opt['label'] .". " .$text;
                    }
                    else {
                        $options[$i]['text'] = $opt['label'] .". " .$opt['text'];
                    }
    
                    //mark answer
                    if(empty($ans['answers'])) {
                        $options[$i]['answered'] = 0;
                    }
                    else if (in_array($opt['option_id'], $arr)) {
                        $options[$i]['answered'] = 1;
                    }
                    else {
                        $options[$i]['answered'] = 0;
                    }

                    $options[$i]['value'] = $opt['option_id'];
                }
            }

            $answers[$idx]['options'] = $options;
            $answers[$idx]['options_json'] = json_encode($options, JSON_INVALID_UTF8_IGNORE);
            
            //var_dump($options);
        }

		$page = $this->Mpages->get_page('answer_assignment', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'answer_assignment',
                'page_title'    => 'Tugas ' .$submission['assign_title'],
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        // $user = $this->Muser->get_user_profile($user_id);
        // $name = $user['lms_fullname'];

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];

        //var_dump($page_data);

		$page_data['page_role']           	 = $this->session->userdata('page_role');

        // $page_data['student_id']  = $user_id;
        // $page_data['student_name'] = $name;
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        $page_data['submission']     = $submission;

        $page_data['answers']     = $answers;
        $page_data['ajax']        = site_url('json/user/answer_assignment/' .$submission_id);

        //var_dump($answers);
        
		$template = '/lms/assignment/answer.tpl';
		$this->smarty->render_theme($template, $page_data);
    }

    function review_assignment($assign_id) {
        if (!$this->Mpermission->is_teacher()) {
            theme_403_with_navigation($this->get_navigation());		//not-authorized
			return;
        }

        if ($assign_id == 0) {
            //redirect
            redirect(site_url('user/my_teacher_assignments'));
            return;
        }

		$this->load->model(array('crud/Mpages', 'lms/assignment/Massignments', 'lms/assignment/Msubmissions', 'lms/assignment/Msubmissions_answers'));

        $user_id = $this->session->userdata('user_id');
        $assign = $this->Massignments->detail($assign_id);
        if ($assign == null || $assign['teacher_id'] !== $user_id) {
            theme_403_with_navigation($this->get_navigation());			//not-authorized
			return;
        }

        $page = $this->Mpages->get_page('review_assignment', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'review_assignment',
                'page_title'    => 'Review Tugas',
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        // $user = $this->Muser->get_user_profile($user_id);
        // $name = $user['lms_fullname'];

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];

        //var_dump($page_data);

		$page_data['page_role']           	 = $this->session->userdata('page_role');

        // $page_data['student_id']  = $user_id;
        // $page_data['student_name'] = $name;
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        $page_data['assignment']     = $assign;

        $page_data['ajax']        = site_url('json/user/review_assignment/' .$assign_id);   

		$template = '/lms/assignment/review_assignment.tpl';
		$this->smarty->render_theme($template, $page_data);
    }
    
    public function assignments($param1 = null, $param2 = null) {
        $is_teacher = $this->session->userdata('is_teacher');
        $is_student = $this->session->userdata('is_student');
        $is_guardian = $this->session->userdata('is_guardian');

        $params = array();
        if (!empty($param1)) {
            $params[] = $param1;
        }
        if (!empty($param2)) {
            $params[] = $param2;
        }

        if ($is_teacher) {
            $this->table('teacher_assignments', $params);
        }
        else if ($is_student) {
            $this->table('student_assignments', $params);
        }
        else if ($is_guardian) {
            $this->guardian_assignments();
        }
        else {
            $navigation = $this->get_navigation();
            theme_404_with_navigation($navigation);
        }
    }

    // function teacher_assignments() {
    //     $this->table('teacher_assignments');
    // }

    // function student_assignments() {
    //     $this->table('student_assignments');
    // }

    function guardian_assignments() {
		$this->load->model(array('crud/Mpages', 'crud/Mnavigation', 'lms/Muser'));

		$page = $this->Mpages->get_page('guardian_assignments', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'student_assignments',
                'page_title'    => 'Tugas Sekolah Siswa',
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];

		$page_data['page_role']           	 = $this->session->userdata('page_role');

        $page_data['student_id']  = null;
        $page_data['students'] = $this->Muser->get_children_lookup($this->session->userdata('user_id'));
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        
        $model = $this->get_model_by_name('child_assignments');
		if ($model == null) {
			theme_404_with_navigation($navigation);
			return;
		}

		$tablemeta = $model->tablemeta();
        $tablemeta['initial_load'] = false;

		$ajax_url = site_url() .'json/user/child_assignments';
		$tablemeta['ajax'] = $ajax_url;
        
        $page_data['crud']			 = $tablemeta; 

		$template = '/lms/assignment/child_assignments.tpl';
		$this->smarty->render_theme($template, $page_data);
    }

    function child_assignments($student_id = 0) {
        if ($student_id == 0) {
            return $this->guardian_assignments();
        }

		$this->load->model(array('crud/Mpages', 'crud/Mnavigation', 'lms/Muser'));

		$page = $this->Mpages->get_page('child_assignments', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'student_assignments',
                'page_title'    => 'Tugas Sekolah Siswa',
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        $user = $this->Muser->get_user_profile($student_id);
        $name = $user['lms_fullname'];

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'] .'_'. $student_id;
		$page_data['page_title']             = $page['page_title'] .' - '. $name;
		$page_data['page_icon']              = $page['page_icon'];

        //var_dump($page_data);

		$page_data['page_role']           	 = $this->session->userdata('page_role');

        $page_data['student_id']  = $student_id;
        $page_data['student_name'] = $name;
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        
        $model = $this->get_model_by_name('child_assignments');
		if ($model == null) {
			theme_404_with_navigation($navigation);
			return;
		}

		$tablemeta = $model->tablemeta();
        $tablemeta['initial_load'] = true;

		$ajax_url = site_url() .'json/user/child_assignments/' .$student_id;
		$tablemeta['ajax'] = $ajax_url;
        
        $page_data['crud']			 = $tablemeta; 

		$template = '/lms/assignment/child_assignments.tpl';
		$this->smarty->render_theme($template, $page_data);
    }

    function clazz_attendances($class_id = 0) {

		$this->load->model(array('crud/Mpages', 'crud/Mnavigation', 'lms/Muser'));

		$page = $this->Mpages->get_page('class_attendances', static::$PAGE_GROUP);
        if ($page == null) {
            $page = array(
                'name'  => 'class_attendances',
                'page_title'    => 'Kehadiran',
                'page_icon'     => '',
                'page_header'   => '',
                'page_footer'   => ''
            );
        }

        $user = $this->Muser->get_class_profile($class_id);
        $name = $user['group_title'];

        //navigation
		$navigation = $this->get_navigation();

		//controller name
        $page_data['controller'] = $this->router->class;

		$page_data['page_name']              = $page['name'] .'_'. $class_id;
		$page_data['page_title']             = $page['page_title'] .' - '. $name;
		$page_data['page_icon']              = $page['page_icon'];

        //var_dump($page_data);

		$page_data['page_role']           	 = $this->session->userdata('page_role');

        $page_data['class_id']  = $class_id;
        $page_data['class_name'] = $name;
      
		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $page['header_view'];
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $page['footer_view'];
		
		//easy access
		//$page_data['page']			 = $page; 
		$page_data['navigation']	 = $navigation;
        
        $model = $this->get_model_by_name('class_attendances');
		if ($model == null) {
			theme_404_with_navigation($navigation);
			return;
		}

		$tablemeta = $model->tablemeta();

		$ajax_url = site_url() .'json/user/clazz_attendances/' .$class_id;
		$tablemeta['ajax'] = $ajax_url;
        
        $page_data['crud']			 = $tablemeta; 

		$template = '/crud/table.tpl';
		$this->smarty->render_theme($template, $page_data);

    }
}
