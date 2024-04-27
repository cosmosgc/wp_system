@extends('layouts.app')

@php
    use App\Models\Editor;

    $valorCodificado = request()->cookie('editor');
    $user=explode('+',base64_decode($valorCodificado));
    $post_configs= Editor::where('name',$user[0])->get();
@endphp

@section('content')
    <div class="dashboard-content">
        <!-- <form action="/submit_file" method="post" enctype="multipart/form-data" class="needs-validation" novalidate> -->
        <div enctype="multipart/form-data" class="needs-validation" novalidate>

            @csrf
            <div class="form-group">
                <label for="csv_file">Select CSV file:</label>
                <input type="file" class="form-control-file" id="csv_file" name="csv_file" required>
                <div class="invalid-feedback">
                    Please select a CSV file.
                </div>
            </div>
            <input type="hidden" name="docType" value="site_register" id="config_creation">
            <input type="hidden" name="user_id" value="{{$post_configs[0]->id}}" id="user_id">
            <button type="submit" class="btn btn-primary" id="submit_csv_button" onclick="process_upload();">Submit</button>
        </div>
    </div>
    <div id="csv_table_container"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
    <script>
const fileInput = document.getElementById('csv_file');
const docType=document.getElementById("config_creation").value
const user_id = document.getElementById('user_id').value;
const submitButton = document.getElementById('submit_csv_button');

fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const data = event.target.result;
            const workbook = XLSX.read(data, { type: 'binary' });
            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];
            const csvData = XLSX.utils.sheet_to_csv(sheet);
            const rows = csvData.split('\n');
            let tableHTML = '<table class="table">';
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].split(',');
                tableHTML += '<tr>';
                for (let j = 0; j < cells.length; j++) {
                    tableHTML += `<td>${cells[j]}</td>`;
                }
                tableHTML += '</tr>';
            }
            tableHTML += '</table>';
            document.getElementById('csv_table_container').innerHTML = tableHTML;
            submitButton.classList.remove('d-none');
        };
        reader.readAsBinaryString(file);
    }
});
function process_upload(){
    const headers = [
        'login',
        'password',
        'domain',

    ];

    const tableRows = document.querySelectorAll('#csv_table_container table tr');
    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
    const csvData = [];
    const progressBar = document.querySelector('.progress-bar');
    const progresslabel = document.querySelector('.progress-label');
    const totalRows = tableRows.length-1;
    let processedRows = 0;

    tableRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const rowData = {};
        const firstCell = cells[0].textContent.trim();
        const firstHeader = headers[0];
        if (firstCell === firstHeader) {
            // If true, skip this row and continue with the next one
            return;
        }
        cells.forEach((cell, index) => {
            // Assuming the headers are in the same order as the table columns
            const header = headers[index]; // Assuming 'headers' is an array of header names
            const cellData = cell.textContent.trim();
            rowData[header] = cellData;
        });
        // console.log(rowData);
        fetch('/submit_file', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ user_id: user_id, docType:docType,csvData: rowData}),
        })
        .then(response => {
            if (response.ok) {
                row.remove();
            } else {
                row.classList.add('csv_error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            processedRows++;
            const progressPercentage = (processedRows / totalRows) * 100;
            progressBar.style.width = `${progressPercentage}%`;
            progressBar.setAttribute('aria-valuenow', progressPercentage);
            roundedPercent = Math.round(progressPercentage);
            progresslabel.innerHTML = (`${processedRows} / ${totalRows} | ${roundedPercent}%`);
        });
    });
}
    </script>
@endsection
