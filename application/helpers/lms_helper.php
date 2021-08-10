<?php 
    /**
	 * get_picture
	 * 
	 * @return string
	 */
	if ( ! function_exists('get_picture'))
	{
		function get_picture($picture, $type = 'other') {
			if($picture == "") {
				switch ($type) {
					case 'male':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_profile_male.jpg';
						break;
					
					case 'female':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_profile_female.jpg';
						break;
		
					case 'other':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_profile_other.jpg';
						break;
		
					case 'page':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_page.jpg';
						break;
		
					case 'group':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_group.jpg';
						break;
		
					case 'event':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_event.jpg';
						break;
		
					case 'article':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_article.jpg';
						break;
		
					case 'movie':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_movie.jpg';
						break;
		
					case 'game':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_game.jpg';
						break;
		
					case 'package':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_package.png';
						break;
		
					case 'flag':
						$picture = FRONTEND_URL.'/content/themes/'.FRONTEND_THEME.'/images/blank_flag.png';
						break;
				}
			} else {
				$picture = UPLOAD_URL.'/'.$picture;
			}
			return $picture;
		}
	}

	if ( ! function_exists('db_get'))
	{
		function db_get($sql) {
            $ci	=&	get_instance();
            $arr = $ci->db->query($sql)->result_array();
            if ($arr == null)   return array();
            return $arr;
        }
    }

	if ( ! function_exists('db_get_single'))
	{
		function db_get_single($sql) {
            $ci	=&	get_instance();
            $arr = $ci->db->query($sql)->row_array();
            if ($arr == null)   return array();
            return $arr;
        }
    }

	if ( ! function_exists('add_group_member'))
	{
		function add_group_member($group_id, $user_id) {
            $ci	=&	get_instance();
    
            //add as member
            if (!is_group_member($group_id, $user_id))
                $ci->db->insert('groups_members', array('group_id'=>$group_id, 'user_id'=>$user_id, 'approved'=>'1'));
        }
    }

	if ( ! function_exists('add_group_admin'))
	{
		function add_group_admin($group_id, $user_id) {
            $ci	=&	get_instance();
    
            //add as member
            if (!is_group_member($group_id, $user_id))
                $ci->db->insert('groups_members', array('group_id'=>$group_id, 'user_id'=>$user_id, 'approved'=>'1'));
    
            //add as admin
            if (!is_group_admin($group_id, $user_id))
                $ci->db->insert('groups_admins', array('group_id'=>$group_id, 'user_id'=>$user_id));
        }
    }

	if ( ! function_exists('remove_group_member'))
	{
		function remove_group_member($group_id, $user_id) {
            $ci	=&	get_instance();
    
            //remove as member
            $ci->db->delete('groups_members', array('group_id'=>$group_id, 'user_id'=>$user_id));
    
            //remove as admins
            $ci->db->delete('groups_admins', array('group_id'=>$group_id, 'user_id'=>$user_id));
        }
    }

	if ( ! function_exists('remove_group_admin'))
	{
		function remove_group_admin($group_id, $user_id) {
            $ci	=&	get_instance();
    
            //remove as admins
            $ci->db->delete('groups_admins', array('group_id'=>$group_id, 'user_id'=>$user_id));
        }
    }

	if ( ! function_exists('remove_page_admin'))
	{
		function remove_page_admin($page_id, $user_id) {
            $ci	=&	get_instance();
    
            //remove as admins
            $ci->db->delete('pages_admins', array('page_id'=>$page_id, 'user_id'=>$user_id));
        }
    }

    global $school_id, $school_profile, $school_membership;

    $school_id = 0;
    $school_profile = array();
    $school_membership = array();
    
    // if ( ! function_exists('get_model'))
    // {
    // 	function get_model($path) {
    // 		$CI	=&	get_instance();
    // 		$CI->load->model($path);
    
    // 		$name = basename($path);
    // 		return $CI->$name;
    // 	}
    // }
    
    if ( ! function_exists('set_current_school'))
    {
        function set_current_school($group_name) {
            global $school_id, $school_profile, $school_membership;
    
            if (!empty($group_name)) {
                //get group info
                $ci	=&	get_instance();
                $ci->load->model("lms/Mschools");
                $school_profile = $ci->Mschools->get_school_profile_from_group_name($group_name);
                if ($school_profile != null && count($school_profile) > 0 && $school_id != $school_profile['group_id']) {
                    $school_id = $school_profile['group_id'];
                    $school_membership = $ci->Mschools->get_school_membership($school_id);
                }
            }
            else {
                $school_id = 0;
                $school_profile = array();
                $school_membership = array();
            }
        }
    }
    
    if ( ! function_exists('get_current_school_id'))
    {
        function get_current_school_id() {
            global $school_id;
            return $school_id;
        }
    }
    
    if ( ! function_exists('get_current_school_name'))
    {
        function get_current_school_name() {
            global $school_profile;
            return isset($school_profile['group_name']) ? $school_profile['group_name'] : '';
        }
    }
    
    if ( ! function_exists('get_current_school_title'))
    {
        function get_current_school_title() {
            global $school_profile;
            return isset($school_profile['group_title']) ? $school_profile['group_title'] : '';
        }
    }
    
    if ( ! function_exists('check_current_school_manage_permission'))
    {
        function check_current_school_manage_permission() {
            global $school_membership;
    
            return is_admin_current_school() || is_teacher_current_school();
        }
    }
    
    if ( ! function_exists('is_admin_current_school'))
    {
        function is_admin_current_school() {
            global $school_membership;
            return !empty($school_membership['lms_is_group_admin']);
        }
    }
    
    if ( ! function_exists('is_teacher_current_school'))
    {
        function is_teacher_current_school() {
            global $school_membership;
            return !empty($school_membership['lms_is_teacher']);
        }
    }
    
    if ( ! function_exists('is_student_current_school'))
    {
        function is_student_current_school() {
            global $school_membership;
            return !empty($school_membership['lms_is_student']);
        }
    }
    
    if ( ! function_exists('is_guardian_current_school'))
    {
    
        function is_guardian_current_school() {
            global $school_membership;
            return !empty($school_membership['lms_is_guardian']);
        }
    }
    
    if ( ! function_exists('make_group_member'))
    {
        function make_group_member($group_id, $user_id) {
            $ci	=&	get_instance();
    
            //add as member
            if (!is_group_member($group_id, $user_id))
                $ci->db->insert('groups_members', array('group_id'=>$group_id, 'user_id'=>$user_id, 'approved'=>'1'));
        }
    }

    if ( ! function_exists('make_group_admin'))
    {
        function make_group_admin($group_id, $user_id) {
            $ci	=&	get_instance();
    
            //add as member
            if (!is_group_member($group_id, $user_id))
                $ci->db->insert('groups_members', array('group_id'=>$group_id, 'user_id'=>$user_id, 'approved'=>'1'));
    
            //add as admin
            if (!is_group_admin($group_id, $user_id))
                $ci->db->insert('groups_admins', array('group_id'=>$group_id, 'user_id'=>$user_id));
        }
    }
    
    if ( ! function_exists('make_page_admin'))
    {
        function make_page_admin($page_id, $user_id) {
            $ci	=&	get_instance();
    
            //add as admin
            if (!is_page_admin($page_id, $user_id))
                $ci->db->insert('pages_admins', array('page_id'=>$page_id, 'user_id'=>$user_id));
        }
    }
    
    if ( ! function_exists('is_page_admin'))
    {
        function is_page_admin($page_id, $user_id) {
            $ci	=&	get_instance();
    
            //add as member
            $sql = "select count(*) as cnt from pages_admins where page_id=? and user_id=?";
            $arr = $ci->db->query($sql, array($page_id, $user_id))->row_array();
    
            return ($arr['cnt'] > 0);
        }
    }
    
    if ( ! function_exists('is_group_admin'))
    {
        function is_group_admin($group_id, $user_id) {
            $ci	=&	get_instance();
    
            //add as member
            $sql = "select count(*) as cnt from groups_admins where group_id=? and user_id=?";
            $arr = $ci->db->query($sql, array($group_id, $user_id))->row_array();
    
            return ($arr['cnt'] > 0);
        }
    }
    
    if ( ! function_exists('is_group_member'))
    {
        function is_group_member($group_id, $user_id) {
            $ci	=&	get_instance();
    
            //add as member
            $sql = "select count(*) as cnt from groups_members where group_id=? and user_id=?";
            $arr = $ci->db->query($sql, array($group_id, $user_id))->row_array();
    
            return ($arr['cnt'] > 0);
        }
    }
    
    if ( ! function_exists('update_group_member_count'))
    {
        function update_group_member_count($group_id) {
    
            $sql = 'update `groups` set group_members = (select count(*) from groups_members where group_id=?) where group_id=?';
    
            $ci	=&	get_instance();
            $ci->db->query($sql, array($group_id, $group_id));
    
        }
    }
    
    if ( ! function_exists('refresh_user_school_role'))
    {
        function refresh_user_school_role($user_id) {
            $user_id = secure($user_id, 'int');
    
            $sql = "select m.user_id, max(lms_is_group_admin) as lms_is_group_admin, max(lms_is_teacher) as lms_is_teacher, max(lms_is_student) as lms_is_student, max(lms_is_guardian) as lms_is_guardian
                from groups s
                join groups_members m on m.group_id=s.group_id
                where s.is_deleted=0 and m.user_id=$user_id
                group by m.user_id";
    
            $ci	=&	get_instance();
            $arr = $ci->db->query($sql)->row_array();
            if($arr != null) {
                $ci->session->set_userdata('is_school_admin', empty($arr['lms_is_group_admin']) ? 0 : 1);
                $ci->session->set_userdata('is_teacher', empty($arr['lms_is_teacher']) ? 0 : 1);
                $ci->session->set_userdata('is_student', empty($arr['lms_is_student']) ? 0 : 1);
                $ci->session->set_userdata('is_guardian', empty($arr['lms_is_guardian']) ? 0 : 1);
            }
        }
    }
    
    if ( ! function_exists('get_uploaded_url'))
    {
        function get_uploaded_url($path) {
            return UPLOAD_URL .'/'. $path;
        }
    }
    
    if ( ! function_exists('get_uploaded_path'))
    {
        function get_uploaded_path($path) {
            return UPLOAD_PATH .'/'. $path;
        }
    }
?>