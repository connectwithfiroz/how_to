<?php
error_reporting(0);
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
// ======== TO STOP CATCHING DURING DEVELOPMENT ADDED BELOW FILE REMOVET AT PRODUCTION =====//
require_once('stop_catche.php');
/* =============================
    CONSTANT VARIABLE DECLARATION START
 =============================*/
define('USER_ID', 0);  
define('OFFICE_ID', 0);  

/* =============================
    CONSTANT VARIABLE DECLARATION END
 =============================*/



//<<<<--------
# For Root Directory to add header and footer or some other file start
if(!isset($root_dir_name) || $root_dir_name == "")
{
    $file_path_with_root_dir = explode("/", $_SERVER['SCRIPT_NAME']);
    $root_dir_name = $file_path_with_root_dir[1];
    unset($file_path_with_root_dir);
}
//-------->>>
//Set Cookies For Language
$docRoot=$_SERVER['DOCUMENT_ROOT'] . "//";
$docRoot = str_replace("\\", "/", $docRoot);
$docRoot = str_replace("//", "/", $docRoot);
$cwd= getcwd(). "//";
$cwd = str_replace("\\", "/", $cwd);
$cwd = str_replace("//", "/", $cwd);
$cwd = substr($cwd, strlen($docRoot));
$prjName=substr($cwd,0, strpos($cwd,"/"));
$path=$docRoot . "$prjName/";

include_once(base("vendor/autoload.php"));
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

//=== To check user is authenticate or not start ===//
// ===> If redirect url is true means user will be redirect to the mentioan url in second parameter if you'll not pass the second parameter the by defult it will redirect to the login page of view/user/login.php
//===> If you'll pass first parameter false then it'll return data instead of redirect to the login page or mention page. you can use auth(false) to perform some action based on the return value.
function auth($redirect=true, $redirectUrl = ''){

    $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    if(isset($_COOKIE['jwt']) and $_COOKIE['jwt'] != FALSE){
        try{
            // return ['status' => false, 'message' => 'Unauthenticated', 'data' => []];
            // $userDetails = ;
            // $user_data = $userDetails->data;   
            if($redirect != true){
                return ['status' => true, 'message' => 'Authenticated', 'data' => JWT::decode($_COOKIE['jwt'], new Key($secretKey, 'HS512'))->data];
            }
        }
        catch(Exception $ex){
            setcookie('jwt', FALSE, -1, '/');
            header("location: ../../view/user/login.php");
        }
    }else{
        if($redirect == true){
            if(empty($redirectUrl)){
                // setcookie('jwt', FALSE, -1, '/');
                header("location: ../../view/user/login.php");
            }else{
                header("location: $redirectUrl");  
            }
                
        }else{
            return ['status' => false, 'message' => 'Unauthenticated', 'data' => []];
        }
        
            
    }
}
//=== To check user is authenticate or not end ===//


$_SESSION['activeNav'] = 'home';
function selectNav($page){
    if(gettype($page) == 'array'){
        return (in_array($_SESSION['activeNav'], $page))?'active':'';
    }else{
        return ($_SESSION['activeNav'] == $page)?'active':'';
    }

   
}
function validateInput($input) {
    return htmlspecialchars(stripslashes(trim($input)), ENT_NOQUOTES);  
}
function senitizeInput($input) {
    return htmlspecialchars(stripslashes(trim($input)), ENT_NOQUOTES);  
}
function senitizeArr($arr) {
    $senitzedData = [];
    foreach ($arr as $key => $value) {
        $value = senitizeInput($value);  
        $senitzedData[$key] = (!empty($value) && isset($value))?$value:NULL;  
    }

    return (Object)$senitzedData;  
}

