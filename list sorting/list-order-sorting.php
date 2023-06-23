<?php
//
$db_connect = '';

// $sql = "UPDATE public.details_of_officers SET list_order=$1, updated_at = now() WHERE id=$2";
// pg_prepare($db_connect, "sorting_update_qry", $sql);

foreach ($_POST['order'] as $key => $value) {
    $list_order = $key + 1;
    $id = senitizeInput($value);
    //___________ Write query to update list_order column of your table blow this comment _____//
    // $result = pg_execute($db_connect, "sorting_update_qry", array($list_order, $id));
    // if(!$result){
    //     $error = pg_last_error($db_connect);
    //     logDetails($_SERVER['HTTP_REFERER'], "WEB ADMIN ==> OFFICER DETAILS ==> SORTING",'FAILED', $error, 'UPDATE', USER_ID, OFFICE_ID);
    //     $response = [
    //         'message' => "Something went wrong",
    //         'success' => false,
    //         'status' => 'failed'
    //     ];
    //     returnJson($response);
    // }
}
