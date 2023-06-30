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
            embed.height = '100%';
            
            // Clear previous preview (if any) and append the new preview
            const previewContainer = document.getElementById('pdfPreview');
            previewContainer.innerHTML = '';
            previewContainer.appendChild(embed);
            };
    
            reader.readAsDataURL(file);

            //set document name
            document.getElementById('documentName').innerText = (file.name);
        }
    }
    var selectFileList = [];
    function handleFileSelect(event) {
        selectFileList = document.getElementById('selectFileList');
        selectFileList.innerHTML = '';
        selected_files = event.target.files;
        for (let index = 0; index < selected_files.length; index++) {
            const element = selected_files[index];
            selectFileList.innerHTML += `<button type="button" class="btn btn-outline-primary file" data-index="${index}">${index + 1}</button>`;
        }
    
        //bind even
        const files = document.querySelectorAll('.file');
        files.forEach(function(current, index) {
            current.addEventListener('click', function(){
                showAttachment(selected_files[index]);
                //add active class on clicked element
                const first_attach = document.querySelector(`[data-index="${index}"]`);
                first_attach.classList.add('active');
                first_attach.setAttribute('disabled', 'true');
            });
        });
    
    
        showAttachment(selected_files[0]);
        //auto select first attachment
        // const first_attach = document.querySelector('[data-index="0"]');
        // first_attach.classList.add('active');
        // first_attach.setAttribute('disabled', 'true');
        return;
    }
    
    // Attach the event listener to the file input element
    const fileInput = document.getElementById('pdfFile');
    fileInput.addEventListener('change', handleFileSelect);
    