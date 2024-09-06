<?php
class FileService {
    /**
     * Persiste um arquivo enviado de client-side em uma pasta especifica
     * 
     * @param string $userfile
     * O valor do atributo "name" do input:file enviado de client-side 
     * @param string $dest
     * Caminho de destino para o arquivo no escopo do servidor
     */
    public static function saveUploadFile(string $userfile, string $dest)
    {
        if (!empty($_FILES["game_image"])) {
            $source = $_FILES[$userfile]["tmp_name"];
            if (move_uploaded_file($source, $dest)) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    public static function getFileExtension(string $filename):string|bool
    {
        // Verificar se o termo tem uma extensão de arquivo
        $extensao = false;
        if (preg_match('/\.\w+$/', $filename, $matches)) {
            // Extrair a extensão do arquivo
            $extensao = $matches[0];
        }
        return $extensao;
    }

    public static function acronimString(string $term, $upper = false) {
        // Check if the term has a file extension
        $extension = '';
        if (preg_match('/\.\w+$/', $term, $matches)) {
            // Extract the file extension
            $extension = $matches[0];
            // Remove the extension from the term for acronym generation
            $term = preg_replace('/\.\w+$/', '', $term);
        }

        // Split the term name into words
        $words = preg_split('/[\s:]+/', $term);

        // Take the first letter of each word and convert it to uppercase or lowercase
        $acronym = '';
        foreach ($words as $word) {
            if ($upper) {
                $acronym .= strtoupper($word[0]);
            } else {
                $acronym .= strtolower($word[0]);
            }
        }

        // Append the extension if it exists
        return $acronym . $extension;
    }
}