function base($userPath=''){
    $docRoot=$_SERVER['DOCUMENT_ROOT'] . "//";
    $docRoot = str_replace("\\", "/", $docRoot);
    $docRoot = str_replace("//", "/", $docRoot);
    $cwd= getcwd(). "//";
    $cwd = str_replace("\\", "/", $cwd);
    $cwd = str_replace("//", "/", $cwd);
    $cwd = substr($cwd, strlen($docRoot));
    $prjName=substr($cwd,0, strpos($cwd,"/"));
    $path=$docRoot . "$prjName/";
    if(!empty($userPath)){
        $path .= $userPath;
    }
    return $path;
}
function view($userPath=''){
    $base = base();
    $base .= 'view/';
    if(!empty($userPath)){
        $base .= $userPath;
    }
    return $base;
}
// dd function used only during development...   
function dd($arr){
    header('Content-Type: application/json');
    $json_response = json_encode($arr);
    http_response_code(200);
    echo $json_response;
    exit();
}
function back(){
    header("location: ".$_SERVER['HTTP_REFERER']);
    exit;
}
function backWithError($var, $msg){
    $_SESSION[$var] = $msg;
    header("location: ".$_SERVER['HTTP_REFERER']);
    exit;
}
function backWithMsg($var, $msg){
    $_SESSION[$var] = $msg;
    header("location: ".$_SERVER['HTTP_REFERER']);
    exit;
}
function redirectWithMsg($url, $var, $msg){
    $_SESSION[$var] = $msg;
    header("location: ".$url);
    exit;
}
function old($key){
    if(isset($_SESSION['old'][$key])){
        echo $_SESSION['old'][$key];
        unset($_SESSION['old'][$key]);
    } 
}
function returnOld($key){
    if(isset($_SESSION['old'][$key])){
        $temp = $_SESSION['old'][$key];
        unset($_SESSION['old'][$key]);
        return $temp;
    } 
}
function error($key){
    if(isset($_SESSION['errors'])){
        foreach ($_SESSION['errors'] as $skey => $svalues) {
            foreach ($svalues as $svkey => $value) {
                if($svkey == $key){
                    echo $value."<br>";
                    unset($_SESSION['errors']);
                }
            }
        }
    }  
}
function session($key){
    if(isset($_SESSION[$key])){
        echo $_SESSION[$key];
        unset($_SESSION[$key]);
    }  
}

