<?php

namespace App\Services;

use League\Csv\Reader;

class CsvReaderService
{
    public function CsvToJson($data)
    {
        if ($data->hasFile('csv_file')) {
            // Obtém o arquivo enviado
            $csvContent = $data->file('csv_file')->get();

            // Divide o conteúdo do CSV em linhas
            $rows = explode("\n", $csvContent);

            // Extrai o cabeçalho do CSV e remove os espaços em branco em excesso
            $header = array_map('trim', explode(';', array_shift($rows)));

            // Inicializa um array para armazenar os registros do CSV
            $csvData = [];

            // Itera sobre as linhas restantes dos dados do CSV
            foreach ($rows as $row) {
                // Divide a linha em valores separados por ponto e vírgula
                $values = explode(';', $row);

                // Cria um novo array associativo para armazenar o registro
                $csvRecord = [];

                // Itera sobre os valores do registro e associa aos cabeçalhos correspondentes
                foreach ($header as $key => $column) {
                    // Remove o byte order mark (BOM) se presente
                    $value = isset($values[$key]) ? $this->removeBom($values[$key]) : null;
                    $csvRecord[$column] = $value;
                }

                // Adiciona o registro ao array $csvData
                $csvData[] = $csvRecord;
            }

            return $csvData;
        } else {
            // Retorna uma mensagem de erro se nenhum arquivo for enviado
            return response()->json(['error' => 'Nenhum arquivo CSV enviado.'], 400);
        }
    }

    // Função para remover o byte order mark (BOM)
    private function removeBom($value)
    {
        return preg_replace('/^\xEF\xBB\xBF/', '', $value);
    }
}
