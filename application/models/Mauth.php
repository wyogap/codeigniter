<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mauth extends CI_Model
{
    
    /**
     * This function used to check the login credentials of the user
     * @param string $username : This is username/email of the user
     * @param string $password : This is encrypted password of the user
     */
    function login($username, $password)
    {
        $this->db->select('Users.*, Roles.role, Roles.page_role');
        $this->db->from('dbo_users as Users');
        $this->db->join('dbo_roles as Roles','Roles.role_id = Users.role_id');
        //$this->db->join('dbo_uploads as Upload','Upload.id = Users.profile_img', "LEFT OUTER");
		$this->db->group_start();
        $this->db->where('Users.email', $username);
        $this->db->or_where('Users.user_name', $username);
		$this->db->group_end();
        $this->db->where('Users.is_deleted', 0);
        $query = $this->db->get();
        
        $user = $query->row_array();
        if ($user == null)  return $user;
        
        if (password_verify($password, $user['password'])) {
            unset($user['password']);
            unset($user['created_on']);
            unset($user['created_by']);
            unset($user['updated_on']);
            unset($user['updated_by']);
            unset($user['is_deleted']);
            return $user;
        }

        return null;
    }

    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function checkEmailExist($email)
    {
        $this->db->select('user_id');
        $this->db->where('email', $email);
        $this->db->where('is_deleted', 0);
        $query = $this->db->get('dbo_users');

        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    function resetPasswordUser($data)
    {
        $result = $this->db->insert('dbo_reset_password', $data);

        if($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function getCustomerInfoByEmail($email)
    {
        $this->db->select('userId, email, name');
        $this->db->from('dbo_users');
        $this->db->where('is_deleted', 0);
        $this->db->where('email', $email);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    function checkActivationDetails($email, $activation_id)
    {
        $this->db->select('id');
        $this->db->from('dbo_reset_password');
        $this->db->where('email', $email);
        $this->db->where('activation_id', $activation_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // This function used to create new password by reset link
    function createPasswordUser($email, $password)
    {
		$data = array(
					'password'=>password_hash($password, PASSWORD_BCRYPT)
				);
				
        $this->db->where('email', $email);
        $this->db->where('is_deleted', 0);
        $this->db->update('dbo_users', $data);
		
        $this->db->where('email', $email);
        $this->db->delete('dbo_reset_password');
    }
}

?>