//Function to validate user request input name must be some similar name for example for name fiels is should be name so that it can return "Name is required"
function validate($data, $rules, $optionArr=[]){
     /*---------------------------------------------------------------
        #First argument of validate is request data,
        #Second argument of validate() is rules
        #Thirst argument of validate() is option. option can be ['return_back' => false, 'key_explode_by' => ','], Where return_back = false means return json of error instead of returning back to requested page, 2) key_explode_by - for exploding to rule key either by , or |, Default is |.
        #returnback parameter must be SET false in case of asyn call, 
        #It will automatically return json with error list and old data.
    ---------------------------------------------------------------*/
   
    //---->>>> FOR GENERATING OPTION RULE AS PER USER SELECTION FOR KEY EXPLOADING__//
    $option = (Object)[
        'return_back' => true,
        'key_explode_by' => '|',
    ]; 
   
    if(isset($optionArr)){
        $optionArr = (Object)$optionArr;
        if((isset($optionArr->return_back) && in_array($optionArr->return_back, [true, false]))){
            $option->return_back = $optionArr->return_back;
        }
        if(($optionArr->key_explode_by)  && in_array($optionArr->key_explode_by, [',', '|', '&'])){
            $option->key_explode_by = $optionArr->key_explode_by;
        }
        
    }

    //<<<<---- FOR GENERATING OPTION RULE AS PER USER SELECTION FOR KEY EXPLOADING END__//

    //IF RULE KEY CONTAINT MORE THAN ONE FILED MEANS ARAY
    $errors = [];
    //FIRST LOOP FOR SEPERATING RULE AND VALUE LIKE 'username' => 'required' 
    foreach ($rules as $ruleKeys => $values) {
        if(gettype($values) != 'array'){
            $values = explode('|',  $values);
        }
        //EXPLODE RULE KEY if 'username|email' => 'required' THEN I
        if(gettype($ruleKeys) != 'array'){
            $ruleKeys = explode($option->key_explode_by, $ruleKeys);
        }
        // returnJson($values);
        
        foreach ($ruleKeys as $valueKey => $ruleKey) {
           
            $ruleKey = trim($ruleKey);
            foreach ($values as $valueKey => $value) {
                $ruleKey = trim($ruleKey);
                switch ($value) {
                    case 'required':
                        if(empty($data[$ruleKey])){
                            array_push($errors,[$ruleKey => "This field is required."]);
                        }
                        break;
                    
                    case 'alpha':
                        if(!preg_match('/^[A-Za-z ]+$/', $data[$ruleKey])){
                            array_push($errors,[$ruleKey => "This field must contains only capital or small letter and space"]);
                        }
                        break;
                    case 'number':
                        if(!preg_match('/^[0-9]+$/', $data[$ruleKey])){
                            array_push($errors,[$ruleKey => "This field must contains only number."]);
                        }
                        break;
                    
                    case 'pincode':
                        if(!preg_match('/^[0-9]{6}+$/', $data[$ruleKey])){
                            array_push($errors,[$ruleKey => "This field is invalid, it must be equal to 6 digits long."]);
                        }
                        break;
                    
                    case (preg_match('/equalToLen:\d+/', $value) ? true : false) :
                        $len = preg_replace('/equalToLen:/', '', $value);
                        if(strlen($data[$ruleKey]) != $len){
                            array_push($errors,[$ruleKey => "Only $len characters required."]);
                        }
                        break;
                    
                    case (preg_match('/minLen:\d+/', $value) ? true : false) :
                        $len = preg_replace('/minLen:/', '', $value);
                        if(strlen($data[$ruleKey]) < $len){
                            array_push($errors,[$ruleKey => "Minimium $len characters required."]);
                        }
                        break;
                    //Firstly check is condition strting with maxLen if true then take the interger value after maxLen
                    case (preg_match('/maxLen:\d+/', $value) ? true : false) :
                        $len = preg_replace('/maxLen:/', '', $value);
                        if(strlen($data[$ruleKey]) > $len){
                            array_push($errors,[$ruleKey => "Maximum $len characters allow."]);
                        }
                        break;
                    
                    case 'email':
                        if(!filter_var($data[$ruleKey], FILTER_VALIDATE_EMAIL)){
                            array_push($errors,[$ruleKey => "$ruleKey must be valid email id."]);
                        }
                        break;
                    
                    case 'mobile':
                        if(!preg_match('/^[0-9]{10}+$/', $data[$ruleKey])){
                            array_push($errors,[$ruleKey => "This field must be 10 digit."]);
                        }
                        break;

                    case 'confirmed':
                        if($data['password'] != $data['password_confirmation'] ){
                            array_push($errors,['password' => "Password and Confirm Password must be same."]);
                        }
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
        }
    }
    // returnJson($errors);

    if(count($errors) > 0){
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $data;
        //IF RETURN BACK IS TRUE THEN RETURN BACK TO PREVIOUS PAGE WITH OLD VALUE AND ERROR
        // returnJson(($option->return_back == false)?1:0);
        if($option->return_back == true){
            header("location: ".$_SERVER['HTTP_REFERER']);
            exit;
        }else{
            $response = [
                'message' => "Validation Failed",
                'errors' => $errors,
                'old' => $data,
                'success' => false,
                'status' => 'failed',
                'status_code' => 422
            ];
            returnJson($response, 200);
            // return ['old' => $data, 'errors'=> $errors, 'success' => false];
        }
    }
   
}
//== To encrypt first and last digit it can be used to display only some part of email or phone
function formateStr($str, $char_from_start=2, $char_from_end=2){
    return substr($str, 0, $char_from_start) .str_repeat('*', strlen($str) - ($char_from_start + $char_from_end)).substr($str, -$char_from_end);
}

//(START)=== MEHTOD CALLING BASED ON ACTION ===//
function bindAction($object, $return=false){
    $action = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1);
    if($action){
        if (method_exists($object, $action)) {
            $object->$action();
        } else {
            $msg = ("$action() Method does not exist in given class");
            $status = 0;
        }
    }else{
        if (method_exists($object, 'index')) {
            $object->index();
        }else{
            $msg = ("index() method must be in Class");
            $status = 0;
        }
        
    }
    if($return){
        return ['status' => $status, 'message' => $msg];
    }else{
        dd($msg);
    }
}
//(END)=== MEHTOD CALLING BASED ON ACTION ===//

// ========= For databse =====//
include_once(base("connection/connection.php"));
$db = new DbConnection();
$db_connect = $db->db_connection();

function getAll($table, $column = "*"){
    global $db_connect;
    $sql = "SELECT ".$column." FROM $table";
    $res = pg_query($db_connect, $sql);
    if(pg_num_rows($res) > 0){
        $row = pg_fetch_all($res);
        return $row;
    }
    return [];  
}
function getResult($sql){
    global $db_connect;
    $res = pg_query($db_connect, $sql) or die("Query failed.");
    if(pg_num_rows($res) > 0){
        $row = pg_fetch_all($res);
        return $row;
    }
    return [];  
}
function getDepartmentName($dep_code){
    global $db_connect;
    $sql = "SELECT dept_name FROM masters.sgvo_dept_mst WHERE dept_id = '$dep_code' LIMIT 1";
    $res = pg_query($db_connect, $sql);
    if(pg_num_rows($res) > 0){
        $row = pg_fetch_assoc($res);
        return $row['dept_name'];
    }else{
        return "";
    }
}
function getDistrictName($id){
    global $db_connect;
    $sql = "SELECT district_name as name FROM masters.sgva_districts_mst WHERE district_id = '$id' LIMIT 1";
    $res = pg_query($db_connect, $sql);
    if(pg_num_rows($res) > 0){
        $row = pg_fetch_assoc($res);
        return $row['name'];
    }else{
        return "";
    }
}
function getTalukaName($id){
    global $db_connect;
    $sql = "SELECT taluka_name as name FROM masters.sgva_talukas_mst WHERE taluka_id = '$id' LIMIT 1";
    $res = pg_query($db_connect, $sql);
    if(pg_num_rows($res) > 0){
        $row = pg_fetch_assoc($res);
        return $row['name'];
    }else{
        return "";
    }
}
// ========= For databse end=====//

//To return JSON response start===//
function returnJson($res, $code = 200){
    header('Content-Type: application/json');
    $json_response = json_encode($res);
    http_response_code($code);
    echo $json_response;
    exit();
}

// ========= For RETURN THE PAGE CONTENT START=====//
function getPageContent($page){
    require_once("../../connection/connection.php");

    $db = new DbConnection();
    $db_connect = $db->db_connection();
    
    $sql="SELECT * FROM public.ofcer_dtl;";
    $res = pg_query($db_connect, $sql);
    $data = '';
    if(pg_num_rows($res) > 0){
        $data=pg_fetch_result($res,0,0);
        $data=json_decode($data,true);  
     }
     return $data;
    // dd($_COOKIE['LANG']);
}
// ========= For RETURN THE PAGE CONTENT END=====//


// ========= Function for Encript and Decript Data ========= //

function my_encrypt($data) {

    // Encrypt Or Decrypt Key
    $key = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';

    // Remove the base64 encoding from our key
    $encryption_key = base64_decode($key);
    // Generate an initialization vector
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
    return base64_encode($encrypted . '::' . $iv);
} 

function my_decrypt($data) {

    // Encrypt Or Decrypt Key
    $key = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';

    // Remove the base64 encoding from our key
    $encryption_key = base64_decode($key);
    // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}

// ================== End ==================== //

// Checking for user Two factore authenticated or not 

function checkTwoFactore(){
    $checkAuthenticated = my_decrypt($_SESSION['user_authenticated']);
    if(!$checkAuthenticated){
        setcookie('jwt', FALSE, -1, '/');
        unset($_SESSION['user_authenticated']);
        $_SESSION['Authorize_error'] = "Unauthorized Entry !!!";
        header('location: login.php');
    }
}

// End


// Tosting Message
function toastMessage($session_key, $type){
    if(isset($_SESSION[$session_key])){
        $message = $_SESSION[$session_key];
        if($type == "success"){
            echo '<script type="text/javascript">toastr.success("'.$message.'")</script>';
        }else if($type == "error"){
            echo '<script type="text/javascript">toastr.error("'.$message.'")</script>';
        }
        unset($_SESSION[$session_key]);
    }
}
// End

//=== For log details start ===//
function logDetails($url, $module, $status, $detail, $action, $user_id, $office_id = 0, $ip_address = null){
    global $db_connect;
    if(empty($ip_address)){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
    }
    try{
        $sql = "INSERT INTO log.log(url, module, status, detail, action, user_id, office_id, ip_address, created_at) VALUES('$url', '$module', '$status', '$detail', '$action', '$user_id', '$office_id', '$ip_address', now())";
        // echo $sql;
        pg_query($db_connect, $sql) or die('LOG INSERT QUERY FAILED');
        // return $rev;
    }catch(Exception $e){
        echo "Error".$e->getMessage();
    }
} 
//=== For log details end ===//

// Get Attachement
function get_attachment($doc_id){
    global $extended;
    
    $response = $extended->getDocument(DATABASE,$doc_id);
    $array = json_decode($response, True);
    if(isset($array['_attachments'])){
        $data = $array['_attachments'];
        foreach($data as $key => $value)
        {
            $k = substr($key, 0, strrpos($key, '.'));
 
            $cont  = file_get_contents(COUCHDB_DOWNLOADURL."/".DATABASE."/".$doc_id."/".$key."");
            header('Content-Disposition: inline; filename="'.$key);
            header("Content-Type:".$value['content_type']);
            header("Cache-Control:  max-age=1");
            echo $cont;

        }
    }else{
        $response = [
            'success' => false,
            'status' => true,
            'message' => 'Document not found.'
        ];
        returnJson($response, 400);
    }
        
            
    exit;
    
}

function getIP(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

//(Start)_______ Admin Login Test ___________//
function adminLoginCheck(){
    if(!isset($_SESSION['user_id']) || (!isset($_SESSION['USER_ROLE']) && $_SESSION['USER_ROLE'] == 'ADMIN') ){
        header("Location: ../pages/login.php");
    }
}
//(End)_______ Admin Login Test ___________//




?>