<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Helper
{

    public function number_format_short($n, $precision = 1)
    {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        }
        else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        }
        else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        }
        else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        }
        else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }
        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }
        return $n_format . $suffix;
    }



    public function skip_zero_array_filter($var)
    {
        return ($var !== NULL && $var !== FALSE && $var !== '');
    }



    public function generate_date_range($start, $end, $step = '+1 day', $format = 'Y-m-d')
    {
        $dates = [];

        $current = strtotime($start);
        $last = strtotime($end);

        while ($current <= $last) {

            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }



    public function calculate_tax($item_total, $tax_percentage)
    {
        $tax_amount = ($tax_percentage / 100) * $item_total;
        return $tax_amount;
    }


    public function count_days($start, $end)
    {
        $datetime1 = date_create($start);
        $datetime2 = date_create($end);
        $interval = date_diff($datetime1, $datetime2);
        $date_differ = $interval->format('%a');


        return $date_differ;
    }

    public function set_user_session($user, $token)
    {
        session()->put('firstname', $user->firstname);
        session()->put('lastname', $user->lastname);
        session()->put('profile_image', $user->profile_image);
        session()->put('slug', $user->slug);
        session()->put('user_id', $user->id);
        session()->put('role', $user->role_id);
        session()->put('initial_link', $user->initial_link);
        session()->put('access_token', $token);
    //Session::save();
    }

    public function check_user_session($user, $token)
    {
        $user_slack = session('slack');
        if ($user_slack != "") {
            return true;
        }
        else {
            return false;
        }
    }

    function generate_slack($table)
    {
        do {
            $slack = str_random(25);
            $exist = DB::table($table)->where("slack", $slack)->first();
        } while ($exist);
        return $slack;
    }

    function generate_response($response_array, $type = "")
    {
        switch ($type) {
            case "SUCCESS":
                $status_code = 200;
                break;
            case "NOT_AUTHORIZED":
                $status_code = 401;
                break;
            case "NO_ACCESS":
                $status_code = 403;
                break;
            case "BAD_REQUEST":
                $status_code = 400;
                break;
            default:
                $status_code = 200;
                break;
        }
        $response = array(
            'status' => true,
            'msg' => (isset($response_array['message'])) ? $response_array['message'] : "",
            'data' => (isset($response_array['data'])) ? $response_array['data'] : "",
            'status_code' => (isset($response_array['status_code'])) ? $response_array['status_code'] : $status_code
        );
        if (isset($response_array['link'])) {
            $response = array_merge($response, array("link" => $response_array['link']));
        }
        if (isset($response_array['new_tab'])) {
            $response = array_merge($response, array("new_tab" => $response_array['new_tab']));
        }
        return $response;
    }

    public function no_access_response_for_listing_table()
    {
        $response = [
            'draw' => 0,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'access' => false
        ];
        return response()->json($response);
    }

    public function get_validation_rules($field, $required = false)
    {
        $rule = "";
        switch ($field) {
            case 'email':
                $rule = "email|max:150|";
                break;
            case 'password':
                $rule = "alpha_dash|min:6|max:100|";
                break;
            case 'fullname':
                $rule = "alpha_spaces|max:100|";
                break;
            case 'phone':
                $rule = "regex:/^[0-9-+()]*$/i|max:15|";
                break;
            case 'new_password':
                $rule = "alpha_dash|min:6|max:100|confirmed|";
                break;
            case 'status':
                $rule = "numeric|";
                break;
            case 'name_label':
                $rule = "nullable|max:250|";
                break;
            case 'role_menus':
                $rule = 'string|';
                break;
            case 'pincode':
                $rule = "alpha_num|max:15|";
                break;
            case 'text':
                $rule = "max:65535|";
                break;
            case 'string':
                $rule = 'string|';
                break;
            case 'numeric':
                $rule = "numeric|";
                break;
            case 'slack':
                $rule = "alpha_num|";
                break;
            case 'order_status':
                $rule = "in:CLOSE,HOLD,IN_KITCHEN,CUSTOMER_ORDER|";
                break;
            case 'codes':
                $rule = "alpha_dash|";
                break;
            case 'filled':
                $rule = "filled|";
                break;
            case 'product_image':
                $rule = "mimes:jpeg,jpg,png,webp|max:1500";
                break;
            case 'company_logo':
                $rule = "mimes:jpeg,jpg,png|max:150|";
                break;
            case 'invoice_print_logo':
                $rule = "mimes:jpeg,jpg,png|max:150|dimensions:width=200,height=100|";
                break;
            case 'navbar_logo':
                $rule = "mimes:jpeg,jpg,png|max:50|dimensions:width=30,height=30|";
                break;
            case 'favicon':
                $rule = "mimes:jpeg,jpg,png|max:10|dimensions:width=30,height=30|";
                break;
        }

        if ($required == true) {
            $rule = implode('|', array('required', $rule));
        }
        else {
            $rule = implode('|', array('nullable', 'sometimes', $rule));
        }
        return $rule;
    }

    public function admin()
    {
        if (Auth::user()->user_type == 'Admin') {
            return true;
        }
        else {
            return false;
        }
    }
    public function customer()
    {
        if (Auth::user()->user_type == 'Customer') {
            return true;
        }
        else {
            return false;
        }
    }


}
