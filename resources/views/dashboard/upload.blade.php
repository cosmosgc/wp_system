<!-- resources/views/upload.blade.php -->
@extends('layouts.app')

@php
    use App\Models\Editor;

    $valorCodificado = request()->cookie('editor');
    $user=explode('+',base64_decode($valorCodificado));
    $post_configs= Editor::where('name',$user[0])->get();
@endphp

@section('content')
<div class="container">
    <h3 class="mt-5 mb-4 text-center">Faça o upload de CSV de configurações prontas</h3>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- <form action="/submit_file" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="csv_file" class="download-label d-flex align-items-center justify-content-center border rounded p-3">
                        <svg class="download-icon mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        <span>upload do arquivo</span>
                    </label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="csv_file" name="csv_file">
                    </div>
                </div>
                <input type="hidden" name="user_id" value="{{$post_configs[0]->id}}">
                <button type="submit" class="btn btn-primary btn-block">Importar</button>
            </form> -->
            <label for="">Selecionar Projetos</label>
            <select class="form-select" name="projects" id="projects_id">
                @foreach ($projects as $project)
                <option value="{{$project->id}}">{{$project->project_name}}</option>
                @endforeach
            </select><br>

            <div class="form-group">
                <label for="csv_file" class="download-label d-flex align-items-center justify-content-center border rounded p-3">
                    <svg class="download-icon mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    <span>upload do arquivo</span>
                </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="csv_file" name="csv_file">
                    <input type="hidden" name="" id="config_creation" value="config_creation">
                </div>
            </div>

        </div>
    </div>
</div>
<button id="submit_csv_button" class="btn btn-primary btn-block mt-3 d-none" onclick="process_upload();">Enviar CSV</button>
<input type="hidden" name="user_id" id="user_id" value="{{$post_configs[0]->id}}">
<div class="progress">
    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
        <span class="progress-label">0%</span>
    </div>
</div>
<div id="csv_table_container" class="mt-3" style="
    overflow: auto;
    width: 100%;
"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>

<script>
//     const fileInput = document.getElementById('csv_file');

//     const fileNameContainer = document.querySelector('.download-label');

// fileInput.addEventListener('change', function() {
//     const file = this.files[0];
//     if (file) {
//         // Aqui você pode exibir o nome do arquivo em algum lugar no documento
//         const file_name= document.createElement('div');
//         file_name.innerHTML=file.name;
//         fileNameContainer.insertAdjacentElement("beforebegin", file_name);
//     }
// });
</script>
<script>
    const fileInput = document.getElementById('csv_file');
    const docType=document.getElementById("config_creation").value
    const user_id = document.getElementById('user_id').value;
    const project= document.getElementById('projects_id').value;
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
                const headers = [
                    'Tema',
                    'Keyword',
                    'Site',
                    'Categoria',
                    'Ancora 1',
                    'URL do Link 1',
                    'Dofollow_link_1',
                    'Ancora 2',
                    'URL do Link 2',
                    'Dofollow_link_2',
                    'Ancora 3',
                    'URL do Link 3',
                    'Dofollow_link_3',
                    'Imagem',
                    'Insere Imagem no Post',
                    'Link Interno',
                    'Programacao de Postagem',
                    'URL da Publicação',
                    'Nota de SEO',
                    'Dominio',
                    'Gdrive',
                    'Video'
                ];

                // Check if headers match
                const headerRow = rows[0].split(',');
                const headersMatch = headers.every((header, index) => header === headerRow[index].trim());
                let errorMessages = ''; // String to store error messages
                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].split(',');
                    if (i === 0 && !headersMatch) {
                        tableHTML += '<tr>';
                        for (let j = 0; j < cells.length; j++) {
                            if (cells[j].trim() !== headers[j]) {
                                tableHTML += `<td class="keyerror">${cells[j]}</td>`;
                                errorMessages += `Encontramos um problema na linha ${i + 1}, coluna ${j + 1}: era para ser '<span style="color: blue;">${headers[j]}</span>' mas tinha '<span style="color: red;">${cells[j]}</span>'.\n <br>`;
                            } else {
                                tableHTML += `<td>${cells[j]}</td>`;
                            }
                        }
                    } else {
                        tableHTML += '<tr>';
                        for (let j = 0; j < cells.length; j++) {
                            tableHTML += `<td>${cells[j]}</td>`;
                        }
                    }
                    tableHTML += '</tr>';
                }
                tableHTML += '</table>';
                if (errorMessages !== '') {
                    Swal.fire({
                        title: 'Error!',
                        html: errorMessages,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
                document.getElementById('csv_table_container').innerHTML = tableHTML;
                submitButton.classList.remove('d-none');
            };
            reader.readAsBinaryString(file);
        }
    });



    function process_upload(){
        const headers = [
            'Tema',
            'Keyword',
            'Site',
            'Categoria',
            'Ancora 1',
            'URL do Link 1',
            'Dofollow_link_1',
            'Ancora 2',
            'URL do Link 2',
            'Dofollow_link_2',
            'Ancora 3',
            'URL do Link 3',
            'Dofollow_link_3',
            'Imagem',
            'Insere Imagem no Post',
            'Link Interno',
            'Programacao de Postagem',
            'URL da Publicação',
            'Nota de SEO',
            'Dominio',
            'Gdrive',
            'Video'
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
            console.log(rowData);
            fetch('/submit_file', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ user_id: user_id, docType:docType, project_id:project ,csvData: rowData}),
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
<style>
    th, td {
        font-size: xx-small !important;
    }
    tr.csv_error, td.keyerror {
        background: #d1464657;
    }
</style>
@endsection

