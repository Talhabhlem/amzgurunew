<?php

namespace App\Helpers;

use App\Sale;

//use Askedio\Laravelcp\Models\User;
//use Askedio\Laravelcp\Models\Role;
//use Askedio\Laravelcp\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class TeHelper
{
    public static function te_set_default_timezone()
    {
        $CI = &get_instance();
        $cuser = $CI->session->userdata('user');
        date_default_timezone_set($cuser['timezone']);
    }

    public static function side_nav()
    {
        $cuser = \Auth::user();
        if ($cuser->role == 'admin') {
            $users = array();
            $users['title'] = 'Users';
            $users['slug'] = 'admin/users';
            $users['link'] = url('/admin/users');
            $users['icon'] = 'fa fa-users';
            $users['nav'] = 'main';
            $sidebarMainNav = [
                'users' => $users,
            ];
        }else {
            $email_alert = array();
            $email_alert['title'] = 'Email Alerts';
            $email_alert['slug'] = 'settings/email';
            $email_alert['link'] = url('settings/email');
            $email_alert['icon'] = 'fa fa-bell';
            $email_alert['nav'] = 'main';

            $manage_event = array();
            $manage_event['title'] = 'Manage Events';
            $manage_event['slug'] = 'events';
            $manage_event['link'] = url('events');
            $manage_event['icon'] = 'fa fa-calendar';
            $manage_event['nav'] = 'main';

            $sale_analysis = array();
            $sale_analysis['title'] = 'Sales Analysis';
            $sale_analysis['slug'] = 'analysis';
            $sale_analysis['link'] = url('analysis');
            $sale_analysis['icon'] = 'fa fa-area-chart';
            $sale_analysis['nav'] = 'main';

            $sidebarMainNav = [
                'email_alert' => $email_alert,
                'manage_event' => $manage_event,
                'sale_analysis' => $sale_analysis,
            ];
        }
        return $sidebarMainNav;
    }

    public static function currentSlug()
    {
        $currentnav = \Illuminate\Support\Facades\Request::segment(TeHelper::te_segment('current_slug'));
        return $currentnav;
    }

    public static function te_segment($index = '')
    {
        $segments = [
            'current_slug' => 1,
        ];

        if (empty($index)) {
            return $segments;
        } else {
            if (isset($segments[$index])) {
                return $segments[$index];
            }
        }
        return '';
    }

    public static function te_email($args = array())
    {
        $CI = &get_instance();
        $CI->load->config('email');
        $config['protocol'] = 'smtp';
        //$config['protocol'] = 'sendmail';
        //$config['smtp_host'] = 'localhost';
        //$config['validate'] = 'TRUE';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html'; // or html
        $CI->load->library('email', $config); // load library
        //$CI->email->set_newline("\n","\n");
        $from_email = $CI->config->item('te_from_email');
        $from_name = $CI->config->item('te_from_name');
        $CI->email->from($from_email, $from_name);
        $CI->email->to($args['to']);
        $CI->email->subject($args['subject']);
        $CI->email->message($args['message']);
        if ($CI->email->send()) {
            return true;
        } else {
            echo $CI->email->print_debugger();
            return false;
        }
    }

    public static function p_rr($arr = array())
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }

    public static function p_exit($arr = array())
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        exit;
    }

    public static function te_rating($points = 0, $total = 5)
    {
        $rating_html = '';
        for ($i = 1; $i <= $total; $i++) {

            if ($points < $i) {
                $rating_html .= '<img src="' . base_url('application/assets/img/grey_star24.png') . '" class="star"/>';
            } else {
                $rating_html .= '<img src="' . base_url('application/assets/img/yellow_star24.png') . '" class="star"/>';
            }
        }
        return $rating_html;
    }

    public static function te_time($t, $f = ' ')
    {

        //return sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
        $h = floor($t / 3600);
        $m = ($t / 60) % 60;
        $s = $t % 60;

        $h = strlen($h) < 2 ? "0" . $h : $h;
        $m = strlen($m) < 2 ? "0" . $m : $m;
        $s = strlen($s) < 2 ? "0" . $s : $s;

        return ($h != 0 ? $h . "h" . $f : '') . ($m != 0 ? $m . "m" . $f : '') . ($s != 0 ? $s . "s" : '');
    }

    public static function seconds_to_dhms($seconds)
    {
        $ret = array();
        $divs = array(86400, 3600, 60, 1);
        for ($d = 0; $d < 4; $d++) {
            $q = $seconds / $divs[$d];
            $r = $seconds % $divs[$d];
            $ret[substr('dhms', $d, 1)] = floor($q);

            $seconds = $r;
        }
        return $ret;
    }

    public static function te_abspath($path = '')
    {
        $abs_path = str_replace('system/', $path, BASEPATH);
        $abs_path = preg_replace("#([^/])/*$#", "\\1/", $abs_path);

        return $abs_path;
    }

    public static function te_create_pagination($total, $page, $per_page = 10, $url = false)
    {


        $adjacents = "2";

        $page = ($page == 0 ? 1 : $page);
        $start = ($page - 1) * $per_page;

        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total / $per_page);
        $lpm1 = $lastpage - 1;

        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<ul class='pagination'>";
            $pagination .= "<li class='details'><a>Page $page of $lastpage</a></li>";
            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}pageno=$counter'>$counter</a></li>";
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination .= "<li><a href='{$url}pageno=$counter'>$counter</a></li>";
                    }
                    $pagination .= "<li class='dot'><a>...</a></li>";
                    $pagination .= "<li><a href='{$url}pageno=$lpm1'>$lpm1</a></li>";
                    $pagination .= "<li><a href='{$url}pageno=$lastpage'>$lastpage</a></li>";
                } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<li><a href='{$url}pageno=1'>1</a></li>";
                    $pagination .= "<li><a href='{$url}pageno=2'>2</a></li>";
                    $pagination .= "<li class='dot'><a>...</a></li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination .= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                    }
                    $pagination .= "<li class='dot'><a>...</a></li>";
                    $pagination .= "<li><a href='{$url}pageno=$lpm1'>$lpm1</a></li>";
                    $pagination .= "<li><a href='{$url}pageno=$lastpage'>$lastpage</a></li>";
                } else {
                    $pagination .= "<li><a href='{$url}pageno=1'>1</a></li>";
                    $pagination .= "<li><a href='{$url}pageno=2'>2</a></li>";
                    $pagination .= "<li class='dot'><a>...</a></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination .= "<li><a href='{$url}pageno=$counter'>$counter</a></li>";
                    }
                }
            }

            if ($page < $counter - 1) {
                $pagination .= "<li><a href='{$url}pageno=$next'>Next</a></li>";
                $pagination .= "<li><a href='{$url}pageno=$lastpage'>Last</a></li>";
            } else {
                $pagination .= "<li><a class='current'>Next</a></li>";
                $pagination .= "<li><a class='current'>Last</a></li>";
            }
            $pagination .= "</ul>\n";
        }


        return $pagination;
    }

    public static function te_create_pagination_ajax($total, $page, $per_page = 10, $url = false)
    {

        $adjacents = "1";

        $page = ($page == 0 ? 1 : $page);
        $start = ($page - 1) * $per_page;

        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total / $per_page);
        $lpm1 = $lastpage - 1;

        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<ul class='pagination'>";
            $pagination .= "<li class='details'><a>Page $page of $lastpage</a></li>";
            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class='active'><a>$counter</a></li>";
                    else
                        $pagination .= "<li><a class='pageno' data-pageno='{$counter}' href='javascript:void(0)'>$counter</a></li>";
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='active'><a>$counter</a></li>";
                        else
                            $pagination .= "<li><a class='pageno' data-pageno='{$counter}' href='javascript:void(0)'>$counter</a></li>";
                    }
                    $pagination .= "<li class='dot'><a>...</a></li>";
                    $pagination .= "<li><a class='pageno' data-pageno='{$lpm1}' href='javascript:void(0)'>$lpm1</a></li>";
                    $pagination .= "<li><a class='pageno' data-pageno='{$lastpage}' href='javascript:void(0)'>$lastpage</a></li>";
                } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<li><a class='pageno' data-pageno='1' href='javascript:void(0)'>1</a></li>";
                    $pagination .= "<li><a class='pageno' data-pageno='2' href='javascript:void(0)'>2</a></li>";
                    $pagination .= "<li class='dot'><a>...</a></li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li  class='active'><a>$counter</a></li>";
                        else
                            $pagination .= "<li><a class='pageno' data-pageno='{$counter}' href='javascript:void(0)'>$counter</a></li>";
                    }
                    $pagination .= "<li class='dot'><a>...</a></li>";
                    $pagination .= "<li><a class='pageno' data-pageno='{$lpm1}' href='javascript:void(0)'>$lpm1</a></li>";
                    $pagination .= "<li><a class='pageno' data-pageno='{$lastpage}' href='javascript:void(0)'>$lastpage</a></li>";
                } else {
                    $pagination .= "<li><a class='pageno' data-pageno='1' href='javascript:void(0)'>1</a></li>";
                    $pagination .= "<li><a class='pageno' data-pageno='2' href='javascript:void(0)'>2</a></li>";
                    $pagination .= "<li class='dot'><a>...</a></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='active'><a>$counter</a></li>";
                        else
                            $pagination .= "<li><a class='pageno' data-pageno='{$counter}' href='javascript:void(0)'>$counter</a></li>";
                    }
                }
            }

            if ($page < $counter - 1) {
                $pagination .= "<li><a class='pageno' data-pageno='{$next}' href='javascript:void(0)'>Next</a></li>";
                $pagination .= "<li><a class='pageno' data-pageno='{$lastpage}' href='javascript:void(0)'>Last</a></li>";
            } else {
                $pagination .= "<li class='disabled'><a class=''>Next</a></li>";
                $pagination .= "<li class='disabled'><a class=''>Last</a></li>";
            }
            $pagination .= "</ul>\n";
        }


        return $pagination;
    }

    public static function ecomm_generate_site_title($slug = '')
    {

        $current_page = Request::segment(1);
        if ($slug == '') {
            $slug = $current_page;
        }
        $sym = array("-", "_");
        $slug = str_replace($sym, " ", trim($slug));
        if ($slug == '')
            return 'EcommElite';
        $title = ucwords(strtolower($slug)) . ' - EcommElite';
        return $title;
    }


    public static function __ajax_check_login()
    {

        $CI = &get_instance();
        $userdata = $CI->session->userdata('user');

        if (empty($userdata)) {

            $response['status'] = 'loginfail';
            $response['lref'] = @urlencode($_SERVER['HTTP_REFERER']);
            $response['error'] = "Your session has been expired. Please login again.";
            te_add_notification('danger', 'Your session has been expired. Please login again.');

            echo json_encode($response);
            exit;
        } else {
            return $userdata;
        }
    }

    public static function ecomm_authenticate($roles = 'enterprise')
    {

        $CI =& get_instance();
        $userdata = $CI->session->userdata('user');

        if (empty($userdata)) {
            $CI->load->helper('url');
            te_add_notification('danger', 'Your session has been expired. Please login again.');
            redirect('/login?lref=' . urlencode(current_url()), 'refresh');
            exit;

        } else {

            if (in_array($userdata['role'], explode(',', $roles))) {
                return $userdata;
            } else {
                $CI->load->helper('url');
                te_add_notification('danger', 'Your have not permission to access this page.');
                redirect('/', 'refresh');
                exit;
            }

        }

    }

    public static function ecomm_authenticate2()
    {

        $CI =& get_instance();
        $userdata = $CI->session->userdata('user');

        if (!empty($userdata)) {
            $CI->load->helper('url');
            if ($userdata['role'] == 'admin') {
                redirect('/admin', 'refresh');
            } else {
                redirect('/analysis', 'refresh');
            }

            exit;

        }

    }


    public static function te_datetime($format, $datetime = '')
    {

        $datetime = $datetime == '' ? date('Y-m-d H:i:s') : $datetime;
        return date($format, strtotime($datetime));
    }

    public static function te_app_icon_path()
    {
        $path['upload_dir_path'] = te_abspath('assets/uploads/appicons/');
        $path['upload_dir_url'] = base_url('assets/uploads/appicons/');
        return $path;
    }

    public static function te_profile_path()
    {
        $path['upload_dir_path'] = te_abspath('assets/uploads/avatars/');
        $path['upload_dir_url'] = base_url('assets/uploads/avatars/');
        return $path;
    }


    public static function te_documents_path()
    {
        $path['upload_dir_path'] = te_abspath('assets/uploads/documents/');
        $path['upload_dir_url'] = base_url('assets/uploads/documents/');
        return $path;
    }


    public static function te_flush()
    {
        echo '<!-- -->'; // ?

        ob_flush();
        flush();
    }

    public static function _fonts()
    {
        $fonts = array();
        $fonts['Arial'] = 'Arial';
        $fonts['Calibri'] = 'Calibri';
        $fonts['Georgia'] = 'Georgia';
        $fonts['Tahoma'] = 'Tahoma';
        $fonts['Times_New_Roman'] = 'Times New Roman';
        $fonts['Verdana'] = 'Verdana';

        $fonts['SourceCodePro'] = "Source Code Pro";
        $fonts['Exo'] = "Exo";
        $fonts['PlayfairDisplaySC'] = "Playfair Display SC";
        $fonts['Roboto'] = "Roboto";
        $fonts['SourceCodePro'] = "Source Code Pro";

        return $fonts;
    }

    public static function _font_sizes($start = 6, $end = 50)
    {
        $fontsizes = array();
        for ($i = $start; $i <= $end; $i++) {
            $fontsizes[$i] = $i . 'px';
        }
        return $fontsizes;
    }

    public static function _text_alignments()
    {
        $fonts = array();
        $fonts['left'] = 'Left';
        $fonts['center'] = 'Center';
        $fonts['right'] = 'Right';
        $fonts['justify'] = 'Justify';

        return $fonts;
    }

    public static function te_merge_default_args($arr, $def)
    {

        foreach ($def as $key => $val) {
            if (!array_key_exists($key, $arr))
                $arr[$key] = $val;
        }
        return $arr;
    }

    public static function te_add_notification($type, $message = '', $heading = '')
    {

        $key = 'te_notifications';

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = array();
        }

        if ($type != '' && $message != '') {
            $_SESSION[$key][] = array('type' => $type, 'msg' => $message, 'heading' => $heading);
        }
    }

    public static function te_notifications($echo = true)
    {

        $key = 'te_notifications';
        if (isset($_SESSION[$key])) {
            $notifications_html = '';
            $notifications = $_SESSION[$key];
            $notifications_html .= count($notifications) > 0 ? '<section class="container te-notification-area">' : '';
            foreach ($notifications as $n) {
                ob_start();
                ?>
                <div
                        class="alert alert-<?php echo $n['type']; ?> alert-block alert-dismissable fade in te_animated2  te_flipOutX2">
                    <button data-dismiss="alert" type="button" class="close">×</button>
                    <?php echo $n['heading'] == '' ? '' : '<h4 class="alert-heading">' . $n['heading'] . '</h4>'; ?>
                    <p><?php echo $n['msg']; ?></p>
                </div>
                <?php
                $notifications_html .= ob_get_clean();
            }
            $notifications_html .= count($notifications) > 0 ? '</section>' : '';
            unset($_SESSION[$key]);
            if ($echo) {
                echo $notifications_html;
            } else {
                return $notifications_html;
            }
        }
    }

    public static function te_tables()
    {

        $tables['users'] = 'users';
        $tables['reset'] = 'reset_password_tokens';
        $tables['order'] = 'pending_orders';
        $tables['sales'] = 'sales';
        $tables['profit'] = 'sku_cost';
        $tables['activity'] = 'activity';
        $tables['keyword'] = 'keyword';
        $tables['keyword_rank'] = 'keyword_rank';
        $tables['api_settings'] = 'api_settings';
        $tables['events'] = 'events';

        return $tables;
    }


    public static function time_differenece($time_1, $time_2)
    {
        $date1 = new DateTime($time_1);
        $date2 = new DateTime($time_2);
        $interval = $date1->diff($date2);
        if ($interval->invert != 0) {
            if ($interval->y != 0) {
                return $interval->y . ' years';
            } elseif ($interval->m != 0) {
                return $interval->m . ' months';
            } elseif ($interval->h != 0) {
                return $interval->i . ' hours';
            } elseif ($interval->i != 0) {
                return $interval->i . ' minuts';
            } else {
                return $interval->s . ' seconds';
            }
        }
    }


    public static function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }


    public static function obj_to_array($obj)
    {
        $array = array();
        foreach ($obj as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }

    public static function str_to_array($string)
    {
        $array = array();
        parse_str($string, $array);
        return $array;
    }

    public static function te_smtp_email($args = array())
    {

        return true;
        $CI =& get_instance();

        $CI->load->config('email');

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.sendgrid.net',
            'smtp_port' => 587,
            'smtp_user' => 'ecommelite',
            'smtp_pass' => 'todd2473',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => true,
            'wrapchars' => 50,
            'crlf' => "\r\n",
            'newline' => "\r\n"
        );
        $CI->load->library('email', $config); // load library
        $CI->email->set_newline("\r\n");
        $CI->email->from($CI->config->item('te_from_email'), $CI->config->item('te_from_name'));

        // $CI->email->from('admin@Metroryde.com', 'MetroRyde');
        $CI->email->to($args['to']);
        $CI->email->subject($args['subject']);
        $CI->email->message($args['message']);

        if ($CI->email->send()) {
            //echo $CI->email->print_debugger();
            return true;
        } else {
            //echo $CI->email->print_debugger();
            return false;
        }
    }

    public static function model_load_model($model_name)
    {
        $CI =& get_instance();
        $CI->load->model($model_name);
        return $CI->$model_name;
    }

    public static function te_change_timezone($date_time, $to_tz)
    {
        $timezone_same = false;
        if (($to_tz == 'America/Los_Angeles' || $to_tz == 'PST8PDT') &&
            (date_default_timezone_get() == 'PST8PDT' || date_default_timezone_get() == 'America/Los_Angeles')
        ) {
            date_default_timezone_set('UTC');
            $timezone_same = true;
        }

        $returnDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date_time)->timezone($to_tz);

        if ($timezone_same) {
            date_default_timezone_set('America/Los_Angeles');
        }
        return $returnDate . "";
    }

    public static function te_dateDiffHoursMins($date1timestamp, $date2timestamp)
    {
        $all = round(($date1timestamp - $date2timestamp) / 60);
        $d = floor($all / 1440);
        $h = floor(($all - $d * 1440) / 60);
        $m = $all - ($d * 1440) - ($h * 60);

        return array('d' => $d, 'h' => $h, 'm' => $m);
    }


    public static function get_sale_dates($user, $data = array(), $change_timezone = true)
    {
        $sale_dates = Sale::get_sale_date($user->id, 'new');
        $dates = array('new' => '', 'old' => '');

        foreach ($sale_dates as $date) {
            $dates[$date->sale_type] = array($date->first_date, $date->latest_date);
        }

        if (empty($dates['old'])) {
            $dates['old'] = $dates['new'];
        }


//        if(count($data)>0) {
//            return isset($data[0]['first_date']) && $data[0]['first_date']!='' ? $data[0]['first_date']:'2000-01-01 00:00:00';
//        } else {
//            return '2000-01-01 00:00:00';
//        }


        if (empty($dates['new']) || empty($dates['new'][0]))
            $dates['new'][0] = date('Y-m-d', strtotime($user->created_at) - 60 * 24 * 50 * 60) . ' 00:00:00';
        if (empty($dates['new']) || empty($dates['new'][1]))
            $dates['new'][1] = date('Y-m-d', strtotime($user->created_at) - 60 * 24 * 50 * 60) . ' 00:00:00';

        if (empty($dates['old']) || empty($dates['old'][0]))
            $dates['old'][0] = '2000-01-01 00:00:00';

        if (empty($dates['old']) || empty($dates['old'][1]))
            $dates['old'][1] = '2000-01-01 00:00:00';

        if ($change_timezone) {
            // echo "in if";exit;
            $data['new_first'] = TeHelper::te_change_timezone($dates['new'][0], 'PST8PDT');
            $data['new_latest'] = TeHelper::te_change_timezone($dates['new'][1], 'PST8PDT');
            $data['old_first'] = TeHelper::te_change_timezone($dates['old'][0], 'PST8PDT');
            $data['old_latest'] = TeHelper::te_change_timezone($dates['old'][1], 'PST8PDT');
        } else {
            // echo "in else";
            // exit;
            $data['new_first'] = $dates['new'][0];
            $data['new_latest'] = $dates['new'][1];
            $data['old_first'] = $dates['old'][0];
            $data['old_latest'] = $dates['old'][1];
        }
        $new_date = date('Y-m-d H:i:s', strtotime($data['new_first']));
        $get_above_row_from_first_new = Sale::get_above_row_from_first_new($user->id, $new_date);
        if (!isset($get_above_row_from_first_new))
            $data['above_from_new_row']['purchase_date'] = '0000-00-00 00:00:00';
        else
            $data['above_from_new_row']['purchase_date'] = TeHelper::te_change_timezone($get_above_row_from_first_new, 'PST8PDT');
        return $data;
    }

}