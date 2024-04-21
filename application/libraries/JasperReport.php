<?php
// @codeCoverageIgnoreStart
defined('BASEPATH') || exit('No direct script access allowed');
// @codeCoverageIgnoreEnd

require 'vendor/autoload.php';
   
use PHPJasper\PHPJasper;

class JasperReport
{
    public function get($table, $id, $reporttype, $additional_params = null) {
        //return report_path (if not exist, generate one)
        $reportinfo = $this->get_report_info($table, $reporttype, $id);
        if ($reportinfo == null || empty($reportinfo['report_path'])) {
            //no data -> generate one
            $reportpath = $this->generate($table, $reporttype, $id, $additional_params);
        }
        else {
            $reportpath = $reportinfo['report_path'];
        }

        $file = FCPATH. $reportpath; 

        if (!file_exists($file)) {
            //delete existing entry first
            $this->delete_report_info($table, $reporttype, $id);
            //file not exist -> generate one
            $reportpath = $this->generate($table, $reporttype, $id, $additional_params);
        }

        return $reportpath;
    }

    public function generate($table, $reporttype, $id, $additional_params = null) {
        global $db, $active_group;

        $templateinfo = $this->get_report_template_info($table, $reporttype);
        if ($templateinfo == null) return null;

        $ext = pathinfo($templateinfo['template'], PATHINFO_EXTENSION);
        $filename = pathinfo($templateinfo['template'], PATHINFO_FILENAME);

        $jasper = new PHPJasper;

        if ($ext == 'jrxml') {
            $input = FCPATH. '/reports/template/' .$filename. '.jrxml';   
            $cmd = $jasper->compile($input)->output();

            // echo $cmd; exit;
            
            $jasper->compile($input)->execute();

            //update
            $this->update_report_template_info($table, $reporttype, $filename. '.jasper');
        }

        $templatefile = 'reports/template/' .$filename. '.jasper';
        $outputfile = 'reports/' .$this->generate_file_name($filename);
        
        //generate report
        $input = FCPATH. $templatefile;  
        $output = FCPATH. $outputfile;  
        $options = [
            'format' => ['xlsx'],
            'locale' => 'en',
        ];
        
        //report parameter
        $options['params'] = [
            'id' => $id
        ];

        //database connection
        if ($db == null) {
            $path = APPPATH;
            if (file_exists($file_path = $path.'config/'.ENVIRONMENT.'/database.php'))
            {
                include($file_path);
            }
            elseif (file_exists($file_path = $path.'config/database.php'))
            {
                include($file_path);
            }
        }

        $dbparam = null;
		if ( isset($db))
		{
            $dbparam = $db[$active_group];
		}
        if ($dbparam != null) {
            $hostname = substr($dbparam['hostname'],0, strpos($dbparam['hostname'], ":", 0));
            $port = substr($dbparam['hostname'],strpos($dbparam['hostname'], ":", 0)+1);
            if ($port == null) {
                $port = "3306";
            }
            $username = $dbparam['username'];
            $password = $dbparam['password'];
            $database = $dbparam['database'];

            $dbparam = [
                'driver' => 'mysql', 
                'username' => $username,
                'password' => $password,
                'host' => $hostname,
                'port' => $port,
                'database' => $database
            ];
        }

        if ($dbparam != null) {
            $options['db_connection'] = $dbparam;
        }

        // $cmd = $jasper->process($input, $output, $options)->output();
        // echo $cmd; exit;

        $jasper->process(
                $input,
                $output,
                $options
        )->execute();
        
        //save
        $this->save_report_info($table, $reporttype, $id, $templatefile, $outputfile .".pdf");

        return $outputfile .".pdf";
    }

    protected function get_report_template_info($table, $reporttype) {
        $filters = [
            'table' => $table,
            'type' => $reporttype
        ];

        $ci =& get_instance();
        $ci->db->select('*');
        $ci->db->where($filters);
        $query = $ci->db->get('dbo_report_templates');
        if ($query == null) return null;

        return $query->row_array();
    }

    protected function update_report_template_info($table, $reporttype, $templatefile) {
        $ext = pathinfo($templatefile, PATHINFO_EXTENSION);
        $filename = pathinfo($templatefile, PATHINFO_FILENAME);
        $template = $filename. ".". $ext;

        $valuepair = [
            'template' => $template
        ];

        $filters = [
            'table' => $table,
            'type' => $reporttype
        ];

        $ci =& get_instance();
        $ci->db->update('dbo_report_templates', $valuepair, $filters);

        return 1;
    }

    protected function get_report_info($table, $reporttype, $id) {
        $filters = [
            'ref_table' => $table,
            'ref_id' => $id
        ];

        if ($reporttype!=null) {
            $filters['type'] = $reporttype;
        }

        $ci =& get_instance();
        $ci->db->select('*');
        $ci->db->where($filters);
        $query = $ci->db->get('dbo_reports');
        if ($query == null) return null;

        return $query->row_array();
    }

    protected function delete_report_info($table, $reporttype, $id) {
        $filters = [
            'ref_table' => $table,
            'ref_id' => $id,
            'type' => $reporttype
        ];

        $ci =& get_instance();
        $ci->db->delete('dbo_reports', $filters);

        return 1;
    }

    protected function save_report_info($table, $reporttype, $id, $templatefile, $outputfile) {
        $ext = pathinfo($templatefile, PATHINFO_EXTENSION);
        $filename = pathinfo($templatefile, PATHINFO_FILENAME);
        $template = $filename. ".". $ext;

        $valuepair = [
            'type' => $reporttype,
            'template' => $template,
            'report_path' => $outputfile,
            'ref_table' => $table,
            'ref_id' => $id
        ];

        $ci =& get_instance();
        $ci->db->insert('dbo_reports', $valuepair);

        $id = $ci->db->insert_id();

        return $id;
    }

    function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    function generate_token($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
        }

        return $token;
    }

    function generate_file_name($filename) {
        $ci =& get_instance();
        $user_id = $ci->session->userdata('user_id');

        return $user_id ."_". time() ."_". $this->generate_token(10);
    }

}