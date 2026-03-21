<?php
    namespace App\Http\Controllers\Client;


    use App\Models\Client\{Anexo};



class ArquivoController extends AController
{
    public function index(){

    }
    public function get(string $id = null, string $download = null){
        $anexo = Anexo::query()->find($id);
        if($anexo->download == false){
            return $this->error([], 'Download deste arquivo não permitido!', 403);
        }
        if($anexo) {
            if($download=='download'){
                return response()->download(storage_path('app/public/' . $anexo->filename), $anexo->original_name);
            }elseif($download=='b64' || $download=='base64'){
                $filePath = storage_path('app/public/' . $anexo->filename);
                if (file_exists($filePath)) {
                    $fileContent = file_get_contents($filePath);
                    $base64 = base64_encode($fileContent);
                    return $this->success([
                        'filename' => $anexo->original_name,
                        'mime' => $anexo->mime,
                        'size' => $anexo->size,
                        'extension' => $anexo->extension,
                        'base64' => $base64,
                    ]);
                } else {
                    return $this->error('Arquivo não encontrado!', 404);
                }
            }else{
                return response()->file(storage_path('app/public/' . $anexo->filename));
            }
        }
        return $this->error('Arquivo não encontrado!', 404);
    }
}
