<?php
#1. Add below function after adding database connection 
function paginateData($table, $param = ['page_size' => 10, 'page' => 1])
{
    if(empty($table)){
        $response = [
            'message' => "Table name is required",
            'success' => false,
            'status' => 'failed',
            'data' => [],
        ];
        returnJson($response);
    }
   
    // Define the number of records per page and current page
    $recordsPerPage = isset($param['page_size']) ? $param['page_size'] : 5;
    $currentPage = isset($param['page']) && !empty($param['page']) ? $param['page'] : 1;
  
  
    // $recordsPerPage = $param['recordsPerPage'];
    // $currentPage = $param['currentPage'];

    // Calculate the offset
    $offset = ($currentPage - 1) * $recordsPerPage;
    // Retrieve records for the current page
    $query = "SELECT * FROM $table ORDER BY id LIMIT $recordsPerPage OFFSET $offset";
    $result = getResult($query);
    // Calculate the total number of records
    $totalRecordsResult = getResult("SELECT COUNT(*) as total FROM $table");
    $totalRecords = $totalRecordsResult[0]['total'];

    // Calculate the total number of pages
    $totalPages = ceil($totalRecords / $recordsPerPage);

    // Bootstrap pagination with links
    $pagination_html = '<div class="btn-group mr-2" role="group" aria-label="First group">';
    if ($currentPage > 1) {
        $pagination_html .= '<a href="?page='.($currentPage - 1).'" class="btn btn-primary"><i class="bi bi-arrow-left"></i></a>';
    }
    for ($i = 1; $i <= $totalPages; $i++) {
        $pagination_html .= '<a  class="btn btn-outline-primary '.($currentPage == $i ? 'active' : '').'" href="?page='.$i.'" >'.$i.'</a>';
    }
    if ($currentPage < $totalPages) {
        $pagination_html .= '<a href="?page='.($currentPage + 1).'" class="btn btn-primary"><i class="bi bi-arrow-right"></i></a>';
    }
    $pagination_html .= '</div>';

    $response = [
        'data' => $result,
        'pagination' => $pagination_html,
        'total_record' => $totalRecords,
        'current_page' => $currentPage,
        'record_per_page' => $recordsPerPage,
        'success' => true,
        'message' => "Paginated Data of table- $table"
    ];
    returnJson($response);
}
#2 Create and hit the below method
function e_complaint_paginated_data(){
    paginateData('e_complaint', $_POST);//IT WILL RETURN PAGENATION DATA AUTOMATICALLY    
}

?>
<!-- #3call function -->
<!-- Dont forget to add the bootstrap and bootstrap icon in head section of your page -->
<?php $response = paginateData('users', $_GET) ?>
<div class="m-3" style="width:100%;"> 
<!-- pagination -->
    <?= $response['pagination'] ?>
</div>



<!-- #4. Call paginate function by api -->
<div class="m-3" style="width:100%;">
        
<div id="paginatedData" class="text-primary">

</div>
<div id="paginationDiv">

</div>
</div>
<script>
    let page = getURLParameter('page');
    console.log(page)
    $.ajax({
        url: `${base_url}/gvc_api/registry/e_complaint_paginated_data`,
        type: 'POST',
        data: {
            page_size: 10,
            page: page
        },
        success: function(response) {
            console.log(response.data)
            $("#paginatedData").html(response.data[0]['uid'])
            $("#paginationDiv").html(response.pagination)
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
</script>
