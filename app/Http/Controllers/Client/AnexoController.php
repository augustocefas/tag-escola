<?php

    namespace App\Http\Controllers\Client;


    use App\Models\Client\Anexo;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

class AnexoController extends AController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request){
        $request->validate([
            'file' => 'required|file|max:60240', // max 10MB, change as needed
        ]);
        $data = $this->storeFile($request);
        if($data){
            return $this->success($data);
        }
        return $this->error('Erro ao salvar o arquivo!', 500);
    }

    public function storeFile(Request $request, $fieldName = 'file', $download = true)
    {
        $request->validate([
            $fieldName => 'required|file|max:60240', // max 10MB, change as needed
        ]);
        $file = $request->file($fieldName);
        DB::beginTransaction();
        try {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mime = $file->getClientMimeType();
            $size = $file->getSize();
            $filename = time() . '_' . Str::random(8) . '.' . $extension;
            $path = $file->storeAs('', $filename, 'public');
            $anexo = Anexo::create([
                'original_name' => $originalName,
                'download' => $download,
                'filename' => $filename,
                'mime' => $mime,
                'size' => $size,
                'extension' => $extension,
                'users_id' => $request->user()->id ?? null,
            ]);



            DB::commit();
            return $anexo;
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error('Falha ao salvar anexo', [
                'field' => $fieldName,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
        return false;
    }

}
