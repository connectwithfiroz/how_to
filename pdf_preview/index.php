<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF PREVIEW</title>
    <!-- css -->
    <style>
        #pdfPreview {
        margin-top: 20px;
        border: 1px solid #ccc;
        padding: 10px;
        }

    </style>
</head>
<body>
<input type="file" id="pdfFile" accept=".pdf" multiple/>
<ol id="selectFileList">
    
</ol>
<div id="pdfPreview"></div>

<script>
    // Function to handle the file selection
function showAttachment(file){
    // const file = event.target.files[0];
    if (file && file.type === 'application/pdf') {
        const reader = new FileReader();

        reader.onload = function(e) {
        // Create a <embed> element to display the PDF preview
        const embed = document.createElement('embed');
        embed.src = e.target.result;
        embed.width = '100%';
        embed.height = '500px';
        
        // Clear previous preview (if any) and append the new preview
        const previewContainer = document.getElementById('pdfPreview');
        previewContainer.innerHTML = '';
        previewContainer.appendChild(embed);
        };

        reader.readAsDataURL(file);
    }
}
var selectFileList = [];
function handleFileSelect(event) {
    // pdfFile =  document.getElementById('pdfFile');
    // console.log(event.target.files);
    selectFileList = document.getElementById('selectFileList');
    selectFileList.innerHTML = '';
    selected_files = event.target.files;
    for (let index = 0; index < selected_files.length; index++) {
        const element = selected_files[index];
        selectFileList.innerHTML += `<li class="file" data-index="${index}"> ${element.name} </li>`;
    }

    //bind even
    const files = document.querySelectorAll('.file');

    // Attach the event listener to each button
    files.forEach(function(current, index) {
        current.addEventListener('click', function(){
            console.log(selected_files[index]);
            showAttachment(selected_files[index])
        });
    });


    showAttachment(selected_files[0]);
    return;
}

// Attach the event listener to the file input element
const fileInput = document.getElementById('pdfFile');
fileInput.addEventListener('change', handleFileSelect);

</script>
</body>
</html>