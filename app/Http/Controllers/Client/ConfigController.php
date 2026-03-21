<?php

namespace App\Http\Controllers\Client;


use App\Models\Client\Config;
use Illuminate\Http\Request;

class ConfigController extends AController
{

    protected $KEY;
    protected $SUBKEY;
    protected $VALUE;
    protected $TIPO_DOMINIO_ID;
    protected $DOMINIO_ID;


    public function set($var, $value){
        $this->$var = $value;
    }
    public function __construct(){
        parent::__construct();
    }

    public static function getValor(string $key, ?string $subkey = null){
        $config = Config::where('key', $key)->where('subkey', $subkey)->first();
        if(!$config){
            return null;
        }
        return !empty($config->value) ? $config->value :
                (!empty($config->tipo_dominio_id) ? $config->tipo_dominio_id :
                (!empty($config->dominio_id) ? $config->dominio_id : null));
    }

    public static function getCt(string $value){
        $valor = self::getValor($value);
        if(!$valor){
            $Config = new Config();
            $Config->key = 'param';
            $Config->subkey = $value;
            $Config->value = (int)0;
            $Config->save();
            return (int)0;
        }
        return (int)$valor;
    }

    public static function get(string $key, ?string $subkey = null){
        $valor = self::getValor($key, $subkey);
        if(!$valor){
            return null;
        }
        return $valor;
    }

    public function atualizar(){
        $config = Config::where('key', $this->KEY)->where('subkey', $this->SUBKEY)->first();
        if(!$config){
            return false;
        }
        $config->value = $this->VALUE ?? $config->value;
        $config->tipo_dominio_id = $this->TIPO_DOMINIO_ID ?? $config->tipo_dominio_id;
        $config->dominio_id = $this->DOMINIO_ID ?? $config->dominio_id;
        return $config->save();
    }
    public function salvar(){
        $config = new Config();
        $config->key = $this->KEY ?? null;
        $config->subkey = $this->SUBKEY ?? null;
        $config->value = $this->VALUE ?? null;
        $config->tipo_dominio_id = $this->TIPO_DOMINIO_ID ?? null;
        $config->dominio_id = $this->DOMINIO_ID ?? null;
        return $config->save();
    }

    public function getConfig(){
        $resultado = [];
        $rows = Config::all()->toArray();
        foreach ($rows as $row) {
            $chave    = strtolower($row['key']);
            $subchave = strtolower($row['subkey']) ?? null;
            $valor    = !empty($row['value']) ? $row['value'] :
                            (!empty($row['tipo_dominio_id']) ? $row['tipo_dominio_id'] :
                            (!empty($row['dominio_id']) ? $row['dominio_id'] : null));
            if (empty($subchave)) {
                $resultado[$chave] = $valor;
                continue;
            }
            if (!isset($resultado[$chave]) || !is_array($resultado[$chave])) {
                    $resultado[$chave] = [];
            }
            $resultado[$chave][$subchave] = $valor;
        }
        return $resultado ?? [];
    }

    public function index(){
        $configs = Config::with(['tipoDominio', 'dominio'])->get();
        return $this->success([
            'configs' => $configs,
        ]);
    }

    public function getById(string $config_id){
        $config = Config::with(['tipoDominio', 'dominio'])->find($config_id);
        if(!$config){
            return $this->error('Config não encontrada!', 404);
        }
        return $this->success($config);
    }

    public function store(Request $request){
        $validated = request()->validate([
            'key' => 'required|string|max:64',
            'subkey' => 'required|string|max:64',
            'value' => 'nullable|string|max:36',
            'tipo_dominio_id' => 'nullable|exists:tipo_dominio,id',
            'dominio_id' => 'nullable|exists:dominio,id',
        ]);
        Config::create($validated);
        return $this->success('Config criada com sucesso!');
    }

    public function update(Request $request, string $config_id){
        $config = Config::find($config_id);
        if(!$config){
            return $this->error('Config não encontrada!', 404);
        }
        $validated = request()->validate([
            'key' => 'sometimes|required|string|max:64',
            'subkey' => 'sometimes|required|string|max:64',
            'value' => 'nullable|string|max:36',
            'tipo_dominio_id' => 'nullable|exists:tipo_dominio,id',
            'dominio_id' => 'nullable|exists:dominio,id',
        ]);
        $config->update($validated);
        return $this->success('Config atualizada com sucesso!');
    }

    public function destroy(string $config_id){
        $config = Config::find($config_id);
        if(!$config){
            return $this->error('Config não encontrada!', 404);
        }
        $config->delete();
        return $this->success('Config removida com sucesso!');
    }
}
